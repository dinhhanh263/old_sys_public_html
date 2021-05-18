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
			消化管理（ALL）
			<span style="margin-left:20px;">
			<?php if(!$_POST['customer_id']){?>
				<a href="./remain.php?pay_date2=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>&staff_id=<?php echo $_POST['staff_id'];?>&ctype=<?php echo $_POST['ctype'];?>&type=<?php echo $_POST['type'];?>&course=<?php echo $_POST['course'];?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="pay_date" type="text" id="day" value="<?php echo $_POST['pay_date'];?>" readonly  />~<input style="width:70px;height:21px;" name="pay_date2" type="text" id="day2" value="<?php echo $_POST['pay_date2'];?>" readonly  />
				<a href="./remain.php?pay_date2=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>&staff_id=<?php echo $_POST['staff_id'];?>&ctype=<?php echo $_POST['ctype'];?>&type=<?php echo $_POST['type'];?>&course=<?php echo $_POST['course'];?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
			<?php } ?>
				<!--<select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select>-->
				<!-- <select name="shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']);?></select> -->
				<select name="ctype" style="height:25px;" ><option value="">会員タイプ</option><?php Reset_Select_Key( $gCustomerType , $_POST['ctype'] );?></select>
				<select id="shop_id" name="shop_id" style="height:25px;" ><option value="0">全店舗</option><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'], $gArea_Group, "area_group"); ?></select>
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
					<th class="table-header-repeat line-left minwidth-1"><a href="">区分</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">店舗</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">来店日</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員タイプ</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">顧客名</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">購入コース</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">請求金額</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">所要時間(H)</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">役務残(ﾊﾟｯｸ)</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">消化回数</a></th>


				</tr>
