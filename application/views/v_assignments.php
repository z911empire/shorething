<div class="navbar navbar-static-top">
    <div class="navbar-inner">
        <a class="brand" href="#"><?php echo "$firstname $lastname"; ?></a>
        <ul class="nav pull-right">
            <li><a href="teachers/logout"><i class="icon-off"></i> Logout</a></li>
        </ul>
    </div>
</div>
<div class="container">    
	<div class="row">
		<div class="span12">
			<h1><?php echo $site_name; ?><br/><small>Assignments</small></h1>
		</div>
	</div>
	<div class="row">
		<div class="span6">
	        <h3><?php echo ucfirst($action); ?> Assignment</h3>
            <hr/>
            <form method="post" action="<?php echo base_url("teachers/engine"); ?>" enctype="multipart/form-data">
                <label>Class</label>
                <?php if ($action=="delete") { ?>
					<span class="input-large uneditable-input"><?php echo $classes['label']; ?></span>                	
                <?php } else { ?>
                    <select name="class_id">
                        <?php 
                            echo "<option value='".$classes['id']."'>".$classes['label']."</option>";	
                        ?>
                    </select>	
                <?php } ?>
                
                <label>Assignment</label>
                <?php if ($action=="delete") { ?>
					<span class="input-large uneditable-input"><?php echo $assignment->label; ?></span>
                    <?php echo "<input type='hidden' name='assignment_label' value='".$assignment->label."'/>"; ?>
                <?php } else { ?>
                	<input type="text" name="assignment_label" value="<?php echo $assignment->label; ?>">
                <?php } ?>

				<label>File</label>
				<span class="input-large uneditable-input"><?php echo "upload/".$assignment->filepath; ?></span>          
                
                <div class="form-actions">
                    <button type="submit" class="btn <?php echo ($action=="delete") ? "btn-danger" : "btn-primary"; ?>"><?php echo ucfirst($action); ?> Assignment</button>
                    <a class='btn' href='<?php echo base_url("teachers"); ?>'>Cancel</a>
                </div>		
                <?php echo "<input type='hidden' name='action' value='".$action."'/>"; ?>
				<?php echo "<input type='hidden' name='assignment_id' value='".$assignment->id."'/>"; ?>
                <?php echo "<input type='hidden' name='filepath' value='".$assignment->filepath."'/>"; ?>	
			</form>
		</div>
	</div>    
</div>
