<?php include_once("../library/customer/index.php");?>
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
      document.search.action = "csv_export.php";
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
			顧客一覧
			<span style="margin-left:20px;">
				<a href="./?reg_date2=<?php echo $pre_date?>&search_shop_id=<?php echo $_POST['search_shop_id'];?>&ctype=<?php echo $_POST['ctype'];?>&rebook_flg=<?php echo $_POST['rebook_flg'];?>&adcode=<?php echo $_POST['adcode'];?>&line_max=<?php echo $_POST['line_max'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="reg_date" type="text" id="day" value="<?php echo $_POST['reg_date'];?>" readonly />~<input style="width:70px;height:21px;" name="reg_date2" type="text" id="day2" value="<?php echo $_POST['reg_date2'];?>" readonly  />
				<a href="./?reg_date2=<?php echo $next_date?>&search_shop_id=<?php echo $_POST['search_shop_id'];?>&ctype=<?php echo $_POST['ctype'];?>&rebook_flg=<?php echo $_POST['rebook_flg'];?>&adcode=<?php echo $_POST['adcode'];?>&line_max=<?php echo $_POST['line_max'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<!--<select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select>-->
				<select name="search_shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['search_shop_id'] );?></select>
				<select name="ctype" style="height:25px;" ><option value="">会員タイプ</option><?php Reset_Select_Key( $gCustomerType , $_POST['ctype'] );?></select>
				<select name="rebook_flg" style="height:25px;" ><?php Reset_Select_Key( $gRebook_type , $_POST['rebook_flg'] );?></select>
				<select name="route" style="height:25px;" ><option value="">全経由</option><?php Reset_Select_Key( $gRoute , $_POST['route'] );?></select>
			<?php if($authority_level<=1){?>
				<select name="adcode" style="height:25px;width:100px;" ><?php Reset_Select_Key( $adcode_list , $_POST['adcode'] );?></select>
			<?php  }?>
				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type="submit" value=" 表示 "  style="height:25px;" onClick="form.action='index.php';return true" />
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
	
	<div id="content-table">
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
			<!--  start table-content  -->
			<div id="table-content">
				<!-- end page-heading -->
	<table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      	<tr><td>総予約件数：<?php echo ($dGet_Cnt+$dGet_Cnt6[0]);?>件,&nbsp;&nbsp;媒体経由の件数：<?php echo $dGet_Cnt4;?>件,&nbsp;電話経由の件数：<?php echo $dGet_Cnt5;?>件,&nbsp;再申込の件数：<?php echo $dGet_Cnt6[0];?>件,&nbsp;<?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?></td></tr>
      </table>
      <!--  end paging................ -->
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
				<!-- 「区分」一時コメントアウト 20160323 -->
					<!-- <th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">区分</font></a>	</th> -->
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">店舗</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">経由</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">会員番号</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">名前</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">生年月日</font></a></th>
				<?php if($authority_level<=6 || $authority['id']==106 && $_POST['ctype']<2){?>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">電話番号</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">メールアドレス</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">媒体</font></a></th>
				<?php } ?>	
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">予約日時</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">登録日時</a></th>
					<!--<th class="table-header-<?php echo ($authority_level<=1) ? "options" : "repeat" ?> line-left"><a href="">オプション</a></th>-->
					<th class="table-header-<?php echo ($authority_level<=1) ? "repeat" : "repeat" ?> line-left"><a href=""><font size="-2">オプション</font></a></th>
				</tr>
