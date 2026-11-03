@extends('admin.layout')

@section('section')
    <div class="py-3">
        <h4>{{ $title }}</h4>
        <table class="table">
            <thead class="text-capitalize border-bottom">
                <tr>
                    <th class="border-left border-right">{{ __('text.sn') }}</th>
                    <th class="border-left border-right">{{ __('text.word_program') }}</th>
                    <th class="border-left border-right">{{ __('text.word_count') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $application)
                    <tr>
                        <td class="border-left border-right">{{ $loop->iteration }}</td>
                        <td class="border-left border-right">{{ $application->program }}</td>
                        <td class="border-left border-right">{{ number_format($application->applicants) }}</td>
                    </tr>
                @endforeach
                <tr class="border-top font-weight-bold">
                    <th class="border-left border-right" colspan="2">{{ __('text.word_total') }}</th>
                    <th class="border-left border-right">{{ number_format($applications->sum('applicants')) }}</th>
                </tr>
            </tbody>
        </table>
    </div>
@endsection