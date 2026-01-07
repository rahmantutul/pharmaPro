@extends('dashboard.layouts.app')

@push('css')
@endpush

@section('content')
    <div class="container">
        @include('dashboard.layouts.toolbar')
        <!-- end: TOOLBAR -->
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="#">
                            {{ __('Medicine Management') }}
                        </a>
                    </li>
                    <li class="active">
                        {{ __('Medicine') }}
                    </li>
                </ol>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <!-- start: DATE/TIME PICKER PANEL -->
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <h4 class="panel-title text-center">{{ __('Medicine') }} <span class="text-bold">
                                    {{ __('Create') }}</span></h4>
                        </div>
                        <div class="panel-body">
                            <form
                                action="{{ isset($medicine) ? route('medicine.update', $medicine->id) : route('medicine.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @if (isset($medicine))
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="qrcode" class="form-label">{{ __('QR Code') }}</label>
                                            <input id="qrcode" type="text" class="form-control" name="qr_code"
                                                value="{{ old('qr_code', $medicine->qr_code ?? '') }}" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label for="hnscode" class="form-label">{{ __('HNS Code') }}</label>
                                            <input id="hnscode" type="text" class="form-control" name="hns_code"
                                                value="{{ old('hns_code', $medicine->hns_code ?? '') }}" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label for="name" class="form-label">{{ __('Name') }}</label>
                                            <input id="name" type="text" class="form-control" name="name"
                                                value="{{ old('name', $medicine->name ?? '') }}" required autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label for="strength" class="form-label">{{ __('Strength') }}</label>
                                            <input id="strength" type="text" class="form-control" name="strength"
                                                value="{{ old('strength', $medicine->strength ?? '') }}" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label for="sell_price" class="form-label">{{ __('Sell Price') }}</label>
                                            <input id="sell_price" type="number" class="form-control" step="0.01"
                                                name="sell_price"
                                                value="{{ old('sell_price', $medicine->sell_price ?? '') }}" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label for="purchase_price"
                                                class="form-label">{{ __('Purchase Price') }}</label>
                                            <input id="purchase_price" type="number" class="form-control" step="0.01"
                                                name="purchase_price"
                                                value="{{ old('purchase_price', $medicine->purchase_price ?? '') }}"
                                                autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label for="genericname" class="form-label">{{ __('Generic Name') }}</label>
                                            <input id="genericname" type="text" class="form-control" name="generic_name"
                                                value="{{ old('generic_name', $medicine->generic_name ?? '') }}" required
                                                autofocus>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="leafId" class="form-label">{{ __('Box Size') }}</label>
                                            <select class="form-control" name="leafId" id="leafId" required>
                                                <option value="">{{ __('Select Box Size') }}</option>
                                                @foreach ($leaves as $leaf)
                                                    <option value="{{ $leaf->id }}"
                                                        {{ old('leafId', $medicine->leafId ?? '') == $leaf->id ? 'selected' : '' }}>
                                                        {{ $leaf->name }} ({{ $leaf->qty }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="categoryId" class="form-label">{{ __('Category') }}</label>
                                            <select class="form-control" name="categoryId" id="categoryId" required>
                                                <option value="">{{ __('Select Category') }}</option>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}"
                                                        {{ old('categoryId', $medicine->categoryId ?? '') == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="vendorId" class="form-label">{{ __('Vendor') }}</label>
                                            <select class="form-control" name="vendorId" id="vendorId" required>
                                                <option value="">{{ __('Select Vendor') }}</option>
                                                @foreach ($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}"
                                                        {{ old('vendorId', $medicine->vendorId ?? '') == $vendor->id ? 'selected' : '' }}>
                                                        {{ $vendor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="supplierId" class="form-label">{{ __('Supplier') }}</label>
                                            <select class="form-control" name="supplierId" id="supplierId" required>
                                                <option value="">{{ __('Select Supplier') }}</option>
                                                @foreach ($suppliers as $sup)
                                                    <option value="{{ $sup->id }}"
                                                        {{ old('supplierId', $medicine->supplierId ?? '') == $sup->id ? 'selected' : '' }}>
                                                        {{ $sup->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="typeId" class="form-label">{{ __('Medicine Type') }}</label>
                                            <select class="form-control" name="typeId" id="typeId" required>
                                                <option value="">{{ __('Select Type') }}</option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->id }}"
                                                        {{ old('typeId', $medicine->typeId ?? '') == $type->id ? 'selected' : '' }}>
                                                        {{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="desc" class="form-label">{{ __('Description') }}</label>
                                            <input id="desc" type="text" class="form-control" name="desc"
                                                value="{{ old('desc', $medicine->desc ?? '') }}" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label for="image" class="form-label">{{ __('Image') }}</label><br>
                                            @if (isset($medicine) && $medicine->image)
                                                <img src="{{ (file_exists(public_path('uploads/images/medicine/' . $medicine->image)) && !empty($medicine->image)) ? asset('uploads/images/medicine/' . $medicine->image) : asset('uploads/images/medicine/default.png') }}"
                                                    alt="Medicine Image" style="height:40px; width:40px;"><br><br>
                                            @endif
                                            <input id="image" type="file" class="form-control" name="image"
                                                {{ !isset($medicine) ? 'required' : '' }} autofocus>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-sm btn-primary form-control">
                                            {{ isset($medicine) ? __('Update') : __('Create') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-white">
                        <div class="panel-body">
                            <h4>Medicine <span class="text-bold">{{ __('List') }}</span></h4>
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <input medicine="text" id="medName" class="form-control"
                                            placeholder="Medicine Name">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <select id="medSupplier" class="form-control single-select">
                                            <option value="">{{ __('Select Supplier') }}</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <select id="medCategory" class="form-control single-select">
                                            <option value="">{{ __('Select Category') }}</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('ID') }}</th>
                                            <th scope="col">{{ __('Image') }}</th>
                                            <th scope="col">{{ __('name') }}</th>
                                            <th scope="col">{{ __('Generic Name') }}</th>
                                            <th scope="col">{{ __('Strength') }}</th>
                                            <th scope="col">{{ __('Sell Price') }}</th>
                                            <th scope="col">{{ __('Purchase Price') }}</th>
                                            <th scope="col">{{ __('Supplier') }}</th>
                                            <th scope="col">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="MedicinesTable">
                                        @include('dashboard.medicine.filter_medicine')
                                    </tbody>
                                </table>
                            </div>
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
            function searchMedicines(page = 1) {
                var medName = $('#medName').val();
                var medSupplier = $('#medSupplier').val();
                var medCategory = $('#medCategory').val();

                $.ajax({
                    url: '{{ route('medicine.search') }}?page=' +
                    page, // Append the page number to the request
                    method: 'GET',
                    data: {
                        medName: medName,
                        medSupplier: medSupplier,
                        medCategory: medCategory
                    },
                    success: function(response) {
                        $('#MedicinesTable').html(response); // Update the table body with the new data
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText); // Log any errors
                    }
                });
            }

            // Trigger search on input or dropdown change
            $('#medName, #medSupplier, #medCategory').on('change keyup', function() {
                searchMedicines();
            });

            // Handle pagination clicks
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                searchMedicines(page);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            //Select 2 plugin 
            $('#medCategory').select2({
                placeholder: "Select an option", // Optional placeholder
                allowClear: true // Allows user to clear selection
            });
            $('#medSupplier').select2({
                placeholder: "Select an option", // Optional placeholder
                allowClear: true // Allows user to clear selection
            });


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

        // Edit Medicine  Modal
        function editMedicine(id, date, categoryId, medicineFor, amount, note) {
            var actionUrl = '{{ url('/medicine/update') }}/' + id;
            $('#editMedicineForm').attr('action', actionUrl);
            $('#medicineId').val(id);
            $('#medicineDate').val(date);
            $('#medicineCategoryId').val(categoryId);
            $('#medicineFor').val(medicineFor);
            $('#medicineAmount').val(amount);
            $('#medicineNote').val(note);
        }
    </script>
@endpush
