@extends('admin.layout')
@section('section')
    <div class="py-2">
        <table class="table table-light">
            <thead class="text-capitalize">
                <tr>
                    <th class="header text-center">@lang('text.word_student')</th>
                    <th colspan="4"> <input type="search" name="" id="" oninput="searchStudent(this)" class="form-control" placeholder="@lang('text.search_by_name_email_or_phone')"> </th>
                </tr>
                <tr class="border-y border-dark">
                    <th>#</th>
                    <th>@lang('text.word_name')</th>
                    <th>@lang('text.word_email')</th>
                    <th>@lang('text.word_phone')</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="students_data"></tbody>
        </table>
    </div>
@endsection
@section('script')
<script>
    let searchStudent = function(element){
        let input = $(element).val();
        let url = `{{ route('admin.search_student') }}`;
        $.ajax({
            method: 'get',
            url: url,
            data: {'key': input},
            success: function(resp){
                let html = '';
                let counter = 1;
                console.log(resp);

                resp.forEach(element => {
                    html += `
                        <tr>
                            <td>${counter++}</td>
                            <td>${element.name}</td>
                            <td>${element.email}</td>
                            <td>${element.phone}</td>
                            <td>
                                <form action="{{ route('admin.platform.bypass') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="student_id" value="${element.id}">
                                    <button class="btn btn-xs btn-primary rounded">@lang('text.word_bypass')</button>
                                </form>
                            </td>
                        </tr>;
                        `
                });
                $('#students_data').html(html);
            }
            
        });
    }
</script>
@endsection