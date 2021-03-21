<!doctype html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta charset="utf-8">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
		<link href="assets/styles.css" rel="stylesheet">
		<title>PWS 2.1</title>
	</head>
	<body>
		<header>
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
				<a class="navbar-brand" href="#">PWS</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
								<li class="nav-item active">
									<a href="?p=home" class="nav-link">Start <span class="sr-only">(current)</span></a>
								</li>
								<li class="nav-item">
									<a href="?p=about" class="nav-link">About</a>
								</li>
								<li class="nav-item">
									<a href="?p=sign-in" class="nav-link">Sign in</a>
								</li>
								<li class="nav-item">
									<a href="?p=sign-up" class="nav-link">Sign up</a>
								</li>
					</ul>
				</div>
			</nav>
		<header>
		<main role="main" class="container">
			<div class="content">
				<?php
					if ( isset( $_GET['p'] ) ) {
						require_once 'partials/' . $_GET['p'] . '.php';
					} else {
						require_once 'partials/home.php';
					}
				?>
			</div>
		</main>
		<footer class="footer">
			<div class="container">
				PloppyLeft - Copy as much as posible - Madman (<?php echo '2006 - '.date('Y'); ?>)
			</div>
    </footer>
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
	</body>
</html>
