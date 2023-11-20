@extends('layout.master3')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  --}}

@php
    use App\Models\category_cabang;
    use App\Models\suplier;
    use App\Models\user_cabang;
    use Illuminate\Support\Facades\Auth;
    $id = Auth::guard('user_cabang')->user()->id;
    $user = user_cabang::where('id',$id)->first();
    $cabang = category_cabang::all();
    $supplier = suplier::all();
@endphp
    <div class="row">
        <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
            <h6 class="card-title">Opname Product</h6>
                <form class="forms-sample">
                <div class="row mb-12">
                    <div class="col">
                        <label class="form-label">Product Name:</label>
                        <select class="js-example-basic-single form-select" name="" id="product-name-input">
                        </select>
                    </div>
                </div>
                <!-- Rest of the form content -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Jumlah</label>
                        <input id="jumlah-input" type="number" class="form-control mb-4 mb-md-0"/>
                        <input id="old-jumlah" type="hidden"/>
                        <input id="id_toko" type="hidden" value="{{$user->cabang_id}}"/>
                    </div>
                </div>
                <!-- Rest of the form content -->
                <input type="button" class="btn btn-success me-2" value="Tambah" onclick="addRow()" />
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
                                    <th>Barcode</th>
                                    <th>Stock Lama</th>
                                    <th>Stock Baru</th>
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
                            const old = document.getElementById('old-jumlah').value;
                            const Name = document.getElementById('product-name-input').value;
                            const id = document.getElementById('id_toko').value;
                    
                            // Create the delete button with red color and label
                            const deleteButton = `<button class="btn btn-danger btn-sm" onclick="deleteRow(this)">Hapus</button>`;
                            const rowValues = {
                                'Name': Name,
                                'jumlah': jumlah,
                                'old': old,
                                            };
                            existingValues.push(rowValues);
                            $('#data-table-values').val(JSON.stringify(existingValues));
                            // Add the row to the DataTable
                            const newRow = productTable.row.add([Name,old,jumlah, deleteButton]).draw();
                    
                            // Store the row node in a custom attribute for easy deletion
                            $(newRow.node()).data('node', newRow);
                    
                            // Clear the form fields
                            document.getElementById('jumlah-input').value = '';
                            document.getElementById('old-jumlah').value = '';
                            document.getElementById('product-name-input').value = '';
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

                        // function updateSelect2(id) {
                        //     const url = `/opname/${id}/show`;
                    
                        //     $("#product-name-input").select2({
                        //         ajax: {
                        //             url: url,
                        //             dataType: 'json',
                        //             delay: 250,
                        //             processResults: function (data) {
                        //                 return {
                        //                     results: $.map(data, function (item) {
                        //                         return {
                        //                             text: item.barcode,
                        //                             id: item.barcode
                        //                         };
                        //                     })
                        //                 };
                        //             },
                        //             cache: true
                        //         },
                        //         placeholder: 'Select Product',
                        //         minimumResultsForSearch: 0,
                        //         // containerCssClass: 'custom-select2-container' // Check this line
                        //     }).on('change', handleProductChange);
                        // }
                        $(document).ready(function() {
                            const id = document.getElementById('id_toko').value;
                            const url = `/opname/${id}/show`;
                            $("#product-name-input").select2({
                                ajax: {
                                    url: url,
                                    dataType: 'json',
                                    delay: 250,
                                    processResults: function (data) {
                                        return {
                                            results: $.map(data, function (item) {
                                                return {
                                                    text: item.barcode,
                                                    id: item.barcode
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
                        });
                        // function handleSupplierChange() {
                        //     const supplier = document.getElementById('supplier-input').value;
                        //     $("select.select2-hidden-accessible").select2('destroy');
                        //     updateSelect2(supplier);
                        // }
                    
                        // // Initial setup
                        function initialize() {
                            // const selectElement = document.getElementById("product-name-input");
                            // selectElement.addEventListener('change', handleSupplierChange);
                            // handleSupplierChange(); // Call handleSupplierChange initially
                        }
                    
                        // Call initialize when the document is ready
                        document.addEventListener('DOMContentLoaded', initialize);
                        function changeharga(event) {
                                    const id = document.getElementById('id_toko').value;
                                    const url = `/opname/${id}/show?namaproduct=${event}`;
                                    $.ajax({
                                        type: 'GET',
                                        url: url,
                                        success: function (data) {
                                            // Assuming the response data is an array with the structure you provided
                                            if (Array.isArray(data) && data.length > 0) {
                                                // Extract stock from the first item in the array
                                                var stock = data[0].stock;

                                                // Log the extracted values
                                                console.log('stock:', stock);
                                                var newstock = parseInt(stock);
                                                // Get the input elements by their IDs
                                                var inputpokok = document.getElementById('jumlah-input');
                                                var inputjual = document.getElementById('old-jumlah');
                                                // Set the value of the input elements to the new value
                                                inputpokok.value = newstock;
                                                inputjual.value = newstock;
                                            } else {
                                                console.error('Invalid or empty response data.');
                                            }
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            console.error('Error:', textStatus, errorThrown);
                                        }
                                    });
                                }


                    </script>
    
                    
                    
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