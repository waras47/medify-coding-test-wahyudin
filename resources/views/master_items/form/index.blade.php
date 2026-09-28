@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('master-items')}}" class="btn btn-secondary">Kembali ke Daftar Item</a>
            </div>
            <div class="card">

                @if($method == 'new')
                <div class="card-header">Buat Master Item Baru</div>
                @else
                <div class="card-header">Edit Master Item</div>
                @endif

                <div class="card-body">
                    @include('master_items.form.form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    function previewFoto(input) {
        var preview = document.getElementById('foto-preview');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = '';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection