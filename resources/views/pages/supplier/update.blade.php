@extends('layout.master')

@section('content')
<nav class="page-breadcrumb">
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supplier">Supplier</a></li>
    <li class="breadcrumb-item active" aria-current="page">Update Supplier</li>
</ol>
</nav>

<div class="row">
<div class="col-md-8 grid-margin stretch-card">
    <div class="card">
    <div class="card-body">

        <h6 class="card-title">Input Supplier</h6>

        <form 
        action="{{ route('supllier.update', $data->uuid) }}" 
        method="POST" 
        enctype="multipart/form-data"    
        class="forms-sample">
            @csrf
            @method('PUT')
        <div class="mb-3">
            <label for="exampleInputUsername1" class="form-label">Nama</label>
            <input type="text" value="{{$data->nama}}" name="nama" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="Nama ">
        </div>
        <div class="mb-3">
            <label for="exampleInputUsername2" class="form-label">Supplier</label>
            <input name="supplier" value="{{$data->product}}" type="text" class="form-control" id="exampleInputUsername2" autocomplete="off" placeholder="Supplier">
        </div>
        <div class="mb-3">
            <label for="exampleInputUsername3" class="form-label">Telepon</label>
            <input type="number" name="telepon" value="{{$data->telepon}}" class="form-control" id="exampleInputUsername3" autocomplete="off" placeholder="Telepon">
        </div>
        <div class="mb-3">
            <label for="exampleInputUsername4" class="form-label">Alamat</label>
            <input type="text" name="alamat" value="{{$data->alamat}}" class="form-control" id="exampleInputUsername4" autocomplete="off" placeholder="Alamat Supplier">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlSelect1" class="form-label">Category Supplier</label>
            <select class="form-select" name="category" id="exampleFormControlSelect1">
                @php
                    use App\Models\category_barang;
                    $categorys = category_barang::all();
                @endphp
                @foreach ($categorys as $item)
                <option value="{{$item->id}}"
                    @if (!empty($item))
                        @if ($item->id == $data->category_barang_id)
                        selected
                        
                        @endif
                    @endif
                    >{{$item->name}}
                </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="exampleInputUsername4" class="form-label">Keterangan</label>
            <input type="text" name="keterangan" value="{{$data->keterangan}}" class="form-control" id="exampleInputUsername4" autocomplete="off" placeholder="Note">
        </div>

        <button type="submit" class="btn btn-primary me-2">Submit</button>
        <button  
        onclick="window.history.go(-1); return false;"
        type="submit"
        value="Cancel" class="btn btn-secondary">Cancel</button>
        </form>

    </div>
    </div>
</div>
</div>

@endsection
