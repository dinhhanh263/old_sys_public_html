<?php include_once("../library/loan2/index.php");?>
<?php if($authority_level>0) header("Location: ./");?>
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
    document.search.action = "index.php";
    document.search.submit();
	return fales;
}
</script>
<script type="text/javascript">
function cic_export () {
    document.search.action = "loan_app_cic_export.php";
    document.search.submit();
	return fales;
}
</script>
<script type="text/javascript">
function csv_export () {
    document.search.action = "loan_app_csv_export.php";
    document.search.submit();
	return fales;
}
</script>
<script type="text/javascript">
function verify_complete () {
    document.search.action = "loan_app_verify_complete_export.php";
    document.search.submit();
	return fales;
}
</script>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">
		<h1>
			ライフティローン申込一覧
			<span style="margin-left:20px;">
				<a href="./index.php?application_date2=<?php echo $pre_date.$param;?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="application_date" type="text" id="day" value="<?php echo $_POST['application_date'];?>" readonly />~
				<input style="width:70px;height:21px;" name="application_date2" type="text" id="day2" value="<?php echo $_POST['application_date2'];?>" readonly />
				<a href="./index.php?application_date2=<?php echo $next_date.$param;?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>

				<!--<select name="verify_status" style="height:25px;"><?php Reset_Select_Key( $gVerifyStatus2 , $_POST['verify_status'] );?></select>
				<select name="contract_status" style="height:25px;"><?php Reset_Select_Key( $gLoanContractStatus2 , $_POST['contract_status'] );?></select>
				<select name="support_status" style="height:25px;"><?php Reset_Select_Key( $gSupportStatus , $_POST['support_status'] );?></select>
				<select name="cor_request" style="height:25px;"><?php Reset_Select_Key( $gCorRequest , $_POST['cor_request'] );?></select>
-->
				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type='button' value=' 表示 ' onclick='display();' style="height:25px;" />
			</span>
			<!--<span style="float:right; margin-right:20px;">
				<input type='button' value=' CIC ' onclick='cic_export();' style="height:25px;" />
			</span>
			<span style="float:right; margin-right:5px;">
				<input type='button' value=' CSV ' onclick='csv_export();' style="height:25px;" />
			</span>
			<span style="float:right; margin-right:5px;">
				<input type='button' value=' OnlineVerify確認済データ ' onclick='verify_complete();' style="height:25px;" />
			</span>

			<table style="font-size:12px;margin:10px 50px;border :1px solid #d2d2d2;">
      			<tr>
			      <td style="width:140px;padding-left:50px;vertical-align:top;">契約終了<br><?php echo InputCheckboxTag8("process_category",$gProcessCategory,$_POST['process_category'])?></td>
			      <td style="width:150px;vertical-align:top;">受付終了<br><?php echo InputCheckboxTag8("regist_category",$gRegistCategory,$_POST['regist_category'])?></td>
			      <td style="width:140px;vertical-align:top;">同意書リカバー<br><?php echo InputCheckboxTag8("consent_recovery",$gConsentRecovery,$_POST['consent_recovery'])?></td>
			      <td style="width:140px;vertical-align:top;">ベリファイ確認状況<br><?php echo InputCheckboxTag8("verify_status",$gVerifyStatus,$_POST['verify_status'])?></td>
			      <td style="width:140px;vertical-align:top;">経過日数<br><?php echo InputCheckboxTag8("pass_days",$gPassDays,$_POST['pass_days'])?></td>
			      <td style="width:140px;vertical-align:top;">契約番号<br><?php echo InputCheckboxTag8("if_contract_no",$gContractNo,$_POST['if_contract_no'])?></td>
			      <td style="width:140px;vertical-align:top;">契約日<br><?php echo InputCheckboxTag8("if_contract_date",$gContractDate,$_POST['if_contract_date'])?></td>
			      <td style="width:140px;vertical-align:top;">契約終了日<br><?php echo InputCheckboxTag8("if_contract_end_date",$gContractEndDate,$_POST['if_contract_end_date'])?></td>
			      <td style="width:140px;vertical-align:top;">支払方法<br><?php echo InputCheckboxTag8("transfer_status",$array_transfer_status3,$_POST['transfer_status'])?></td>
      			</tr>
      		</table>-->

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
				<!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      <tr>

      <td>
      <?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
      </td>
      </tr>
      </table>
      <!--  end paging................ -->
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">ID</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">申込日</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">申込店舗</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">会員番号</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">顧客名</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">フリガナ</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">申込金額</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">支払回数</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">承認番号</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">承認日</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">受付ｽﾃｰﾀｽ</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">審査ｽﾃｰﾀｽ</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">集計</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">店ｷｬﾝｾﾙ</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">信販会社ｷｬﾝｾﾙ</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">最終更新日時</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">経過日数</font></a></th>
					<!--<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">申込詳細</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">支払詳細</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">オプション</font></a></th>-->
				</tr>
<?php
if ( $dRtn3 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {

		echo '<tr'. ( $data['payment_loan']<=0 && !$data['amount'] ? ' style="background-color: yellow;"' : ($i%2==0 ? ' class="alternate-row"' : '') ) .'>';
		echo 	'<td>'.($data['apl_id'] ? $data['apl_id'] : '').'</td>';
		echo 	'<td>'.$data['application_date'].'</td>';
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		if($data['no']){
		echo 	'<td>'.$data['no'].'</td>';
		}else{
		echo 	'<td>'.$data['cno'].'</td>';
		}
		echo 	'<td><a rel="facebox" href="./loan_customer_detail.php?id='.$data['id'].'&no='.$data['no'].'">'.($data['name'] ? $data['name'] : $data['name_kana']).'</a></td>';
		echo 	'<td>'.$data['name_kana'].'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['payment_loan']).'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['number_of_payments']).'</td>';
		echo 	'<td>'.$data['addm_no'].'</td>';
		echo 	'<td>'.($data['addm_date']=="0000-00-00" ? "" : $data['addm_date']).'</td>';

		echo 	'<td>'.$gRegistCategory2[$data['regist_category']].'</td>';
		echo 	'<td>'.$gEvalStatus[$data['eval_status']].'</td>';
		echo 	'<td>'.($data['sum_up'] ? "集計済" : "").'</td>';
		echo 	'<td>'.($data['shop_cancel'] ? "キャンセル" : "").'</td>';
		echo 	'<td>'.($data['own_cancel'] ? "キャンセル" : "").'</td>';
		echo 	'<td>'.$data['last_update'].'</td>';

		echo 	'<td>'.day_diff($data['application_date'],date("Y-m-d")).'</td>';
		//echo 	'<td><a rel="facebox" href="./loan_app_detail.php?id='.$data['id'].'">申込詳細</a></td>';
		//echo 	'<td><a target="_blank" href="./loan_pay_method.php?id='.$data['id'].'&customer_id='. $data['customer_id'].'&no='.$data['no'].'">支払詳細</a></td>';
		//echo 	'<td><a target="_blank" href="./loan_app_info.php?id='.$data['id'].'&no='.$data['no'].'" title="ローン申込詳細" class="icon-1 info-tooltip"></a>';
		// echo		'<a href="../service/loan_application.php?customer_id='. $data['customer_id'].'&contract_id='. $data['contract_id'].'" title="ローン申込詳細" target="_blank" class="icon-1 info-tooltip"></a>';
		echo	'</td>';
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