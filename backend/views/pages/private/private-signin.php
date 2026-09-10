<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;} ?>

<div class="section mt2">
	<div class="row">
		<h1 class="text-center">Private Login</h1>
		<div class="col-lg-12">
			<div class="col-md-4 col-md-offset-4">
				<form method="post">
				  <div class="form-group">
				    <label for="exampleInputEmail1">Username</label>
				    <input name="username" type="text" class="form-control" id="user" placeholder="Enter username">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputPassword1">Password</label>
				    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
				  </div>
				  <button type="submit" class="btn btn-default">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>