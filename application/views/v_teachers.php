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
			<h1>Shorething<br/><small>Teacher's Page</small></h1>
		</div>
	</div>
	<div class="row">
		<div class="span6">
			<h3>Add Assignments</h3>
            <hr/>
            <form method="post" action="teachers/engine" enctype="multipart/form-data">
                <label>Class</label>
                <select name="class_id">
                	<?php 
						echo "<option value='".$classes['id']."'>".$classes['label']."</option>";	
					?>
                </select>
                
                <label>Assignment</label>
                <input type="text" name="assignment_label" placeholder="Assignment Name">

				<label>File</label>
				<div class="fileupload fileupload-new" data-provides="fileupload">
	                <div class="input-append">
                    	<div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="assignment_filepath" /></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                	</div>
				</div>                
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Assignment</button>
                    <!--<button type="button" class="btn">Reset</button>-->
                </div>				
			</form>
		</div>
        <div class="span6">
			<h3>Assignments List</h3>
            <hr/>
            <table class="table table-bordered table-striped">
				<thead>
                    <tr><th colspan="2">Assignment Name</th><th>Added</th></tr>
                </thead>
                <tbody>
                <?php
                    foreach ($assignments->result() as $row) {
                        echo "<tr><td><a class='btn btn-small' href='upload/".$row->filepath."'><i class='icon-search'></i></a></td>";
						echo "<td>".$row->label."</td>";
                        echo "<td>".date('l, m/d/Y g:i A',strtotime($row->submitted))."</td></tr>";
                    }
                ?>	
                </tbody>
			</table>
		</div>
	</div>    
</div>
