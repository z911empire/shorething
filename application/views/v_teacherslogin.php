<div class="container">
	<div class="row">
		<div class="span9 offset3">
			<h1>Shorething</h1>
		</div>
	</div>
	<div class="row">
		<div class="span6 offset3 well">
        	<form class="form-inline" accept-charset="utf-8" action="<?php echo base_url("teachers/verifylogin"); ?>" method="post">
            	<legend>Teacher's Login</legend>
	            <input type="text" name="fullname" class="input-medium" placeholder="Full Name">
    	        <input type="password" name="password" class="input-medium" placeholder="Password">
           		<button type="submit" class="btn btn-primary">Login</button>
            </form>
            <?php echo validation_errors(); ?>
		</div>
	</div>    
</div>
