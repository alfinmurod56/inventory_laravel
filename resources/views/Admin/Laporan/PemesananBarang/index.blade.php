@extends('Master.Layouts.app', ['title' => $title])

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Laporan Pemesanan Barang</h1>

    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Data</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="" class="fw-bold">Filter Tanggal</label>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="tglawal" class="form-control datepicker-date"
                                    placeholder="Tanggal Awal">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="tglakhir" class="form-control datepicker-date"
                                    placeholder="Tanggal Akhir">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-success-light" onclick="filter()"><i class="fe fe-filter"></i>
                                Filter</button>
                            <button class="btn btn-secondary-light" onclick="reset()"><i class="fe fe-refresh-ccw"></i>
                                Reset</button>
                            <button class="btn btn-primary-light" onclick="print()"><i class="fe fe-printer"></i>
                                Print</button>
                            <button class="btn btn-danger-light" onclick="pdf()"><i class="fa fa-file-pdf-o"></i>
                                PDF</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table-1"
                            class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                            <thead>
                                <th class="border-bottom-0" width="1%">No</th>
                                <th class="border-bottom-0">Tanggal Pesan</th>
                                <th class="border-bottom-0">Kode Pemesanan</th>
                                <th class="border-bottom-0">Kode Barang</th>
                                <th class="border-bottom-0">Barang</th>
                                <th class="border-bottom-0">QTY</th>
                                <th class="border-bottom-0">Total Harga</th>
                                <th class="border-bottom-0">Supplier</th>
                                <th class="border-bottom-0">Status</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW -->
@endsection

@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            getData();
        });

        function getData() {
            //datatables
            table = $('#table-1').DataTable({

                "processing": true,
                "serverSide": true,
                "info": true,
                "order": [],
                "scrollX": true,
                "stateSave": true,
                "lengthMenu": [
                    [5, 10, 25, 50, 100, -1],
                    [5, 10, 25, 50, 100, 'Semua']
                ],
                "pageLength": 10,

                lengthChange: true,

                "ajax": {
                    "url": "{{ route('lap-pb.getlap-pb') }}",
                    "data": function(d) {
                        d.tglawal = $('input[name="tglawal"]').val();
                        d.tglakhir = $('input[name="tglakhir"]').val();
                    }
                },

                "columns": [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'tgl',
                        name: 'pesan_tanggal',
                    },
                    {
                        data: 'pesan_kode',
                        name: 'pesan_kode',
                    },
                    {
                        data: 'barang_kode',
                        name: 'barang_kode',
                    },

                    {
                        data: 'barang',
                        name: 'barang_nama',
                    },
                    {
                        data: 'pesan_jumlah',
                        name: 'pesan_jumlah',
                    },
                    {
                        data: 'pesan_totalharga',
                        name: 'pesan_totalharga',
                    },
                    {
                        data: 'supplier',
                        name: 'supplier_nama',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                ],

            });
        }

        function filter() {
            var tglawal = $('input[name="tglawal"]').val();
            var tglakhir = $('input[name="tglakhir"]').val();
            if (tglawal != '' && tglakhir != '') {
                table.ajax.reload(null, false);
            } else {
                validasi("Isi dulu Form Filter Tanggal!", 'warning');
            }

        }

        function reset() {
            $('input[name="tglawal"]').val('');
            $('input[name="tglakhir"]').val('');
            table.ajax.reload(null, false);
        }

        function print() {
            var tglawal = $('input[name="tglawal"]').val();
            var tglakhir = $('input[name="tglakhir"]').val();
            if (tglawal != '' && tglakhir != '') {
                window.open(
                    "{{ route('lap-pb.print') }}?tglawal=" + tglawal + "&tglakhir=" + tglakhir,
                    '_blank'
                );
            } else {
                swal({
                    title: "Yakin Print Semua Data?",
                    type: "warning",
                    buttons: true,
                    dangerMode: true,
                    confirmButtonText: "Yakin",
                    cancelButtonText: 'Batal',
                    showCancelButton: true,
                    showConfirmButton: true,
                    closeOnConfirm: false,
                    confirmButtonColor: '#09ad95',
                }, function(value) {
                    if (value == true) {
                        window.open(
                            "{{ route('lap-pb.print') }}",
                            '_blank'
                        );
                        swal.close();
                    }
                });

            }

        }

        function pdf() {
            var tglawal = $('input[name="tglawal"]').val();
            var tglakhir = $('input[name="tglakhir"]').val();
            if (tglawal != '' && tglakhir != '') {
                window.open(
                    "{{ route('lap-pb.pdf') }}?tglawal=" + tglawal + "&tglakhir=" + tglakhir,
                    '_blank'
                );
            } else {
                swal({
                    title: "Yakin export PDF Semua Data?",
                    type: "warning",
                    buttons: true,
                    dangerMode: true,
                    confirmButtonText: "Yakin",
                    cancelButtonText: 'Batal',
                    showCancelButton: true,
                    showConfirmButton: true,
                    closeOnConfirm: false,
                    confirmButtonColor: '#09ad95',
                }, function(value) {
                    if (value == true) {
                        window.open(
                            "{{ route('lap-pb.pdf') }}",
                            '_blank'
                        );
                        swal.close();
                    }
                });

            }

        }

        function validasi(judul, status) {
            swal({
                title: judul,
                type: status,
                confirmButtonText: "Iya."
            });
        }
    </script>
@endsection