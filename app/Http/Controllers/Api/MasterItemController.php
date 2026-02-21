<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //DB::enableQueryLog();
        // Eager loading untuk API
        // $items = MasterItem::with(['supplierRelation'])->get(); 
        $items = MasterItem::all(); 

        //dd(DB::getQueryLog()); // Lihat semua query yang dijalankan
        
        return response()->json(
            [
                'status' => 200,
                'data' => $items
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
            'harga_beli' => 'required|numeric',
            'laba' => 'required|numeric',
            'supplier' => 'required'
        ]);

        $kode = MasterItem::count() + 1;
        $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

        $item = MasterItem::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'harga_beli' => $request->harga_beli,
            'laba' => $request->laba,
            'supplier' => $request->supplier,
            'jenis' => $request->jenis,
        ]);

        return response()->json(
            [
                'status' => 201,
                'message' => 'Item created successfully',
                'data' => $item
            ], 201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = MasterItem::find($id);
        if (!$item) {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Item not found'
                ], 404
            );
        }

        return response()->json(
            [
                'status' => 200,
                'data' => $item
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = MasterItem::find($id);
        if (!$item) {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Item not found'
                ], 404
            );
        }

        $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
            'harga_beli' => 'required|numeric',
            'laba' => 'required|numeric',
            'supplier' => 'required'
        ]);

        $item->update([
            'nama' => $request->nama,
            'harga_beli' => $request->harga_beli,
            'laba' => $request->laba,
            'supplier' => $request->supplier,
            'jenis' => $request->jenis,
        ]);

        return response()->json(
            [
                'status' => 200,
                'message' => 'Item updated successfully',
                'data' => $item
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = MasterItem::find($id);
        if (!$item) {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'Item not found'
                ], 404
            );
        }

        $item->delete();

        return response()->json(
            [
                'status' => 200,
                'message' => 'Item deleted successfully'
            ]
        );
    }
}
