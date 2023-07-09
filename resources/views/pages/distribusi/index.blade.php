@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Distribusi</li>
  </ol>
</nav>
  @php
    use App\Models\cabang;
      $data = cabang::all();
  @endphp
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        {{-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> --}}
        
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Toko</th>
                <th>Pimpinan Toko</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tb-category">
              @foreach ($data as $item)
              <tr>

                    <td>{{$loop->index+1}}</td>
                    <td>{{$item->nama}}</td>
                    <td>{{$item->kepala_cabang}}</td>
                    <td>{{$item->alamat}}</td>
                    <td>{{$item->telepon}}</td>
                    <td>
                      <div class="text-center">
                        <a href="{{url("/distribusi/$item->uuid/barang")}}" class="btn btn-primary btn-icon">
                          <i data-feather="repeat"></i>
                        </a>
                      </div>
                    </td>

                </tr>
                @endforeach              
            </tbody>
          </table>
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