<?php

print_r( $_POST );

if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) {
	$user = new User();
	if ( $user->userExists( $_POST['username'], $_POST['password'] ) ) {
		// TODO !?!??!?
		// TODO Display an alert and hide form?
	} else {

	}
}



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