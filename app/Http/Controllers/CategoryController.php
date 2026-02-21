<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('categories.index.index');
    }

    public function search(Request $request)
    {
        $nama = $request->nama;
        $kode = $request->kode;

        $data_search = Category::query();

        if (!empty($nama)) {
            $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        }

        if (!empty($kode)) {
            $data_search = $data_search->where('kode', 'LIKE', '%' . $kode . '%');
        }

        $data_search = $data_search->select('id', 'kode', 'nama')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $category = null;
        } else {
            $category = Category::find($id);
        }

        return view('categories.form.index', [
            'category' => $category,
            'method' => $method
        ]);
    }

    public function singleView($kode)
    {
        $category = Category::where('kode', $kode)->first();
        
        if (!$category) {
            return redirect('categories')->with('error', 'Category tidak ditemukan');
        }

        $items = $category->masterItems()->get();

        return view('categories.single.index', [
            'category' => $category,
            'items' => $items
        ]);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        // Validasi hanya untuk nama (kode otomatis)
        $request->validate([
            'nama' => 'required|string|max:255',
        ], [
            'nama.required' => 'Nama category wajib diisi',
        ]);

        if ($method == 'new') {
            $category = new Category;
            
            // Generate kode otomatis dengan prefix 'CAT'
            $lastCategory = Category::withTrashed()->max('id');
            $kode = ($lastCategory ?? 0) + 1;
            $kode = 'CAT' . str_pad($kode, 4, '0', STR_PAD_LEFT); // CAT0001, CAT0002, dst
        } else {
            $category = Category::find($id);
            $kode = $category->kode; // Kode tidak berubah saat edit
        }

        $category->kode = $kode;
        $category->nama = $request->nama;
        $category->save();

        return redirect('categories')->with('success', 'Category berhasil disimpan');
    }

    public function delete($id)
    {
        $category = Category::find($id);
        
        if ($category) {
            $category->masterItems()->detach();
            $category->delete();
        }

        return redirect('categories')->with('success', 'Category berhasil dihapus');
    }

    /**
     * Export PDF - Detail Category dengan list items
     */
    public function exportPdf($kode)
    {
        $category = Category::where('kode', $kode)->first();
        
        if (!$category) {
            return redirect('categories')->with('error', 'Category tidak ditemukan');
        }

        // Eager loading items
        $items = $category->masterItems()->get();
        
        // Data untuk PDF
        $data = [
            'category' => $category,
            'items' => $items,
            'printed_at' => now()->format('d F Y H:i:s'),
        ];

        // Load view detail.blade.php dari folder categories/pdf
        $pdf = Pdf::loadView('categories.pdf.detail', $data);
        
        // Download PDF dengan nama file
        $filename = 'Category_' . $category->kode . '_' . now()->format('Ymd_His') . '.pdf';
        
        return $pdf->download($filename);
    }
}