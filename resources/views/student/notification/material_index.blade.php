@extends('student.layout')

@section('section')

<div class="container m-t-5">

    <!-- notes -->
    <div class="col-sm-12 col-md-11 mt-5">

        <div class="">
            <div class="container-fluid">
                <table  class="table" id="hidden-table-info">
                    <thead class="text-capitalize">
                        <th>#</th>
                        <th>{{__('text.word_type')}}</th>
                        <th>{{__('text.word_name')}}</th>
                        <th>{{__('text.upload_date')}}</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach($notifications as $k=>$note)
                        <tr>
                            <td>{{ $k+1 }}</td>
                            <td>{{ $note->title}} </td>
                            <td>{{ $note->note_name}} </td>
                            <td>{{date('jS F Y', strtotime($note->created_at))}}</td>

                            <td style="">
                                <a href="{{asset('storage/material/'. $note->file)}}" class="btn btn-xs btn-success m-3" target="_new"> <i class="fa fa-download"> Download</i>
                                </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>

<script>

</script>
@endsection