
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>ttwwoo - Mohit Mamoria</title>

	<!-- Bootstrap core CSS -->
	<link href="styles/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="styles/style.css" rel="stylesheet">
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-44224165-1', 'mohitmamoria.com');
	  ga('send', 'pageview');

	</script>
  </head>

  <body>

  	<div class="navbar navbar-inverse navbar-fixed-top">
  		<div class="container">
  			<div class="navbar-header">
  				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
  					<span class="icon-bar"></span>
  					<span class="icon-bar"></span>
  					<span class="icon-bar"></span>
  				</button>
  				<a class="navbar-brand" href="/">ttwwoo</a>
  			</div>
  			<div class="collapse navbar-collapse">
  				<ul class="nav navbar-nav navbar-right">
  					<li><a href="/about">About</a></li>
  					<li><a href="/login">Login</a></li>
  				</ul>
  			</div><!--/.nav-collapse -->
  		</div>
  	</div>

  	<div class="container">

  		<div class="content">
  			@yield('content')
  		</div>

  	</div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="scripts/jquery.min.js"></script>
    <script src="scripts/bootstrap.min.js"></script>
</body>
</html>
