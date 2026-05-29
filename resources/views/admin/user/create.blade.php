@extends('admin.layout')

@section('section')
    <div class="mx-3">
        <div class="form-panel">
            <form class="form-horizontal" role="form" method="POST" action="{{route('admin.users.store')}}">
                <input name="type" value="{{request('type','teacher')}}" type="hidden" />
                @csrf
                <div class="form-group @error('name') has-error @enderror">
                    <label for="cname" class="control-label col-lg-2 text-capitalize">@lang('text.full_name') <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input class=" form-control" name="name" value="{{old('name')}}" type="text" required />
                        @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="form-group @error('email') has-error @enderror">
                    <label for="cname" class="control-label col-lg-2 text-capitalize">@lang('text.word_email') (@lang('text.word_required'))</label>
                    <div class="col-lg-10">
                        <input class=" form-control" name="email" value="{{old('email')}}" type="email" required />
                        @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group @error('campus') has-error @enderror">
                    <label for="cname" class="control-label col-lg-2 text-capitalize">{{__('text.word_campus')}}</label>
                    <div class="col-lg-10">
                        @if(\Auth::user()->campus_id != null)
                            <input type="hidden" name="campus" id="" value="{{\Auth::user()->campus_id}}">
                        @endif
                        <select class=" form-control" name="campus" type="text" {{\Auth::user()->campus_id != null ? 'readonly' : ''}}>
                            <option value="" selected>{{__('text.word_campus')}}</option>
                            @foreach($campuses as $cmps)
                                <option value="{{$cmps->id}}" {{\Auth::user()->campus_id == $cmps->id ? 'selected' : ''}}>{{$cmps->name}}</option>
                            @endforeach
                        </select>
                        @error('campus')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="form-group @error('phone') has-error @enderror">
                    <label for="cname" class="control-label col-lg-2 text-capitalize">@lang('text.word_phone')</label>
                    <div class="col-lg-10">
                        <input class=" form-control" name="phone" value="{{old('phone')}}" type="text" required />
                        @error('phone')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group @error('address') has-error @enderror">
                    <label for="cname" class="control-label col-lg-2 text-capitalize">@lang('text.word_address')</label>
                    <div class="col-lg-10">
                        <input class=" form-control" name="address" value="{{old('address')}}" type="text" required />
                        @error('address')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group @error('gender') has-error @enderror">
                    <label for="cname" class="control-label col-lg-2 text-capitalize">@lang('text.word_gender')</label>
                    <div class="col-lg-10">
                        <select class="form-control" name="gender">
                            <option selected disabled>@lang('text.word_gender')</option>
                            <option {{old('gender') == 'male'?'selected':''}} value="male">@lang('text.word_male')</option>
                            <option {{old('gender') == 'female'?'selected':''}} value="female">@lang('text.word_female')</option>
                        </select>
                        @error('gender')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <input type="hidden" name="type" id="" value="{{request('type') ?? ''}}">
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button class="btn btn-xs btn-primary text-capitalize" type="submit">@lang('text.word_save')</button>
                        <a class="btn btn-xs btn-danger text-capitalize" href="{{route('admin.users.index')}}" type="button">@lang('text.word_cancel')</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
