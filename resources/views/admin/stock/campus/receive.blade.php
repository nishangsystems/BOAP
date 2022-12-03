@extends('admin.layout')

@section('section')
<div class="col-sm-12">
    
    @if(request()->has('student_id') && !(request('student_id') == null ))
    <form method="get" action="{{route('admin.stock.campus.accept', [request('campus_id'), request('id')])}}">
        <input type="hidden" name="student_id" value="{{request('student_id')}}">
        <div class="row my-2">
            <label class="col-md-3 col-lg-3 text-capitalize">{{__('text.word_quantity')}}</label>
            <div class="col-md-9 col-lg-9">
                <input type="number" name="quantity" class="form-control" id="" min="1" value="1" required>
            </div>
        </div>
        <div class="my-2 d-flex justify-content-end">
            <a href="{{url()->previous()}}" class="btn btn-sm btn-danger">{{__('text.word_cancel')}}</a>|
            <input type="submit" class="btn btn-sm btn-primary" value="{{__('text.word_save')}}" name="" id="">
        </div>
    </form>
    @else
    <div class="my-3">
        <input class="form-control" id="search" placeholder="Type student name to search" required />
    </div>


    <div class="content-panel">
        <div class="table-responsive">
            <table class="table-bordered">
                <thead>
                    <tr class="text-capitalize">
                        <th>#</th>
                        <th>{{__('text.word_matricule')}}</th>
                        <th>{{__('text.word_name')}}</th>
                        <th>{{__('text.word_campus')}}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="content">

                </tbody>
            </table>

        </div>
    </div>
    @endif
</div>
@endsection
@section('script')
<script>
    $('#search').on('keyup', function() {
        val = $(this).val()
        // val = val.replace('/', '\\/', val);
        console.log(val);
        url = "{{route('get-search-all-students')}}";
        url = url.replace(':id', val);
        $.ajax({
            type: "get",
            url: url,
            data: {'key' : val},
            success: function(data) {
                console.log(data)
                let html = "";
                for (i = 0; i < data.length; i++) {
                    html += '<tr>' +
                        '    <td>' + (i + 1) + '</td>' +
                        '    <td>' + data[i].matric + '</td>' +
                        '    <td>' + data[i].name + '</td>' +
                        '    <td>' + data[i].campus + '</td>' +
                        '    <td class="d-flex justify-content-between align-items-center">' +
                        '        <a class="btn btn-xs btn-primary" href="{{Request::url()}}?student_id=' + data[i].id + '"> {{__("text.word_receive")}}</a>' +
                        '    </td>' +
                        '</tr>';
                }
                $('#content').html(html)
            },
            error: function(e) {}
        });
    });
</script>
@endsection