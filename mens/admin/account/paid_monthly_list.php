<?php include_once("../library/account/paid_monthly_list.php");?>
<?php include_once("../include/header_menu.html");?>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.ympicker.js"></script>
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.all.css" />
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.datepicker.css" />

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox() 
})
</script>

<style type="text/css">
#hoge {
    width:100%;
    position:absolute;
    top:160px;
    left:0;
}
#hogeInner {
    text-align: right;
    margin:0 0;
    padding: 0 23px;
}
</style>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			(月額支払済)役務未消化一覧
			<span style="margin-left:20px;"><span style="font-size:15px;">
				<input style="width:50px;height:21px;" name="pay_date" type="text"  class="ympicker" value="<?php echo $_POST['pay_date'];?>" readonly  />
				
				店舗：<select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select>
				
				<input type="submit" value=" 表示 "  style="height:20px;" />
			</span>
		</h1>
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
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href="">支払日</a>	</th>
					
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">顧客氏名</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">コース</a></th>							
					<th class="table-header-repeat line-left minwidth-1"><a href="">支払金額</a></th>							
					<th class="table-header-repeat line-left minwidth-1"><a href="">支払方法</a></th>


				</tr>
<?php
if ( $dRtn3 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		//役務消化がある場合、除外
		if( Get_Table_Col("sales","id"," where del_flg=0 and r_times<>0 and customer_id=".$data['customer_id']." and pay_date>='".$pay_date1."' AND pay_date<='".$pay_date2."'") 
		 || Get_Table_Col("sales","id"," where type in(4,5,12) and customer_id=".$data['customer_id']." and pay_date>='".$pay_date1."' AND pay_date<='".$pay_date2."'") 
			) continue;
		
		if($data['option_card']) $pay_type = 2;
		elseif($data['option_transfer']) $pay_type = 3;
		elseif($data['option_price']) $pay_type = 1;
		else  $pay_type = 0;

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$data['pay_date'].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td>'.($data['name'] ? $data['name'] : $data['name_kana']).'</td>';
		echo 	'<td>'.$course_list[$data['course_id']].'</td>';
		echo 	'<td class="priceFormat">'.( number_format($data['option_price'] + $data['option_card'] + $data['option_transfer']) ).'</td>';
		echo 	'<td>'.$gPayType2[$pay_type].'</td>'; 
		echo '</tr>';
		$i++;
	}
}
?>
				
				</table>
				<!--  end product-table................................... --> 
				
				</form>
				<div id="hoge">
    <div id="hogeInner">
       
    </div>
</div>
			</div>


			<!--  end content-table  -->
			<!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      <tr>

      <td>
      <?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
      </td>
      </tr>
      </table>
      <!--  end paging................ -->
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