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
                            {{ __('Demurrage Management')}}
                        </a>
                    </li>
                    <li class="active">
                        {{ __('Create Demurrage')}}
                    </li>
                </ol>
            </div>
            <div class="row" style="display:flex;">
                <div class="col-sm-10" style="margin:auto !important;">
                    <div class="panel panel-white">
                        <div class="panel-body">
                            <h3>{{__('Demurrage create')}}</h3>
                            <form method="POST" action="{{ route('demurrage.update') }}">
                                @csrf
                                    <div id="form-repeater">
                                        <div class="form-row align-items-end">
                                            <input type="hidden" name="dataId" value="{{$dataInfo->id}}">
                                            <div class="col-md-2">
                                                <select name="medicineId" id="medicine" class="form-control single-select" required>
                                                    <option value="">{{ __('Select Medicine')}}</option>
                                                    @foreach($medicines as $medicine)
                                                        <option {{ $dataInfo->medicineId == $medicine->id ? 'selected' : '' }} value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="date" name="demurrage_date" class="form-control" value="{{$dataInfo->demurrage_date}}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="price" name="price" class="form-control" step="0.01" value="{{$dataInfo->price}}" placeholder="Price" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="quantity" name="quantity" class="form-control" value="{{$dataInfo->qty}}" placeholder="Quantity" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" id="total" name="total" class="form-control total" value="{{$dataInfo->total}}" placeholder="Total" readonly>
                                            </div>
                                        </div>
                                    </div>
                                <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
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
                        $(`#price`).val(response.dataInfo.price);

                        // Recalculate total if qty is already set
                        const qty = $(`#quantity`).val();
                        if (qty > 0) {
                            const total = qty * response.dataInfo.purchase_price;
                            $(`#total`).val(purchase_price);
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
