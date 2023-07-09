    @extends('layout.master')

    @section('content')
    <nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/category">Category</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Category Barang</li>
    </ol>
    </nav>

    <div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">

            <h6 class="card-title">Input Category</h6>
            <form 
            action="{{ route('category.store') }}" 
            method="POST" 
            enctype="multipart/form-data"    
            class="forms-sample">
                @csrf 
                @php
                $uniqueValue = hash('sha256', uniqid(mt_rand(), true));

                @endphp
                <input type="text" hidden value="{{$uniqueValue}}" name="uuid">
            <div class="mb-3">
                <label for="exampleInputUsername1" class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="Nama Catgory Barang">
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
