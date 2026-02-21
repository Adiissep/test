<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::with('categories');

        if (!empty($kode)) {
            $data_search = $data_search->where('kode', 'LIKE', '%' . $kode . '%');
        }

        if (!empty($nama)) {
            $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        }

        if (!empty($hargamin) && is_numeric($hargamin)) {
            $data_search = $data_search->where('harga_beli', '>=', (float) $hargamin);
        }

        if (!empty($hargamax) && is_numeric($hargamax)) {
            $data_search = $data_search->where('harga_beli', '<=', (float) $hargamax);
        }

        $data_search = $data_search->orderBy('id')->get();

        // Format data untuk response
        $result = $data_search->map(function ($item) {
            return [
                'kode' => $item->kode,
                'nama' => $item->nama,
                'jenis' => $item->jenis,
                'harga_beli' => $item->harga_beli,
                'laba' => $item->laba,
                'supplier' => $item->supplier,
                'foto' => $item->foto,
                'categories' => $item->categories->pluck('nama')->implode(', ')
            ];
        });

        return response()->json([
            'status' => 200,
            'data' => $result
        ]);
    }

    public function formView($method, $id = 0)
    {
        // Ambil semua categories untuk dropdown
        $categories = Category::orderBy('nama')->get();

        if ($method == 'new') {
            $item = null;
            $selectedCategories = [];
        } else {
            $item = MasterItem::with('categories')->find($id);
            $selectedCategories = $item->categories->pluck('id')->toArray();
        }

        return view('master_items.form.index', [
            'item' => $item,
            'method' => $method,
            'categories' => $categories,
            'selectedCategories' => $selectedCategories
        ]);
    }

    public function singleView($kode)
    {
        // Eager loading categories
        $data = MasterItem::with('categories')->where('kode', $kode)->first();
        return view('master_items.single.index', ['data' => $data]);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_beli' => 'required|numeric|min:0',
            'laba' => 'required|numeric|min:0|max:100',
            'supplier' => 'required|string',
            'jenis' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ], [
            'nama.required' => 'Nama item wajib diisi',
            'nama.max' => 'Nama item maksimal 255 karakter',
            'harga_beli.required' => 'Harga beli wajib diisi',
            'harga_beli.numeric' => 'Harga beli harus berupa angka',
            'harga_beli.min' => 'Harga beli tidak boleh negatif',
            'laba.required' => 'Laba wajib diisi',
            'laba.numeric' => 'Laba harus berupa angka',
            'laba.min' => 'Laba tidak boleh negatif',
            'laba.max' => 'Laba maksimal 100%',
            'supplier.required' => 'Supplier wajib dipilih',
            'jenis.required' => 'Jenis wajib dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, JPEG, PNG, atau GIF',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;

        // Handle upload foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($data_item->foto) {
                $oldPath = public_path('storage/items/' . $data_item->foto);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Upload foto baru
            $file = $request->file('foto');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

            // Pastikan folder exists
            $destinationPath = public_path('storage/items');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Simpan file langsung ke public/storage/items
            $file->move($destinationPath, $filename);

            $data_item->foto = $filename;
        }

        $data_item->save();

        // Sync categories (Many-to-Many)
        if ($request->has('categories')) {
            $data_item->categories()->sync($request->categories);
        } else {
            $data_item->categories()->detach();
        }

        return redirect('master-items');
    }

    public function delete($id)
    {
        $item = MasterItem::find($id);

        if ($item) {
            // Hapus foto jika ada
            if ($item->foto) {
                $fotoPath = public_path('storage/items/' . $item->foto);
                if (file_exists($fotoPath)) {
                    unlink($fotoPath);
                }
            }

            // Detach categories
            $item->categories()->detach();

            $item->delete();
        }

        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);
        return $array[$random];
    }
}
