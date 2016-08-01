@extends('layouts/app')

@section('content')
<div class ="container">
    <div class="row">
        <div class="col-md-10">
        <div class = "panel panel-success">
	@if(Session::has('message'))
           <div class = "panel-heading">
                <h3 class = "panel-title">{{ Session::get('message')}}</h3>
           </div>
	@endif
   <div class = "panel-body">
	@if (!empty($data))

	    <div class="media">
              @if (isset($profileData->profile_photo))
	      <a class="pull-left" href="{{ $profileData->profile_photo}}">
	        <img class="profile-photo media-object" src="{{ $profileData->profile_photo}}" alt="Profile image">
	      </a>
              @endif
	      <div class="media-body">
	        <h4 class="media-heading">{{{ $data['name'] }}} </h4>
	        Your email is {{ $data['email']}}
              
                @if (isset($profileData->profile_photo))
                  <div>
                    Your last used Social Media Login is {{ $profileData->provider}}
                     </div>
                @endif
               
	      </div>
	    </div>
	    <hr>
	    <a href="{{url('logout')}}">Logout</a>
	@else
        <strong>Please </strong><a href="{{url('/login')}}">Login</a>
	@endif
           </div>
        </div>
        </div>
    </div>
</div>
@stop