<?php
if ( $dRtn3 ) {
	$i = 0;
	$cnt = array();
	while ( $data = $dRtn3->fetch_assoc() ) {
		// 月額制除外
		// if( $course_type[$data['course_id']] ) continue;

		// 請求金額
		$data['price'] = $data['fixed_price'] - $data['discount'] ;
		/* if($course_times[$data['course_id']]) $price_once = round($data['price'] / $course_times[$data['course_id']] , 0); // 金額/回
		else $price_once = 0; */

		// コース回数
		$times = $course_times[$data['course_id']] ? $course_times[$data['course_id']] : 1;

		// 割引率の計算 2016/12/16 add by shimada
		if($data['introducer_type']==3){
			// スタッフ紹介
			$rate_intro = 0.2;
		} else if($data['introducer_type']==5){
			// 企業紹介
			$rate_intro = 0.1;
		}else{
			// 紹介なし
			$rate_intro = 0;
		}

		// 単価の種類を定義
		$price_once    = 0;                                                 // 消化単価(初期化)
		$per_price_dis = round($data['price']/$times);                      // 割引単価
		$per_price_adj = $data['price']-($times-1)*$per_price_dis;          // 調整単価
		$per_price     = round($data['fixed_price']*(1-$rate_intro)/$times);// 通常単価

		// コース別 加算値を設定 2016/12/16 add by shimada
		// 旧月額/パック ×1回毎、新月額 ×2回毎
		$course_plus = 1;
		if($course_new_type[$data['course_id']]){
			// 加算値
			$course_plus = 2;
		}

		// 消化単価を計算する 2016/12/16 add by shimada
		if($course_type[$data['course_id']]){
		// 月額処理
			// 割引期間内(割引最終回を含む)
			if( ($data['r_times']-1)*$course_plus < $times && $data['r_times']*$course_plus >= $times){
				// コース回数で偶数・奇数のときの計算
				if($times%2==1){// 奇数
					// 全身:2回分、半身:1回分の振り分け
					if($data['part']==0){
						// 調整単価+通常単価
						$price_once = $per_price_adj+$per_price;
					} else {
						// 調整単価
						$price_once = $per_price_adj;
					}
				} else { // 偶数
					// 全身:2回分、半身:1回分の振り分け
					if($data['part']==0){
						// 調整単価+割引単価
						$price_once = $per_price_adj+$per_price_dis;
					} else {
						// 割引単価(運用上想定なし)
						$price_once = $per_price_dis;
					}
				}
			}
			// 割引期間内+割引期間外(割引最終回は含まない)
			elseif($data['r_times']*$course_plus < $times){
				// 全身:2回分、半身:1回分の振り分け
				if($data['part']==0){
					// 割引単価*$course_plus
					$price_once = $per_price_dis *$course_plus;
				} else {
					// 割引単価
					$price_once = $per_price_dis;
				}
			}
			// 通常の消化
			 else {
			 	// 全身:2回分、半身:1回分の振り分け
			 	if($data['part']==0){
			 		$price_once = $per_price*$course_plus;
			 	} else {
			 		$price_once = $per_price;
			 		//ホットペッパー月額ケース(既存)
			 		if($data['course_id']==70){
			 		    $price_once = $course_price['45']*1.08/$course_times['45']; //消費税1.08に固定
			 		}
			 	}
			}
		} else {
		// パック処理
			// 端数処理動作確認後、下記のelse内のコメントアウトを外す 2016/12/26 shimada
			// 消化回数==コース回数が一致したとき調整単価を消化単価とする
			// if($data['r_times']==$times){
			// 調整単価
			//$price_once = $per_price_adj;
				// 端数処理動作確認後、使用可能です。 2016/12/16 add by shimada
				// 消化（された）金額
				// $price_used =  $per_price_dis * ($times-1)+ $per_price_adj;
				// 役務残
				$price_remain = 0;
			//} else {
				// 割引単価
				$price_once = $per_price_dis;
				// 端数処理動作確認後、使用可能です。 2016/12/16 add by shimada
				// 消化（された）金額
				// $price_used =  $price_once * $data['r_times'] ;
				// 役務残(請求金額-消化済単価)
				$price_remain = $data['price'] - $price_used ;
			//}
		}
		// 端数処理動作確認後、下記処理不要。 2016/12/16 add by shimada
		// 消化（された）金額
		$price_used =  $price_once * $data['r_times'] ;

		/* if($course_type[$data['course_id']] && $data['r_times']%2 ){
			$length = 1;
		}else{
			$length = $course_length[$data['course_id']] * 0.5;
		} */

		// 端数処理動作確認後、下記処理不要。 2016/12/16 add by shimada
		// 役務残(請求金額-消化済単価-端数),月額除外
		$price_remain = $course_type[$data['course_id']] ?  0 : ( $data['price'] - $price_used) ;


		// 最終消化日
		$latest_date = $data['latest_date'] == "0000-00-00" ? "" : $data['latest_date'] ;

		echo '<tr'. ( $i%2<>0 ? ' class="alternate-row"' : '' ) .'>';
		if($data['type']==2) {
			echo 	'<td><span onmouseover=\'this.innerText="'. $data['id']. '"\' onmouseout=\'this.innerText="'. $gResType3[$data['type']]. '"\'>'. $gResType3[$data['type']]. '</span></td>';
		} else {
			$_id = ($data['rsv_status'] ? $gRsvStatus[$data['rsv_status']] : $gResType3[$data['type']]);
			echo 	'<td><font color="red"><span onmouseover=\'this.innerText="'. $data['id']. '"\' onmouseout=\'this.innerText="'. $gResType3[$data['type']]. '"\'>'. $gResType3[$data['type']]. '</span></font></td>';
			//echo '<td><font color="red">'. ($data['rsv_status'] ? $gRsvStatus[$data['rsv_status']] : $gResType3[$data['type']]) .'</font></td>';
		}
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$data['pay_date'].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td>'.$gCustomerType[$data['ctype']].'</td>';// 会員タイプ 2016/12/08 add by shimada
		// echo 	'<td><a href="../customer/edit.php?id='.$data['customer_id'].'">'.($data['name'] ? $data['name'] : $data['name_kana']).'</a></td>';
		echo 	'<td title="'.$data['name_kana'].'">'.($data['name'] ? $data['name'] : ($data['name_kana'] ? $data['name_kana'] : '無名')).'</td>';
		echo 	'<td>'.$course_list[$data['course_id']].'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['price']).'</td>';
		//echo 	'<td class="priceFormat">'.number_format($price_used).'</td>';
		// echo 	'<td class="priceFormat">'.($length).'</td>';
		echo 	'<td class="priceFormat">'.($data['length']*0.5).'</td>';
		echo 	'<td class="priceFormat">'.number_format($price_remain).'</td>'; // 役務残
		//echo 	'<td class="priceFormat">'.($data['r_times']).'</td>'; // 月額の場合、消化回数０
		echo 	'<td class="priceFormat">'.($course_type[$data['course_id']] ? 0 : $data['r_times']).'</td>'; // 月額の場合、消化回数０

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

		// if( !$course_type[$data['course_id']] ) $i++; // 月額の場合、消化回数合計に計上しない
	}
		echo '<tr'. ( $i%2<>0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td colspan="7">合計</td>';
		echo 	'<td class="priceFormat">'.number_format($total_price_once).'</td>';
		echo 	'<td class="priceFormat">'.($total_length).'</td>';
		echo 	'<td class="priceFormat">'.number_format($total_price_remain).'</td>';
		echo 	'<td class="priceFormat">'.number_format($cnt_all).'</td>';
		echo '</tr>';

		echo '<tr>';
		echo 	'<td colspan="9"></td>';
		echo 	'<td colspan="3">月額消化回数：'.number_format($cnt_monthly).' , パック消化回数：'.number_format($cnt_pack).'</td>';
		echo '</tr>';

}
?>

				</table>
				<!--  end product-table................................... -->
				※ 消化金額、役務残(パック)、消化回数：各来店日までの消化済みデータ<br>　　消化回数合計：表示されているデータ個数の集計（各消化回数の合計ではありません。）<br>　　月額の場合、消化回数０、合計に計上しない

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
