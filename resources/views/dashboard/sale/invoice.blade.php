@extends('dashboard.layouts.app')

@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/css/sales_invoice.css') }}">
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
                        {{ __('Sales') }}
                    </a>
                </li>
                <li class="active">
                    {{ __('Invoice') }}
                </li>
            </ol>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <div id="invoice" class="row">
                        <section
                            style="width: 302px; margin: 10px auto;background-color: #fff; padding:5px;margin-bottom: 70px;height: auto; border-top: 1px solid #000;"
                            id="invoiceArea">
                            <header style="text-align: center; padding-bottom: 0px">
                                <h3 style="font-size: 24px; font-weight: 700; margin: 0; padding: 0;">
                                    {{ $general_setting->appname }}</h3>
                                <div style="margin-bottom: 5px; line-height: 1;">
                                    <span style="font-size: 12px;">{{ $general_setting->address }}</span>
                                    <div style="display: block;">
                                        <span style="font-size: 12px;">{{ __('Mobile') }}: {{ $general_setting->phone }}</span>,
                                        <span style="font-size: 12px;">{{ __('Email') }}: {{ $general_setting->email }}</span>
                                    </div>
                                </div>
                            </header>
                            <section style="font-size: 12px;  line-height: 1.222; border-top: 1px solid #000;">
                                <table style="width: 100%;">
                                    <tr style=" border-bottom: 1px solid #000;">
                                        <td class="w-30" style="font-size:12px"><span
                                                style="font-size:12px"><b>{{ __('Date') }}:</b></span>
                                        </td>
                                        <td style="font-size:12px">
                                            {{ date('d M, Y h:i A', strtotime($dataInfo->invoice_date)) }} 
                                        </td>
                                    </tr>
                                    <tr style=" border-bottom: 1px solid #000;">
                                        <td class="w-30" style="font-size:12px"><span
                                                style="font-size:12px"><b>{{ __('Invoice ID') }}:</b></span>
                                        </td>
                                        <td style="font-size:12px">{{ $dataInfo->invoice_no }}</td>
                                    </tr>
                                    <tr style=" border-bottom: 1px solid #000;">
                                        <td class="w-30" style="font-size:12px"><b>{{ __('Customer Name') }}:</b></td>
                                        <td style="font-size:12px">{{ $dataInfo->customer?->name }}</td>
                                    </tr>
                                    <tr style=" border-bottom: 1px solid #000;">
                                        <td class="w-30" style="font-size:12px"><b>{{ __('Phone') }}:</b></td>
                                        <td style="font-size:12px">{{ $dataInfo->customer?->phone }}</td>
                                    </tr>
                                    <tr style=" border-bottom: 1px solid #000;">
                                        <td class="w-30" style="font-size:12px"><b>{{ __('Address') }}:</b></td>
                                        <td style="font-size:12px">{{ $dataInfo->customer?->address }}</td>
                                    </tr>
                                </table>
                            </section>
                            <h4 style="font-size: 18px; font-weight: 700; text-align: center; margin: 5px 0 0px 0; padding: 0px 0;">
                                {{ __('INVOICE') }}</h4>

                            <section style="line-height: 1.23; border-top: 1px solid #000;">
                                <table style="width: 100%">
                                    <thead>
                                        <tr style=" border-bottom: 1px solid #000; font-weight: 700;">
                                            <th class="w-10 text-center" style="font-size: 12px; text-align: center">{{ __('Sl.') }}
                                            </th>
                                            <th class="w-40" style="font-size: 12px;">{{ __('Name') }}</th>
                                            <th class="w-15 text-center" style="font-size: 12px; text-align: center">{{ __('Qty') }}
                                            </th>
                                            <th class="w-15 text-right" style="font-size: 12px; text-align: center">
                                                {{ __('Price') }}</th>
                                            <th class="w-20 text-right"
                                                style="border-bottom: none; font-size: 12px; text-align: center">{{ __('Total') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataInfo->details as $key => $item)
                                            <tr style=" border-bottom: 1px solid #000;">
                                                <td class="text-center"
                                                    style="vertical-align: top; font-size: 12px; text-align: center">
                                                    {{ $key + 1 }}</td>
                                                <td style="vertical-align: top; font-size: 12px; text-align: center">
                                                    {{ $item->medicine->name }} </td>
                                                <td class="text-center"
                                                    style="vertical-align: top; font-size: 12px; text-align: center">
                                                    {{ $item->qty }} </td>
                                                <td class="text-right"
                                                    style="vertical-align: top; font-size: 12px; text-align: center">
                                                    {{ $general_setting->currency }} {{ $item->sell_price }}</td>
                                                <td
                                                    class="text-right" style="border-bottom: none;vertical-align: top;font-size: 12px;text-align: center ">
                                                    {{ $general_setting->currency }} {{ $item->total }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </section>
                            <section
                                style="line-height: 1.23; font-size: 12px; border-top: 1px solid #000; border-top: 1px solid #000;">
                                <table style="width: 93%; margin-right: 7%">
                                    <tr style="  border-bottom: 1px solid #000;">
                                        <td style="text-align: right; font-size: 12px; width: 70%">{{ __('Sub Total') }}:</td>
                                        <td style="text-align: right; font-size: 12px; width: 70%">
                                            {{ $general_setting->currency }}
                                            {{ number_format($dataInfo->grand_total, 2) }}</td>
                                    </tr>
                                    <tr style="  border-bottom: 1px solid #000;">
                                        <td style="text-align: right; font-size: 12px; width: 70%">{{ __('Discount') }}:</td>
                                        <td style="text-align: right; font-size: 12px; width: 70%">
                                            {{ $general_setting->currency }}{{ number_format($dataInfo->invoice_discount, 2) }}
                                        </td>
                                    </tr>
                                    <tr style="  border-bottom: 1px solid #000;">
                                        <td style="text-align: right; font-size: 12px; width: 70%">{{ __('Due') }}:</td>
                                        <td style="text-align: right; font-size: 12px; width: 70%">
                                            {{ $general_setting->currency }}{{ number_format($dataInfo->due_amount, 2) }}
                                        </td>
                                    </tr>

                                    <tr style="  border-bottom: 1px solid #000;">
                                        <td style="text-align: right; font-size: 12px; width: 70%">{{ __('Grand Total') }}:</td>
                                        <td style="text-align: right; font-size: 12px; width: 70%">
                                            {{ $general_setting->currency }}{{ number_format($dataInfo->payable_total, 2) }}
                                        </td>
                                    </tr>
                                </table>
                            </section>

                            <section
                                style="line-height: 1.222; font-size: 12px; font-style: italic; padding: 0px 0; border-top: 1px solid #000; border-bottom: 1px solid #000;">
                                <span
                                    style="line-height: 1.222; font-size: 12px; font-style: italic; text-transform: uppercase"><b>{{ __('In Text:') }}
                                    </b>
                                    {{ Helper::convert_number_to_words($dataInfo->payable_total) }}</span><br>
                            </section>
                            <section style="font-size: 12px; line-height: 1.222; text-align: center; padding-top: 0px;">
                                <span style="display: block; font-weight: 700;">{{ __('Thank you for choosing us!') }}</span>
                            </section>
                        </section>
                        <div class="row">
                            <div class="col-md-12" style="text-align: center;">
                                <button type="button" onclick="printDiv('invoiceArea')" class="btn print-btn">
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                    {{ __('Print') }}
                                </button>
                            </div>
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
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            // location.reload();
            // Dictionary update of auto print
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('print')) {
                printDiv('invoiceArea');
            }
        }
    </script>
@endpush
