<?php
if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) {
	$user = new User();
	$user_id = $user->userExists( $_POST['username'], $_POST['password'] );
	if (isset($user_id) && $user_id > 0) {
		$_SESSION['LoggedIn'] = true;
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['id'] = $user_id;
		$arrCookie = array (
				'expires' => strtotime( '+4 hours' ),
				'path' => '/',
				'secure' => true,
				'samesite' => 'None',
		);
		// TODO hash and store username in cookie?
		setcookie("pws", $_POST['username'], $arrCookie);
	} else {
		$signin_failed = true;
	}
}

if ( isset( $_SESSION['LoggedIn'] ) && $_SESSION['LoggedIn'] ) {
	?>
	<div class="alert alert-success" role="alert">
		You are signed in.
	</div>
	<?php
} else {
	// User is not logged in
	?>
	<div class="text-center">
		<form class="form-signin" method="post">
			<h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
			<label for="inputUsername" class="sr-only">Username</label>
			<input type="text" id="inputUsername" name="username" class="form-control" placeholder="Username" required>
			<label for="inputPassword" class="sr-only">Password</label>
			<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
			<button class="btn btn-lg btn-primary-pws btn-block" type="submit">Sign in</button>
		</form>
	</div>
	<?php
	if ( isset( $signin_failed ) && $signin_failed) {
	?>
	<div class="alert alert-danger" role="alert">
		A simple danger alert—check it out!
	</div>
	<?php
	}
}
