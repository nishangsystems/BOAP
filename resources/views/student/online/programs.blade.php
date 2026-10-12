@extends('student.layout')
@section('section')
    <div class="w-100">
        <div id="accordion" class="panel-group">
            @foreach (($campuses[$campus_id]->programs) ?? [] as $key => $school)                    
                <table class="border">
                    <thead><tr class="text-capitalize border-bottom">
                        <th class="border-left border-right">{{ __('text.sn') }}</th>
                        <th class="border-left border-right">{{ __('text.word_program') }}</th>
                        <th class="border-left border-right"></th>
                    </tr></thead>
                    <tbody>
                        @php($k = 1)
                        <tr><th class="text-center text-uppercase border-top border-bottom border-dark" colspan="3">{{ $key }}</th></tr>
                        <tr>
                            <td colspan="3">
                                <div id="accordion" class="accordion-style1 panel-group">
                                    @foreach (collect($school??[])->groupBy('degree_type') as $_key => $degree_programs)
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{str_replace(' ', '_', $key.$_key)}}">
                                                        <i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
                                                        &nbsp;{!! $_key !!}
                                                    </a>
                                                </h4>
                                            </div>
                                            
                                            <div class="panel-collapse collapse" id="collapse_{{str_replace(' ', '_', $key.$_key)}}">
                                                <div class="">
                                                    <div class="itemdiv dialogdiv">
                                                        <table>
                                                            <thead><tr class="text-capitalize border-bottom">
                                                                <th class="border-left border-right">{{ __('text.sn') }}</th>
                                                                <th class="border-left border-right">{{ __('text.word_program') }}</th>
                                                                <th class="border-left border-right">{{ __('text.word_action') }}</th>
                                                            </tr></thead>
                                                            <tbody>
                                                            @php($k = 1)
                                                                @foreach ($degree_programs??[] as $program)
                                                                <tr class="border-bottom">  
                                                                    <td class="border-left border-right">{{ $k++ }}</td>
                                                                    <td class="border-left border-right">{{ $program->name }}</td>
                                                                    <td class="border-left border-right">
                                                                        <a href="{{ route('student.application.start', 0) }}?_prg={{ $program->id }}&degree_id={{ $program->degree_id }}" class="btn btn-sm rounded btn-primary">@lang('text.word_apply')</a>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
@endsection