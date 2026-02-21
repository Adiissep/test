<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Category - {{ $category->nama }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        
        .container {
            padding: 20px;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 12px;
            color: #666;
        }
        
        /* Category Info */
        .category-info {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f5f5f5;
        }
        
        .category-info h2 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .category-info table {
            width: 100%;
        }
        
        .category-info table td {
            padding: 5px 0;
        }
        
        .category-info table td:first-child {
            width: 120px;
            font-weight: bold;
        }
        
        /* Items Table */
        .items-section h3 {
            font-size: 14px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .items-table th {
            background-color: #4a90d9;
            color: white;
            font-weight: bold;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        .no-items {
            padding: 20px;
            text-align: center;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            color: #666;
        }
        
        /* Summary */
        .summary {
            margin-top: 15px;
            padding: 10px;
            background-color: #e8f4fd;
        }
        
        .summary p {
            font-weight: bold;
        }
        
        /* Footer */
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>DETAIL CATEGORY</h1>
            <p>Laporan Data Category dan Item Terkait</p>
        </div>
        
        <!-- Category Info -->
        <div class="category-info">
            <h2>Informasi Category</h2>
            <table>
                <tr>
                    <td>Kode Category</td>
                    <td>: {{ $category->kode }}</td>
                </tr>
                <tr>
                    <td>Nama Category</td>
                    <td>: {{ $category->nama }}</td>
                </tr>
                <tr>
                    <td>Jumlah Item</td>
                    <td>: {{ $items->count() }} item</td>
                </tr>
            </table>
        </div>
        
        <!-- Items Section -->
        <div class="items-section">
            <h3>Daftar Item dengan Category Ini</h3>
            
            @if($items->count() > 0)
                <table class="items-table">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="12%">Kode Item</th>
                            <th width="25%">Nama Item</th>
                            <th width="10%">Jenis</th>
                            <th class="text-right" width="15%">Harga Beli</th>
                            <th class="text-center" width="8%">Laba (%)</th>
                            <th class="text-right" width="15%">Harga Jual</th>
                            <th width="10%">Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item->kode }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jenis }}</td>
                            <td class="text-right">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->laba }}%</td>
                            <td class="text-right">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ $item->supplier }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Summary -->
                <div class="summary">
                    <p>Total Item: {{ $items->count() }} item</p>
                </div>
            @else
                <div class="no-items">
                    <p>Belum ada item yang memiliki category ini.</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Footer dengan tanggal cetak -->
    <div class="footer">
        <p>Dicetak pada: {{ $printed_at }}</p>
    </div>
</body>
</html>