@extends('layout.master')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  --}}

@php
    use App\Models\category_cabang;
    use App\Models\suplier;
    $cabang = category_cabang::all();
    $supplier = suplier::all();
@endphp
    <div class="row">
        <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
            <h6 class="card-title">Input Barang</h6>
                <form class="forms-sample">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Product Name:</label>
                        <select class="js-example-basic-single form-select" name="" id="product-name-input">
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Supplier:</label>
                        <select class="form-control" id="supplier-input">
                            @foreach ($supplier as $item)
                            <option value="{{$item->nama}}">{{$item->nama}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Rest of the form content -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Harga Pokok</label>
                        
                        <input id="harga-pokok-input" class="form-control mb-4 mb-md-0"/>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga Jual</label>
                        <input id="harga-jual-input" class="form-control mb-4 mb-md-0"  />
                    </div>
                </div>
                <!-- Rest of the form content -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Jumlah</label>
                        <input id="jumlah-input" class="form-control mb-4 mb-md-0"/>
                    </div>
                </div>
                <!-- Rest of the form content -->
                <input type="button" class="btn btn-warning me-2" value="Tambah Barang"data-bs-toggle="modal" data-bs-target="#exampleModalLongScollable" />
                <input type="button" class="btn btn-success me-2" value="Tambah" onclick="addRow()" />
                <button type="button" class=" mx-2 btn btn-success" data-bs-toggle="modal" data-bs-target="#excelmodal">
                    Excel
                </button>
                <button onclick="window.history.go(-1); return false;" type="submit" value="Cancel" class="btn btn-secondary">Cancel</button>
            </form>

            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">Tabel Input</h6>
                <div class="tabel-sementara" class="mt-5">
                    <div class="table-responsive">
                        <table id="product-table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Barang</th>
                                    <th>Harga Pokok Barang</th>
                                    <th>Harga jual Barang</th>
                                    <th>Supplier Barang</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table rows will be dynamically added here -->

                            </tbody>
                        </table>
                    </div>
                    <form class="forms-sample"
                    action="{{ route('barang.input.create') }}" 
                    method="POST" 
                    enctype="multipart/form-data" 
                    >
                    @csrf
                        <!-- Rest of the form content -->
                        <input type="hidden" id="data-table-values" name="data_table_values">
                        <input type="submit" class="btn btn-primary me-2" value="Kirim">
                        <!-- Rest of the code -->
                    </form>
                </div>
                    <!-- Modal -->
            
                    <div class="modal fade bd-example-modal-lg" id="exampleModalLongScollable" tabindex="-1" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Create Data Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                        </div>
                        <div class="modal-body">
                            @php
                                use App\Models\category_barang;
                                $supllier = suplier::all();
                                $category = category_barang::all();
                            @endphp
                                    <form 
                                    action="{{ route('barang.store') }}" 
                                method="POST" 
                                enctype="multipart/form-data"    
                                class="forms-sample">
                                @csrf
                                @php
                                $uniqueValue = hash('sha256', uniqid(mt_rand(), true));
                                @endphp
                                <input type="text" hidden name="uuid" id="" value="{{$uniqueValue}}">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label class="form-label">Product Name:</label>
                                            <input name="name" class="form-control mb-4 mb-md-0" id="product-name-input" type="text" placeholder="Search for a product..." />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Merek Barang</label>
                                            <input name="merek_barang" class="form-control mb-4 mb-md-0" id="product-name-input" type="text" placeholder="Merek Barang" />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Supplier:</label>
                                            <select class="form-control" name="supplier" id="supplier-select">
                                                @foreach ($supllier as $item)
                                                    <option value="{{$item->uuid}}">{{$item->nama}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Rest of the form content -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">category</label>
                                            <select name="category_barang" class="form-control" id="supplier-select">
                                                @foreach ($category as $item)
                                                    <option value="{{$item->uuid}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Jumlah</label>
                                            <input class="form-control" name="jumlah" type="number"/>
                                        </div>
                                    </div>
                                    <!-- Rest of the form content -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Harga Pokok</label>
                                            <input class="form-control mb-4 mb-md-0" id="harga_pokok" name="harga_pokok" type="number" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Harga Jual</label>
                                            <input class="form-control" id="harga_jual" name="harga_jual" type="number" />
                                        </div>
                                    </div>
                                    <label for="">Harga Khusus</label>
                            <table class="table table-bordered" id="tabelhargakhusus">
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
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="nama[]">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="jumlah_minimal[]">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="harga[]">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="diskon[]">
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm float-right btn-info" id="add_tr2" type="button"><i class="fa fa-plus"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    
                                <div class="mt-3">
                            <!-- Rest of the form content -->
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <button data-bs-dismiss="modal" type="button" value="Cancel" class="btn btn-secondary">Cancel</button>
                                        </div>
                                
                                </div>
                            </div>
                            <script>
                                $('#add_tr2').on('click', function (e) {
                                    var newRowContent =
                                        `<tr id="tr2_` + ($("#tabelhargakhusus > tbody > tr").length + 1) + `">` +
                                        `<td>` +
                                        `<input type="text" class="form-control" name="nama[]">` +
                                        `</td>` +
                                        `<td>` +
                                        `<input type="text" class="form-control" name="jumlah_minimal[]">` +
                                        `</td>` +
                                        `<td>` +
                                        `<input type="text" class="form-control" name="harga[]">` +
                                        `</td>` +
                                        `<td>` +
                                        `<input type="text" class="form-control" name="diskon[]">` +
                                        `</td>` +
                                        `<td class="text-center">` +
                                        `<button class="btn btn-sm btn-danger" onclick="deletetr2(` + ($("#tabelhargakhusus > tbody > tr").length + 1) + `)" type="button"><i class="fa fa-minus"></i></button>` +
                                        `</td>` +
                                        `</tr>`;
                                    $("#tabelhargakhusus tbody").append(newRowContent);
                                });
                            
                                function deletetr2(id) {
                                    document.getElementById("tr2_" + id).remove();
                                }
                            </script>
                        </div>
                        </div>
                        </form>
                </div>
                </div>
            </div>
                    
                    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
                    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
                    
                    <script>
                        const productTable = $('#product-table').DataTable();
                        const existingValues = [];
                    
                        function addRow() {
                            // Retrieve values from the form fields
                            const jumlah = document.getElementById('jumlah-input').value;
                            const Harga_pokok = document.getElementById('harga-pokok-input').value;
                            const Name = document.getElementById('product-name-input').value;
                            const supplier = document.getElementById('supplier-input').value;
                            const harga_jual = document.getElementById('harga-jual-input').value;
                    
                            // Create the delete button with red color and label
                            const deleteButton = `<button class="btn btn-danger btn-sm" onclick="deleteRow(this)">Hapus</button>`;
                            const rowValues = {
                                'Name': Name,
                                'jumlah': jumlah,
                                'Harga_pokok': Harga_pokok,
                                'harga_jual': harga_jual,
                                'supplier': supplier,
                                            };
                            existingValues.push(rowValues);
                            $('#data-table-values').val(JSON.stringify(existingValues));
                            // Add the row to the DataTable
                            const newRow = productTable.row.add([Name, jumlah, Harga_pokok, Harga_pokok, supplier, deleteButton]).draw();
                    
                            // Store the row node in a custom attribute for easy deletion
                            $(newRow.node()).data('node', newRow);
                    
                            // Clear the form fields
                            document.getElementById('jumlah-input').value = '';
                            document.getElementById('harga-pokok-input').value = '';
                            document.getElementById('product-name-input').value = '';
                            document.getElementById('supplier-input').value = '';
                            document.getElementById('harga-jual-input').value = '';
                        }
                    
                        function deleteRow(button) {
                            // Get the row node associated with the delete button
                            const rowNode = $(button).closest('tr');
                    
                            // Remove the row from the DataTable
                            productTable.row(rowNode).remove().draw();
                        }
                    </script>
                    <script>
                        function handleProductChange(event) {
                        const selectedProduct = event.target.value; // Assuming the value contains the product name
                        console.log('Selected product:', selectedProduct);
                        changeharga(selectedProduct);
                    }

                        function updateSelect2(supplier) {
                            const url = `/product/${supplier}/show`;
                    
                            $("#product-name-input").select2({
                                ajax: {
                                    url: url,
                                    dataType: 'json',
                                    delay: 250,
                                    processResults: function (data) {
                                        return {
                                            results: $.map(data, function (item) {
                                                return {
                                                    text: item.name,
                                                    id: item.name
                                                };
                                            })
                                        };
                                    },
                                    cache: true
                                },
                                placeholder: 'Select Product',
                                minimumResultsForSearch: 0,
                                // containerCssClass: 'custom-select2-container' // Check this line
                            }).on('change', handleProductChange);
                        }
                    
                        function handleSupplierChange() {
                            const supplier = document.getElementById('supplier-input').value;
                            $("select.select2-hidden-accessible").select2('destroy');
                            updateSelect2(supplier);
                        }
                    
                        // Initial setup
                        function initialize() {
                            const selectElement = document.getElementById("supplier-input");
                            selectElement.addEventListener('change', handleSupplierChange);
                            handleSupplierChange(); // Call handleSupplierChange initially
                        }
                    
                        // Call initialize when the document is ready
                        document.addEventListener('DOMContentLoaded', initialize);
                        function changeharga (event){
                            const supplier = document.getElementById('supplier-input').value;
                            const url = `/product/${supplier}/show?namaproduct=${event}`;
                            $.ajax({
                                    type: 'GET',
                                    url: url,
                                    success: function(data) {
                                    // Assuming the response data is an array with the structure you provided
                                    if (Array.isArray(data) && data.length > 0) {
                                        // Extract harga_pokok and harga_jual from the first item in the array
                                        var hargaPokok = data[0].harga_pokok;
                                        var hargaJual = data[0].harga_jual;

                                        // Log the extracted values
                                        console.log('Harga Pokok:', hargaPokok);
                                        console.log('Harga Jual:', hargaJual);
                                        var newPokok = parseInt(hargaPokok);
                                        var newJual = parseInt(hargaJual);
                                        // Get the input element by its ID
                                        var inputpokok = document.getElementById("harga-pokok-input");
                                        var inputjual = document.getElementById("harga-jual-input");
                                        // Set the value of the input element to the new value
                                        inputpokok.value = newPokok;
                                        inputjual.value = newJual;
                                    } else {
                                        console.error('Invalid or empty response data.');
                                    }
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                    console.error('Error:', textStatus, errorThrown);
                                    }
                                });
                        }

                    </script>
    
                    
                    
                </div>
        </div>
        </div>
    </div>
    <div class="modal fade" id="excelmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <form action="{{ route('barang.update.excel') }}" method="POST" enctype="multipart/form-data">
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
{{-- @push('plugin-scripts')
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/typeahead-js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pickr/pickr.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
@endpush
@push('custom-scripts')
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-maxlength.js') }}"></script>
    <script src="{{ asset('assets/js/inputmask.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/js/tags-input.js') }}"></script>
    <script src="{{ asset('assets/js/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/dropify.js') }}"></script>
    <script src="{{ asset('assets/js/pickr.js') }}"></script>
    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
@endpush --}}
@push('custom-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@endpush