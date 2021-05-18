<?php include_once("../library/customer/q_detail.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox();
  $(".date_box").datepicker(
    {duration: "fast",dateFormat: 'yy-mm-dd'}
  );
})
</script>
<style type="text/css" media="screen">
	.block_select{
		display:block;
		padding:5px;
	}
</style>
<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading">
			<h1>
				お客様アンケート
			</h1>
		</div>
		<div id="content-table">
					<!--  start content-table-inner -->
					<div id="content-table-inner">
						<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
	    				<tr>
	    					<th class="table-header-repeat"><a href="">番号</a></th>
	    					<th class="table-header-repeat line-left minwidth-1"><a href="">アンケート名</a></th>
	    					<th class="table-header-repeat line-left minwidth-1"><a href="">登録日時</a></th>
	    					<th class="table-header-repeat line-left minwidth-1"><a href="">開始日</a></th>
	    					<th class="table-header-repeat line-left minwidth-1"><a href="">終了日</a></th>
	    					<th class="table-header-repeat line-left minwidth-1"><a href="">詳細</a></th>
	    					<th class="table-header-repeat line-left minwidth-1"><a href="">公開/非公開</a></th>
	    					<th class="table-header-<?php echo ($authority_level<=1) ? "repeat" : "repeat" ?> line-left" style="width:430px;"><a href="">出力</a></th>
	    				</tr>
	              <?php
									// $shops_html = Reset_Select_Key_ShopGroup( $shop_lists , "", $gArea_Group, "area_group" );
	                if ( $dRtn2->num_rows >= 1 ) {
	                	$i = 1;
	                	while ( $data = $dRtn2->fetch_assoc() ) {
	                		echo 	'<td>'.$data['id'].'</td>';
	                		echo 	'<td>'.$data['name'].'</td>';
	                		echo 	'<td>'.$data['reg_date'].'</td>';
	                		echo 	'<td>'.$data['start_date'].'</td>';
	                		echo 	'<td>'.$data['end_date'].'</td>';
	                		echo 	'<td><a rel="facebox" href="./q_mini.php?id='.$data['id'].'">詳細</a></td>';
	                		echo 	'<td>'.($data['status']==1 ? '非公開' : '公開').($data['end_date'] != "0000-00-00" && $data['end_date'] <=$yesterday ? '（終了）' : '').'</td>';
	                		echo 	'<td style="width:140px;">';
	                		echo 	'<form id="'.$data['id'].'" action="./q_export.php">';
	                		echo 	'<input name="id" type="hidden" value="'.$data['id'].'">';
	                		echo 	'<select name="search_shop_id" class="block_select" ><option value="0">-</option>';
					                	Reset_Select_Key_ShopGroup( $shop_lists , "", $gArea_Group, "area_group" );
	                		echo 	'</select>';
	                		echo 	'<input class="date_box registration-form w7" name="reg_date" type="text" value="'.(($data['end_date'] != "0000-00-00") ? $data['start_date'] : substr_replace($yesterday, '01', -2, 2) ).'" />~<input class="date_box registration-form w7" name="reg_date2" type="text" value="'.(($data['end_date'] != "0000-00-00") ? $data['end_date'] : $yesterday ).'" />';
	                		echo 		'<input class="reset" type="submit" name="submit" value="CSV" />';
	                		echo 	'</form>';
	                		echo 	'</td>';
	                		echo '</tr>';
	                		$i++;
	                	}
	                }
	              ?>
	  				</table>
					</div>
					<!--  end content-table-inner  -->
		</div>
	</div>
	<!--  end content -->
	<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer -->
<script type="text/javascript">
	new AutoKana('Name', 'NameKana', {katakana: true, toggle: false});
</script>
<?php include_once("../include/footer.html");?>