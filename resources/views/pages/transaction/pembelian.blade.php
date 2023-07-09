@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
@php
    use App\Models\suplier;
@endphp

@php
    use App\Models\harga_khusus;
@endphp
<div class="row">
  <div class="d-flex justify-content-end align-items-center flex-wrap grid-margin">
    <form 
      action="{{ route('transaction.pembelian.cari') }}" 
            method="POST" 
            enctype="multipart/form-data"    
    >
    @csrf
    <div class="d-flex align-items-center flex-wrap text-nowrap">
      <span class="me-2 text-secondary">Mulai:</span>
      <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
        <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle><i data-feather="calendar" class="text-primary"></i></span>
        <input type="text" name="from" class="form-control bg-transparent border-primary" placeholder="Select date" data-input>
      </div>
      <span class="me-2 text-secondary">Sampai:</span>
      <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
        <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle><i data-feather="calendar" class="text-primary"></i></span>
        <input type="text" name="to" class="form-control bg-transparent border-primary" placeholder="Select date" data-input>
      </div>
      <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
        <select name="supplier" class="form-select" id="" name="supplier">
          <option value="all">ALL Supllier</option>
          @foreach (suplier::all() as $item)
            <option value="{{ $item->uuid }}">{{ $item->nama }}</option>
          @endforeach
        </select>
      </div>
      <button type="post" class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
        <i class="btn-icon-prepend" data-feather="search"></i>
          cari
      </button>
    </form>
    <form action="{{ route('transaction.pembelian.pdf') }}" 
    method="POST" 
    enctype="multipart/form-data"  >
      @csrf
      <input type="hidden" value="{{$hidde['supplier']}}" name="supplier" >
      <input type="hidden" value="{{$hidde['from']}}" name="from" >
      <input type="hidden" value="{{$hidde['to']}}" name="to" >
      <button type="submit" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
        <i class="btn-icon-prepend" data-feather="download-cloud"></i>
        Download
      </button>
    </form>
</div>
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">History Pembelian</h4>
      <div class="card-body">
        {{-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> --}}
        
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Product</th>
                <th>Kode Barang</th>
                <th>Jumlah</th>
                <th>Harga Pokok</th>
                <th>Harga Jual</th>
                <th>Supplier</th>
                <th>Waktu</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data as $item)
              @php
                   $supplier = suplier::where('uuid', $item->id_supllayer)->first();
              @endphp
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->kode_barang}}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ $item->harga_pokok }}</td>
                <td>{{ $item->harga_jual }}</td>
                <td>{{$supplier->nama }}</td>
                <td>{{ $item->created_at }}</td>
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
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush