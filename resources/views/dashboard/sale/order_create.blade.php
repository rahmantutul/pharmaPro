@extends('dashboard.layouts.app')

@push('stylesheet')
    <link rel="stylesheet" href="{{asset('assets/css/sales.css')}}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <!-- start: DATE/TIME PICKER PANEL -->
            <div class="panel-heading" style="background:#c2dcf7;">
                <h2 class="panel-title text-center">{{ __('New') }} <span class="text-bold">{{ __('Sales') }}</span></h2>
            </div>
            <div class="panel panel-white" style="padding:40px;">
                <div class="row">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="row">
                                <div class="panel-body">
                                    <div class="col-md-4">
                                        <label for="name">{{ __('Select Medicine') }}</label>
                                        <input type="text" id="name" class="form-control" placeholder="{{ __('Medicine Name') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="supplier">{{ __('Select Supplier') }}</label>
                                        <select id="supplier" class="form-control">
                                            <option value="">{{ __('Select Supplier') }}</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="category">{{ __('Select Category') }}</label>
                                        <select id="category" class="form-control">
                                            <option value="">{{ __('Select Category') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="col-md-12">
                                    <span style="color: blue">{{ __('Image < Name < Strength < Available stock') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="medicines-list">
                            @include('dashboard.sale.search_result', ['medicines' => $medicines])
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="panel-body">
                            <div class="card-body">
                                <form id="posForm" method="POST" action="{{ route('sales.order.store') }}" style="padding:20px 10px; background: #f1f1f1;">
                                    @csrf
                                    <div class="row">
                                        <!-- Date, Invoice, Customer Fields -->
                                        <div class="col-md-4">
                                            <div>
                                                <label for="date" class="form-label">{{ __('Date Today') }}</label>
                                                <input id="date" type="date" class="form-control" name="invoice_date" value="{{$today}}" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div>
                                                <label for="Invoice" class="form-label">{{ __('Invoice') }}</label>
                                                <input id="Invoice" type="text" class="form-control" name="invoice_no" value="{{$invoice}}" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div>
                                                <label for="customer" class="form-label">
                                                    {{ __('Select Customer') }} 
                                                    <span class="text-muted">({{ __('Optional for walk-in') }})</span>
                                                </label>
                                                <strong>{{ __('OR') }}</strong> 
                                                <a href="#" data-toggle="modal" data-target="#addModal" class="btn btn-xs btn-primary">
                                                    {{ __('Add New') }}
                                                </a>
                                                <select class="form-select form-select-sm single-select" id="customer" name="customerId">
                                                    <option value="">{{ __('Walk-in Customer') }}</option>
                                                    @foreach ($customers as $cust)
                                                        <option value="{{$cust->id}}">{{$cust->name}} - {{$cust->phone}}</option>
                                                    @endforeach
                                                </select>
                                                <small class="text-info">
                                                    <i class="fa fa-info-circle"></i> 
                                                    {{ __('Leave empty for walk-in customers. Required only if there is due amount.') }}
                                                </small>
                                            </div>
                                        </div>
                                        <!-- Cart Table -->
                                        <div class="col-md-12">
                                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                                <table id="cartTable" class="table table-bordered table-striped table-hover align-middle mb-0" style="min-width: 800px;">
                                                    <thead class="table-primary sticky-top" style="top: 0;">
                                                        <tr>
                                                            <th class="text-center" style="width: 15%;">{{ __('Medicine') }}</th>
                                                            <th class="text-center" style="width: 20%;">{{ __('Expire Date') }}</th>
                                                            <th class="text-center" style="width: 12%;">{{ __('Price') }}</th>
                                                            <th class="text-center" style="width: 10%;">{{ __('Quantity') }}</th>
                                                            <th class="text-center d-none" style="width: 12%;">{{ __('Subtotal') }}</th>
                                                            <th class="text-center" style="width: 10%;">{{ __('Discount') }}</th>
                                                            <th class="text-center" style="width: 12%;">{{ __('Total') }}</th>
                                                            <th class="text-center" style="width: 5%;">{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Dynamic rows will go here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Totals and Payment Details -->
                                        <div class="col-md-12 align-self-end">
                                            <table class="table estimate-acount-table text-right">
                                                <tbody>
                                                    <tr>
                                                        <th>{{ __('Total Amount') }}</th>
                                                        <td>:</td>
                                                        <td>
                                                            <input class="form-control" id="grandTotal" readonly type="number" step="0.01" name="grand_total" value="0">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('Invoice Discount') }}</th>
                                                        <td>:</td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <select id="invoiceDiscountType" class="form-control" name="discount_type" onchange="updatePayableTotal()">
                                                                        <option value="">{{ __('Select') }}</option>
                                                                        <option value="1">{{ __('Fixed') }}</option>
                                                                        <option value="2">{{ __('Percentage(%)') }}</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input id="invoiceDiscountAmount" class="form-control" name="invoice_discount" type="number" value="0" oninput="updatePayableTotal()">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('Payable Total') }}</th>
                                                        <td>:</td>
                                                        <td>
                                                            <input type="number" name="payable_total" readonly class="form-control" step="0.01" id="payableTotal">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('Paid Amount') }}</th>
                                                        <td>:</td>
                                                        <td>
                                                            <input id="paidAmount" oninput="updateDueAmount()" class="form-control" step="0.01" type="number" name="paid_amount">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('Due Amount') }}</th>
                                                        <td>:</td>
                                                        <td>
                                                            <input id="dueAmount" class="form-control" type="number" readonly name="due_amount" step="0.01" value="0.00">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{ __('Payment Method') }}</th>
                                                        <td>:</td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <select class="form-control" id="paymentMethod" name="paymentId" required>
                                                                        <option value="">{{ __('Select Payment Method') }}</option>
                                                                        @foreach ($methods as $method)
                                                                            <option value="{{ $method->id }}" 
                                                                                {{ isset($defaultMethod) && $defaultMethod->id == $method->id ? 'selected' : '' }}>
                                                                                {{ $method->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <small class="text-info">
                                                                        @if(isset($defaultMethod))
                                                                            <i class="fa fa-info-circle"></i> Default: {{ $defaultMethod->name }}
                                                                        @endif
                                                                    </small>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row" style="margin-top:10px;">
                                                                        <div class="col-md-6">
                                                                            <strong>{{ __('Print Invoice?') }}</strong>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="checkbox-wrapper-8">
                                                                                <input class="tgl tgl-skewed" name="print_invoice" id="cb3-8" type="checkbox" checked/>
                                                                                <label class="tgl-btn" data-tg-off="{{ __('NO') }}" data-tg-on="{{ __('YES') }}" for="cb3-8"></label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <!-- Submit Button -->
                                            <div class="col-12">
                                                <div>
                                                    <button type="submit" id="submitForm" class="form-control btn btn-sm btn-primary">
                                                        {{ __('Create Sale') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end: DATE/TIME PICKER PANEL -->
        </div>
        <!-- Start Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">{{ __('Create New Customer') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="createCustomerForm" action="{{ route('customer.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="customerName">{{ __('Customer Name') }}</label>
                                <input type="text" name="name" id="customerName" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="customerPhone">{{ __('Phone') }}</label>
                                <input type="text" name="phone" id="customerPhone" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="customerEmail">{{ __('Email') }}</label>
                                <input type="email" name="email" id="customerEmail" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="customerAddress">{{ __('Address') }}</label>
                                <textarea name="address" id="customerAddress" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Add Modal -->
    </div>
</div>

@endsection
@push('javascript')
<script>
// ==================== SMART CALCULATION SYSTEM ====================

$(document).ready(function() {
    // Initialize Select2
    $('#supplier').select2({ placeholder: "Select Supplier", allowClear: true, width: '100%' });
    $('#category').select2({ placeholder: "Select Category", allowClear: true, width: '100%' });
    $('#customer').select2({ placeholder: "Walk-in Customer", allowClear: true, width: '100%' });

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

    // Show empty cart message initially
    if ($('#cartTable tbody tr').length === 0) {
        showEmptyCart();
    }
});

// ==================== CART MANAGEMENT ====================

function addToCart(medicineId) {
    $.ajax({
        url: '{{ route('sales.cart.add') }}',
        type: 'GET',
        data: { id: medicineId },
        success: function(response) {
            if (!response.success || !response.medicine || !response.stocks) {
                toastr.error('Medicine data not found!');
                return;
            }

            const medicine = response.medicine;
            const stocks = response.stocks;

            if (stocks.length === 0) {
                toastr.warning('No stock available!');
                return;
            }

            if ($(`#cartTable tr[data-id="${medicineId}"]`).length > 0) {
                toastr.warning('Already in cart!');
                return;
            }

            $('.empty-cart-message').remove();

            let expiryOptions = '';
            stocks.forEach(stock => {
                if (stock.total_qty > 0) {
                    const date = formatDate(stock.expire_date);
                    expiryOptions += `<option value="${stock.expire_date}" data-qty="${stock.total_qty}">${date} (${stock.total_qty})</option>`;
                }
            });
                    {{--  <td class="text-center">
                        <input type="hidden" name="medicineId[]" value="${medicineId}"/>
                        <img src="/uploads/images/medicine/${medicine.image}" width="50" class="img-thumbnail">
                    </td>
                    <td>${medicine.supplier.name}</td>  --}}

            const row = `
                <tr data-id="${medicineId}" class="cart-row">
                   
                    <td>
                        <strong>${medicine.name}</strong><br><small>${medicine.generic_name || ''}</small>
                        <input type="hidden" name="medicineId[]" value="${medicineId}"/>
                    </td>
                    <td>
                        <select id="expiry_${medicineId}" class="form-control" name="expire_date[]" required>
                            ${expiryOptions}
                        </select>
                    </td>
                    <td>
                        <input id="price_${medicineId}" type="number" class="form-control" name="price[]" value="${medicine.sell_price}" step="0.01" min="0" required>
                    </td>
                    <td>
                        <input id="qty_${medicineId}" type="number" class="form-control" name="qty[]" value="1" step="0.01" min="0.01" required>
                    </td>
                    <td class="d-none">
                        <input id="subtotal_${medicineId}" type="number" class="form-control" name="subtotal[]" step="0.01" readonly>
                    </td>
                    <td>
                        <input id="discount_${medicineId}" type="number" class="form-control" name="discount[]" value="0" step="0.01" min="0">
                    </td>
                    <td>
                        <input id="total_${medicineId}" type="number" class="form-control" name="total[]" step="0.01" readonly>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeFromCart(${medicineId})">
                            <i class="fa fa-times"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            $('#cartTable tbody').append(row);
            
            // Attach events to new row
            $(`#qty_${medicineId}, #price_${medicineId}, #discount_${medicineId}`).on('input', function() {
                calculateRow(medicineId);
            });
            
            $(`#expiry_${medicineId}`).on('change', function() {
                const maxQty = $(this).find(':selected').data('qty');
                $(`#qty_${medicineId}`).attr('max', maxQty);
            });
            
            calculateRow(medicineId);
            toastr.success(`${medicine.name} added!`);
        },
        error: function() {
            toastr.error('Failed to add medicine!');
        }
    });
}

function removeFromCart(id) {
    $(`tr[data-id="${id}"]`).remove();
    
    if ($('#cartTable tbody tr').length === 0) {
        showEmptyCart();
    } else {
        calculateGrandTotal();
    }
}

function showEmptyCart() {
    $('#cartTable tbody').html(`
        <tr class="empty-cart-message">
            <td colspan="10" class="text-center py-5">
                <i class="fa fa-shopping-cart fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                <h5 class="text-muted">Cart is empty</h5>
                <p>Add medicines to continue</p>
            </td>
        </tr>
    `);
    resetTotals();
}

function resetTotals() {
    $('#grandTotal').val('0.00');
    $('#invoiceDiscountAmount').val('0');
    $('#invoiceDiscountType').val('');
    $('#payableTotal').val('0.00');
    $('#paidAmount').val('0.00');
    $('#dueAmount').val('0.00');
}

// ==================== CALCULATIONS ====================

function calculateRow(medicineId) {
    const price = parseFloat($(`#price_${medicineId}`).val()) || 0;
    const qty = parseFloat($(`#qty_${medicineId}`).val()) || 0;
    const discount = parseFloat($(`#discount_${medicineId}`).val()) || 0;
    
    // Check max quantity
    const maxQty = parseFloat($(`#expiry_${medicineId}`).find(':selected').data('qty')) || 0;
    if (qty > maxQty) {
        toastr.warning(`Max quantity: ${maxQty}`);
        $(`#qty_${medicineId}`).val(maxQty);
        return calculateRow(medicineId);
    }
    
    const subtotal = price * qty;
    let total = subtotal - discount;
    
    if (total < 0) {
        toastr.warning('Discount too high!');
        $(`#discount_${medicineId}`).val(subtotal.toFixed(2));
        total = 0;
    }
    
    $(`#subtotal_${medicineId}`).val(subtotal.toFixed(2));
    $(`#total_${medicineId}`).val(total.toFixed(2));
    
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let grandTotal = 0;
    
    $('input[name="total[]"]').each(function() {
        grandTotal += parseFloat($(this).val()) || 0;
    });
    
    $('#grandTotal').val(grandTotal.toFixed(2));
    calculatePayable();
}

function calculatePayable() {
    const grandTotal = parseFloat($('#grandTotal').val()) || 0;
    const discountAmount = parseFloat($('#invoiceDiscountAmount').val()) || 0;
    const discountType = $('#invoiceDiscountType').val();
    
    let payableTotal = grandTotal;
    
    if (discountType === "1") {
        payableTotal = grandTotal - discountAmount;
    } else if (discountType === "2") {
        payableTotal = grandTotal - (grandTotal * discountAmount / 100);
    }
    
    payableTotal = Math.max(0, payableTotal);
    
    $('#payableTotal').val(payableTotal.toFixed(2));
    $('#paidAmount').val(payableTotal.toFixed(2));  // Always update paid = payable
    
    calculateDue();
}

function calculateDue() {
    const payableTotal = parseFloat($('#payableTotal').val()) || 0;
    const paidAmount = parseFloat($('#paidAmount').val()) || 0;
    const dueAmount = Math.max(0, payableTotal - paidAmount);
    
    $('#dueAmount').val(dueAmount.toFixed(2));
    
    // Update customer requirement
    const $customerLabel = $('label[for="customer"]');
    if (dueAmount > 0) {
        $customerLabel.html('{{ __("Select Customer") }} <span class="text-danger">* (Required)</span>');
        $('#customer').addClass('border-warning');
    } else {
        $customerLabel.html('{{ __("Select Customer") }} <span class="text-muted">({{ __("Optional") }})</span>');
        $('#customer').removeClass('border-warning');
    }
}

// Event listeners
$('#invoiceDiscountType, #invoiceDiscountAmount').on('change input', calculatePayable);
$('#paidAmount').on('input', calculateDue);

// ==================== FORM SUBMISSION ====================

$('#submitForm').on('click', function(e) {
    e.preventDefault();
    
    const cartItems = $('#cartTable tbody tr.cart-row').length;
    const dueAmount = parseFloat($('#dueAmount').val()) || 0;
    const customer = $('#customer').val();
    const paymentMethod = $('#paymentMethod').val();
    const grandTotal = parseFloat($('#grandTotal').val()) || 0;
    
    // Validations
    if (cartItems === 0) {
        toastr.error('Cart is empty!');
        return false;
    }
    
    if (!paymentMethod) {
        toastr.error('Select payment method!');
        $('#paymentMethod').focus();
        return false;
    }
    
    if (dueAmount > 0 && !customer) {
        toastr.error('Customer required for due amount!');
        $('#customer').select2('open');
        return false;
    }
    
    if (grandTotal <= 0) {
        toastr.error('Total must be greater than 0!');
        return false;
    }
    
    // Confirm and submit
    const customerName = customer ? $('#customer option:selected').text() : 'Walk-in Customer';
    const paidAmount = parseFloat($('#paidAmount').val()) || 0;
    
    Swal.fire({
        title: 'Confirm Sale',
        html: `
            <div class="text-left">
                <p><strong>Customer:</strong> ${customerName}</p>
                <p><strong>Items:</strong> ${cartItems}</p>
                <p><strong>Total:</strong> ${window.APP_CURRENCY}${grandTotal.toFixed(2)}</p>
                <p><strong>Paid:</strong> ${window.APP_CURRENCY}${paidAmount.toFixed(2)}</p>
                <p><strong>Due:</strong> ${window.APP_CURRENCY}${dueAmount.toFixed(2)}</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Complete Sale',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#submitForm').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            $('#posForm').submit();
        }
    });
});

// ==================== SEARCH & FILTER ====================

let searchTimeout;
$(document).on('keyup', '#name', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        fetchMedicines(1);
    }, 500);
});

$(document).on('change', '#supplier, #category', function() {
    fetchMedicines(1);
});

$(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    const page = $(this).attr('href').split('page=')[1];
    fetchMedicines(page);
});

function fetchMedicines(page) {
    $.ajax({
        url: "{{ route('sales.medicines.filter') }}?page=" + page,
        method: 'GET',
        data: {
            name: $('#name').val(),
            supplier: $('#supplier').val(),
            category: $('#category').val()
        },
        beforeSend: function() {
            $('#medicines-list').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
        },
        success: function(response) {
            $('#medicines-list').html(response);
        },
        error: function() {
            $('#medicines-list').html('<div class="alert alert-danger">Failed to load medicines</div>');
        }
    });
}

// ==================== UTILITIES ====================

function formatDate(dateString) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

// Keyboard shortcuts
$(document).on('keydown', function(e) {
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        $('#submitForm').click();
    }
});
</script>
@endpush
