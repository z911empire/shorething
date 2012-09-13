<?php echo $navbar; ?>

<div class="container">    
	<div class="row">
		<div class="span12">
			<h1><?php echo $site_name; ?><br/><small>Folders</small></h1>
		</div>
	</div>
	<div class="row">
		<div class="span4">
	        <h3><?php echo ucfirst($action); ?> Folder</h3>
            <hr/>
            <form method="post" action="<?php echo base_url("teachers/foldersEngine"); ?>">
                <label>Folder</label>
                <?php if ($action=="delete") { ?>
					<span class="input-large uneditable-input"><?php echo $folder->label; ?></span>
                    <?php echo "<input type='hidden' name='folder_label' value='".$folder->label."'/>"; ?>
                <?php } else { ?>
                	<input type="text" name="folder_label" value="<?php echo isset($folder->label) ? $folder->label : ""; ?>">
                <?php } ?>

                <div class="form-actions">
                    <button type="submit" class="btn <?php echo ($action=="delete") ? "btn-danger" : "btn-primary"; ?>"><?php echo ucfirst($action); ?> Folder</button>
                    <a class='btn' href='<?php echo base_url("teachers/folders"); ?>'>Cancel</a>
                </div>		
                <?php 	if ($action!="add") {
							echo "<input type='hidden' name='action' value='".$action."'/>";
							echo "<input type='hidden' name='folder_id' value='".$folder->id."'/>";
						}	?>	
			</form>
		</div>
        <div class="span8">
        	<h3>Your Folders</h3><hr />
        	<table class="table table-bordered table-striped">
				<thead>
                    <tr><th>Folder Actions</th><th>Folder Name</th><th>Assignments</th></tr>
                </thead>
                <tbody>
                <?php
                    foreach ($folders->result() as $row) {
                        echo "<tr><td class='span2'>";
						echo "<a class='btn btn-small' href='".base_url("teachers/folders/modify/".$row->id)."'><i class='icon-pencil'></i></a> ";
						echo "<a class='btn btn-small' href='".base_url("teachers/folders/delete/".$row->id)."'><i class='icon-trash'></i></a>";
						echo "<td><span class='label label-info'>".$row->label."</span></td>";
                        echo "<td class='span1'><span class='badge badge-info'>".$row->assignments_count."</span></td></tr>";
                    }
                ?>	
                </tbody>
			</table>        
        </div>
	</div>    
</div>
