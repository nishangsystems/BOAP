@extends('admin.layout')
@section('section')
@php
$user = \Auth()->user()
@endphp
<div>
    <table class="table border">
        <thead class="text-capitalize">
            <tr class="border-y">
                <th colspan="3" class="text-center header">{{ $title ?? '' }}</th>
            </tr>
            <tr class="border-y">
                <th>#</th>
                <th>@lang('text.word_program')</th>
                <th>@lang('text.word_totals')</th>
                {{-- <th>@lang('text.word_international')</th>
                <th>@lang('text.first_instalment')</th>
                <th>@lang('text.word_bank')</th> --}}
            </tr>
        </thead>
        <tbody>
            @php
                $counter = 1;
            @endphp
            <tr class="border-y text-center"><td colspan="3"><b class="heading text-capitalize">@lang('text.total_applicants'): {{ collect($totals)->sum('applicants') }}</b></td></tr>
            @foreach ($totals as $_sc => $school)
                <tr class="border-y text-center"><td colspan="3"> <b class="header text-capitalize"> @lang('text.SCHOOL_OF') {{ $_sc ?? '----' }} :: @lang('text.word_applicants') : {{ $school['applicants'] }}</b> </td></tr>
                @foreach ($school['depts'] as $_dp => $department)
                    <tr class="border-y text-center"><td colspan="3"> <span class="heading text-capitalize"> @lang('text.DEPARTMENT_OF') {{ $_dp ?? '----' }} :: @lang('text.word_applicants') : {{ $department['applicants'] }}</span> </td></tr>
                    @foreach ($department['progs'] as $_pg => $program)
                        <tr class="border-y text-center">
                            <td class="border">{{ $counter++ }}</td>
                            <td class="border">{{ $program[0]['program'] ?? '----' }}</td>
                            <td class="border">@lang('text.word_applicants') : {{ $program['applicants'] }} </td>
                        </tr>
                        {{-- @foreach ($program as $class)
                            <tr>
                                <td class="border">{{ $class['class_name'] }}</td>
                                <td class="border">{{ $class['amount'] }}</td>
                                <td class="border">{{ $class['international_amount'] }}</td>
                                <td class="border">{{ $class['first_instalment'] }}</td>
                                <td class="border">
                                    <span> bank: {{ $class['bank_name'] }}</span><br>
                                    <span> account name: {{ $class['bank_account_name'] }}</span><br>
                                    <span> account number: {{ $class['bank_account_number'] }}</span>
                                </td>
                            </tr>
                        @endforeach --}}
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection
