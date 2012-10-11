<?php echo $navbar; ?>

<div class="container">    
	<div class="row">
		<div class="span12">
			<h1><?php echo $site_name; ?><br/><small>Teacher Settings</small></h1>
		</div>
	</div>
	<div class="row">
	<?php if (strlen(validation_errors())) { ?>
   		<br />
	    <div class="alert alert-error span10">
    		<button type="button" class="close" data-dismiss="alert">&times;</button>
    		<?php echo validation_errors(); ?>
    	</div>
    </div>
    <div class="row">
    <?php } else if ($postsubmit=='success') { ?>
	    <br />
        <div class="alert alert-success span10">
    		<button type="button" class="close" data-dismiss="alert">&times;</button>
			Settings changed successfully.
    	</div>
    </div>
    <div class="row">
    <?php } ?>      
        <div class="span6">
        	<h3>Change Password</h3><hr />
      		<form method="post" action="<?php echo base_url("teachers/settingsEngine"); ?>">
                <label>Old Password</label>
               	<input type="password" name="oldpassword" value="">
                <label>New Password</label>
               	<input type="password" name="newpassword" value="">
                <label>Confirm</label>
               	<input type="password" name="confirm" value="">                              
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                    <a class='btn' href='<?php echo base_url("teachers"); ?>'>Cancel</a>
                </div>
                <input type="hidden" name="action" value="change_password" />		
			</form>
        </div>      
        <div class="span6">
        	<h3>Change Student Contact Email</h3><hr />
      		<form method="post" action="<?php echo base_url("teachers/settingsEngine"); ?>">
                <label>Set E-mail (Visible to Students)</label>
                	<input type="text" name="teacher_email" value="<?php echo isset($email) ? $email : ""; ?>">
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update E-mail</button>
                    <a class='btn' href='<?php echo base_url("teachers"); ?>'>Cancel</a>
                </div>		
                <input type="hidden" name="action" value="update_email" />
			</form>
        </div>      
	</div>    
</div>
