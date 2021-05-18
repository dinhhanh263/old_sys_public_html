<?php include_once("../library/reservation/cc_request.php");?>
<?php include_once("../include/header_menu.html");?>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox()
})
</script>

<script type="text/javascript">
function display () {
    document.search.action = "cc_request.php";
    document.search.submit();
	return false;
}
</script>
<script type="text/javascript">
function csv_export () {
    var name=prompt("パスワードを入力して下さい。", "");
    var path = "<?php echo $home_url;?>" + "admin/img/" + name + ".gif";
	var img = new Image();
    img.src = path;
    img.onload = function() {
      document.search.action = "cc_request_csv.php";
	  document.search.submit();
	  document.search.csv_pw.value = name;
	  return true;
  	};
}
</script>

<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<div id="content"><!-- start content -->
	<div id="page-heading"><!--  start page-heading -->
		<h1>
			依頼事項一覧
			<span style="margin-left:20px;">
				<a href="./cc_request.php?date2=<?php echo $pre_date.$param;?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="date1" type="text" id="day" value="<?php echo $date1;?>" readonly  />~<input style="width:70px;height:21px;" name="date2" type="text" id="day2" value="<?php echo $date2;?>" readonly  />
				<a href="./cc_request.php?date2=<?php echo $next_date.$param;?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<!--<select id="shop_id" name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><option value="0">全店舗</option><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'], $gArea_Group, "area_group"); ?></select>-->
				<?php if($authority_level!=17){?>
				<select name="cc_request" style="height:25px;"><?php Reset_Select_Key( $gRequest , $_POST['cc_request'] );?></select>
				<select name="cc_type" style="height:25px;"><?php Reset_Select_Key( $gRequestType , $_POST['cc_type'] );?></select>
				<?php }?>
				<select name="search_process_status" style="height:25px;"><?php Reset_Select_Key( $gProcessStatus2 , $_POST['search_process_status'] );?></select>
				<select name="search_loan_respond" style="height:25px;"><?php Reset_Select_Key( $gLoanRespond2 , $_POST['search_loan_respond'] );?></select>
				<select name="search_loan_request_status" style="height:25px;"><?php Reset_Select_Key( $gLoanRequestStatus2 , $_POST['search_loan_request_status'] );?></select>
				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type='button' value=' 表示 ' onclick='display();' style="height:25px;" />
			</span>
			<?php if($authority_level<=6){?>
			<span style="float:right; margin-right:25px;">
				<input type='button' value='　CSV　' onclick='csv_export();' style="height:25px;" />
				<input type='hidden' name="csv_pw" value="" />
			</span>
			<?php  }?>
		</h1>
		</form>
	</div><!-- end page-heading -->
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
			<div id="table-content"><!--  start table-content  -->
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
					<?php echo "<font color='red' size='-1'>".$gMsg.$_REQUEST['gMsg'] ."</font>";?>
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">依頼事項</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">店舗</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">会員番号</font></a></th>
					<!-- <th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">名前</font></a></th> -->
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">フリガナ</font></a></th>
					<!-- <th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">生年月日</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">電話番号</font></a></th> -->
					<!--<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">店舗</font></a></th>-->
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">契約状況</font></a></th>
					<!-- <th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">契約日</font></a></th> -->
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">契約期間</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">コース名</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-4">ローン会社</font></a></th>
					<?php if($authority_level!=17){?>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">支払情報</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">解約情報</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">口座情報</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">引落情報</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">備考</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">本社処理</font></a></th>
					<?php }?>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">店舗処理</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">依頼日</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">ステータス</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">オプション</font></a></th>
				</tr>
