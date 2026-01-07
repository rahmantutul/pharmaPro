<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('assets/plugins/font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/css/invoice.css') }}">
    </head>
    <body>
        <div class="invoice-wrapper" id="print-area">
            <div class="invoice">
                <div class="invoice-container">
                    <div class="invoice-head">
                        <div class="invoice-head-top">
                            <div class="invoice-head-top-left text-start">
                                <img src="{{ asset('uploads/images/settings/' . Helper::getStoreInfo()->logo) }}">
                            </div>
                            <div class="invoice-head-top-right text-end">
                                <h3>{{ __('Purchase Invoice') }}</h3>
                            </div>
                        </div>
                        <div class="hr"></div>
                        <div class="invoice-head-middle">
                            <div class="invoice-head-middle-left text-start">
                                <p><span class="text-bold">{{ __('Date') }}:</span> {{ $dataInfo->created_at->format('F j, Y, g:i A'); }}</p>
                            </div>
                            <div class="invoice-head-middle-right text-end">
                                <p><span class="text-bold" style="text-transform: uppercase">{{ __('Invoice No') }}:</span> {{ $dataInfo->invoice_no }}</p>
                            </div>
                        </div>
                        <div class="hr"></div>
                        <div class="invoice-head-bottom">
                            <div class="invoice-head-bottom-left">
                                <ul>
                                    <li class="text-bold">{{ __('Invoiced To') }}:</li>
                                    <li>{{ Helper::getStoreInfo()->appname }}</li>
                                    <li>{{ Helper::getStoreInfo()->address }}</li>
                                    <li>{{ Helper::getStoreInfo()->phone }}</li>
                                    <li>{{ Helper::getStoreInfo()->email }}</li>
                                </ul>
                            </div>
                            <div class="invoice-head-bottom-right">
                                @php
                                    $supplier = optional($dataInfo->details->first())->medicine->supplier ?? 'No Supplier';
                                @endphp
                                <ul class="text-end">
                                    <li class="text-bold">{{ __('Pay To') }}:</li>
                                    <li>{{ $supplier->name }}</li>
                                    <li>{{ $supplier->address }}</li>
                                    <li>{{ $supplier->phone }}</li>
                                    <li>{{ $supplier->email }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-view">
                        <div class="invoice-body">
                            <table>
                                <thead>
                                    <tr>
                                        <td>{{ __('Medicine Name') }}</td>
                                        <td>{{ __('Strength') }}</td>
                                        <td>{{ __('Supplier') }}</td>
                                        <td>{{ __('Price') }}</td>
                                        <td>{{ __('Quantity') }}</td>
                                        <td>{{ __('Total') }}</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $subtotal = 0; 
                                    @endphp
                                    @foreach ($dataInfo->details as $item)
                                    @php
                                    $subtotal +=  $item->total;
                                    @endphp
                                    <tr>
                                        <td>{{ $item->medicine->name }}</td>
                                        <td>{{ $item->medicine->strength }}</td>
                                        <td>{{ $item->medicine->supplier->name }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ Helper::getStoreInfo()->currency }} {{ $item->total }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="invoice-body-bottom">
                                <div class="invoice-body-info-item border-bottom">
                                    <div class="info-item-td text-end text-bold">{{ __('Sub Total') }}:</div>
                                    <div class="info-item-td text-end">{{ Helper::getStoreInfo()->currency }} {{ $subtotal }}</div>
                                </div>
                                <div class="invoice-body-info-item border-bottom">
                                    <div class="info-item-td text-end text-bold">{{ __('Discount') }}:</div>
                                    <div class="info-item-td text-end">{{ Helper::getStoreInfo()->currency }} {{ $dataInfo->total_discount }}</div>
                                </div>
                                <div class="invoice-body-info-item border-bottom">
                                    <div class="info-item-td text-end text-bold">{{ __('Total') }}:</div>
                                    <div class="info-item-td text-end">{{ Helper::getStoreInfo()->currency }} {{ $dataInfo->total_amount }}</div>
                                </div>
                                <div class="invoice-body-info-item border-bottom">
                                    <div class="info-item-td text-end text-bold">{{ __('Paid Amount') }}:</div>
                                    <div class="info-item-td text-end">{{ Helper::getStoreInfo()->currency }} {{ $dataInfo->paid_amount }}</div>
                                </div>
                                <div class="invoice-body-info-item">
                                    <div class="info-item-td text-end text-bold">{{ __('Due Amount') }}:</div>
                                    <div class="info-item-td text-end">{{ Helper::getStoreInfo()->currency }} {{ $dataInfo->due_amount }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-foot text-center">
                        <p><span class="text-bold text-center">{{ __('NOTE:') }}&nbsp;</span>{{ __('This is a computer-generated receipt and does not require a physical signature.') }}</p>
        
                        <div class="invoice-btns">
                            <button type="button" class="invoice-btn" onclick="printInvoice()">
                                <span>
                                    <i class="fa fa-print"></i>
                                </span>
                                <span>{{ __('Print') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <script>
            function printInvoice(){
                window.print();
            }
        </script>
    </body>
</html>