@extends('student.layout')
@section('section')
@php
$user = auth('student')->user();
$user = $user == null ? auth()->user() : $user;
$bg1 = \App\Http\Controllers\HomeController::getColor('background_color_1');
@endphp
    <div class="container-fluid">
        <div class="row">
            @foreach ($campuses as $campus)
                <div class="col-md-6">
                    <div class="" style="border: 1px solid gray; margin: 2rem auto; border-radius: 0.5rem; max-width: 300px;">
                        <div class="text-center" style="padding-block: 2rem;">
                            <h4 class="card-title" style="font-size: 2.5rem; font-weight: 900; color: {{ $bg1 }}; ">{{ $campus->name }}</h4>
                            <div style="margin-block: 0.5rem !important; padding-block: 0.5rem !important;">{{ $campus->telephone }} &Rang; {{ $campus->address }}</div>
                            <div class="" style="margin: 0.5rem auto !important; padding: 0.5rem auto !important;">
                                <a href="{{ route('student.programs.index', ['campus_id' => $campus->id]) }}" style="border: 2px solid {{ $bg1 }}; border-radius: 0.5rem; padding: 1.5rem 3rem; margin-block: 1rem; text-decoration: none; color: {{ $bg1 }}; font-weight: 700; margin: 2rem auto; font-size: 1.85rem;" class="text-capitalize">@lang('text.view_programs')</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection