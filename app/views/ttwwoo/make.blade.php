@extends('ttwwoo.master')

@section('content')
	@if(isset($errors) and count($errors) > 0)
		@foreach($errors as $error)
			<div class="alert alert-danger"><strong>Aw Snap!</strong> {{$error}}</div>
		@endforeach
	@endif
	@if(isset($success) and count($success) > 0)
		@foreach($success as $message)
			<div class="alert alert-success"><strong>Congrats!</strong> {{$message}}</div>
		@endforeach
	@endif
	<h1>Hello {{$user['name']}}</h1>
	<h3 class="text-muted">Start making a ttwwoo now</h3>
	<div class="row well">
		<div class="col-xs-6">
			<h4>How?</h4>
			<ol>
				<li>Select two photos</li>
				<li>Write a caption for each of those (Eg. Then-Now, Before-After, Here-There)</li>
				<li>Put a description of the ttwwoo</li>
				<li>Dance!!! Hurray... Your ttwwoo is ready to share.</li>
			</ol>
		</div>
		<div class="col-xs-6">
			<h4>Some Ideas!</h4>
			<ol>
				<li>Transformation of Miley Cyrus</li>
				<li>Your look before that awesome haircut</li>
				<li>Your look after you lost oodles of weight</li>
				<li>You with your Ex and Next</li>
			</ol>
		</div>
		<hr>
	</div>
	<form method="POST" action="index" accept-charset="UTF-8" enctype="multipart/form-data">
		<div class="row">
			<div class="col-xs-6">
				<div class="form-group">
					<label for="first">First Photo</label>
					<input type="file" class="form-control" id="first" name="first">
				</div>
				<div class="form-group">
					<label for="firstText">Caption for this photo</label>
					<input type="text" class="form-control" id="firstText" name="firstText" placeholder="Then">
				</div>
			</div>
			<div class="col-xs-6">
				<div class="form-group">
					<label for="second">Second Photo</label>
					<input type="file" class="form-control" id="second" name="second">
				</div>
				<div class="form-group">
					<label for="secondText">Caption for this photo</label>
					<input type="text" class="form-control" id="secondText" name="secondText" placeholder="Now">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="form-group">
					<label for="message">Description of this ttwwoo! (in less than 200 characters)</label>
					<input type="text" class="form-control" id="message" name="message" placeholder="See, how Miley transformed! :O">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-lg btn-success btn-block">Make a ttwwoo!</button>
				</div>
			</div>
		</div>
	</form>
@stop