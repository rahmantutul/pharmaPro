@extends('dashboard.layouts.app')

@push('stylesheet')
<style>
    table > thead > tr > th {
        color: black;
    }
    .table-row-hover:hover {
        background-color: #f5f5f5;
    }
    .form-control-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .action-buttons {
        display: flex;
        gap: 5px;
    }
    .summary-card {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-top: 20px;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        border-bottom: 1px solid #dee2e6;
    }
    .summary-item:last-child {
        border-bottom: none;
        font-weight: bold;
        font-size: 1.1em;
        color: #28a745;
    }
    .btn-add-row {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    }
    .required-field::after {
        content: " *";
        color: red;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    @include('dashboard.layouts.toolbar')
    
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('return.sales.index') }}">Sales Returns</a></li>
                <li class="active">Create Sales Return</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-undo"></i> Create Sales Return
                        <a href="{{ route('return.sales.index') }}" class="btn btn-sm btn-default pull-right">
                            <i class="fa fa-list"></i> View All Returns
                        </a>
                    </h4>
                </div>
                
                <div class="panel-body">
                    <form action="{{ route('return.sales.store') }}" method="POST" id="salesReturnForm">
                        @csrf
                        
                        <!-- Instructions -->
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            <strong>Instructions:</strong> 
                            Select medicines that are being returned. Add quantity, price, and optionally select the customer. 
                            Click the <i class="fa fa-plus"></i> button to add more items.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="returnTable">
                                <thead class="bg-primary text-light">
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="25%" class="required-field text-black">Medicine</th>
                                        <th width="15%">Customer</th>
                                        <th width="12%" class="required-field">Expire Date</th>
                                        <th width="8%" class="required-field">Qty</th>
                                        <th width="10%" class="required-field">Price</th>
                                        <th width="10%">Total</th>
                                        <th width="12%">Reason</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <tr id="row_1" class="table-row-hover">
                                        <td class="text-center">1</td>
                                        <td>
                                            <select name="medicine[]" class="form-control form-control-sm medicine-select" 
                                                    id="medicine_1" required>
                                                <option value="">-- Select Medicine --</option>
                                                @foreach ($medicines as $med)
                                                    <option value="{{ $med->id }}" 
                                                            data-price="{{ $med->sell_price }}"
                                                            data-supplier="{{ $med->supplier->name ?? 'N/A' }}">
                                                        {{ $med->name }} | {{ $med->generic_name }} | {{ $med->strength }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="customer[]" class="form-control form-control-sm customer-select" 
                                                    id="customer_1">
                                                <option value="">-- Optional --</option>
                                                @foreach ($customers as $cust)
                                                    <option value="{{ $cust->id }}">
                                                        {{ $cust->name }} | {{ $cust->phone }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="date" name="expire_date[]" 
                                                   class="form-control form-control-sm expire-date" 
                                                   id="expire_date_1" required>
                                        </td>
                                        <td>
                                            <input type="number" name="qty[]" 
                                                   class="form-control form-control-sm qty" 
                                                   id="qty_1" value="1" min="0.01" step="0.01" required>
                                        </td>
                                        <td>
                                            <input type="number" name="price[]" 
                                                   class="form-control form-control-sm price" 
                                                   id="price_1" min="0" step="0.01" readonly required>
                                        </td>
                                        <td>
                                            <input type="number" name="total[]" 
                                                   class="form-control form-control-sm total" 
                                                   id="total_1" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="return_reason[]" 
                                                   class="form-control form-control-sm" 
                                                   placeholder="Optional" maxlength="500">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row" 
                                                    title="Remove Row">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Section -->
                        <div class="row">
                            <div class="col-md-8">
                                <button type="button" id="addRowBtn" class="btn btn-success">
                                    <i class="fa fa-plus"></i> Add More Items
                                </button>
                            </div>
                            <div class="col-md-4">
                                <div class="summary-card">
                                    <div class="summary-item">
                                        <span>Total Items:</span>
                                        <span id="totalItems">0</span>
                                    </div>
                                    <div class="summary-item">
                                        <span>Total Quantity:</span>
                                        <span id="totalQty">0</span>
                                    </div>
                                    <div class="summary-item">
                                        <span>Grand Total:</span>
                                        <span id="grandTotal">{{ Helper::getStoreInfo()->currency }}0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="form-group text-right" style="margin-top: 20px;">
                            <a href="{{ route('return.sales.index') }}" class="btn btn-default">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fa fa-save"></i> Create Sales Return
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
$(document).ready(function() {
    let rowCount = 1;

    // Initialize Select2 on first row
    initializeSelect2(1);

    // Show messages
    @if (session('success'))
        toastr.success("{{ session('success') }}", 'Success');
    @endif

    @if (session('error'))
        toastr.error("{{ session('error') }}", 'Error');
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}", 'Error');
        @endforeach
    @endif

    // Initialize Select2
    function initializeSelect2(rowId) {
        $(`#medicine_${rowId}`).select2({
            placeholder: "-- Select Medicine --",
            allowClear: true,
            width: '100%'
        });

        $(`#customer_${rowId}`).select2({
            placeholder: "-- Optional --",
            allowClear: true,
            width: '100%'
        });
    }

    // Medicine selection change
    $(document).on('change', '.medicine-select', function() {
        const rowId = $(this).attr('id').split('_')[1];
        const selectedOption = $(this).find('option:selected');
        const price = selectedOption.data('price') || 0;

        // Set price
        $(`#price_${rowId}`).val(parseFloat(price).toFixed(2));

        // Calculate total
        calculateRowTotal(rowId);
        calculateGrandTotal();

        // Check for duplicates
        checkDuplicateMedicine($(this));
    });

    // Quantity change
    $(document).on('input', '.qty', function() {
        const rowId = $(this).attr('id').split('_')[1];
        calculateRowTotal(rowId);
        calculateGrandTotal();
    });

    // Calculate row total
    function calculateRowTotal(rowId) {
        const qty = parseFloat($(`#qty_${rowId}`).val()) || 0;
        const price = parseFloat($(`#price_${rowId}`).val()) || 0;
        const total = qty * price;
        $(`#total_${rowId}`).val(total.toFixed(2));
    }

    // Calculate grand total
    function calculateGrandTotal() {
        let totalItems = 0;
        let totalQty = 0;
        let grandTotal = 0;

        $('.medicine-select').each(function() {
            if ($(this).val()) {
                totalItems++;
            }
        });

        $('.qty').each(function() {
            const qty = parseFloat($(this).val()) || 0;
            totalQty += qty;
        });

        $('.total').each(function() {
            const total = parseFloat($(this).val()) || 0;
            grandTotal += total;
        });

        $('#totalItems').text(totalItems);
        $('#totalQty').text(totalQty.toFixed(2));
        $('#grandTotal').text(window.APP_CURRENCY + grandTotal.toFixed(2));
    }

    // Check duplicate medicine
    function checkDuplicateMedicine(selectElement) {
        const selectedValue = selectElement.val();
        if (!selectedValue) return;

        let duplicateFound = false;
        $('.medicine-select').not(selectElement).each(function() {
            if ($(this).val() === selectedValue) {
                duplicateFound = true;
                return false;
            }
        });

        if (duplicateFound) {
            toastr.warning('This medicine is already added!', 'Warning');
            selectElement.val('').trigger('change');
        }
    }

    // Add new row
    $('#addRowBtn').on('click', function() {
        if (!validateAllRows()) {
            return;
        }

        rowCount++;
        const newRow = `
            <tr id="row_${rowCount}" class="table-row-hover">
                <td class="text-center">${rowCount}</td>
                <td>
                    <select name="medicine[]" class="form-control form-control-sm medicine-select" 
                            id="medicine_${rowCount}" required>
                        <option value="">-- Select Medicine --</option>
                        @foreach ($medicines as $med)
                            <option value="{{ $med->id }}" 
                                    data-price="{{ $med->sell_price }}">
                                {{ $med->name }} | {{ $med->generic_name }} | {{ $med->strength }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="customer[]" class="form-control form-control-sm customer-select" 
                            id="customer_${rowCount}">
                        <option value="">-- Optional --</option>
                        @foreach ($customers as $cust)
                            <option value="{{ $cust->id }}">
                                {{ $cust->name }} | {{ $cust->phone }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="date" name="expire_date[]" 
                           class="form-control form-control-sm expire-date" 
                           id="expire_date_${rowCount}" required>
                </td>
                <td>
                    <input type="number" name="qty[]" 
                           class="form-control form-control-sm qty" 
                           id="qty_${rowCount}" value="1" min="0.01" step="0.01" required>
                </td>
                <td>
                    <input type="number" name="price[]" 
                           class="form-control form-control-sm price" 
                           id="price_${rowCount}" min="0" step="0.01" readonly required>
                </td>
                <td>
                    <input type="number" name="total[]" 
                           class="form-control form-control-sm total" 
                           id="total_${rowCount}" readonly>
                </td>
                <td>
                    <input type="text" name="return_reason[]" 
                           class="form-control form-control-sm" 
                           placeholder="Optional" maxlength="500">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-row" 
                            title="Remove Row">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#tableBody').append(newRow);
        initializeSelect2(rowCount);
        calculateGrandTotal();
    });

    // Remove row
    $(document).on('click', '.remove-row', function() {
        const rowsCount = $('#tableBody tr').length;
        
        if (rowsCount <= 1) {
            toastr.warning('At least one item is required!', 'Warning');
            return;
        }

        $(this).closest('tr').remove();
        updateRowNumbers();
        calculateGrandTotal();
    });

    // Update row numbers after removal
    function updateRowNumbers() {
        $('#tableBody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    // Validate all rows
    function validateAllRows() {
        let isValid = true;

        $('#tableBody tr').each(function() {
            const medicine = $(this).find('.medicine-select').val();
            const expireDate = $(this).find('.expire-date').val();
            const qty = parseFloat($(this).find('.qty').val()) || 0;
            const price = parseFloat($(this).find('.price').val()) || 0;

            if (!medicine) {
                toastr.error('Please select a medicine for all rows', 'Validation Error');
                isValid = false;
                return false;
            }

            if (!expireDate) {
                toastr.error('Please enter expire date for all rows', 'Validation Error');
                isValid = false;
                return false;
            }

            if (qty <= 0) {
                toastr.error('Quantity must be greater than 0', 'Validation Error');
                isValid = false;
                return false;
            }

            if (price <= 0) {
                toastr.error('Price must be greater than 0', 'Validation Error');
                isValid = false;
                return false;
            }
        });

        return isValid;
    }

    // Form submission
    $('#salesReturnForm').on('submit', function(e) {
        if (!validateAllRows()) {
            e.preventDefault();
            return false;
        }

        $('#submitBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    });

    // Initial calculation
    calculateGrandTotal();
});
</script>
@endpush