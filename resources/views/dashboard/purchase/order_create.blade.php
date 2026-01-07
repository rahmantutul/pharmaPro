@extends('dashboard.layouts.app')

@push('stylesheet')
<style>
    td{
        padding: 2px !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    @include('dashboard.layouts.toolbar')
    <!-- end: TOOLBAR -->
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li>
                    <a href="#">
                        {{ __('Purchase Management') }}
                    </a>
                </li>
                <li class="active">
                    {{ __('Create Purchase') }}
                </li>
            </ol>
        </div>
        <div class="row" style="display:flex;">
            <div class="col-sm-10" style="margin:auto !important;">
                <div class="panel panel-white">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6"><h4>{{ __('Create Purchase Order') }}</h4> </div>
                            <div class="col-md-6"><a style="float: right" href="{{ route('purchase.order.index') }}">{{ __('Go To List') }}</a></div>
                        </div>
                        <form action="{{ route('purchase.order.store') }}" method="post">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr style="background: #cfeffc;">
                                            <th width="5%" colspan="8" scope="col">
                                                <label for="supplierList">{{ __('Select A supplier') }}</label>
                                                <select name="supplier" class="form-control medicine-select" id="supplierList" required>
                                                    <option value="" disabled selected>{{ __('Select supplier') }}</option>
                                                    @foreach ($suppliers as $sup)
                                                        <option value="{{ $sup->id }}">{{ $sup->name }} || {{ $sup->phone }}</option>
                                                    @endforeach
                                                </select>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th width="5%" scope="col">{{ __('ID') }}</th>
                                            <th width="40%" scope="col">{{ __('Medicine') }}</th>
                                            <th width="15%" scope="col">{{ __('Qty') }}</th>
                                            <th width="15%" scope="col">{{ __('Price') }}</th>
                                            <th width="20%" scope="col">{{ __('Total') }}</th>
                                            <th width="5%" scope="col">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="medicineTableBody">
                                        <tr id="row_1">
                                            <td>1</td>
                                            <td>
                                                <select name="medicine[]" class="form-control medicine-select" onchange="getMedicineDetail(this);" id="medList_1" required>
                                                    <option value="" disabled selected>{{ __('Select Medicine') }}</option>
                                                    @foreach ($medicines as $med)
                                                        <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->generic_name }},{{ $med->strength }} ) {{ $med->supplier->name }} </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="qty[]" class="form-control qty" min="1" value="1" id="qty_1" required />
                                            </td>
                                            <td>
                                                <input type="text" name="price[]" class="form-control price" readonly id="price_1" step="0.01" required />
                                            </td>
                                            <td>
                                                <input type="text" name="total[]" class="form-control total" id="total_1" step="0.01" readonly/>
                                            </td>
                                            <td>
                                                <!-- No remove button in the first row -->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="col-md-12">
                                    <b style="text-align:right;">{{ __('Total Cost') }}</b>
                                    <input type="number" style="width:20%; float:right; text-align:right;" class="form-control" step="0.01" name="grandTotal" id="grandTotal" value="0.00" readonly>
                                </div> 
                                <div>
                                    <button type="button" id="addRowBtn" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></button>
                                    <button type="submit" style="float:right; margin-top: 12px;" class="btn btn-sm btn-primary">{{ __('Create Order') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('javascript')
    <script>
        $(`#supplierList`).select2({
            placeholder: "Select an option",
            allowClear: true
        });
        $(document).ready(function() {
            // Show success message
            @if (session('success'))
                toastr.success("{{ session('success') }}", 'Success');
            @endif
            // Show validation errors
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}", 'Error');
                @endforeach
            @endif
        });
    </script>
    <script>
        $(document).ready(function () {
            let rowCount = 1;
            // Add new row when clicking the "Add" button
            $('#addRowBtn').on('click', function () {
                if (validateAllRows()) {
                    addNewRow();
                    countGrandTotal(); // Update grand total
                }
            });
    
            // Add new row when 'Enter' is pressed in the last qty input field
            $(document).on('keydown', '.qty', function (e) {
                if (e.which === 13 && validateRow($(this).closest('tr'))) {
                    if (validateAllRows()) {
                        addNewRow();
                        countGrandTotal(); // Update grand total
                    }
                }
            });
    
            // Initialize the first medicine dropdown
            $(`#medList_1`).select2({
                placeholder: "Select an option",
                allowClear: true
            });
    
            // Function to add a new row
            function addNewRow() {
                rowCount++;
                const rowHtml = `
                <tr id="row_${rowCount}">
                    <td>${rowCount}</td>
                    <td>
                        <select name="medicine[]" class="form-control medicine-select" onchange="getMedicineDetail(this);" id="medList_${rowCount}" required>
                            <option value="" disabled selected>Select Medicine</option>
                            @foreach ($medicines as $med)
                                <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->generic_name }},{{ $med->strength }} ) {{ $med->supplier->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="qty[]" class="form-control qty" min="1" value="1" id="qty_${rowCount}" required /></td>
                    <td><input type="text" name="price[]" class="form-control price" readonly id="price_${rowCount}" required /></td>
                    <td><input type="text" name="total[]" class="form-control total" id="total_${rowCount}" readonly /></td>
                    <td><button type="button" class="btn btn-danger removeRow"><i class="fa fa-trash-o icon-trash"></i></button></td>
                </tr>`;
                $('#medicineTableBody').append(rowHtml);
                $(`#medList_${rowCount}`).select2({
                    placeholder: "Select an option",
                    allowClear: true
                });
            }
    
            // Remove row and recalculate grand total
            $(document).on('click', '.removeRow', function () {
                $(this).closest('tr').remove();
                recalculateRowNumbers();
                countGrandTotal(); // Recalculate grand total
            });
    
            // Validate all rows before allowing the addition of a new one
            function validateAllRows() {
                let isValid = true;
                $('#medicineTableBody tr').each(function () {
                    if (!validateRow($(this))) {
                        isValid = false;
                    }
                });
                return isValid;
            }
    
            // Validate an individual row
            function validateRow(row) {
                const medicine = row.find('.medicine-select').val();
                const qty = row.find('.qty').val();
                const price = row.find('.price').val();
                const supplier = row.find('.supplier').val();
    
                if (!medicine) {
                    alert('Please select a medicine.');
                    return false;
                }
                if (qty <= 0 || qty === '') {
                    alert('Quantity must be greater than 0.');
                    return false;
                }
    
                if (!price || price <= 0) {
                    alert('Price must be set.');
                    return false;
                }
    
                return true;
            }
    
            // Prevent selecting the same medicine twice
            $(document).on('change', '.medicine-select', function () {
                const selectedMedicine = $(this).val();
                const isDuplicate = $('.medicine-select').not(this).filter(function () {
                    return $(this).val() === selectedMedicine;
                }).length > 0;
    
                if (isDuplicate) {
                    alert('This medicine is already selected.');
                    $(this).val('').trigger('change');  // Reset to the "Select Medicine" option
                }
            });
    
            // Calculate total based on price and qty
            $(document).on('change click keyup', '.qty', function () {
                const qty = $(this).val();
                const price = $(this).closest('tr').find('.price').val();
                const total = parseFloat(qty * price, 2);
                $(this).closest('tr').find('.total').val(total);
                countGrandTotal(); // Recalculate grand total
            });
    
            // Function to recalculate row numbers after deletion
            function recalculateRowNumbers() {
                let rowCount = 1;
                $('#medicineTableBody tr').each(function () {
                    $(this).find('td:first').text(rowCount);
                    rowCount++;
                });
            }
        });

        // Calculate the grand total
        function countGrandTotal(){
            let grandTotal = 0.00; 
            document.querySelectorAll('input[id^="total_"]').forEach(function(input){
                let value = parseFloat(input.value) || 0; // Ensure empty values are treated as 0
                grandTotal += value;
            });
            $('#grandTotal').val(grandTotal.toFixed(2)); // Set the grand total to the input field
        }

        // Fetch medicine details via AJAX
        function getMedicineDetail(selectElement) {
            let medId = $(selectElement).val();
            if (medId) {
                let url = `{{ route('medicine.getMedicineDetails') }}`;
                $.ajax({
                    url: url,
                    method: 'GET',
                    data: { id: medId },
                    success: function(response) {
                        // Assuming response contains 'price' and 'supplier'
                        const rowId = $(selectElement).attr('id').split('_')[1];
                        $(`#price_${rowId}`).val(response.dataInfo.purchase_price);    
                        // Recalculate total if qty is already set
                        const qty = $(`#qty_${rowId}`).val();
                        if (qty > 0) {
                            const total = qty * response.dataInfo.purchase_price;
                            $(`#total_${rowId}`).val(total);
                        }
                        countGrandTotal(); // Recalculate grand total after fetching details
                    },
                    error: function() {
                        alert("An error occurred!");
                    }
                });
            }
        }
    </script>
@endpush
