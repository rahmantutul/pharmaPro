@extends('dashboard.layouts.app')

@push('stylesheet')
<style>
    .table-row-hover:hover { background-color: #f5f5f5; }
    .form-control-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
    .summary-card { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 20px; }
    .summary-item { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #dee2e6; }
    .summary-item:last-child { border-bottom: none; font-weight: bold; font-size: 1.1em; color: #dc3545; }
    .required-field::after { content: " *"; color: red; }
    .stock-warning { background-color: #fff3cd; padding: 5px; border-radius: 3px; font-size: 0.85em; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    @include('dashboard.layouts.toolbar')
    
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('return.purchase.index') }}">Purchase Returns</a></li>
                <li class="active">Create Purchase Return</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-reply"></i> Create Purchase Return
                        <a href="{{ route('return.purchase.index') }}" class="btn btn-sm btn-default pull-right">
                            <i class="fa fa-list"></i> View All Returns
                        </a>
                    </h4>
                </div>
                
                <div class="panel-body">
                    <form action="{{ route('return.purchase.store') }}" method="POST" id="purchaseReturnForm">
                        @csrf
                        
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i>
                            <strong>Important:</strong> 
                            Purchase returns will reduce your stock. Make sure you have sufficient stock before creating a return.
                            The system will automatically check stock availability.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="returnTable">
                                <thead class="bg-danger">
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="22%" class="required-field">Medicine</th>
                                        <th width="15%" class="required-field">Supplier</th>
                                        <th width="10%">Stock</th>
                                        <th width="10%" class="required-field">Expire Date</th>
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
                                                            data-price="{{ $med->purchase_price }}"
                                                            data-supplier-id="{{ $med->supplier->id ?? '' }}"
                                                            data-supplier-name="{{ $med->supplier->name ?? 'N/A' }}">
                                                        {{ $med->name }} | {{ $med->generic_name }} | {{ $med->strength }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm supplier-name" 
                                                   id="supplier_name_1" readonly>
                                            <input type="hidden" name="supplier_id[]" id="supplier_id_1" required>
                                        </td>
                                        <td>
                                            <div class="stock-info" id="stock_info_1">
                                                <span class="badge badge-info" id="stock_badge_1">0</span>
                                            </div>
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
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

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
                                        <span>Refund Amount:</span>
                                        <span id="grandTotal">{{ Helper::getStoreInfo()->currency }}0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right" style="margin-top: 20px;">
                            <a href="{{ route('return.purchase.index') }}" class="btn btn-default">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fa fa-save"></i> Create Purchase Return
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
    const medicineStocks = {};

    initializeSelect2(1);

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

    function initializeSelect2(rowId) {
        $(`#medicine_${rowId}`).select2({
            placeholder: "-- Select Medicine --",
            allowClear: true,
            width: '100%'
        });
    }

    // Medicine selection change
    $(document).on('change', '.medicine-select', function() {
        const rowId = $(this).attr('id').split('_')[1];
        const selectedOption = $(this).find('option:selected');
        const medicineId = $(this).val();
        const price = selectedOption.data('price') || 0;
        const supplierName = selectedOption.data('supplier-name') || '';
        const supplierId = selectedOption.data('supplier-id') || '';

        // Set supplier
        $(`#supplier_name_${rowId}`).val(supplierName);
        $(`#supplier_id_${rowId}`).val(supplierId);

        // Set price
        $(`#price_${rowId}`).val(parseFloat(price).toFixed(2));

        // Fetch stock
        if (medicineId) {
            fetchStock(medicineId, rowId);
        }

        calculateRowTotal(rowId);
        calculateGrandTotal();
        checkDuplicateMedicine($(this));
    });

    // Fetch available stock
    function fetchStock(medicineId, rowId) {
        $.ajax({
            url: '/api/medicine/stock/' + medicineId,
            method: 'GET',
            success: function(response) {
                const stock = response.stock || 0;
                medicineStocks[medicineId] = stock;
                
                $(`#stock_badge_${rowId}`).text(stock);
                
                if (stock <= 0) {
                    $(`#stock_info_${rowId}`).addClass('stock-warning');
                    toastr.warning('No stock available for this medicine!', 'Warning');
                } else {
                    $(`#stock_info_${rowId}`).removeClass('stock-warning');
                }
            },
            error: function() {
                $(`#stock_badge_${rowId}`).text('?');
            }
        });
    }

    // Quantity validation
    $(document).on('input', '.qty', function() {
        const rowId = $(this).attr('id').split('_')[1];
        const medicineId = $(`#medicine_${rowId}`).val();
        const qty = parseFloat($(this).val()) || 0;
        const availableStock = medicineStocks[medicineId] || 0;

        if (qty > availableStock) {
            toastr.error(`Quantity cannot exceed available stock (${availableStock})`, 'Error');
            $(this).val(availableStock);
        }

        calculateRowTotal(rowId);
        calculateGrandTotal();
    });

    function calculateRowTotal(rowId) {
        const qty = parseFloat($(`#qty_${rowId}`).val()) || 0;
        const price = parseFloat($(`#price_${rowId}`).val()) || 0;
        const total = qty * price;
        $(`#total_${rowId}`).val(total.toFixed(2));
    }

    function calculateGrandTotal() {
        let totalItems = 0;
        let totalQty = 0;
        let grandTotal = 0;

        $('.medicine-select').each(function() {
            if ($(this).val()) totalItems++;
        });

        $('.qty').each(function() {
            totalQty += parseFloat($(this).val()) || 0;
        });

        $('.total').each(function() {
            grandTotal += parseFloat($(this).val()) || 0;
        });

        $('#totalItems').text(totalItems);
        $('#totalQty').text(totalQty.toFixed(2));
        $('#grandTotal').text(window.APP_CURRENCY + grandTotal.toFixed(2));
    }

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

    $('#addRowBtn').on('click', function() {
        if (!validateAllRows()) return;

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
                                    data-price="{{ $med->purchase_price }}"
                                    data-supplier-id="{{ $med->supplier->id ?? '' }}"
                                    data-supplier-name="{{ $med->supplier->name ?? 'N/A' }}">
                                {{ $med->name }} | {{ $med->generic_name }} | {{ $med->strength }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm supplier-name" 
                           id="supplier_name_${rowCount}" readonly>
                    <input type="hidden" name="supplier_id[]" id="supplier_id_${rowCount}" required>
                </td>
                <td>
                    <div class="stock-info" id="stock_info_${rowCount}">
                        <span class="badge badge-info" id="stock_badge_${rowCount}">0</span>
                    </div>
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
                    <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#tableBody').append(newRow);
        initializeSelect2(rowCount);
        calculateGrandTotal();
    });

    $(document).on('click', '.remove-row', function() {
        if ($('#tableBody tr').length <= 1) {
            toastr.warning('At least one item is required!', 'Warning');
            return;
        }

        $(this).closest('tr').remove();
        updateRowNumbers();
        calculateGrandTotal();
    });

    function updateRowNumbers() {
        $('#tableBody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    function validateAllRows() {
        let isValid = true;

        $('#tableBody tr').each(function() {
            const medicine = $(this).find('.medicine-select').val();
            const supplier = $(this).find('input[name="supplier_id[]"]').val();
            const expireDate = $(this).find('.expire-date').val();
            const qty = parseFloat($(this).find('.qty').val()) || 0;
            const price = parseFloat($(this).find('.price').val()) || 0;

            if (!medicine) {
                toastr.error('Please select a medicine for all rows', 'Error');
                isValid = false;
                return false;
            }

            if (!supplier) {
                toastr.error('Supplier is required', 'Error');
                isValid = false;
                return false;
            }

            if (!expireDate) {
                toastr.error('Expire date is required', 'Error');
                isValid = false;
                return false;
            }

            if (qty <= 0) {
                toastr.error('Quantity must be greater than 0', 'Error');
                isValid = false;
                return false;
            }

            if (price <= 0) {
                toastr.error('Price must be greater than 0', 'Error');
                isValid = false;
                return false;
            }
        });

        return isValid;
    }

    $('#purchaseReturnForm').on('submit', function(e) {
        if (!validateAllRows()) {
            e.preventDefault();
            return false;
        }

        $('#submitBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    });

    calculateGrandTotal();
});
</script>
@endpush