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
                           {{ __('Demurrage')}}
                        </a>
                    </li>
                    <li class="active">
                        {{ __('Create')}}
                    </li>
                </ol>
            </div>
            <div class="row" style="display:flex;">
                <div class="col-sm-8" style="margin:auto !important;">
                    <div class="panel panel-white">
                        <div class="panel-heading" style="border-bottom: 2px solid #f1f3f5; padding: 20px;">
                            <h4 style="margin: 0; color: #212529; font-weight: 600;">
                                <i class="fa fa-clock-o" style="color: #6c757d;"></i> {{__('Create Demurrage')}}
                            </h4>
                            <p style="margin: 5px 0 0 0; color: #6c757d; font-size: 14px;">{{__('Record demurrage charges for medicines')}}</p>
                        </div>
                        <div class="panel-body" style="padding: 30px;">
                            <form method="POST" action="{{ route('demurrage.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="medicine" class="form-label" style="font-weight: 600; color: #495057; margin-bottom: 8px;">
                                            {{ __('Medicine')}} <span style="color: #dc3545;">*</span>
                                        </label>
                                        <select name="medicineId" id="medicine" class="form-control single-select" onchange="getMedicineDetail(this);" required style="height: 42px;">
                                            <option value="">{{ __('Select Medicine')}}</option>
                                            @foreach($medicines as $medicine)
                                                <option value="{{ $medicine->id }}">{{ $medicine->name }} - {{ $medicine->strength }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="demurrage_date" class="form-label" style="font-weight: 600; color: #495057; margin-bottom: 8px;">
                                            {{ __('Demurrage Date')}} <span style="color: #dc3545;">*</span>
                                        </label>
                                        <input type="date" name="demurrage_date" id="demurrage_date" class="form-control" value="{{date('Y-m-d')}}" required style="height: 42px;">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="price" class="form-label" style="font-weight: 600; color: #495057; margin-bottom: 8px;">
                                            {{ __('Price')}} <span style="color: #dc3545;">*</span>
                                        </label>
                                        <input type="number" id="price" name="price" class="form-control" step="0.01" placeholder="0.00" required style="height: 42px;">
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="quantity" class="form-label" style="font-weight: 600; color: #495057; margin-bottom: 8px;">
                                            {{ __('Quantity')}} <span style="color: #dc3545;">*</span>
                                        </label>
                                        <input type="number" id="quantity" name="quantity" class="form-control" value="1" placeholder="1" required style="height: 42px;">
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="total" class="form-label" style="font-weight: 600; color: #495057; margin-bottom: 8px;">
                                            {{ __('Total Amount')}}
                                        </label>
                                        <input type="number" id="total" name="total" class="form-control total" placeholder="0.00" readonly style="height: 42px; background-color: #e9ecef; font-weight: 600;">
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12" style="border-top: 1px solid #e9ecef; padding-top: 20px;">
                                        <button type="submit" class="btn btn-primary" style="padding: 10px 30px; font-weight: 500;">
                                            <i class="fa fa-save"></i> {{ __('Save Demurrage')}}
                                        </button>
                                        <a href="{{ route('demurrage.index') }}" class="btn btn-secondary" style="padding: 10px 30px; font-weight: 500;">
                                            <i class="fa fa-times"></i> {{ __('Cancel')}}
                                        </a>
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

            $('#medicine').select2({
                placeholder: "Select an option", // Optional placeholder
                allowClear: true // Allows user to clear selection
            });
        });
    </script>
    <script>
        $('.single-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const priceInput = document.getElementById('price');
            const quantityInput = document.getElementById('quantity');
            const totalInput = document.getElementById('total');
    
            function calculateTotal() {
                const price = parseFloat(priceInput.value) || 0;
                const quantity = parseFloat(quantityInput.value) || 0;
                totalInput.value = (price * quantity).toFixed(2);
            }
    
            priceInput.addEventListener('input', calculateTotal);
            quantityInput.addEventListener('input', calculateTotal);
        });

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
                        $(`#price`).val(response.dataInfo.purchase_price);

                        // Recalculate total if qty is already set
                        const qty = $(`#quantity`).val();
                        if (qty > 0) {
                            const total = qty * response.dataInfo.purchase_price;
                            $(`#total`).val(total);
                        }
                    },
                    error: function() {
                        alert("An error occurred!");
                    }
                });
            }
        }
    </script>
@endpush
