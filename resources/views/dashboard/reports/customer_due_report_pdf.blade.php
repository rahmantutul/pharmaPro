<!DOCTYPE html>
<html lang="en" >
<head>
    <title>{{ __('Sales Report') }}</title>
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
                  <tr><th align="center" colspan="5"><h2>{{env('APP_NAME')}}</h2></b></th></tr>
                  <tr><th align="center" colspan="5"><b>{{ __('Customer Due Report')}}</b></th></tr>
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
                          <th scope="col">{{__('#')}}</th>
                          <th scope="col" align="center">{{__('Customer')}}</th>
                          <th scope="col" align="center">{{__('Total Sales Amount')}}</th>
                          <th scope="col" align="center">{{__('Paid Amount')}}</th>
                          <th scope="col" align="center">{{__('Invoice Due')}}</th>
                        </tr>
                    </thead>
                    @php
                        $payableTotal=0;
                        $paidTotal=0;
                        $dueTotal=0;    
                    @endphp
                    <tbody>
                        @foreach ($dataList as $key=>$data)
                        @php
                        $payableTotal += $data->total_payable;
                        $paidTotal += $data->total_paid;
                        $dueTotal += $data->total_due;
                        @endphp
                        <tr>
                          <td>{{$key+1}}</td>
                          <td align="center"><b>{{$data->name}}</b></td>
                          <td align="right">{{number_format($data->total_payable,2)}}</td>
                          <td align="right">{{number_format($data->total_paid,2)}}</td>
                          <td align="right">{{number_format($data->total_due,2)}}</td>
                        </tr>
                        @endforeach
                        <tr>
                          <td colspan="2"></td>
                          <td align="right"><b>{{number_format($payableTotal,2)}}</b></td>
                          <td align="right"><b>{{number_format($paidTotal,2)}}</b></td>
                          <td align="right"><b>{{number_format($dueTotal,2)}}</b></td>
                        </tr>
                    </tbody>
                    </table>
                </div>
             </div>
            </p>
        </main>
    </body>
</html>
