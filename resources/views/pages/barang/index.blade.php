@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Data Barang</li>
  </ol>
</nav>
@php
    use App\Models\harga_khusus;
@endphp
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Data Barang</h4>
            <a href="{{ url('/barang/create') }}" class="btn btn-primary btn-sm">Tambah Data</a>
            <button type="button" class=" mx-2 btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
              Excel
          </button>

      <div class="card-body">
        {{-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> --}}
        
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kode Barang</th>
                <th>Barcode</th>
                <th>Category</th>
                <th>Harga Pokok</th>
                <th>Harga</th>
                <th>Stock</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data as $item)
              @php
                  $category = DB::table('category_barangs')->where('uuid', $item->category_id)->first();
              @endphp
              <tr>
                <td>{{ $loop->index+1 }}</td>
                <td>{{$item->name}}</td>
                <td>{{$item->kode_barang}}</td>
                <td>{{$item->kode_barang}}</td>
                <td>@if(!empty($category))
                  {{$category->name}}
                @endif</td>
                <td>{{$item->harga_pokok}}</td>
                <td>{{$item->harga_jual}}</td>
                <td>{{$item->stok}}</td>
                <td>
                  <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_{{$item->uuid}}">Show</button>
                  <a href="{{url("/barang/$item->uuid/edit")}}" class="btn btn-primary btn-sm">Edit</a>
                  <form id="form-delete-{{ $item->id }}" action="{{ route('barang.destroy', $item->uuid) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                <button class="btn btn-danger btn-sm delete-button" data-form-delete="{{ $item->id }}">Delete</button>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
    @foreach ($data as $item)
    <div class="modal fade bd-example-modal-lg" id="modal_{{$item->uuid}}" tabindex="-1" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="exampleModalScrollableTitle">Harga Khusus {{$item->name}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
          </div>
          <div class="modal-body">
            @php
              
                $harga_khusus = harga_khusus::where('id_barang', $item->uuid)->get();
            @endphp
            <div class="table-responsive">
              <table id="dataTableExample" class="table">
                <thead>
                    <tr>
                      <th>Keterangan</th>
                      <th>Jumlah Minimal</th>
                      <th>Harga</th>
                      <th>Diskon</th>
                      <th>#</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($harga_khusus as $item)
                    <tr>
                      <td>{{$item->keterangan}}</td>
                      <td>{{$item->jumlah_minimal}}</td>
                      <td>{{$item->harga}}</td>
                      <td>{{$item->diskon}}</td>
                      <td>
                        <form id="form-delete-{{ $item->id }}" action="{{ route('barang.hapus') }}" method="POST" style="display: none;">
                          @csrf
                          <input type="hidden" value="{{$item->id}}" name="id">
                      </form>
                      <button class="btn btn-danger btn-sm delete-button" data-form-delete="{{ $item->id }}">Delete</button>
                    </tr>
                    @endforeach
                </table>
              </div>

          </div>
          
        </div>
      </div>
    </div>
    @endforeach
    {{-- model --}}
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body">
                    <form action="{{ route('barang.excel') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="excelFile" class="form-label">Choose Excel File</label>
                            <input type="file" class="form-control" id="excelFile" name="file">
                        </div>
                        <!-- Add other necessary form fields for file import if needed -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </form>
                </div>
                
                </div>
            </div>
            </div>
        </div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush