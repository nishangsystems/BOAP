@extends('admin.layout')
@section('section')
<div class="py-4">
    <table class="table">
        <thead class="text-capitalize">
            <th>{{__('text.word_name')}}</th>
            <th>{{__('text.word_quantity')}}</th>
            <th>{{__('text.word_type')}}</th>
            <th></th>
        </thead>
        <tbody>
                @foreach($stock as $item)
                <tr>
                    <td>{{$item->name}}</td>
                    <td>{{$item->campusStock(auth()->user()->campus_id)->quantity ?? 0}}</td>
                    <td>{{$item->type}}</td>
                    <td>
                        <a href="{{route('admin.stock.campus.restore', [auth()->user()->campus_id, $item->id])}}" class="btn btn-sm btn-warning">{{__('text.word_restore')}}</a>|
                        @if($item->type == 'receivable')
                        <a href="{{route('admin.stock.campus.receive', [auth()->user()->campus_id, $item->id])}}" class="btn btn-sm btn-primary">{{__('text.word_receive')}}</a>
                        @else
                        <a href="{{route('admin.stock.campus.giveout', [auth()->user()->campus_id, $item->id])}}" class="btn btn-sm btn-success">{{__('text.give_out')}}</a>
                        @endif
                    </td>
                </tr>
                @endforeach
        </tbody>
    </table>
</div>
@endsection