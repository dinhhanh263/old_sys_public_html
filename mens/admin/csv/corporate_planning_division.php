<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth.php" );
include_once("../include/header_menu.html");
?>
</form>
<script type="text/javascript">
function csv_export1 () {
      document.frm1.action = "contract1_csv.php";
	  document.frm1.submit();
}

</script>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">
		<h1>CSVエクスポートリンク集	</h1>
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
				  <table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
					<tr>
						<th class="table-header-repeat line-left minwidth-1"><a href="">利用部署</a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href="">利用者</a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href="">タイトル</a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href="">説明</a>	</th>
						<th class="table-header-repeat line-left minwidth-1"><a href="">開始日</a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href="">終了日</a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href="">CSVエクスポート</a></th>
					</tr>
					<tr>
					  <form action="" method="post" name="frm1">
						<td>営業部</td>
						<td>岸</td>
						<td>店別コース別月別契約者数</td>
						<td>月初出力</td>
						<td></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export1();' style="height:25px;" /></td>
					  </form>
					</tr>
				  </table>
				<!--  end product-table................................... -->
			</div>
			<!--  end content-table  -->
      <div class="clear"></div>
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