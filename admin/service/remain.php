<?php include_once("../library/service/remain.php");?>
<?php include_once("../include/header_menu.html");?>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox() 
})
</script>

<script type="text/javascript">
function csv_export () {
    var name=prompt("パスワードを入力して下さい。", "");
    var path = "<?php echo $home_url;?>" + "admin/img/" + name + ".gif";
	var img = new Image();
    img.src = path;
    img.onload = function() {
      document.search.action = "csv_remain.php";
	  document.search.submit();
	  document.search.csv_pw.value = name;
	  return true;
  	};
}
</script>

<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			施術管理（担当別）
			<span style="margin-left:20px;">
				<a href="./remain.php?hope_date2=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>&staff_id=<?php echo $_POST['staff_id'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="hope_date" type="text" id="day" value="<?php echo $_POST['hope_date'];?>" readonly  />~<input style="width:70px;height:21px;" name="hope_date2" type="text" id="day2" value="<?php echo $_POST['hope_date2'];?>" readonly  />
				<a href="./remain.php?hope_date2=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>&staff_id=<?php echo $_POST['staff_id'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<select name="shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']);?></select>
				<select name="staff_id"  style="height:25px;"><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$_POST['hope_date']) , $_POST['staff_id'] ? $_POST['staff_id'] : $data['staff_id'],getDatalist5("shop",$_POST['shop_id']));?></select>
				<input type="submit" value=" 表示 "  style="height:25px;" />
			</span>
			<?php if($authority_level<=1){?>
			<span style="float:right; margin-right:25px;">
				<input type='button' value='　CSV　' onclick='csv_export();' style="height:25px;" />
				<input type='hidden' name="csv_pw" value="" />
			</span>
			<?php  }?>
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
					<th class="table-header-repeat line-left minwidth-1"><a href="">店舗</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">来店日</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">顧客名</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">購入コース</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">請求金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">消化単価</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">所要時間(H)</a></th>
					<!--<th class="table-header-repeat line-left minwidth-1"><a href="">役務残(ﾊﾟｯｸ)</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">消化回数</a></th>-->
					<th class="table-header-repeat line-left minwidth-1"><a href="">担当</a></th>
					<!--<th class="table-header-repeat line-left minwidth-1"><a href="">レジ担当</a></th>-->

				</tr>
<?php
if ( $dRtn3 ) {
	$i = 0;
	$cnt = array();
	while ( $data = $dRtn3->fetch_assoc() ) {

		$data['price'] = $data['fixed_price'] - $data['discount'] ;
		$times = $course_times[$data['course_id']] ? $course_times[$data['course_id']] : 1;
		// tax
		if($data['hope_date']<"2014-04-01"){
			$tax = 0.05;
			$tax2 = 1.05;
		}else{
			$tax_data = Get_Table_Row("basic"," WHERE id = 1");
			$tax =$tax_data['value'];
			$tax2 = 1+$tax_data['value'];
		}
		// 月額単価
		if($course_type[$data['course_id']] && $data['r_times']>$course_times[$data['course_id']]){
			//ホットペッパー月額ケース
			$price_once = $data['course_id']==70 ? $course_price['45']*$tax2/$course_times['45'] : $course_price[$data['course_id']]*$tax2/$course_times[$data['course_id']];
		}else{
			$price_once = round($data['price'] / $course_times[$data['course_id']] , 0);
		}

		$price_used =  $price_once * $data['r_times'] ; // 消化（された）金額
		
		/* if($course_type[$data['course_id']] && $data['r_times']%2 ){
			$length = 1;
		}else{
			$length = $course_length[$data['course_id']] * 0.5;
		} */
		$length = $data['length']*0.5;

		$price_remain = $course_type[$data['course_id']] ?  0 : ( $data['price'] - $price_used) ;  // 役務残,月額除外
		$latest_date = $data['latest_date'] == "0000-00-00" ? "" : $data['latest_date'] ;  // 最終消化日

		echo '<tr'. ( $i%2<>0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$data['hope_date'].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td><a href="../reservation/edit.php?reservation_id='.$data['reservation_id'].'">'.($data['name'] ? $data['name'] : $data['name_kana']).'</a></td>';
		echo 	'<td>'.$course_list[$data['course_id']].'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['price']).'</td>';
		echo 	'<td class="priceFormat">'.number_format($price_once).'</td>';
		echo 	'<td class="priceFormat">'.($length).'</td>';
		//echo 	'<td class="priceFormat">'.number_format($price_remain).'</td>';
		//echo 	'<td class="priceFormat">'.($course_type[$data['course_id']] ? 0 : $data['r_times']).'</td>'; // 月額の場合、消化回数０
		echo 	'<td>'.$staff_list[$data['tstaff_id']].'</td>';
		echo '</tr>';

		$cnt_all++; //総件数
		if($length>=2) $cnt_long++; // 120分以上件数
		elseif($length>=1) $cnt_short++; // 1時間以上2時間未満件数


		if($data['course_id']){
			if($course_type[$data['course_id']]) $cnt_monthly++; // 月額件数
			else $cnt_pack++; // パック件数
		}else $cnt_nocontract++; // 契約なし件数
		


		$total_price += $data['price'];
		$total_price_once += $price_once;
		$total_price_used += $price_used;
		$total_length += $length;
		$total_price_remain += $price_remain ;

	}
		echo '<tr'. ( $i%2<>0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td colspan="5">合計</td>';
		echo 	'<td></td>';
		echo 	'<td class="priceFormat">'.number_format($total_price_once).'</td>';
		echo 	'<td class="priceFormat">'.($total_length).'</td>';
		//echo 	'<td class="priceFormat">'.number_format($total_price_remain).'</td>';
		//echo 	'<td class="priceFormat">'.number_format($cnt_all).'</td>';
		echo 	'<td></td>';
		echo '</tr>';

		echo '<tr><td colspan="10" align="right">施術総件数：'.number_format($cnt_all).'件、&nbsp;1時間以上2時間未満：'.number_format($cnt_short).'件、&nbsp;120分以上：'.number_format($cnt_long).'件</td></tr>';
		echo '<tr><td colspan="10" align="right">月額：'.number_format($cnt_monthly).'件、&nbsp;パック：'.number_format($cnt_pack).'件、&nbsp;契約なし：'.number_format($cnt_nocontract).'件</td></tr>';

		
}
?>
				
				</table>
				<!--  end product-table................................... --> 
				<!--※ 消化金額、役務残(パック)、消化回数：各来店日までの消化済みデータ<br>　　消化回数合計：表示されているデータ個数の集計（各消化回数の合計ではありません。）<br>　　月額の場合、消化回数０、合計に計上しない-->

				</form>
			</div>
			<!--  end content-table  -->
			<!--  start paging..................................................... -->
     <!-- <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      <tr>

      <td>
      <?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
      </td>
      </tr>
      </table>-->
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