<?php include_once("../library/customer/search.php");?>
<?php include_once("../include/header_menu.html");?>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">
		<h1>
			顧客検索

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
					<input type="hidden" name="mode" value="search" />
					<input type="hidden" name="route" value="2" />

					<input type="hidden" name="hope_date" value="<?php echo $_POST['hope_date']?>" />
					<input type="hidden" name="hope_time" value="<?php echo $_POST['hope_time']?>" />
					<input type="hidden" name="type" value="<?php echo $_POST['type']?>" />
					<input type="hidden" name="room_id" value="<?php echo $_POST['room_id']?>" />

				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr><th colspan="2" class="table-header-repeat line-left minwidth-1"><a href="">検索条件設定</a></th></tr>
				<!-- <tr><td>店舗</td><td><select name="serach_shop_id"><?php Reset_Select_Key( $shop_list , $_POST['serach_shop_id'] );?></select></td></tr> -->
				<tr><td>店舗</td><td><select name="serach_shop_id"><option>-</option><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['serach_shop_id'] ? $_POST['serach_shop_id'] : "", $gArea_Group, "area_group" );?></select></td></tr>
				<tr><td>会員番号</td><td><input type="text" name="no" value="<?php echo $_POST['no']?>" style="width:500px;"></td></tr>
				<tr><td>お名前</td><td><input type="text" name="name" value="<?php echo $_POST['name']?>" style="width:500px;"></td></tr>
				<tr><td>お名前(カナ)</td><td><input type="text" name="name_kana" value="<?php echo $_POST['name_kana']?>" style="width:500px;"></td></tr>
				<tr><td>電話番号</td><td><input type="text" name="tel" value="<?php echo $_POST['tel']?>" style="width:500px;"></td></tr>
				<tr><td>メールアドレス</td><td><input type="text" name="mail" value="<?php echo $_POST['mail']?>" style="width:500px;"></td></tr>
				<?php if($authority_level>22){?>
				<tr><td>住所</td><td><input type="text" name="address" value="<?php echo $_POST['address']?>" style="width:500px;"></td></tr>
				<?php }?>
				</table>
				<!--  end product-table................................... -->
				<div>検索結果表示件数 <input style="width:30px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /> 件</div>
				<div style="padding:10px;text-align:center;"><input type="submit" value=" この条件で検索 " style="padding:10px;border:5px;color:white;line-height:14px;font-weight:bold;family-font:メイリオ;"/></div>
				</form>
			</div>
			<!--  end content-table  -->
<?php if($_POST['mode']=="search"){ ?>
			<!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      <tr>

      <td>
      <?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
      </td>
      </tr>
      </table>
      <br><br>
      <!--  end paging................ -->
      				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href="">店舗</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">経由</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">名前</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">生年月日</a></th>

					<th class="table-header-repeat line-left minwidth-1"><a href="">電話番号</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">メールアドレス</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">媒体</a></th>

					<th class="table-header-repeat line-left minwidth-1"><a href="">予約日時</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">登録日時</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">オプション</a></th>
				</tr>
<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		$counseling = Get_Table_Row("reservation"," WHERE  customer_id = '".addslashes($data['id'])."' order by reg_date limit 1");//type=1

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td style="width:40px;">'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td style="width:35px;">'.$gRoute[$data['route']].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td title="'.$data['name'].'">'.$data['name_kana'].'</td>';

		echo 	'<td>'.$data['birthday'].'</td>';

		echo 	'<td>'.( ($authority_level<=6 || $data['ctype']<2 && ($authority['id']==106 || $authority['id']==1449)) ? $data['tel'] : "").'</td>';
		echo 	'<td>'.( ($authority_level<=6 || $data['ctype']<2 && ($authority['id']==106 || $authority['id']==1449)) ? $data['mail'] : "").'</td>';
		echo 	'<td>'.$adcode_list[$data['adcode']].'</td>';

		echo 	'<td><a href="../main/?hope_date='.$counseling['hope_date'].'">'.$counseling['hope_date']." ".$gTime2[$counseling['hope_time']].'</a></td>';
		echo 	'<td>'.$data['reg_date'].'</td>';
		echo 	'<td style="width:170px;">';
		echo 		'<a href="edit.php?id='.$data['id'].'" title="顧客詳細" class="icon-1 info-tooltip"></a>';
		echo 		'<a href="index.php?action=delete&id='.$data['id'].'" onclick="return confirm(\'仮削除しますか？\')" title="仮削除" class="icon-2 info-tooltip"></a>';
		// echo 		'<a href="index.php?action=send&id='.$data['id'].'" onclick="return confirm(\'マイページ情報を送信しますか？\')" title="マイページ情報送信" class="icon-3 info-tooltip"></a>';
	if($contract['balance']){
		echo 		'<a href="../service/detail.php?mode=balance&customer_id='.$data['id'].'&shop_id='.($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']).'" onclick="return confirm(\'売掛回収処理をしますか？\')" title="売掛回収処理" class="icon-4 info-tooltip"></a>';
	}
		// echo 		'<a href="index.php?action=send&id='.$data['id'].'" onclick="return confirm(\'マイページ情報を送信しますか？\')" title="マイページ情報送信" class="icon-3 info-tooltip"></a>';
		// echo 		'<a href="../reservation/edit.php?mode=new_rsv&customer_id='.$data['id'].'&shop_id='.($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']).'&hope_date='.$_POST['hope_date'].'&hope_time='.$_POST['hope_time'].'&type='.$_POST['type'].'&room_id='.$_POST['room_id'].'" onclick="return confirm(\'次の予約をしますか？\')" title="次の予約" class="icon-5 info-tooltip"></a>';
		// echo 		'<a href="../account/reg_detail.php?id='.$rsv['id'].'&shop_id='.$_POST['shop_id'].'" onclick="return confirm(\'レジ精算に移動しますか？\')" title="レジ精算" class="icon-4 info-tooltip"></a>';
	if($authority_level<=1 && $contract['id']){
		echo 		'<a href="../service/cooling_off.php?mode=cooling_off&customer_id='.$data['id'].'&shop_id='.($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']).'" onclick="return confirm(\'クーリングオフ処理をしますか？\')" title="クーリングオフ処理" class="icon-2 info-tooltip"></a>';
		echo 		'<a href="../service/cancel_detail.php?mode=contract_cacel&customer_id='.$data['id'].'&shop_id='.($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']).'" onclick="return confirm(\'中途解約処理をしますか？\')" title="中途解約処理" class="icon-2 info-tooltip"></a>';

	}
		echo 	'</td>';
		echo '</tr>';
		$i++;
	}
}
?>

				</table>
				<!--  end product-table................................... -->
				</form>
<?php } ?>
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