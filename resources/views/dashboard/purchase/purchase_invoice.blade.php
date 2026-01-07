<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" href="">

    <!-- Font -->
    <title>Print Invoice</title>
    <style>
        body {
            background-color: #444;
        }

        .btn {
            color: #fff;
            cursor: pointer;
            font-size: 17px;
            border-radius: 2px;
            text-decoration: none;
            border: 1px solid inherit;
            height: 40px;
            padding: 10px 35px;
            font-family: sans-serif;
        }

        .back-to-list {
            background-color: #FF9800;
            border: 1px solid #FF9800;
            margin: 0 20px;
        }

        .back-to-dashboard {
            background-color: #2196F3;
            border: 1px solid #2196F3;
            margin: 0 20px;
        }

        .print-button {
            background-color: #673AB7;
            border: 1px solid #673AB7;
            margin: 0 20px;
        }

        .send-to-email {
            background-color: #FF5722;
            border: 1px solid #FF5722;
            margin: 0 20px;
        }

        .send-to-whatsapp {
            background-color: #22ff2d;
            border: 1px solid #22ff2d;
            margin: 0 20px;
        }

        .action-button {
            position: fixed;
            left: 0;
            bottom: 0;
            background: #081624;
            width: 100%;
            text-align: center;
            padding: 11px 0;
            z-index: 123;
        }
    </style>
