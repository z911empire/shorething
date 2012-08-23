<div class="container">
	<div class="navbar navbar-static-top">
    	<div class="navbar-inner">
        	<a class="brand" href="#"><?php echo "$firstname $lastname"; ?></a>
            <ul class="nav pull-right">
        		<li><a href="teachers/logout">Logout</a></li>
	        </ul>
        </div>
    </div>
	<div class="row">
		<div class="span12">
			<h1>Shorething<br/><small>Teacher's Page</small></h1>
		</div>
	</div>
	<div class="row">
		<div class="span6">
			<h3>Add Assignments</h3>
            <hr/>
            <form>
                <label>Class</label>
                <select>
                	<?php 
						echo "<option value='".$classes['id']."'>".$classes['label']."</option>";	
					?>
                </select>
                
                <label>Assignment</label>
                <input type="text" name="label" placeholder="Assignment Name">


                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Assignment</button>
                    <!--<button type="button" class="btn">Reset</button>-->
                </div>				
			</form>
		</div>
        <div class="span6">
			<h3>Assignments List</h3>
            <hr/>
            
		</div>
	</div>    
</div>
