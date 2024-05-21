@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Distribusi Barang</li>
  </ol>
</nav>
@php
    use App\Models\cabang;
    use App\Models\suplier;
    $data_cabang = cabang::where('uuid',$uuid_cabang)->first();

@endphp
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="input-group mb-3">
          <span class="input-group-text" id="inputGroup-sizing-default">Nama Cabang :</span>
          <span class="form-control" id="inputGroup-sizing-default">{{$data_cabang->nama}}</span>
        </div>
        <div class="input-group mb-3">
          <span class="input-group-text" id="inputGroup-sizing-default">Nama Kepala Cabang:</span>
          <span class="form-control" id="inputGroup-sizing-default">{{$data_cabang->kepala_cabang}}</span>
        </div>
        
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-7 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table id="dataTableExample1" class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Supplier</th>
                <th>Stock</th>
                <th>Jumlah</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tb-local">
              @foreach ($barang as $item)
              @php
                  $suplier = suplier::where('uuid', $item->id_supplier)->value('nama');
              @endphp
              <tr>
                  <td>{{$loop->index+1}}</td>
                  <td>{{$item->kode_barang}}</td>
                  <td>{{$item->name}}</td>
                  <td>{{$suplier}}</td>
                  <td>{{$item->stok}}</td>
                  <td><input type="number" name="jumlah" value="0" class="form-control form-control-sm"></td>
                  <td>
                    <div class="text-center">
                      <button type="button" class="btn btn-primary btn-icon add-to-tb-barang">
                        <i data-feather="repeat"></i>
                      </button>
                    </div>
                    <input type="hidden" name="kode" value="{{ $item->kode_barang}}">
                    <input type="hidden" name="nama" value="{{ $item->name }}">
                    <input type="hidden" name="stock" value="{{ $item->stock }}">
                  </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-5 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <form  
              action="{{ route('distribusi.barang.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              id="postDataForm1" >
              @csrf
              {{-- create input hidden value $uuid_cabang name id_cabang --}}
              <input type="hidden" name="id_cabang" value="{{$uuid_cabang}}">
          <table id="dataTableExample2" class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Jumlah</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="tb-barang">
              <!-- Data akan ditambahkan melalui JavaScript -->
            </tbody>
          </table>
        </div>
        <div class="d-grid  col-6 mx-auto mt-3">
          <button type="button" class="btn btn-outline-success "   onclick="submitForm()" id="kirim" style="display: none;">
            <i data-feather="send"></i>
            Kirim
          </button>
        </div>
        </form>
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
  <script>
    $(document).ready(function() {
      var tableExample;
      var tableExample2;

      // Initialize DataTableExample
      function initDataTableExample() {
        tableExample = $('#dataTableExample1').DataTable();
      }

      // Initialize DataTableExample2
      function initDataTableExample2() {
        tableExample2 = $('#dataTableExample2').DataTable();
      }

      // Initialize DataTables
      initDataTableExample();
      initDataTableExample2();

      $(document).on('click', '.add-to-tb-barang', function() {
      var row = $(this).closest('tr');
      var kode = row.find('input[name="kode"]').val();
      var nama = row.find('input[name="nama"]').val();
      var stock = parseInt(row.find('input[name="stock"]').val());
      var jumlah = parseInt(row.find('input[name="jumlah"]').val());

      // Validasi jumlah
      if (jumlah < 1) {
        alert('Jumlah harus lebih besar dari 0.');
        return;
      }

      // Cek apakah item sudah ada di tb-barang
      var existingRow = tableExample2.rows().nodes().toArray().find(function(node) {
        return $(node).find('td:eq(1)').text() === kode;
      });

      if (existingRow) {
        // Jika item sudah ada, update stocknya
        var existingJumlah = parseInt($(existingRow).find('td:eq(3)').text());
        var updatedJumlah = existingJumlah + jumlah;
        $(existingRow).find('td:eq(3)').text(updatedJumlah);

        // Update value di input jumlah
        $(existingRow).find('input[name="jumlah"]').val(updatedJumlah);
      } else {
        // Jika item belum ada, tambahkan baris baru
        var newRow = '<tr>';
        newRow += '<input type="hidden" name="kode[]" value="' + kode + '">';
        newRow += '<input type="hidden" name="nama[]" value="' + nama + '">';
        newRow += '<input type="hidden" name="jumlah[]" value="' + jumlah + '">';
        newRow += '<td>' + (tableExample2.rows().count() + 1) + '</td>';
        newRow += '<td>' + kode + '</td>';
        newRow += '<td>' + nama + '</td>';
        newRow += '<td>' + jumlah + '</td>';
        newRow += '<td><button class="btn btn-danger btn-icon delete-row"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button></td>';
        newRow += '</tr>';

        // Tambahkan baris ke tb-barang
        tableExample2.row.add($(newRow)).draw();
      }

      // Tampilkan tombol Send
      toggleSendButton();
    });

      // Menghapus baris pada tb-barang
      $(document).on('click', '.delete-row', function() {
        tableExample2.row($(this).closest('tr')).remove().draw();

        // Tampilkan atau sembunyikan tombol Send
        toggleSendButton();
      });

      // Toggle tombol Send
      function toggleSendButton() {
        var rowCount = tableExample2.rows().count();
        var sendButton = $('#kirim');

        if (rowCount > 0) {
          sendButton.show();
        } else {
          sendButton.hide();
        }
      }

      // Submit form
      // $('#postDataForm').submit(function(e) {
      //   e.preventDefault();
      //   var dataBarang = tableExample2.data().toArray();

      //   // Mengisi nilai input hidden dengan dataBarang yang dikonversi ke JSON
      //   $('#dataBarangInput').val(JSON.stringify(dataBarang));

      //   // Kirim dataBarang ke server menggunakan AJAX atau form submit
      //   // ...

      //   // // Contoh menggunakan AJAX
      //   // $.ajax({
      //   //   url: '/post-data',
      //   //   method: 'POST',
      //   //   data: $(this).serialize(),
      //   //   success: function(response) {
      //   //     // Handle response dari server
      //   //   },
      //   //   error: function(error) {
      //   //     // Handle error pada AJAX request
      //   //   }
      //   // });
      // });
    });
    function submitForm() {
    var form = document.getElementById("postDataForm1");
    form.submit(); // Mengirimkan formulir secara otomatis
  }
  </script>
@endpush
