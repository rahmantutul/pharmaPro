<div class="card-body">
    @if (count($errors) > 0)
    <ul class="list-unstyled">
        @foreach ($errors->all() as $error)
            <li class="alert alert-danger">{!! $error !!}</li>
        @endforeach
    </ul>
    @endif 
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form id="posForm" method="POST" action="{{ route('sales.order.store') }}">
        @csrf
        <div class="row">
            <!-- Date, Invoice, Customer Fields -->
            <div class="col-md-4">
                <div class="">
                    <label for="date" class="form-label">{{ __('Date Today') }}</label>
                    <input id="date" type="date" class="form-control" name="invoice_date" value="{{$today}}" readonly required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="">
                    <label for="Invoice" class="form-label">{{ __('Invoice') }}</label>
                    <input id="Invoice" type="text" class="form-control" name="invoice_no" value="{{$invoice}}" readonly required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="">
                    <label for="customer" class="form-label">
                        {{ __('Select Customer') }} 
                        <span class="text-muted">({{ __('Optional') }})</span>
                    </label> 
                    <strong>{{ __('OR') }}</strong> 
                    <a href="#" data-toggle="modal" data-target="#addModal" class="btn btn-xs btn-primary">
                        {{ __('Add New') }}
                    </a>
                    <select class="form-select form-select-sm" id="customer" name="customerId">
                        <option value="">{{ __('Walk-in Customer') }}</option>
                        @foreach ($customers as $cust)
                            <option value="{{$cust->id}}">{{$cust->name}} - {{$cust->phone ?? ''}}</option>
                        @endforeach
                    </select>
                    <small class="text-info">
                        <i class="fa fa-info-circle"></i> 
                        {{ __('Required only when due amount exists') }}
                    </small>
                </div>
            </div>
            
            <!-- Cart Table -->
            <div class="col-md-12 mt-3">
                <table id="cartTable" class="table table-bordered" border="1">
                    <thead class="bg-info text-dark">
                        <tr>
                            <th class="text-center">{{ __('Image') }}</th>
                            <th class="text-center">{{ __('Medicine') }}</th>
                            <th class="text-center">{{ __('Supplier') }}</th>
                            <th class="text-center">{{ __('Expire Date') }}</th>
                            <th class="text-center">{{ __('Price') }}</th>
                            <th class="text-center">{{ __('Quantity') }}</th>
                            <th class="text-center d-none">{{ __('Subtotal') }}</th>
                            <th class="text-center">{{ __('Discount') }}</th>
                            <th class="text-center">{{ __('Total') }}</th>
                            <th class="text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic Rows Will Be Added Here -->
                    </tbody>
                </table>
            </div>
            
            <!-- Totals and Payment Details -->
            <div class="col-md-12 align-self-end">
                <table class="table estimate-acount-table text-right">
                    <tbody>
                        <tr>
                            <th>{{ __('Total Amount') }}</th>
                            <td>:</td>
                            <td>
                                <input class="form-control" id="grandTotal" readonly type="number" step="0.01" name="grand_total" value="0.00">
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Invoice Discount') }}</th>
                            <td>:</td>
                            <td>
                                <div class="row">
                                    <div class="col-md-6">
                                        <select id="invoiceDiscountType" class="form-control" name="discount_type">
                                            <option value="">{{ __('No Discount') }}</option>
                                            <option value="1">{{ __('Fixed Amount') }}</option>
                                            <option value="2">{{ __('Percentage (%)') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input id="invoiceDiscountAmount" class="form-control" name="invoice_discount" type="number" step="0.01" value="0" min="0">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Payable Total') }}</th>
                            <td>:</td>
                            <td>
                                <input type="number" name="payable_total" readonly class="form-control" step="0.01" id="payableTotal" value="0.00">
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Paid Amount') }}</th>
                            <td>:</td>
                            <td>
                                <input id="paidAmount" class="form-control" step="0.01" type="number" name="paid_amount" value="0.00" min="0">
                                <small class="text-muted">
                                    <i class="fa fa-lightbulb-o"></i> 
                                    {{ __('Auto-filled. You can change it for partial payment') }}
                                </small>
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
                                <select class="form-control" id="paymentMethod" name="paymentId" required>
                                    <option value="">{{ __('Select Payment Method') }}</option>
                                    @foreach ($methods as $method)
                                        <option value="{{$method->id}}" 
                                            {{ isset($defaultMethod) && $defaultMethod->id == $method->id ? 'selected' : '' }}>
                                            {{$method->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Submit Button -->
                <div class="col-12 mt-3">
                    <button type="button" id="submitForm" class="btn btn-primary btn-lg btn-block">
                        <i class="fa fa-check-circle"></i> {{ __('Complete Sale') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
/* Remove number input arrows */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield;
    appearance: textfield;
}

/* Highlight important fields */
#grandTotal {
    background-color: #e7f3ff;
    font-size: 16px;
    font-weight: bold;
}

#payableTotal {
    background-color: #fff3cd;
    font-size: 16px;
    font-weight: bold;
}

#paidAmount {
    background-color: #d4edda;
    font-size: 16px;
    font-weight: bold;
}

#dueAmount {
    background-color: #f8d7da;
    font-size: 16px;
    font-weight: bold;
    color: #721c24;
}

.estimate-acount-table th {
    text-align: right;
    padding-right: 10px;
}
</style>