</head>
<body>
    <div class="table-responsive">
        <div id="invoice" class="row">
            <div class="action-button">
                <a href="{{ route('home') }}" class="btn back-to-dashboard">
                    Back to Dashboard
                </a>
                <a href="{{ route('customer.index') }}" class="btn back-to-list">
                    Back to list
                </a>
                <button type="button" onclick="printDiv('invoiceArea')" class="btn print-button">
                    Print
                </button>
                <a href="#" class="btn send-to-email">
                    Send to email
                </a>
            </div>
            <section style="width: 302px; margin: 10px auto;background-color: #fff; padding:5px;margin-bottom: 70px;height: auto; border-top: 1px solid #000;" id="invoiceArea">
                <header style="text-align: center; padding-bottom: 0px">
                    <h2 style="font-size: 24px; font-weight: 700; margin: 0; padding: 0;">{{ $general_setting->appname}}</h2>
                    <div style="margin-bottom: 5px; line-height: 1;">
                        <span style="font-size: 12px;">{{ $general_setting->address}}</span>
                        <div style="display: block;">
                            <span style="font-size: 12px;">Mobile: {{ $general_setting->phone}}</span>, <span
                                style="font-size: 12px;">Email: {{ $general_setting->email}}</span>
                        </div>
                    </div>
                </header>
                
                <section style="font-size: 12px;  line-height: 1.222; border-top: 1px solid #000;">
                    <table style="width: 100%;">
                        <tr style=" border-bottom: 1px solid #000;">
                            <td class="w-30" style="font-size:12px"><span style="font-size:12px"><b>Date:</b></span>
                            </td>
                            <td style="font-size:12px">{{ \Carbon\Carbon::parse($dataInfo->created_at)->format('d M, Y h:i A') }}</td>
                        </tr>
                        <tr style=" border-bottom: 1px solid #000;">
                            <td class="w-30" style="font-size:12px"><span style="font-size:12px"><b>Invoice ID:</b></span>
                            </td>
                            <td style="font-size:12px">{{ $dataInfo->invoice_no }}</td>
                        </tr>
                        <tr style=" border-bottom: 1px solid #000;">
                            <td class="w-30" style="font-size:12px"><b>Customer Name:</b></td>
                            <td style="font-size:12px">{{ $dataInfo->customer->name }}</td>
                        </tr>
                        <tr style=" border-bottom: 1px solid #000;">
                            <td class="w-30" style="font-size:12px"><b>Phone:</b></td>
                            <td style="font-size:12px">{{ $dataInfo->customer->phone }}</td>
                        </tr>
                        <tr style=" border-bottom: 1px solid #000;">
                            <td class="w-30" style="font-size:12px"><b>Address:</b></td>
                            <td style="font-size:12px">{{ $dataInfo->customer->address }}</td>
                        </tr>
                    </table>
                </section>

                <h4 style="font-size: 18px; font-weight: 700; text-align: center; margin: 5px 0 0px 0; padding: 0px 0;">INVOICE</h4>
                
                <section style="line-height: 1.23; border-top: 1px solid #000;">
                    <table style="width: 100%">
                        <thead>
                            <tr style=" border-bottom: 1px solid #000; font-weight: 700;">
                                <th class="w-10 text-center" style="font-size: 12px; text-align: center">Sl.</th>
                                <th class="w-40" style="font-size: 12px;">Name</th>
                                <th class="w-15 text-center" style="font-size: 12px; text-align: center">Qty</th>
                                <th class="w-15 text-right" style="font-size: 12px; text-align: center">Price</th>
                                <th class="w-20 text-right"
                                    style="border-bottom: none; font-size: 12px; text-align: center">Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataInfo->details as $key=>$item)
                            <tr style=" border-bottom: 1px solid #000;">
                                <td class="text-center" style="vertical-align: top; font-size: 12px; text-align: center">{{ $key+1 }}</td>
                                <td style="vertical-align: top; font-size: 12px; text-align: center">{{ $item->medicine->name }} </td>
                                <td class="text-center" style="vertical-align: top; font-size: 12px; text-align: center">{{ $item->qty }} </td>
                                <td class="text-right" style="vertical-align: top; font-size: 12px; text-align: center">{{ $general_setting->currency }} {{ $item->sell_price }}</td>
                                <td class="text-right"style="border-bottom: none;vertical-align: top;font-size: 12px;text-align: center ">{{ $general_setting->currency }} {{ $item->total }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
                <section style="line-height: 1.23; font-size: 12px; border-top: 1px solid #000; border-top: 1px solid #000;">
                    <table style="width: 93%; margin-right: 7%">
                        <tr style="  border-bottom: 1px solid #000;">
                            <td style="text-align: right; font-size: 12px; width: 70%">Sub Total:</td>
                            <td style="text-align: right; font-size: 12px; width: 70%">{{ $general_setting->currency }} {{ number_format($dataInfo->grand_total, 2) }}</td>
                        </tr>
                        <tr style="  border-bottom: 1px solid #000;">
                            <td style="text-align: right; font-size: 12px; width: 70%">Discount:</td>
                            <td style="text-align: right; font-size: 12px; width: 70%">{{ $general_setting->currency }}{{ number_format($dataInfo->invoice_discount, 2) }}</td>
                        </tr>
                        <tr style="  border-bottom: 1px solid #000;">
                            <td style="text-align: right; font-size: 12px; width: 70%">Due:</td>
                            <td style="text-align: right; font-size: 12px; width: 70%">{{ $general_setting->currency }}{{ number_format($dataInfo->due_amount, 2) }}</td>
                        </tr>

                        <tr style="  border-bottom: 1px solid #000;">
                            <td style="text-align: right; font-size: 12px; width: 70%">Grand Total:</td>
                            <td style="text-align: right; font-size: 12px; width: 70%">{{ $general_setting->currency }}{{ number_format($dataInfo->payable_total, 2) }}</td>
                        </tr>
                    </table>
                </section>
                
                <section style="line-height: 1.222; font-size: 12px; font-style: italic; padding: 0px 0; border-top: 1px solid #000; border-bottom: 1px solid #000;">
                    <span style="line-height: 1.222; font-size: 12px; font-style: italic; text-transform: uppercase"><b>In Text:</b> {{ Helper::convert_number_to_words($dataInfo->payable_total) }}</span><br>
                </section>
                
                <section style="font-size: 12px; line-height: 1.222; text-align: center; padding-top: 0px;">
                    <span style="display: block; font-weight: 700;">Thank you for choosing us!</span>
                </section>

            </section>
        </div>
    </div>
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            // location.reload();
        }
    </script>
</body>

</html>
