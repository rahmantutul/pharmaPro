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
                            <div class="col-md-6">
                                <a style="float: right" href="{{ route('purchase.order.invoice.list') }}">{{ __('Go To List') }}</a>
                            </div>
                        </div>
                        <form action="{{ route('purchase.order.invoice.store') }}" method="post">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-striped" id="purchaseTable">
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
                                            <th width="30%" scope="col">{{ __('Medicine') }}</th>
                                            <th width="15%" scope="col">{{ __('Expire Date') }}</th>
                                            <th width="15%" scope="col">{{ __('Qty') }}</th>
                                            <th width="20%" scope="col">{{ __('Price') }}</th>
                                            <th width="10%" scope="col">{{ __('Total') }}</th>
                                            <th width="5%" scope="col">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="medicineTableBody">
                                        <tr id="row_1" data-row-number="1">
                                            <td>1</td>
                                            <td>
                                                <select name="medicine[]" class="form-control medicine-select" onchange="getMedicineDetail(this);" id="medList_1" required>
                                                    <option value="" disabled selected>{{ __('Select Medicine') }}</option>
                                                    @foreach ($medicines as $med)
                                                        <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->generic_name }},{{ $med->strength }} ) {{ $med->supplier->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="date" name="expire_date[]" class="form-control expire_date" min="1" id="expire_date_1" required />
                                            </td>
                                            <td>
                                                <input type="number" name="qty[]" value="1" class="form-control qty" min="1" id="qty_1" required />
                                            </td>
                                            <td>
                                                <input type="text" name="price[]" class="form-control price" readonly id="price_1" required />
                                            </td>
                                            <td>
                                                <input type="text" name="total[]" class="form-control total" id="total_1" readonly/>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger removeRow"><i class="fa fa-trash-o icon-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="col-md-11">
                                    <div class="form-row">
                                        <div class="form-group col-md-2">
                                            <select id="discountType" name="discount_type" class="form-control">
                                                <option value="" disabled selected>{{ __('Discount Type') }}</option>
                                                <option value="1">{{ __('Fixed') }}</option> <!-- Fixed type -->
                                                <option value="2">{{ __('Percentage') }}</option> <!-- Percentage type -->
                                            </select>
                                            <small class="text-muted">{{ __('Select discount type') }}</small>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" name="discount" class="form-control" id="discount" value="0.00" placeholder="{{ __('Discount Amount') }}">
                                            <small class="text-muted">{{ __('Discount Amount') }}</small>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" step="0.01" class="form-control" readonly id="total_dicount" value="0.00" placeholder="{{ __('Total Discount') }}">
                                            <small class="text-muted">{{ __('Total Discount') }}</small>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" class="form-control" name="grandTotal" id="grandTotal" value="0.00" step="0.01" readonly>
                                            <small class="text-muted">{{ __('Total Invoice Amount') }}</small>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" class="form-control" name="paidAmount" id="paidAmount" value="0.00" step="0.01">
                                            <small class="text-muted">{{ __('Payable Amount') }}</small>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" class="form-control" name="dueAmount" id="dueAmount" value="0.00" step="0.01" readonly>
                                            <small class="text-muted">{{ __('Total Due Left') }}</small>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-12">
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
        // Fetch medicine details via AJAX
        function getMedicineDetail(selectElement) {
            const medId = $(selectElement).val();
            if (medId) {
                const url = `{{ route('medicine.getMedicineDetails') }}`;
                $.ajax({
                    url: url,
                    method: 'GET',
                    data: { id: medId },
                    success: function (response) {
                        const rowId = $(selectElement).attr('id').split('_')[1];
                        $(`#price_${rowId}`).val(response.dataInfo.purchase_price);
                        const qty = $(`#qty_${rowId}`).val();
                        const total = qty * response.dataInfo.purchase_price;
                        $(`#total_${rowId}`).val(total);
                        countGrandTotal();
                    },
                    error: function () {
                        alert('An error occurred!');
                    }
                });
            }
        }

            let rowCount = $('#purchaseTable tbody tr').length;
        
            // Initialize Select2 on existing rows
            $('#purchaseTable tbody tr').each(function (index) {
                $(`#medList_${index + 1}`).select2({
                    placeholder: "Select an option",
                    allowClear: true
                });
            });
        
            // Add new row when clicking the "Add" button
            $('#addRowBtn').on('click', function () {
                if (validateAllRows()) {
                    addNewRow();
                    countGrandTotal(); // Update grand total
                }
            });
        
            // Add new row on 'Enter' key press in the qty input
            $(document).on('keydown', '.qty', function (e) {
                if (e.which === 13 && validateRow($(this).closest('tr'))) {
                    if (validateAllRows()) {
                        addNewRow();
                        countGrandTotal();
                    }
                }
            });
            // Add a new row
            function addNewRow() {
                rowCount++;
                const rowHtml = `
                    <tr id="row_${rowCount}">
                        <td>${rowCount}</td>
                        <td>
                            <select name="medicine[]" class="form-control medicine-select" onchange="getMedicineDetail(this);" id="medList_${rowCount}" required>
                                <option value="" disabled selected>Select Medicine</option>
                                @foreach ($medicines as $med)
                                    <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->generic_name }},{{ $med->strength }} ) {{ $med->supplier->name }} </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="date" name="expire_date[]" class="form-control expire_date" min="1" id="expire_date_${rowCount}" required /> </td>
                        <td><input type="number" class="form-control qty" name="qty[]" min="1" value="1" id="qty_${rowCount}" required /></td>
                        <td><input type="text" class="form-control price" name="price[]" id="price_${rowCount}" readonly /></td>
                        <td><input type="text" class="form-control total" name="total[]" id="total_${rowCount}" readonly /></td>
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
                countGrandTotal();
            });
        
            // Validate all rows
            function validateAllRows() {
                let isValid = true;
                $('#medicineTableBody tr').each(function () {
                    if (!validateRow($(this))) isValid = false;
                });
                return isValid;
            }
        
            // Validate individual row
            function validateRow(row) {
                const medicine = row.find('.medicine-select').val();
                const qty = row.find('.qty').val();
                const price = row.find('.price').val();
                const expire_date = row.find('.expire_date').val();
                if (!medicine) {
                    alert('Please select a medicine.');
                    return false;
                }

                if (!expire_date) {
                    alert('Please give expire date');
                    return false;
                }

                if (qty <= 0) {
                    alert('Quantity must be greater than 0.');
                    return false;
                }
                if (price <= 0) {
                    alert('Price must be set.');
                    return false;
                }
                return true;
            }
        
            // Prevent duplicate medicine selection
            $(document).on('change', '.medicine-select', function () {
                const selectedMedicine = $(this).val();
                const isDuplicate = $('.medicine-select').not(this).filter(function () {
                    return $(this).val() === selectedMedicine;
                }).length > 0;
        
                if (isDuplicate) {
                    alert('This medicine is already selected.');
                    $(this).val('').trigger('change');
                }
            });
        
            // Calculate total for a row
            $(document).on('input', '.qty', function () {
                const qty = $(this).val();
                const price = $(this).closest('tr').find('.price').val();
                const total = parseFloat(qty * price).toFixed(2);
                $(this).closest('tr').find('.total').val(total);
                countGrandTotal();
            });
        
            // Recalculate row numbers after deletion
            function recalculateRowNumbers() {
                let rowCount = 1;
                $('#medicineTableBody tr').each(function () {
                    $(this).find('td:first').text(rowCount++);
                });
            }
            // Calculate grand total
            function countGrandTotal() {
                let grandTotal = 0.00; // Initialize grandTotal as a number
                $('input[id^="total_"]').each(function () {
                    const value = parseFloat($(this).val()) || 0; // Convert to float or default to 0
                    grandTotal += value; // Add valid numbers
                });
                $('#grandTotal').data('original',grandTotal.toFixed(2)); // Set formatted total
                calculateDiscount(); // Apply discount
            }

           
            // Calculate discount and update totals
            $('#discount, #discount_type').on('input change keyup', calculateDiscount);

            function calculateDiscount() {
                const discountType = $('select[name="discount_type"]').val();
                const discountValue = parseFloat($('#discount').val()) || 0;
                let grandTotal = parseFloat($('#grandTotal').data('original')); // Use original grand total stored in a data attribute

                // Check if discount type is selected
                $('#discount').on('input change keyup', function () {
                    const discountType = $('#discountType').val(); // Get the discount type
                    if (!discountType) {
                        alert('Select discount type first');
                        $(this).val('0.00'); // Reset discount input field
                        $('#grandTotal').val(grandTotal.toFixed(2)); // Reset grand total
                        $('#paidAmount').val(grandTotal.toFixed(2)); // Reset grand total
                    }
                });

                let totalDiscount = 0;

                if (discountType === '1') { // Fixed discount
                    totalDiscount = discountValue;
                } else if (discountType === '2') { // Percentage discount
                    totalDiscount = (grandTotal * discountValue) / 100;
                }

                // Ensure total discount does not exceed grand total
                if (totalDiscount > grandTotal) {
                    totalDiscount = grandTotal; // Cap the discount at the grand total
                }

                $('#total_dicount').val(totalDiscount.toFixed(2));
                const finalTotal = grandTotal - totalDiscount;

                $('#grandTotal').val(finalTotal.toFixed(2));
                $('#paidAmount').val(finalTotal.toFixed(2));
                updatePayableAndDue();
            }
        // Store the original grand total when the document is ready
        $(document).ready(function () {
            const originalGrandTotal = parseFloat($('#grandTotal').val());
            $('#grandTotal').data('original', originalGrandTotal);
        });

         // Ensure paidAmount updates when user changes it manually
        $('#paidAmount').on('input', function () {
            isPaidAmountManuallySet = true; // Flag as manually set
            updatePayableAndDue(); // Update due amount when paidAmount is manually entered
        });

        // Update payable and due amounts based on grandTotal and paidAmount
        function updatePayableAndDue() {
            const grandTotal = parseFloat($('#grandTotal').val()) || 0;
            const paidAmount = parseFloat($('#paidAmount').val()) || 0;
            $('#dueAmount').val((grandTotal - paidAmount).toFixed(2)); // Calculate and set dueAmount
        }
    </script>
@endpush
