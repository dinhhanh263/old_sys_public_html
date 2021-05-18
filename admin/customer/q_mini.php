<?php include_once("../library/customer/q_mini.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SYSTEM</title>
<link rel="stylesheet" href="../css/screen.css" type="text/css" media="screen" title="default" />
<script type="text/javascript" src="../js/main.js"></script>
<style type="text/css" media="screen">
	.ques_sel>*{
    -webkit-appearance: none;
	}
	#table-content{
		max-height:500px;
		overflow-y:scroll;
	}
	.dt_ques {
	  background: #7AD678;
	  color: #fff;
	  font-size: 18px;
	  padding: 0.4em 1em;
	}
	.ques_sel{
	  color: #232330;
	  margin: 0 2% 15px 2%;
	}
	.one_item {
	  margin: 5px 0;
	  width: 100%;
	}
	.one_item label{
	  color: #232330;
	}
	.textarea{
		display: block;
	}
</style>
</head>
<body>


<!-- start content-outer ........................................................................................................................START -->
<div >
<!-- start content -->
	<div id="content-table">
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">

			<!--  start table-content  -->
			<div id="table-content">
				<!--  start product-table ..................................................................................... -->
				<fieldset>
					<?php echo $html; ?>
				</fieldset>
				<!--  end product-table................................... -->

			</div>
			<!--  end content-table  -->

		</div>
		<!--  end content-table-inner ............................................END  -->
	</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->
