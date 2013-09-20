@extends('ttwwoo.master')

@section('content')
	@if(isset($errors) and count($errors) > 0)
		@foreach($errors as $error)
			<div class="alert alert-danger"><strong>Aw Snap!</strong> {{$error}}</div>
		@endforeach
	@endif

	<a href="{{$loginUrl}}" class="btn btn-primary btn-block btn-lg">Login using Facebook</a>
@stop