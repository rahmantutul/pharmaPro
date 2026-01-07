<!DOCTYPE html>
<html lang="en" >
<head>
    <title>Purchase Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" href="{{ public_path('assets/css/pdf.css') }}">
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
          <div class="row justify-content-center">
            <div class="col-md-12">
              <table>
                <thead>
                  <tr><th align="center" colspan="5"><h2>{{ $general_info->appname }}</h2></b></th></tr>
                  <tr><th align="center" colspan="5"><b>Purchase Report</b></th></tr>
                  <tr><th align="center" colspan="5"><font size="2"><b>Date: &nbsp;{{ date('d-m-Y', strtotime($fromdate)) }} TO {{ date('d-m-Y', strtotime($todate)) }} </b><font></th></tr>
                </thead>
              </table>
            </div>
          </div>
        </header>
        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <p>
              <div class="row justify-content-center">
                <div class="col-md-12">
                    <table class="table table-striped table-data table-view">
                      <thead>
                        <tr>
                            <th scope="col">{{ __('#') }}</th>
                            <th scope="col" align="center">{{__('Date')}}</th>
                            <th scope="col" align="center">{{__('Invoice')}}</th>
                            <th scope="col" align="center">{{__('Supplier')}}</th>
                            <th scope="col" align="center">{{__('Inv. Discount')}}</th>
                            <th scope="col" align="center">{{__('Total')}}</th>
                            <th scope="col" align="center">{{__('Paid Amount')}}</th>
                            <th scope="col" align="center">{{__('Invoice Due')}}</th>
                        </tr>
                    </thead>
                    @php
                        $total=0;
                        $totalDiscount=0;
                        $paidTotal=0;
                        $dueTotal=0;    
                    @endphp
                    <tbody>
                        @foreach ($dataList as $key=>$data)
                        @php
                            $total += $data->total_amount;
                            $totalDiscount += $data->total_discount;
                            $paidTotal += $data->paid_amount;
                            $dueTotal += $data->due_amount;
                        @endphp
                        <tr>
                            <td>{{$key+1}}</td>
                            <td class="text-bold-500 text-center">{{date('j F Y',strtotime($data->invoice_date))}}</td>
                            <td align="center">{{$data->invoice_no}}</td>
                            <td align="center">{{$data?->supplier?->name}}</td>
                            <td align="center"> {{number_format($data->total_discount,2)}}</td>
                            <td align="center"> {{number_format($data->total_amount,2)}}</td>
                            <td align="center"> {{number_format($data->paid_amount,2)}}</td>
                            <td align="center"> {{number_format($data->due_amount,2)}}</td>
                        </tr>
                        @endforeach
                        <tr>
                          <td colspan="4"></td>
                          <td align="center"><b> {{number_format($totalDiscount,2)}}</b></td>
                          <td align="center"><b> {{number_format($total,2)}}</b></td>
                          <td align="center"><b> {{number_format($paidTotal,2)}}</b></td>
                          <td align="center"><b> {{number_format($dueTotal,2)}}</b></td>
                        </tr>
                    </tbody>
                    </table>
                </div>
             </div>
            </p>
        </main>
    </body>
</html>
