<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    var start_date = '';
    var end_date = '';
    var data_per_fetch = 500;
    var data_fetched = 0;

    $(document).ready(function() {
        $('#table').DataTable({
            searching: false,
            order: [[0, 'desc']],
        });
        getData()
    });

    $('.btn-get-data').click(function() {
        getData()
    })

    function getData(){
        
        $('#loading-filter').show();
        var dataTableObj = $('#table').DataTable();
        var filter_kode = $('#filter-kode').val()
        var filter_nama = $('#filter-nama').val()
        var filter_harga_min = $('#filter-harga-min').val()
        var filter_harga_max = $('#filter-harga-max').val()
        dataTableObj.clear().draw();

        $.ajax({
            url: '{{url("master-items/search")}}',
            dataType: 'json',
            tryCount: 0,
            retryLimit: 3,
            data: 'kode=' + filter_kode + '&nama=' + filter_nama + '&hargamin=' + filter_harga_min + '&hargamax=' + filter_harga_max,
            success: function(results) {
                var data = results.data

                $.each(data, function(index, item) {
                    var harga_jual = item.harga_beli + item.harga_beli * item.laba / 100;
                    harga_jual = Math.round(harga_jual)
                    var kode = item.kode;

                    var viewBtn = `<a href="{{url('master-items/view/')}}/` + kode + `" class="btn btn-primary btn-sm">View</a>`

                    var fotoHtml = '';
                    if (item.foto && item.foto !== null && item.foto !== '') {
                        fotoHtml = `<img src="{{ url('storage/items') }}/` + item.foto + `" alt="Foto" width="50" height="50" style="object-fit: cover;" onerror="this.onerror=null; this.src='https://via.placeholder.com/50';">`;
                    } else {
                        fotoHtml = `<span class="text-muted">No Image</span>`;
                    }

                    // Categories
                    var categoriesHtml = '';
                    if (item.categories && item.categories !== '') {
                        categoriesHtml = item.categories;
                    } else {
                        categoriesHtml = '<span class="text-muted">-</span>';
                    }

                    // Susun data sesuai urutan kolom di tabel (9 kolom)
                    var rowData = [
                        item.kode,           // 1. Kode
                        item.nama,           // 2. Nama
                        item.jenis,          // 3. Jenis
                        'Rp ' + Number(item.harga_beli).toLocaleString('id-ID'),  // 4. Harga Beli
                        'Rp ' + Number(harga_jual).toLocaleString('id-ID'),       // 5. Harga Jual
                        item.supplier,       // 6. Supplier
                        categoriesHtml,      // 7. Categories
                        fotoHtml,            // 8. Foto
                        viewBtn              // 9. View
                    ];

                    dataTableObj.row.add(rowData).draw(false);
                });
                
                dataTableObj.draw();
                $('#loading-filter').hide();
            },
            error: function(xhr, textStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
                alert('Terjadi kesalahan server, tidak dapat mengambil data')
                $('#loading-filter').hide();

                return;
            }
        })
    }
</script>