<div class="masthead">
    <div class="container">
        <div class="row">
            <div class="span11 offset1">
                <h1><?php echo $site_name ?></h1>
                <h3><?php echo $site_tagline ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="mainlogin">
	<div class="container">
		<div class="row">
        	<div class="span6 pull-right" style="text-align:right;">
                <h3>Student Login</h3>
                <form class="form-inline" accept-charset="utf-8" action="<?php echo base_url("students/verifylogin"); ?>" method="post">
                
                    <input type="text" name="fullname" class="input-large" placeholder="Student's Full Name">
                    <!--<input type="password" name="password" class="input-medium" placeholder="Password">-->
                    <button type="submit" class="btn ">Login</button>
                </form>
                <?php echo validation_errors(); ?>
             </div>
		</div>    
	</div>	
</div>

<div class="container">
	<div class="row entrancelinks">
    	<?php foreach ($links as $link) { 
    	echo "<div class='span4 pull-right'><a target='_blank' href='".$link['linkurl']."'>".$link['linklabel']."</a></div>";
		} ?>
    </div>
</div>