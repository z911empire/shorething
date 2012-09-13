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

            <table class="table table-bordered table-striped">
				<thead>
                    <tr><th>Assignment Actions</th><th>Assignment Name</th><th>Folder</th><th>Added</th></tr>
                </thead>
                <tbody>
                <?php
                    foreach ($assignments->result() as $row) {
                        echo "<tr><td class='span3'>";
						echo "<a class='btn btn-small' href='".base_url("upload/".$row->filepath)."'><i class='icon-search'></i></a> ";
						echo "<a class='btn btn-small' href='".base_url("teachers/assignments/modify/".$row->id)."'><i class='icon-pencil'></i></a> ";
						echo "<a class='btn btn-small' href='".base_url("teachers/assignments/delete/".$row->id)."'><i class='icon-trash'></i></a>";
						echo "<td>".$row->label."</td>";
						echo "<td>&nbsp;</tb>";
                        echo "<td class='span2'><small>".date('l, m/d/Y g:i A',strtotime($row->submitted))."</small></td></tr>";
                    }
                ?>	
                </tbody>
			</table>
		</div>
	</div>    
</div>