<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		$rsv = Get_Table_Row("reservation"," WHERE del_flg=0 and customer_id = '".addslashes($data['id'])."' order by hope_date desc,id desc limit 1");//最新予約
		switch ($rsv['today_status']) {
			case 1:
				$con_status ="green";
				break;
			case 2:
				$con_status ="purple";
				break;
			case 3:
				$con_status ="red";
				break;
			case 4:
				$con_status ="orange";
				break;
			default:
				$con_status ="black";
				break;

		}

		$contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($data['id'])."' and  (end_date>='".date("Y-m-d")."' or end_date>='0000-00-00') order by contract_date desc,id desc");//最新予約

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .' style="color:'.$con_status.'">';
		/* 「区分」一時コメントアウト 20160323 */
		// if(!$contract['status']){
		// echo 	'<td>'.($contract['id'] ? $gContractStatus[$contract['status']] : "").'</td>';
		// }else{
		// echo 	'<td><font color="red">'.($course_type[$contract['course_id']] ? $gContractStatus3[$contract['status']] : $gContractStatus[$contract['status']]).'</font></td>';
		// }
		echo 	'<td style="width:40px;">'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td style="width:35px;">'.$gRoute[$data['route']].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		//echo 	'<td title="'.$data['name'].'">'.($data['name_kana'] ? $data['name_kana'] : $data['name']).'</td>';
		echo 	'<td title="'.$data['name'].'"><a  rel="facebox" href="mini.php?id='.$data['id'].'">'.($data['name_kana'] ? $data['name_kana'] : ($data['name'] ? $data['name'] : '無名')).'</a></td>';
		echo 	'<td>'.($data['birthday']=="0000-00-00" ? "" : $data['birthday']).'</td>';
	if($authority_level<=6 || $authority['id']==106 && $_POST['ctype']<2){	
		echo 	'<td>'.str_replace('-' , '' , $data['mobile'] ? $data['mobile'] : $data['tel']).'</td>';
		echo 	'<td>'.$data['mail'].'</td>';
		echo 	'<td>'.$adcode_list[$data['adcode']].'</td>';
	}
		echo 	'<td><a href="../main/?id='.$rsv['id'].'&shop_id='.$rsv['shop_id'].'&hope_date='.$rsv['hope_date'].'">'.$rsv['hope_date']." ".$gTime2[$rsv['hope_time']].'</a></td>';
		echo 	'<td>'.$data['reg_date'].'</td>';
		echo 	'<td style="width:140px;">';
		echo 		'<a href="../reservation/edit.php?id='.$rsv['id'].'&shop_id='.$rsv['shop_id'].'&hope_date='.$rsv['hope_date'].'" title="予約詳細" class="icon-1 info-tooltip"></a>';
	if($authority_level<=1)	echo 		'<a href="index.php?action=delete&id='.$data['id'].'&shop_id='.$_POST['shop_id'].'&keyword='.$_POST['keyword'].'" onclick="return confirm(\'仮削除しますか？\')" title="仮削除" class="icon-2 info-tooltip"></a>';
	if($contract['balance']){
		echo 		'<a href="../service/detail.php?mode=balance&customer_id='.$data['id'].'&shop_id='.$rsv['shop_id'].'" onclick="return confirm(\'売掛回収処理をしますか？\')" title="売掛回収処理" class="icon-4 info-tooltip"></a>';
	}	
		//echo 		'<a href="index.php?action=send&id='.$data['id'].'" onclick="return confirm(\'マイページ情報を送信しますか？\')" title="マイページ情報送信" class="icon-3 info-tooltip"></a>';
		echo 		'<a href="../reservation/edit.php?mode=new_rsv&customer_id='.$data['id'].'&shop_id='.$rsv['shop_id'].'" onclick="return confirm(\'次の予約をしますか？\')" title="次の予約" class="icon-5 info-tooltip"></a>';
		//echo 		'<a href="../account/reg_detail.php?id='.$rsv['id'].'&shop_id='.$rsv['shop_id'].'" onclick="return confirm(\'レジ精算に移動しますか？\')" title="レジ精算" class="icon-4 info-tooltip"></a>';
	if($authority_level<=1 && $contract['id']){
		echo 		'<a href="../service/cooling_off.php?mode=cooling_off&customer_id='.$data['id'].'&shop_id='.$rsv['shop_id'].'" onclick="return confirm(\'クーリングオフ処理をしますか？\')" title="クーリングオフ処理" class="icon-2 info-tooltip"></a>';
		echo 		'<a href="../service/cancel_detail.php?mode=contract_cacel&customer_id='.$data['id'].'&shop_id='.$rsv['shop_id'].'" onclick="return confirm(\'中途解約処理をしますか？\')" title="中途解約処理" class="icon-2 info-tooltip"></a>';

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
			</div>
			<!--  end content-table  -->
			<!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      	<tr>
				<td style="background-color:green">予約時telOK</td><td>&nbsp;</td>
				<td style="background-color:purple">予約時telﾙｽ</td><td>&nbsp;</td>
				<td style="background-color:red">予約時telNG</td><td>&nbsp;</td>
				<td style="background-color:orange">お客様切電</td><td>&nbsp;</td>
				<td><?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?></td>
			</tr>
      </table>
      <!--  end paging................ -->
    </div>
    <!--  end content-table-inner ............................................END  -->
  </div>
</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>