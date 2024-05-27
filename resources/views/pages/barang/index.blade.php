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
            <button type="button" class="mx-2 btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Excel</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="masterdata" class="table">
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
                </table>
            </div>
        </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $('#masterdata').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/resource/barang/datatables',
            data: function (d) {
                // Add custom parameters for server-side processing here if needed
            }
        },
        searchDelay: 500, 
        pageLength: 25, 
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'kode_barang', name: 'kode_barang' },
            { data: 'kode_barang', name: 'kode_barang' },
            { data: 'category', name: 'category' },
            { data: 'harga_pokok', name: 'harga_pokok' },
            { data: 'harga_jual', name: 'harga_jual' },
            { data: 'stok', name: 'stok' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    $('body').on('click', '.delete-button', function () {
        var id = $(this).data("id");
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ url('barang') }}" + '/' + id,
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        $('#masterdata').DataTable().ajax.reload();
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });
});
</script>

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
              @foreach ($harga_khusus as $hk)
              <tr>
                <td>{{$hk->keterangan}}</td>
                <td>{{$hk->jumlah_minimal}}</td>
                <td>{{$hk->harga}}</td>
                <td>{{$hk->diskon}}</td>
                <td>
                  <form id="form-delete-{{ $hk->id }}" action="{{ route('barang.hapus') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" value="{{$hk->id}}" name="id">
                  </form>
                  <button class="btn btn-danger btn-sm delete-button" data-form-delete="{{ $hk->id }}">Delete</button>
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
@endforeach

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('barang.excel') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="excelFile" class="form-label">Choose Excel File</label>
            <input type="file" class="form-control" id="excelFile" name="file">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Import</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Add SweetAlert -->
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