<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		$gRequestStatus = ($data['type']==2) ? $gShopRequest : $gCCRequest;
		$name = $data['name_kana'] ? $data['name_kana'] : ( $data['name'] ? $data['name'] :'無名');

		// 複数ローン会社表示処理
		$loan_company_name = $loan_company_list[$data['loan_company_id']];
		if($data['loan_company_id'] && $data['old_contract_id']){
			$old_loan_company_id = Get_Table_Col("contract","loan_company_id"," WHERE del_flg=0 AND loan_company_id<>0 AND id =".$data['old_contract_id']);
			if( $old_loan_company_id && $old_loan_company_id<>$data['loan_company_id'] ){
				$loan_company_name = $loan_company_name.'<br>'.$loan_company_list[$old_loan_company_id];
			}
		}
		if($authority['login_id']=='system'){
			if($loan_company_name=='サクシード') $loan_company_name = '<a rel="facebox" href="../loan/?keyword='.$data['no'].'">サクシード</a>';
			if($loan_company_name=='ライフティ') $loan_company_name = '<a rel="facebox" href="../loan2/?keyword='.$data['no'].'">ライフティ</a>';
		}

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td style="max-width:62px;">'.$gRequestStatus[$data['cc_request']].'</td>';
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		// echo 	'<td>'.$data['name'].'</td>';
		echo 	'<td title="'. $data['name'].'"><a href="/admin/customer/index.php?customer_id='.$data['id'].'" target="_blank">'.$name.'</a></td>';
		// echo 	'<td>'.$data['birthday'].'</td>';
		// echo 	'<td>'.$data['tel'].'</td>';
		// echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$gContractStatus[$data['status']].'</td>';
		// echo 	'<td>'.$data['contract_date'].'</td>';
		if($data['course_type']==1){
			echo 	'<td>'.date('Y/m～',strtotime($data['start_ym'].'01')).'</td>';
		}else{
			echo 	'<td>'.str_replace('-', '/', $data['contract_date']).'～'.str_replace('-', '/', $data['end_date']).'</td>';
		}
		echo 	'<td style="max-width:180px;">'.$course_list[$data['course_id']].'</td>';
		echo 	'<td>'.$loan_company_name.'</td>';
		if($authority_level!=17){
		echo 	'<td><a rel="facebox" href="../account/?customer_id='.$data['id'].'">支払詳細</a></td>';
		echo 	'<td><a rel="facebox" href="./cc_cancel.php?contract_id='.$data['contract_id'].'&no='.$data['no'].'&name='.$name.'">解約詳細</a></td>';
		if($data['bid']){
		echo 	'<td><a rel="facebox" href="./cc_bank.php?customer_id='.$data['id'].'&no='.$data['no'].'&name='.$name.'">口座詳細</a></td>';
		}else{
		echo 	'<td>-</td>';
		}
		echo 	'<td><a rel="facebox" href="./cc_withdrawal.php?contract_id='.$data['contract_id'].'&pay_type='.$data['pay_type'].'&no='.$data['no'].'&name='.$name.'">引落詳細</a></td>';
		echo 	'<td><a rel="facebox" href="./cc_memo.php?customer_id='.$data['id'].'&no='.$data['no'].'&name='.$name.'">備考</a></td>';
		echo 	'<td><a rel="facebox" href="./cc_process.php?request_id='.$data['request_id'].'&no='.$data['no'].'&name='.$name.'&last_visit_ym='.$data['last_visit_ym'].'&attorney_status='.$data['attorney_status'].'">本社処理</a></td>';
		}
		echo 	'<td><a rel="facebox" href="./shop_process.php?request_id='.$data['request_id'].'&name='.$name.'">店舗処理</a></td>';
		echo 	'<td>'.substr($data['reg_date'],0,10).'</td>';
		echo 	'<td>'.$gLoanStatus[$data['loan_status']].'</td>';
		echo 	'<td><a  rel="facebox" href="/admin/contract/mini.php?contract_id=' . $data['contract_id'] . '" title="予約履歴" class="icon-history info-tooltip"></a>';
		if($authority_level<=6 || ($authority_level==17 && $data['type']==2 && substr($data['reg_date'],0,10)==date('Y-m-d'))){
			echo '<a href="cc_request.php?action=delete&request_id='.$data['request_id'].'&keyword='.$data['no'].'" onclick="return confirm(\'仮削除しますか？\')" title="仮削除" class="icon-delete info-tooltip"></a>';
		}
		echo '</tr>';
		$i++;
	}
}
?>
				</table><!--  end product-table................................... -->
				</form>
			</div><!--  end content-table  -->
			<!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      	<tr>
      		<td>
      			<?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
      		</td>
      	</tr>
      </table><!--  end paging................ -->
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
</div><!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>