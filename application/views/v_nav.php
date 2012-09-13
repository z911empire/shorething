<?php 
	function drawNavButton(&$thisLI, $activeLI) {
		$thisLI['active'] =	(isset($activeLI) && $activeLI==$thisLI['controller']) ? "active" : "";
		echo "<li class='".$thisLI['active']."'>";
		echo "<a href='".base_url($thisLI['controller'])."'>".$thisLI['label']."</a>"; 
		echo "</li>";
	}
?>


<div class="navbar navbar-static-top">
    <div class="navbar-inner">
        <a class="brand" href="<?php echo base_url("teachers"); ?>"><?php echo "$firstname $lastname"; ?></a>
        <ul class="nav">
        <?php 
			$thisLI['controller']	=	"teachers/assignments";
			$thisLI['label']		=	"Assignments";
			drawNavButton($thisLI, $activeLI);			
			
			$thisLI['controller']	=	"teachers/folders";
			$thisLI['label']		=	"Folders";
			drawNavButton($thisLI, $activeLI);
        ?>
        </ul>
        <ul class="nav pull-right">
            <li><a href="teachers/logout"><i class="icon-off"></i> Logout</a></li>
        </ul>
    </div>
</div>