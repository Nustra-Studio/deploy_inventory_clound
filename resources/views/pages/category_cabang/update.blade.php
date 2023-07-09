    @extends('layout.master')

    @section('content')
    <nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/categorycabang">Category Cabang</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Category Cabang</li>
    </ol>
    </nav>

    <div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">

            <h6 class="card-title">Input Cabang Category</h6>

            <form 
            action="{{ route('categorycabang.update', $data->uuid) }}" 
            method="POST" 
            enctype="multipart/form-data"    
            class="forms-sample">
                @csrf
                @method('PUT')
            <div class="mb-3">
                <label for="exampleInputUsername1" class="form-label">Nama</label>
                <input type="text" value="{{$data->name}}" name="name" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="Nama Catgory Barang">
            </div>
            <div class="mb-3">
                <label for="exampleInputUsername2" class="form-label">Keterangan</label>
                <input type="text" name="keterangan" value="{{$data->keterangan}}" class="form-control" id="exampleInputUsername2" autocomplete="off" placeholder="Keterangan Cabang">
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
