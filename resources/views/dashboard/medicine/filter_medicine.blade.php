@foreach ($medicines as $key => $data)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>
            @php
                $imagePath = 'uploads/images/medicine/' . $data->image;
                $displayImage = (file_exists(public_path($imagePath)) && !empty($data->image)) 
                               ? asset($imagePath) 
                               : asset('uploads/images/medicine/default.png');
            @endphp
            <img style="height:40px; width:40px; border:1px solid #000;" src="{{ $displayImage }}" alt="Medicine Image">
        </td>
        <td class="text-bold-500">{{ $data->name }}</td>
        <td>{{ $data->generic_name }}</td>
        <td>{{ $data->strength }}</td>
        <td>{{ $data->sell_price }}</td>
        <td>{{ $data->purchase_price }}</td>
        <td>{{ $data->supplier ? $data->supplier->name : 'N/A' }}</td>
        <td>
            <a class="btn btn-xs btn-primary" href="{{ route('medicine.index', $data->id) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
            <a class="btn btn-xs btn-danger" href="{{ route('medicine.destroy', $data->id) }}" onclick="return confirm('{{ __('Are you sure you want to delete this medicine?') }}')"><i class="fa fa-trash-o icon-trash"></i></a>
        </td>
    </tr>
@endforeach

<!-- Pagination links -->
<tr>
    <td colspan="9">
        {{$medicines->links("pagination::bootstrap-4")}}
    </td>
</tr>