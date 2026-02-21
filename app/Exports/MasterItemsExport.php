<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MasterItemsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $rowNumber = 0;

    /**
     * Ambil data dengan eager loading categories
     */
    public function collection()
    {
        return MasterItem::with('categories')->orderBy('id')->get();
    }

    /**
     * Header kolom Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori',
            'Nama Items',
            'Nama Supplier',
            'Harga',
            'Laba (%)',
            'Harga Jual',
        ];
    }

    /**
     * Mapping data per row
     */
    public function map($item): array
    {
        $this->rowNumber++;

        // Ambil nama kategori, pisahkan dengan koma
        $categories = $item->categories->pluck('nama')->implode(', ');
        
        // Hitung harga jual
        $hargaJual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);

        return [
            $this->rowNumber,
            $categories ?: '-',
            $item->nama,
            $item->supplier,
            $item->harga_beli,
            $item->laba,
            round($hargaJual),
        ];
    }

    /**
     * Styling untuk Excel
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row bold
            1 => ['font' => ['bold' => true]],
        ];
    }
}