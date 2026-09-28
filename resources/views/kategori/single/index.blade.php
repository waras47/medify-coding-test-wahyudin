@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('kategori')}}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
            </div>
            <div class="card">
                <div class="card-header">Detail Kategori</div>
                <div class="card-body">
                    <table>
                        <tr><th>Kode</th><td>:</td><td>{{$data->kode}}</td></tr>
                        <tr><th>Nama</th><td>:</td><td>{{$data->nama}}</td></tr>
                    </table>
                    <a class="btn btn-info" href="{{url('kategori/form/edit')}}/{{$data->id}}">Edit</a>
                    <a class="btn btn-danger" href="{{url('kategori/delete')}}/{{$data->id}}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    <a class="btn btn-success" href="{{url('kategori/export-pdf')}}/{{$data->kode}}">Download PDF</a>

                    <hr>
                    <h5>Master Item dengan Kategori Ini</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Harga Beli</th>
                                    <th>Supplier</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $mi)
                                <tr>
                                    <td>{{$mi->kode}}</td>
                                    <td>{{$mi->nama}}</td>
                                    <td>{{$mi->jenis}}</td>
                                    <td>{{$mi->harga_beli}}</td>
                                    <td>{{$mi->supplier}}</td>
                                </tr>
                                @empty
                                <tr><td colspan="5">Belum ada item.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection
