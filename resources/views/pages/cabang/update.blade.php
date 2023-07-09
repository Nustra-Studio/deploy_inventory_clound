    @extends('layout.master')

    @section('content')
    <nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/cabang">Cabang</a></li>
        <li class="breadcrumb-item active" aria-current="page">update Cabang</li>
    </ol>
    </nav>
    @php
        use App\Models\category_cabang;
        $cabang = category_cabang::all();
    @endphp
    <div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">

            <h6 class="card-title">Input Cabang</h6>
            <form 
            action="{{ route('cabang.update', $data->uuid) }}" 
            method="POST" 
            enctype="multipart/form-data"    
            class="forms-sample">
                @csrf
                @method('PUT')
                @php
                $uniqueValue = hash('sha256', uniqid(mt_rand(), true));

                @endphp
            <div class="mb-3">
                <label for="exampleInputUsername1" class="form-label">Nama</label>
                <input type="text" value="{{$data->nama}}" name="nama" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="Nama Cabang">
            </div>
            <div class="mb-3">
                <label for="exampleInputUsername2" class="form-label">Kepala</label>
                <input name="kepala_cabang" value="{{$data->kepala_cabang}}" type="text" class="form-control" id="exampleInputUsername2" autocomplete="off" placeholder="Kepala Cabang">
            </div>
            <div class="mb-3">
                <label for="exampleInputUsername3" class="form-label">Telepon</label>
                <input type="number" name="telepon" value="{{$data->telepon}}" class="form-control" id="exampleInputUsername3" autocomplete="off" placeholder="Telepon">
            </div>
            <div class="mb-3">
                <label for="exampleInputUsername4" class="form-label">Alamat</label>
                <input type="text" name="alamat" value="{{$data->alamat}}" class="form-control" id="exampleInputUsername4" autocomplete="off" placeholder="Alamat Cabang">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlSelect1" class="form-label">Category Cabang</label>
                <select class="form-select" name="category_id" id="exampleFormControlSelect1">
                    @foreach ($cabang as $item)
                    <option value="{{$item->id}}
                        @if ($item->id == $data->category_id)
                            selected
                        @endif
                        ">{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="exampleInputUsername4" class="form-label">Keterangan</label>
                <input type="text" name="keterangan" value="{{$data->keterangan}}" class="form-control" id="exampleInputUsername4" autocomplete="off" placeholder="Keterangan Cabang">
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
