@extends('layouts.app')

@section('content')
<div class="page-content">
    @if (\Session::has('success'))
    <div class="alert alert-success">
        <ul>
            <li>{!! \Session::get('success') !!}</li>
        </ul>
    </div>
    @endif
    @if (\Session::has('error'))
    <div class="alert alert-danger">
        <ul>
            <li>{!! \Session::get('error') !!}</li>
        </ul>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <span>User List</span>
                    @if(\Auth::check())
                    <a href="{{ route('user.create') }}" title="Add New" class="btn 
                    btn-primary" style="float:right;">Add New</a>
                    @endif
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered table-striped datatable" style="width:100%" id="datatableId">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Profile Picture</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Age</th>
                                    <th>Status</th>
                                    @if(\Auth::check())
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                        </table>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

<script type="text/javascript">
    var userId = 0
    var col = [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'profile_picture', name: 'profile_picture', orderable: false, render: function(data, type, full, meta){
                    return "<img src=\"" + data + "\" width=\"100px\" height=\"58px\"alt='No Image'/>";
                }},
                {data: 'name', name: 'name', orderable: false},
                {data: 'email', name: 'email', orderable: false},
                {data: 'age', name: 'age', orderable: false},
                {data: 'status', name: 'status', orderable: false},
            ];
@if(\Auth::check()) 
    userId = '{{\Auth::id()}}';
    col = [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'profile_picture', name: 'profile_picture', orderable: false, render: function(data, type, full, meta){
                    return "<img src=\"" + data + "\" width=\"100px\" height=\"58px\"alt='No Image'/>";
                }},
                {data: 'name', name: 'name', orderable: false},
                {data: 'email', name: 'email', orderable: false},
                {data: 'age', name: 'age', orderable: false},
                {data: 'status', name: 'status', orderable: false},
                {data: 'actions', name: 'action', orderable: false, searchable: false}
            ];
@endif

    $(document).ready(function () {
        $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            "scrollX": true,
            ajax: '{{ route('user.getUsers') }}',
            columns:col
        });
    });

    $('body').on('click','.delete', function () {
        return confirm('Are you sure?');
    });
</script>
@endsection    
