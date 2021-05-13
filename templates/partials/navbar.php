<?php
if ( isset( $_POST['sign-out'] ) ) {
	session_destroy();
	$_SESSION = array();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<a class="navbar-brand" href="#">PWS</a>
	<button
		class="navbar-toggler"
		type="button" data-toggle="collapse"
		data-target="#navbarSupportedContent"
		aria-controls="navbarSupportedContent"
		aria-expanded="false"
		aria-label="Toggle navigation">
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
			<?php
			if ( ! isset( $_SESSION['LoggedIn'] ) ) {
				?>
				<li class="nav-item">
					<a href="?p=sign-in" class="nav-link">Sign in</a>
				</li>
				<li class="nav-item">
					<a href="?p=sign-up" class="nav-link">Sign up</a>
				</li>
				<?php
			} else {
				?>
				<form class="form-inline" method="post">
					<button class="btn btn-primary-pws" type="submit" name="sign-out" >Sign Out</button>
				</form>
				<?php
			}
			?>
		</ul>
	</div>
</nav>