<?php echo $navbar; ?>

<div class="container">    
	<div class="row">
		<div class="span12">
			<h1><?php echo $site_name; ?><br/><small>Teacher's Page</small></h1>
		</div>
	</div>
	<div class="row">
        <div class="span12">
        	<h3><a class='btn' href='<?php echo base_url("teachers/assignments/add");?>'><i class='icon-plus'></i> Add Assignment</a></h3>
            <hr/>
			<h3>Assignments List</h3>

            <table class="table table-bordered table-striped table-condensed">
				<thead>
                    <tr><th>Assignment Actions</th><th>Assignment Name</th><th>Folder</th><th>Added</th></tr>
                </thead>
                <tbody>
                <?php
                    foreach ($assignments as $assignment) {
                        echo "<tr id='".$assignment['id']."'><td class='span3'>";
						echo "<a class='btn btn-small disabled sequencemover' style='cursor:move;'><i class='icon-move'></i></a> ";
						echo "<a class='btn btn-small' href='".base_url("upload/".$assignment['filepath'])."'><i class='icon-search'></i></a> ";
						echo "<a class='btn btn-small' href='".base_url("teachers/assignments/modify/".$assignment['id'])."'><i class='icon-pencil'></i></a> ";
						echo "<a class='btn btn-small' href='".base_url("teachers/assignments/delete/".$assignment['id'])."'><i class='icon-trash'></i></a>";
						echo "<td>".$assignment['label']."</td>";
						echo "<td class='span2'>";
						$i=0;
						foreach ($assignment['folders']->result() as $folder) {
							$i++;
							echo "<div class='btn-group'><button class='btn btn-small btn-info'>".$folder->label."</button>";
							echo "<button class='btn btn-small btn-info unmap' value='".$assignment['id']."'><i class='icon-remove icon-white'></i></button></div>";
						}
						if ($i==0) {
							echo "<div class='btn-group'>";
							echo "<a class='btn btn-small dropdown-toggle' data-toggle='dropdown' href='#'>Add to Folder &nbsp&nbsp<span class='caret'></span></a>";
							echo "<ul class='dropdown-menu'>";
							$j=0;
							foreach ($folders->result() as $folderoption) {
								$j++;
								echo "<li><a tabindex='-1' name='".$assignment['id']."'>".$folderoption->label."</a></li>";
							}
							if ($j==0) {
								echo "<li><small>&nbsp;&nbsp;No folders defined yet.</small></li>";	
							}
							echo "</ul></div>";	
						}
						echo "</td>";
                        echo "<td class='span2'><small>".date('l, m/d/Y g:i A',strtotime($assignment['submitted']))."</small></td></tr>";
                    }
                ?>	
                </tbody>
			</table>
		</div>
	</div>    
</div>