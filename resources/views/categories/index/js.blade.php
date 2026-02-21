<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table').DataTable({
            searching: false,
            order: [[0, 'desc']],
        });
        getData();
    });

    $('.btn-get-data').click(function() {
        getData();
    });

    $('.btn-reset-filter').click(function() {
        $('#filter-kode').val('');
        $('#filter-nama').val('');
        getData();
    });

    $('#filter-kode, #filter-nama').keypress(function(e) {
        if (e.which == 13) {
            getData();
        }
    });

    function getData() {
        $('#loading-filter').show();
        var dataTableObj = $('#table').DataTable();
        var filter_kode = $('#filter-kode').val();
        var filter_nama = $('#filter-nama').val();
        dataTableObj.clear().draw();

        $.ajax({
            url: '{{ url("categories/search") }}',
            dataType: 'json',
            data: {
                kode: filter_kode,
                nama: filter_nama
            },
            success: function(results) {
                var data = results.data;

                $.each(data, function(index, item) {
                    var viewBtn = `<a href="{{ url('categories/view') }}/` + item.kode + `" class="btn btn-primary btn-sm">View</a>`;

                    var rowData = [
                        item.kode,
                        item.nama,
                        viewBtn
                    ];

                    dataTableObj.row.add(rowData).draw(false);
                });

                dataTableObj.draw();
                $('#loading-filter').hide();
            },
            error: function(xhr, textStatus, errorThrown) {
                alert('Terjadi kesalahan server');
                $('#loading-filter').hide();
            }
        });
    }
</script>