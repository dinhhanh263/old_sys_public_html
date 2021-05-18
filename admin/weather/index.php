<?php include_once("../library/weather/index.php");?>
<?php include_once("../include/header_menu.html");?>
 <link rel="stylesheet" href="../css/colorbox.css" />
 <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
 <script src="../js/jquery.colorbox.js"></script>
 <script>
$(document).ready(function(){
	$(".iframe").colorbox({iframe:true, opacity: 0.8, width:"50%", height:"90%"});
});
</script>
<script language="JavaScript">
<!--
function whereTo(url,room,time){
	var isConfirmed=confirm(room + 'に' + time + 'から新規予約をしますか？')
	if(isConfirmed){
		window.open(url)
	}
}
//-->
</script> 

 <script src="../js/jquery.jPrintArea.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.link').click(function(){ $.jPrintArea('#mainform') });
});
</script> 

 <script src="../js/jquery.jPrintArea.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.link').click(function(){ $.jPrintArea('#content-table-inner') });
});
</script> 

<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">

	<!--  start page-heading -->
	<div id="page-heading">

		<h1>天気カレンダー</h1>

		</form>
	</div>
	<!-- end page-heading -->

	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<th rowspan="3" class="sized"><img src="../images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>
		<th rowspan="3" class="sized"><img src="../images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
		
			<!--  start table-content  -->
			<div id="table-content">
						

				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">

					<?php echo $html;?>
				<!--  end product-table................................... --> 
				</form>
			</div>
			<!--  end content-table  -->

		</div>
		<!--  end content-table-inner ............................................END  -->
		</td>
		<td id="tbl-border-right"></td>
	</tr>
	<tr>
		<th class="sized bottomleft"></th>
		<td id="tbl-border-bottom">&nbsp;</td>
		<th class="sized bottomright"></th>
	</tr>
	</table>
	<div class="clear">&nbsp;</div>

</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>