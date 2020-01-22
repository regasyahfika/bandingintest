@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Aplikasi Pengajian</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form id="regForm" autocomplete="off" class="form-group">
                            {{--                        <div class="form-group">--}}
                            {{--                            <span class="form-control alert-danger" id="error" style="display: none"></span>--}}
                            {{--                            <span class="form-control alert-success" id="success" style="display: none"></span>--}}
                            {{--                        </div>--}}
                            <div class="form-group">
                                <label for="">Nama Karyawan</label>
                                <input type="text" name="name" placeholder="Input Nama Karyawan" id="name"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Gaji Karyawan</label>
                                <input type="text" name="gaji" placeholder="Input Gaji Karyawan" id="gaji"
                                       class="form-control price number">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Tidak Hadir (Rp. 200.000/day)</label>
                                        <input type="number" id="tidak_hadir" class="form-control"
                                               placeholder="Input Hari">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Telat Hadir (Rp. 50.000/day)</label>
                                        <input type="number" id="telat_hadir" class="form-control"
                                               placeholder="Input Hari">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Jamsostek (2%)</label>
                                        <input type="text" id="jamsostek" class="form-control" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="">PPH 21 (1%)</label>
                                        <input type="text" id="pph" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">BPJS Kesehatan (2%)</label>
                                        <input type="text" id="bpjs" class="form-control" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Iuran Pensiun (2%)</label>
                                        <input type="text" id="iuran" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12 pb-2">
                                    <label for="">Total : <span id="total"></span></label>
                                </div>

                            </div>
                            <button id="btnCheck" type="button" class="btn btn-success btn-sm">Check Gaji</button>
                            {{--                            <button class="btn btn-primary btn-sm" type="reset">Clear Data</button>--}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        var hitung = {
            gajipokok: 0,
            jamsostek: 0,
            tdk_hadir: 0,
            pph: 0,
            bpjs: 0,
            iuran: 0,
            telat_hadir: 0,
            total: 0
        };

        function formatMoney(n, c, d, t) {
            var c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? "." : d,
                t = t == undefined ? "," : t,
                s = n < 0 ? "-" : "",
                i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
                j = (j = i.length) > 3 ? j % 3 : 0;

            return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
        }

        function digitOnly(e) {
            let allowed = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'MetaLeft', 'MetaRight'];
            if (!allowed.includes(e.key)) {
                return false
            }
            return true
        }

        $(document).on('change keydown', 'input[type="number"], input[type="tel"], .number', function (e) {
            if (!digitOnly(e)) {
                e.preventDefault();
            }
        });


        $(document).on('click', '#btnCheck', function () {
            $('label.error').remove();
            $.ajax({
                url: '{{ route('checkgaji') }}',
                data: {
                    gaji: $('#gaji').val(),
                    name: $('#name').val(),
                    tidak_hadir: $('#tidak_hadir').val(),
                    telat_hadir: $('#telat_hadir').val()
                },
                success: function (data) {
                    $(document).find('#jamsostek').val('Rp ' + formatMoney(data.jamsostek, 0, '.', '.'));
                    $(document).find('#iuran').val('Rp ' + formatMoney(data.iuran, 0, '.', '.'));
                    $(document).find('#bpjs').val('Rp ' + formatMoney(data.bpjs, 0, '.', '.'));
                    $(document).find('#pph').val('Rp ' + formatMoney(data.pph, 0, '.', '.'));
                    $(document).find('#total').text('Rp ' + formatMoney(data.total, 0, '.', '.'));

                    hitung.jamsostek = data.jamsostek;
                    hitung.iuran = data.iuran;
                    hitung.bpjs = data.bpjs;
                    hitung.pph = data.pph;
                    hitung.total = data.total;
                },
                error: function (e) {
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').closest('.form-group').append('<label class="form-control alert-danger error">' + e[0] + '</label>');
                        })
                    }
                }
            });
        });
    </script>
@stop
