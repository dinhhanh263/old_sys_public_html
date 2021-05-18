<?php include_once("../library/account/remain.php");?>
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
    if( img.height > 0){ // 読み込みに失敗すれば 0 になる。
      document.search.action = "csv_remain.php";
	  document.search.submit();
	  document.search.csv_pw.value = name;
	  return true;
  	}else{
    	return false;
  	}
}
</script>


<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			消化管理（ALL）
			<span style="margin-left:20px;">
			<?php if(!$_POST['customer_id']){?>	
				<a href="./remain.php?pay_date2=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>&staff_id=<?php echo $_POST['staff_id'];?>&type=<?php echo $_POST['type'];?>&course=<?php echo $_POST['course'];?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="pay_date" type="text" id="day" value="<?php echo $_POST['pay_date'];?>" readonly  />~<input style="width:70px;height:21px;" name="pay_date2" type="text" id="day2" value="<?php echo $_POST['pay_date2'];?>" readonly  />
				<a href="./remain.php?pay_date2=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>&staff_id=<?php echo $_POST['staff_id'];?>&type=<?php echo $_POST['type'];?>&course=<?php echo $_POST['course'];?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
			<?php } ?>		
				<!--<select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select>-->
				<select name="shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']);?></select>
				<select name="type" style="height:25px;"><?php Reset_Select_Key( $gResType5 , $_POST['type'] );?></select>
				<select name="course" style="height:25px;"><?php Reset_Select_Key( $gCourseType2 , $_POST['course'] );?></select>
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
	<div id="content-table">
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
			<!--  start table-content  -->
			<div id="table-content">
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href="">区分</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">店舗</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">来店日</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">顧客名</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">契約番号</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">購入コース</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">請求金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">消化単価</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">所要時間(H)</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">役務残(ﾊﾟｯｸ)</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">消化回数</a></th>
				</tr>
<?php
if ( $dRtn3 ) {
	$i = 0;
	$cnt = array();
	while ( $data = $dRtn3->fetch_assoc() ) {
		//月額制除外
		//if( $course_type[$data['course_id']] ) continue;

		$data['price'] = $data['fixed_price'] - $data['discount'] ;
		/*if($course_times[$data['course_id']]) $price_once = round($data['price'] / $course_times[$data['course_id']] , 0); // 金額/回
		else $price_once = 0;*/
		

		$times = $course_times[$data['course_id']] ? $course_times[$data['course_id']] : 1;
		// $price_once = round($data['price'] / $course_times[$data['course_id']] , 0);
		$price_once = $data['unit_price']; // 消化単価
		$price_used =  $price_once * $data['r_times'] ; // 消化（された）金額
		$length = $course_length[$data['course_id']] * 0.5;
		$price_remain = $course_type[$data['course_id']] ?  0 : ( $data['price'] - $price_used) ;  // 役務残,月額除外
		$latest_date = $data['latest_date'] == "0000-00-00" ? "" : $data['latest_date'] ;  // 最終消化日

		echo '<tr'. ( $i%2<>0 ? ' class="alternate-row"' : '' ) .'>';
		if($data['type']==2) echo'<td>'.$gResType3[$data['type']].'</td>';
		else echo '<td><font color="red">'. ($data['rsv_status'] ? $gRsvStatus[$data['rsv_status']] : $gResType3[$data['type']]) .'</font></td>';
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$data['pay_date'].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		//echo 	'<td><a href="../customer/edit.php?id='.$data['customer_id'].'">'.($data['name'] ? $data['name'] : $data['name_kana']).'</a></td>';
		echo 	'<td title="'.$data['name_kana'].'"><a  rel="facebox" href="../customer/mini.php?id='.$data['customer_id'].'">'.($data['name'] ? $data['name'] : ($data['name_kana'] ? $data['name_kana'] : '無名')).'</a></td>';
		echo 	'<td>'.$data['pid'].'</td>';
		echo 	'<td>'.$course_list[$data['course_id']].'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['price']).'</td>';
		//echo 	'<td class="priceFormat">'.number_format($price_used).'</td>';
		echo 	'<td class="priceFormat">'.number_format($price_once).'</td>';
		echo 	'<td class="priceFormat">'.($length).'</td>';
		echo 	'<td class="priceFormat">'.number_format($price_remain).'</td>';
						// echo 	'<td class="priceFormat">'.($course_type[$data['course_id']] ? 0 : $data['r_times']).'</td>'; //月額の場合、消化回数０
						echo 	'<td class="priceFormat">'.($data['r_times'] ? $data['r_times'] : "-").'</td>';

		echo '</tr>';

		if($data['r_times']){
			$cnt_all++; //月額+パック 
			if($course_type[$data['course_id']]) $cnt_monthly++; // 月額件数
			else $cnt_pack++; // パック件数
		}

		$total_price += $data['price'];
		$total_price_once += $price_once;
		$total_price_used += $price_used;
		$total_length += $length;
		$total_price_remain += $price_remain ;
		
		//if( !$course_type[$data['course_id']] ) $i++; //月額の場合、消化回数合計に計上しない
	}
				?>
				<tr>
					<td class="table-header-repeat2 line-left">区分	</td>
					<td class="table-header-repeat2 line-left">店舗	</td>
					<td class="table-header-repeat2 line-left">来店日</td>
					<td class="table-header-repeat2 line-left">会員番号</td>
					<td class="table-header-repeat2 line-left">顧客名	</td>
					<td class="table-header-repeat2 line-left">契約番号</td>
					<td class="table-header-repeat2 line-left">購入コース</td>
					<td class="table-header-repeat2 line-left">請求金額</td>
					<td class="table-header-repeat2 line-left">消化単価</td>
					<td class="table-header-repeat2 line-left">所要時間(H)</td>
					<td class="table-header-repeat2 line-left">役務残(ﾊﾟｯｸ)</td>
					<td class="table-header-repeat2 line-left">消化回数</td>
				</tr>
				<?php
						echo '<tr class="'. ( $i%2<>0 ? 'alternate-row ' : '' ) .'hr">';
		echo 	'<td colspan="7">合計</td>';
		echo 	'<td></td>';
		echo 	'<td class="priceFormat">'.number_format($total_price_once).'</td>';
		echo 	'<td class="priceFormat">'.($total_length).'</td>';
		echo 	'<td class="priceFormat">'.number_format($total_price_remain).'</td>';
		echo 	'<td class="priceFormat">'.number_format($cnt_all).'</td>';
		echo '</tr>';

		// echo '<tr>';
		// echo 	'<td colspan="15"></td>';
						// echo 	'<td colspan="2">月額消化回数：'.number_format($cnt_monthly).' , パック消化回数：'.number_format($cnt_pack).'</td>';
						// echo 	'<td colspan="2">パック消化回数：'.number_format($cnt_pack).'</td>';
		// echo '</tr>';
		
}
?>
				</table>
				<!--  end product-table................................... --> 
				<!-- ※ 消化金額、役務残(パック)、消化済回数：各来店日までの消化済みデータ<br>　　消化済回数合計：表示されているデータ個数の集計（各消化済回数の合計ではありません。）<br>　　月額の場合、消化回数０、合計に計上しない -->
				<div style="border-style: solid ; border-width: 1px;padding: 20px 20px 20px 20px; color: red; width: 450px; margin-left: auto;">　※ 消化単価、役務残(パック)、消化回数：各来店日までの消化済みデータ<br>　　パック消化回数：表示されているデータ個数の集計 </div>

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
    </div>
    <!--  end content-table-inner ............................................END  -->
  </div>
</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>