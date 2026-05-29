@extends('admin.layout')
@section('section')
    <div class="container-fluid">   
        {{-- form to select certificate for report --}}
        <form method="get">
            <div class="row">
                <div class="col-md-8">
                    <select name="certificate_id" required class="form-control" id="">
                        <option value=""></option>
                        @foreach($certificates as $certificate)
                            <option value="{{$certificate->id}}" {{(request()->get('certificate_id') == $certificate->id) ? 'selected' : ''}}>{{$certificate->certi}}</option>
                        @endforeach
                    </select>
                    <label for="" class="text-capitalize text-info">@lang('text.word_certificate')</label>
                </div> 
                <div class="col-md-4">
                    <button class="btn btn-primary form-control btn-sm text-capitalize" type="submit">{{__('text.word_generate')}}</button>
                </div> 
            </div> 
        </form>
        <hr>
        @if(isset($report))
            <div class="row">
                <div class="col-md-12">
                    <div>
                        <form method="get" style="float: right;">
                            <input type="hidden" name="certificate_id" value="{{request()->get('certificate_id')}}">
                            <input type="hidden" name="download" value="csv">
                            <button class="btn btn-success btn-sm text-capitalize" type="submit" formaction="{{route('admin.applications.entry_qualification.report', ['download' => 'pdf'])}}">
                                <i class="fa fa-file-pdf-o"></i> @lang('text.download_csv')
                            </button>
                        </form>
                    </div>
                    <h5 class="text-center text-capitalize"><b>@lang('text.word_certificate'): {{$selected_certificate->certi}}</b></h5>
                    <hr>
                    <table class="table table-bordered table-striped">
                        <thead class="text-capitalize">
                        {{-- show following columns: Name, Date of birth, Place of birth, Region of origin, Sex, Phone number, Email, School, Level, Option, Diplome --}}
                            <tr>
                                <th>@lang('text.sn')</th>
                                <th>@lang('text.word_name')</th>
                                <th>@lang('text.date_of_birth')</th>
                                <th>@lang('text.place_of_birth')</th>
                                <th>@lang('text.word_region')</th>
                                <th>@lang('text.word_gender')</th>
                                <th>@lang('text.word_phone')</th>
                                <th>@lang('text.word_email')</th>
                                <th>@lang('text.word_school')</th>
                                <th>@lang('text.word_level')</th>
                                <th>@lang('text.word_program')</th>
                                <th>@lang('text.entry_certificate')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp
                            @foreach($report as $application)
                                <tr>
                                    <td>{{$counter++}}</td>
                                    <td>{{$application->name}}</td>
                                    <td>{{$application->dob}}</td>
                                    <td>{{$application->pob}}</td>
                                    <td>{{$application->region_name}}</td>
                                    <td>{{$application->gender}}</td>
                                    <td>{{$application->phone}}</td>
                                    <td>{{$application->email}}</td>
                                    <td>{{$application->school_name}}</td>
                                    <td>{{$application->level}}</td>
                                    <td>{{$application->program_name}}</td>
                                    <td>{{$application->certificate_name}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection