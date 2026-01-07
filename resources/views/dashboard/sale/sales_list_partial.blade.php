@foreach ($dataList as $key=>$data)
<tr>
    <td>{{ $dataList->firstItem() + $key }}</td>
    <td class="text-bold-500">{{ date('d M, Y h:i A', strtotime($data->invoice_date)) }}</td>
    <td>{{$data->invoice_no}}</td>
    <td>
        @if ($data->customer)
            {{$data?->customer?->name}}
        @else
           {{ __('Walking Customer') }}
        @endif
        
    </td>
    <td align="right">{{ $general_setting->currency }}{{$data->grand_total}}</td>
    <td align="right">{{ $general_setting->currency }}{{$data->invoice_discount}}</td>
    <td align="right">{{ $general_setting->currency }}{{$data->payable_total}}</td>
    <td align="right">{{ $general_setting->currency }}{{$data->paid_amount}}</td>
    <td align="right">{{ $general_setting->currency }}{{$data->due_amount}}</td>
    <td>
        <a href="#" data-toggle="modal" data-id="{{$data->id}}" data-target="#medicineModal" class="btn btn-sm btn-success actionButton"><i class="fa fa-eye"></i></a>
        <a href="{{route('sales.order.invoice')}}?id={{ $data->id }}" class="btn btn-sm btn-primary"><i class="fa fa-file-text-o"></i></a>
        <a href="{{route('sales.order.invoice.download')}}?id={{ $data->id }}" class="btn btn-sm btn-danger"><i class="fa fa-file-pdf-o"></i> PDF</a>
        <a href="{{route('sales.order.destroy', $data->id)}}" onclick="return confirm('Are you sure you want to delete this sale?')" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></a>
    </td>
</tr>
@endforeach
<tr>
    <td colspan="10" align="center">
        {!! $dataList->links() !!}
    </td>
</tr>
