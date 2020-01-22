@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Aplikasi Palindrom</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form id="regForm" autocomplete="off" class="form-group">
                        <div class="form-group">
                            <span class="form-control alert-danger" id="error" style="display: none"></span>
                            <span class="form-control alert-success" id="success" style="display: none"></span>
                        </div>
                        <div class="form-group">
                            <input type="text" name="palindrom" placeholder="Input Palindrom" id="data" class="form-control">
                        </div>
                        <button id="btnSubmit" type="button" class="btn btn-success btn-sm">Check</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
    <script>
        $(document).on('click', '#btnSubmit', function () {
            let data = $(document).find('input[name=palindrom]').val();
            $(document).find('label.error').remove();
            $(document).find('#success').hide();
            $.ajax({
                url: '{{ route('searchajax') }}',
                data: {
                    palindrom: data
                },
                success: function (data) {
                    $('#success').show().text(data.data)
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
