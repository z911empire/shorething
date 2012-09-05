<div class="masthead">
    <div class="container">
        <div class="row">
            <div class="span11 offset1">
                <h1>Shorething</h1>
                <h3>School Name 7th Grade Assignments</h3>
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
    	<div class="span4"><a>LINK 1</a></div>
		<div class="span4"><a>LINK 2</a></div>
        <div class="span4"><a>LINK 3</a></div>
    </div>
</div>