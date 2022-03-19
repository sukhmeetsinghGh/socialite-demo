@extends('layouts.app')

@section('title', 'User')

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
                    <span>Create User</span>
                </div>
                <div class="card-body">
                    <form action="{{route('user.save')}}"  method="POST" enctype="multipart/form-data" id="addUser">
                        @csrf
                        <div class="form-group">
                          <label for="name">Name<span class="required-astrick"></span></label>
                          <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" required>
                        </div>
                        <div class="form-group">
                          <label for="email">Email<span class="required-astrick"></span></label>
                          <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" data-parsley-type="email" required>
                        </div>
                        <div class="form-group">
                          <label for="dob">Date of Birth<span class="required-astrick"></span></label>
                          <input type="date" class="form-control" id="dob"name="dob" required>
                        </div>
                        <div class="form-group">
                          <label for="address">Address<span class="required-astrick"></span></label>
                          <textarea class="form-control" id="address" name="address" required></textarea>
                        </div>

                        <div class="form-group">
                          <label for="country">Country<span class="required-astrick"></span></label>
                          <select class="form-control" id="country" name="country_id" required>
                           <option value="" selected disabled>Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{$country->id}}">
                                 {{$country->name}}
                                </option>
                            @endforeach
                          </select>
                        </div>

                        <div class="form-group">
                          <label for="states">States<span class="required-astrick"></span></label>
                          <select class="form-control" id="states" name="states" required>
                           <option value="" selected disabled>Select State</option>
                          </select>
                        </div>

                        <div class="form-group">
                          <label for="city">City<span class="required-astrick"></span></label>
                          <select class="form-control" id="city" name="city_id" required>
                           <option value="" selected disabled>Select City</option> 
                            
                          </select>
                        </div>

                        <div class="form-group">
                          <label for="eduction">Education<span class="required-astrick"></span></label>
                          <select class="form-control" id="education" name="education" required>
                            <option value="1">Science</option>
                            <option value="2">Commerce</option>
                            <option value="3">Arts</option>
                          </select>
                        </div>

                        <div class="form-group">
                            <label for="file">Profile Picture</label>
                            <input type="file" name="profile_picture" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="status">Status<span class="required-astrick"></span></label>
                            <input type="radio" class="form-check-input" id="active" name="is_active" value="1" checked>
                            <label class="radio-inline" for="status">active </label>
                            <input type="radio" class="form-check-input" id="inactive" name="is_active" value="0">
                            <label class="radio-inline" for="inactive" id="radio-label">in-active</label>
                        </div>

                        <input type="submit" name="btn_submit" id="btn_submit" value="Submit" class="mt-1 btn btn-primary">
                        <a href="{{route('user.index')}}" class="btn btn-warning mt-1">Cancel</a>
                    </form>    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">

    $(document).ready(function () {
       
       $('#addUser').parsley();

       //get states
       $('#country').change(function() {
          var countryID = $(this).val();  
          if(countryID){
            $.ajax({
              type:"GET",
              url:"{{url('get-states')}}/"+countryID,
              success:function(res){        
                  if(res)
                  {
                    $("#states").empty();
                    $("#city").empty();
                    $("#states").append('<option>Select State</option>');
                    $("#city").append('<option>Select City</option>');
                    $.each(res,function(key,value){
                      $("#states").append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                  }else{
                    $("#states").empty();
                  }
              }
            });
          }else{
            $("#states").empty();
            $("#city_id").empty();
          }   
        });
    });

        //get cities
    $('#states').on('change',function(){
        var stateID = $(this).val();
        console.log(stateID); 
        if(stateID)
        {
            $.ajax({
              type:"GET",
              url:"{{url('get-cities')}}/"+stateID,
              success:function(res){        
              if(res){
                $("#city").empty();
                $("#city").append('<option>Select City</option>');
                $.each(res,function(key,value){
                  $("#city").append('<option value="'+value.id+'">'+value.name+'</option>');
                });
              
              }else{
                $("#city").empty();
              }
              }
            });
        }else{
            $("#city").empty();
        }
      });


    //disable future dates
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();

    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;    
    $('#dob').attr('max', maxDate);
</script>
@endsection    
