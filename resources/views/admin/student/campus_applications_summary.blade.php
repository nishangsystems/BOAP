@extends('admin.layout')
@section('section')
    <div class="py-3">
        <table class="table">
            <thead class="text-uppercase border-bottom">
                <th class="border-left border-right">{{ __('text.sn') }}</th>
                <th class="border-left border-right">{{ __('text.word_program') }}</th>
                <th class="border-left border-right">{{ __('text.word_count') }}</th>
            </thead>
            <tbody>
                @php($k = 1)
                @foreach ($programs as $program)
                    <tr class="border-bottom">
                        <td class="border-left border-right">{{ $k++ }}</td>
                        <td class="border-left border-right">{{ $program->name }}</td>
                        <td class="border-left border-right">{{ $program->applicants }}</td>
                    </tr>
                @endforeach
                <tr class="font-weight-bold border-bottom">
                    <td class="border-left border-right" colspan="2">{{ __('text.word_total') }}</td>
                    <td class="border-left border-right">{{ $total_applicants }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection