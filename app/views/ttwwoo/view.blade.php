@extends('ttwwoo.master')

@section('content')
	@if(isset($errors) and count($errors) > 0)
		@foreach($errors as $error)
			<div class="alert alert-danger"><strong>Aw Snap!</strong> {{$error}}</div>
		@endforeach
	@endif

	<img src="{{asset($ttwwoo['path'])}}" alt="{{$ttwwoo['message']}}" class="ttwwoo-pic"/>
	<br/><br/>
	<a href="/share" class="btn btn-primary btn-lg btn-block">Share with your friends</a>
@stop