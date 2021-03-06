<div class="navbar navbar-static-top">
    <div class="navbar-inner">
        <a class="brand" href="#"><?php echo "$firstname $lastname"; ?></a>
        <ul class="nav pull-right">
            <li><a href="students/logout"><i class="icon-off"></i> Logout</a></li>
        </ul>
    </div>
</div>
<div class="container">
    <div class="row">
		<div class="span12">
			<h1><?php echo $site_name; ?><br/><small><?php echo "$firstname $lastname"; ?>'s Assignments</small></h1>
		</div>
	</div>
	<div class="row">
    	<?php 
		$numclasses	=	count($all_classes);
		$modnum		=	$numclasses % 3;
		$spansize 	=	($modnum==1) ? 6 : 4;
		$colperrow	=	($modnum==1) ? 2 : 3;
		$i=0;
		foreach ($all_classes as $class) { ?>
            <div class="span<?php echo $spansize; ?>">
                <table class="table table-striped table-bordered">
                    <thead>
                    	<tr><th colspan="2"><?php echo $class['course_label']; ?> &middot; 
							<span class="muted"><?php echo ($class['teacher_gender']=='F') ? "Ms. " : "Mr. "; echo $class['teacher_lastname']; ?></span> <?php if (strlen($class['teacher_email'])) { echo "(<a target='_blank' href='mailto:".$class['teacher_email']."'>email</a>)"; } ?></th></tr>
                    </thead>
                    <tbody>
                    	<?php foreach ($all_assignments[$class['class_id']] as $assignment) {
								echo "<tr><td><a class='btn btn-small' href='upload/".$assignment['assignment_filepath']."'>";
								echo "<i class='icon-file'></i></a></td>";
								echo "<td><a href='upload/".$assignment['assignment_filepath']."'>";
								echo $assignment['assignment_label']."</a></td></tr>";
                        	} ?>
                    	<tr><td colspan="2">
                        	<ul class="pager">
                                <li class="previous disabled"><a id="" href="#">&larr; Newer</a></li>
                                <?php 
									if ($class['assignment_count']==count($all_assignments[$class['class_id']])) {
										$idnext			= "";
										$disablenext 	=  "disabled";
									} else {
										$idnext 		= $class['class_id']."|1|".$class['assignment_count'];	
										$disablenext	= "";
									}
                                	echo "<li class='next $disablenext'><a id='$idnext' href='#'>Older &rarr;</a></li>";
								?>
                           	</ul>
                    	</td></tr>
                    </tbody>
                </table>
            </div>
        <?php 
			$i++;
			if ($i % $colperrow == 0 && $i!=$numclasses) {
				echo "</div><div class='row'>";
			}
		} ?>  
	</div>
</div>
