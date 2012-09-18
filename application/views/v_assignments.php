<?php echo $navbar; ?>

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
            <?php if (strlen(validation_errors())) { ?>
            <div class="alert alert-error">
            	<button type="button" class="close" data-dismiss="alert">&times;</button>
            	<?php echo validation_errors(); ?>
            </div>
            <?php } ?>
            
            <form method="post" action="<?php echo base_url("teachers/assignmentsEngine"); ?>" enctype="multipart/form-data">
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
                	<input type="text" name="assignment_label" value="<?php echo isset($assignment->label) ? $assignment->label : ""; ?>">
                <?php } ?>

				<label>File</label>
                <?php 	if ($action!="add") {
							echo "<span class='input-large uneditable-input'>upload/".$assignment->filepath."</span>";
						} else { ?>
                <div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="input-append">
                    <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="assignment_filepath" /></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                    </div>
                </div> 
				<?php 	} ?>
                
                <div class="form-actions">
                    <button type="submit" class="btn <?php echo ($action=="delete") ? "btn-danger" : "btn-primary"; ?>"><?php echo ucfirst($action); ?> Assignment</button>
                    <a class='btn' href='<?php echo base_url("teachers"); ?>'>Cancel</a>
                </div>		
                <?php 	if ($action!="add") {
							echo "<input type='hidden' name='action' value='".$action."'/>";
							echo "<input type='hidden' name='assignment_id' value='".$assignment->id."'/>";
							echo "<input type='hidden' name='filepath' value='".$assignment->filepath."'/>"; 
						}?>	
			</form>
		</div>
	</div>    
</div>
