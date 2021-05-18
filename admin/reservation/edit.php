<?php include_once("../library/reservation/edit.php");?>
<?php include_once("../include/header_menu.html");?>
<!-- 月のDATEPICKER を読み込むためのJS -->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.ympicker.js"></script>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox()
})
</script>

<script type="text/javascript">

function submit_check() {
	// mode=new_rsv && トリートメント & 来店状況未入力 & 予約日程入力済み & 予約日程が現在日以降  & 予約日程が予約可能日より前 の場合アラート
	var hope_date = $(".hasDatepicker ,#day").val();
	var hope_time = $("#hope_time").val();
	var current_date = "<?php echo $now_date;?>";
	var base_date = "<?php echo $baseDate;?>";
	var mode = "<?php echo $_GET['mode'];?>";
	var monthlyBaseDate = "<?php echo $monthlyBaseDate;?>";
	var weekdaysPlanTypeNum = "<?php echo $course['weekdays_plan_type']; ?>"

	var holidayList =  JSON.parse('<?php echo $holidayJsonList; ?>');
	var weekdaysPlanType =  JSON.parse('<?php echo $weekdaysPlanTypeJSON; ?>');
	var date_hope_date = new Date(hope_date);
	var hope_year = date_hope_date.getFullYear();
	//月と日は2桁にする
	var hope_month_day = ("0"+(date_hope_date.getMonth() + 1)).slice(-2) + '-' + ("0" + date_hope_date.getDate()).slice(-2);
	var day_of_week = date_hope_date.getDay();

	if (mode == "new_rsv" && hope_date != ""  && hope_date >= current_date && $('#kbn').val() == "2" && $('[name="status"]').val() == "0") {
		  if (hope_date < base_date) {
			  alert("【パック契約確認】\n予約日程がマイページからの予約可能日より前になっています");
		  }

		  if (hope_date < monthlyBaseDate) {
			  alert("月額クーリングオフ期間中です。");
		  }
	}

	if(weekdaysPlanTypeNum != "" && weekdaysPlanTypeNum >= 0) {
		var day_of_week_limited = false;
		if(holidayList[hope_year]) {
			if(holidayList[hope_year].indexOf(hope_month_day) >= 0) {
				day_of_week_limited = true;
			}
		}
		if(weekdaysPlanType[weekdaysPlanTypeNum]['day_of_week_limited'].indexOf(day_of_week) >= 0) {
			day_of_week_limited = true;
		}
		if(day_of_week_limited && hope_date != ""  && hope_date >= current_date && $('#kbn').val() == "2" && $('[name="status"]').val() == "0") {
			alert("平日とく得プランで予約不可の曜日に予約に入れようとしています。");
		}

		if(weekdaysPlanType[weekdaysPlanTypeNum]['time_limited'].indexOf(Number(hope_time)) >= 0
		&& hope_date != ""  && hope_date >= current_date && $('#kbn').val() == "2" && $('[name="status"]').val() == "0") {
			alert("平日とく得プランで予約不可の時間帯に予約に入れようとしています。");
		}
	}

	var feature_reservation_flg = <?php echo $feature_reservation_flg;?>;
	if (feature_reservation_flg == 1) {
		alert("【複数予約確認】\n既に未来日にトリートメント予約が入っています。");
	}

	var contract_status = "<?php echo $contract['status']; ?>";
	var terminate_pending_status = "<?php echo $contract['terminate_pending_status']; ?>";
	if (contract_status != 0 && terminate_pending_status ==0 && $('[name="terminate_pending_status"]').val() == "1") {
		alert("【解約保留確認】\n契約中のプランではありません。");
	}

}

$(function(){
	$('#next_reserve_type').change(
		function() {
			if ($(this).val() != "") {
				location.href = $(this).val();
			}
		}
	);
});

</script>
<script type="text/javascript">
// ローンステータスが5.ローン不備 だった場合ポップアップ確認を表示させる add by 2016/08/30 shimada
var loan_status = <?php echo $contract['loan_status'] ? $contract['loan_status'] : 3; ?>;
// var loan_delay = <?php echo $customer['loan_delay_flg']>0 ? $customer['loan_delay_flg'] : 0; ?>;
var loan_delay = <?php echo $contract['loan_delay_flg']>0 ? $contract['loan_delay_flg'] : 0; ?>;
var onelife_flg = <?php echo $customer['onelife_flg']>0 ? $customer['onelife_flg'] : 0; ?>;
var coupon_type = <?php echo $coupon_type[$data['coupon']]>0 ? $coupon_type[$data['coupon']] : 0; ?>;

if(loan_status ==5){
	confirm('必ずクレピコ登録してください。');
}
if(loan_delay >0){
	confirm('ローン延滞しています。確認してください。');
}
// else if(loan_delay ==11){
// 	confirm('サクシードで自動解約になっています。確認してください。');
// }
if(onelife_flg >0){
	confirm('ワンライフにローン申請したお客様で、予約不可です。');
}
if(coupon_type ==1){
	confirm('フリーチケットの方です。施術後割引Aパターンで契約をお勧めしてください。');
}
var minor_plan_alert_flg = <?php echo $minor_plan_alert_flg;?>;
var minor_plan_alert_days_view = <?php echo "'".$minor_plan_alert_days_view."'";?>;

if (minor_plan_alert_flg) {
	alert("【U-19応援プラン契約者】\n定額制月額プラン移行まで" + minor_plan_alert_days_view + "をきりました。\n定額制月額に切り替えの際、引き落とし金額が変わる事をお客様にお伝えしシステム備考にお伝え済みと入力ください。");
}
</script>
<!-- start content-outer -->
<div id="content-outer" <?php if($contract['loan_status']==5){
								echo "class='atenntion_y';";
							// }elseif($customer['loan_delay_flg']==11){
							// 	echo "class='atenntion_o';";
							}elseif($contract['loan_delay_flg']<>0){
								echo "class='atenntion_b';";
							}elseif($customer['onelife_flg']>0){
								echo "class='atenntion_g';";
							}elseif($coupon_type[$data['coupon']]==1){
								echo "class='atenntion_p';";
							}elseif($cooling_off_flg){
								echo "class='atenntion_c';";
							} else {
								echo "";
							}?>>
	<!-- start content -->
	<div id="content">
		<div id="page-heading">
			<h1>
				予約詳細&nbsp;
						<!-- <a href="../account/?customer_id=<?php echo $customer['id'];?>" target="_blank">売上詳細</a>,
						<a href="../account/remain.php?customer_id=<?php echo $customer['id'];?>" target="_blank">消化詳細</a>） -->
				<?php if($_POST['reservation_id'] || $_POST['customer_id'] || $_POST['contract_id']) { ?>
						<a class="button register_btn" href="../sales/register.php?customer_id=<?php echo $customer['id'];?>&hope_date=<?php echo $data['hope_date'] ?>" target="_blank">物販レジへ</a>&nbsp;
						<a class="button contract_btn" href="../contract/index.php?customer_id=<?php echo $customer['id'];?>">契約一覧へ</a>&nbsp;
						<a class="button contract_btn" href="../customer/index.php?customer_id=<?php echo $customer['id'];?>">顧客概要へ</a>&nbsp;
				<?php } ?>

			<input name="id" type="hidden" value="<?php echo $data['id'];?>" />
			</form>
			    <?php if($contract['id']) { ?>
				<span style="float:right;margin-right:25px;">
					<!-- <a href="./edit.php?mode=new_rsv&contract_id=<?php echo $contract['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>" onclick="return confirm('次の予約をしますか？')" class="side" title="次の予約" >次の施術予約へ</a> -->
				<?php if($next_reserve_flg) { ?>
				<span>次回予約取得:</span>
					<select id="next_reserve_type" class="styledselect_form_3">
					    <option value=""> 予約タイプを選択 </option>
						<?php Print_Select_List_Next_Reserve($course, $contract, $data['shop_id'], $cooling_off_flg);?>
					</select>
				<?php } ?>
				</span>
				<?php }?>
			</h1>
		</div><!--予約新規?次回予約新規?予約詳細?顧客新規以外顧客情報を右側に-->
		<div id="content-table">
					<style type="text/css" media="screen">
						.resev_form{
							width: 330px;
						}
						.edit_right{
							width: 280px;
						}
						#related-activities{
							background: #fff;
						}
					</style>
					<!--  start content-table-inner -->
					<div id="content-table-inner">
									<form action="" method="post" id="form1" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
										<input type="hidden" name="action" value="edit" />
										<input type="hidden" name="mode" value="<?php echo $_POST["mode"];?>" />
										<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
										<input type="hidden" name="customer_id" value="<?php echo $customer["id"];?>" />
										<input type="hidden" name="contract_id" value="<?php echo ($contract["id"] ? $contract["id"] : 0);?>" />
										<input type="hidden" name="course_id" value="<?php echo ($contract["course_id"] ? $contract["course_id"] : 0);?>" />
										<input type="hidden" name="edit_date" value="<?php echo $data["edit_date"];?>" />
										<input type="hidden" name="from_cc" value="<?php echo $_POST["from_cc"];?>" />
										<input type="hidden" name="customer_memo_id" value="<?php echo $customer_memo["id"];?>" />
										<?php echo $gMsg;?>
										<?php
											if ($data['sales_id']) {
												echo "<font color='red' size='-1'>請求データが存在するため、「区分」と「予約状況」は変更できません。</font>";
											}
											if ($data['type']==3) {
												echo "<font color='red' size='-1'>区分キャンセルからは変更できません。</font>";
											}
										?>
										<?php echo "<font color='red' size='-1'>".$_REQUEST['gMsg'] ."</font>";?>
										<?php if($_POST['mode']=="new_rsv"){ ?>
											<div class="f_b_16 flash center_box hope_date">新規予約は「ルーム」「時間」を選択してください。</div>
										<?php } ?>
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
								<tr valign="top">
									<td class="resev_form">
										<!-- start id-form -->
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<? //php if ($_POST['type'] == 2 || $data['type'] == 2) { ?>
											<!-- <tr>
												<th valign="top">施術タイプ:</th>
												<td><select name="treatment_type" id="treatment_type" class="styledselect_form_3" disabled><?php Reset_Select_Key( $gtreatmentType , $course_treatment_type[$contract['course_id']]);?></select></td>
											</tr>
											<? // } else {?>
											<tr>
												<th valign="top">施術タイプ:</th>
												<td><select name="treatment_type" id="treatment_type" class="styledselect_form_3" ><?php Reset_Select_Key( $gtreatmentType , $course_treatment_type[$contract['course_id']]);?></select></td>
											</tr> -->
											<? //} ?>
											<tr>
												<th valign="top">区分:</th>
												<td><select <?php if($data['type'] == 3 || $data['sales_id']) echo "style='pointer-events: none;' tabindex='-1'"; ?> name="type" id="kbn" class="styledselect_form_3"><?php Reset_Select_Key( $gResType4 , $_POST['type'] ? $_POST['type'] : $data['type']);?></select></td>
											</tr>
											<tr>
												<th valign="top">予約状況:</th>
												<td><select <?php if($data['sales_id']) echo "style='pointer-events: none;' tabindex='-1'"; ?> name="rsv_status" class="styledselect_form_3"><?php Reset_Select_Key( $gRsvStatus , $_POST['rsv_status'] ? $_POST['rsv_status'] : $data['rsv_status']);?></select></td>
											</tr>
											<!-- 来店プルダウンを表示しない 2017/09/05 modify by shimada-->
											<!-- ↑↑ SVよりも番号が下の権限で、CCとCC2以外 OR CCとCC2で予約済み以外 -->
											<?php if((6<$authority_level && (!$data['id'] || date('Y-m-d')<$data['hope_date']) && $authority['id']<>106 && $authority['id']<>1449)
													|| (($authority['id']==106 || $authority['id']==1449) && !$data['id'])){?>
												<th valign="top">来店状況:</th>
												<td><select disabled name="status" class="styledselect_form_3"></select></td>
												<input type="hidden" name="status" value="<?php echo $_POST['status'] ? $_POST['status']:$data['status'];?>">
											<!-- 予約日に関わらず、来店プルダウンを表示する -->
											<!-- ↑↑ 店長よりも上の権限者 -->
											<?php } else { ?>
												<tr>
													<th valign="top">来店状況:</th>
													<!-- 予約タイプがカウンセリング、プラン変更 -->
													<?php if($_GET['type'] == 1 || $data['type'] == 1 || $_GET['type'] == 10 || $data['type'] == 10) { ?>
														<td><select name="status" class="styledselect_form_3"><?php Reset_Select_Key($gBookNewContractStatus, $_POST['status'] ? $_POST['status'] : $data['status']);?></select></td>
													<!-- 予約タイプがキャンセル -->
													<?php } else if($_GET['type'] == 3 || $data['type'] == 3){ ?>
														<td><select name="status" class="styledselect_form_3"><?php Reset_Select_Key($gBookStatus, $_POST['status'] ? $_POST['status'] : $data['status']);?></select></td>
													<!-- 予約タイプが追加契約-->
													<?php } else if($_GET['type'] == 32 || $data['type'] == 32){ ?>
														<td><select name="status" class="styledselect_form_3"><?php Reset_Select_Key($gBookAddStatus, $_POST['status'] ? $_POST['status'] : $data['status']);?></select></td>
													<!-- 予約タイプがショット-->
													<?php } else if($_GET['type'] == 33 || $data['type'] == 33){ ?>
														<td><select name="status" class="styledselect_form_3"><?php Reset_Select_Key($gBookShotStatus, $_POST['status'] ? $_POST['status'] : $data['status']);?></select></td>
													<?php } else { ?>
														<td><select name="status" class="styledselect_form_3"><?php Reset_Select_Key($gBookOtherStatus, $_POST['status'] ? $_POST['status'] : $data['status']);?></select></td>
													<?php }  ?>
												</tr>
											<?php }?>
											<?php if($_POST['reservation_id'] && ($data['type']<=1 || $data['type']==3 && !$data['contract_id'])){?>
											<tr>
												<th valign="top">確認状況(前日tel):</th>
												<td><select name="preday_status" class="styledselect_form_3"><?php Reset_Select_Key( $gPreDayStatus , $_POST['preday_status'] ? $_POST['preday_status'] : $data['preday_status']);?></select></td>

											</tr>
											<tr>
												<th valign="top">前日架電回数:</th>
												<td><select name="preday_cnt" class="styledselect_form_3" ><?php Reset_Select_Key( $gTelCnt , $data['preday_cnt'] );?></select></td>
											</tr>
											<tr>
												<th valign="top">担当(前日tel):</th>
												<td><select name="preday_staff_id" class="styledselect_form_3" ><?php Reset_Select_Key( $ccstaff_list , $data['preday_staff_id'] );?></select></td>
											</tr>

											<tr>
												<th valign="top">確認状況(予約時tel):</th>
												<td><select name="today_status" class="styledselect_form_3"><?php Reset_Select_Key( $gTodayStatus , $_POST['today_status'] ? $_POST['today_status'] : $data['today_status']);?></select></td>

											</tr>
											<tr>
												<th valign="top">予約時架電回数:</th>
												<td><select name="today_cnt" class="styledselect_form_3" ><?php Reset_Select_Key( $gTelCnt , $data['today_cnt'] );?></select></td>
											</tr>
											<tr>
												<th valign="top">担当(予約時tel):</th>
												<td><select name="today_staff_id" class="styledselect_form_3" ><?php Reset_Select_Key( $ccstaff_list , $data['today_staff_id'] );?></select></td>
											</tr>

											<tr>
												<th valign="top">カウンセリング担当:</th>
												<td><select name="cstaff_id" class="styledselect_form_3" >
												<?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['cstaff_id'],getDatalist5("shop",$_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']));?>
												</select></td>
											</tr>
											<?php } elseif($_POST['reservation_id'] && ($data['type']==32 || $data['type']==33)) { ?>
												<tr>
													<th valign="top">ミドルカウンセリング担当:</th>
													<td><select name="cstaff_id" class="styledselect_form_3" >
														<?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['cstaff_id'],getDatalist5("shop",$_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']));?>
													</select></td>
												</tr>
											<?php } ?>

											<?php if($_POST['reservation_id'] &&  $data['type']<>1){?>

													<!-- 何さんが今後複数コース対応になった場合に備えて作成。現在使っていないためコメントアウト 20160906 -->
													<!-- <th valign="top">契約コース:</th>
													<td><select name="course_id" class="styledselect_form_3" disabled><?php Reset_Select_Key( $course_list , $contract['course_id']);?></select></td>
													<td><select name="course_id" class="styledselect_form_3" ><option></option><?php Reset_Select_Key_Group( $course_list , $contract['course_id'],$gCourseGroup);?></select></td> -->

											<?php } ?>
											<tr>
												<th valign="top">店舗:</th>
												<!--<td><select name="shop_id" class="styledselect_form_1" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : ($data['shop_id'] ? $data['shop_id'] : 1));?></select></td>-->
												<td>
													<!-- <select name="shop_id" class="styledselect_form_3"><?php Reset_Select_Key( $shop_list , $data['shop_id']  ? $data['shop_id']  : ($_POST['shop_id'] ? $_POST['shop_id'] : ($authority_shop['id'] ? $authority_shop['id'] : 1)  ));?></select> -->
													<select id="shop_id" name="shop_id" class="styledselect_form_3"><?php Reset_Select_Key_ShopGroup( $shop_lists , $data['shop_id']  ? $data['shop_id']  : ($_POST['shop_id'] ? $_POST['shop_id'] : ($authority_shop['id'] ? $authority_shop['id'] : 1) ), $gArea_Group, "area_group"); ?></select>
												</td>
											</tr>
											<tr>
												<th valign="top">ルーム:</th>
												<td><select name="room_id" class="styledselect_form_3"><?php Reset_Select_Key( $room_list , $data['room_id']);?></select></td>
											</tr>



											<tr>
												<th valign="top">予約日程:</th>
												<td class="noheight">
													<table border="0" cellpadding="0" cellspacing="0">
														<tr  valign="top">
															<td><input  class="inp-form" name="hope_date" type="text" id="day" value="<?php echo ($data['hope_date'] ? $data['hope_date'] : date('Y-m-d'));?>" readonly /></td>
														</tr>
													</table>
												</td>
												<td></td>
											</tr>
											<tr>
												<th valign="top">予約時間:</th>
												<td>
													<select size="1" name="hope_time" class="styledselect_form_3" id="hope_time"><?php Reset_Select_Key( $gTime  , $data['hope_time']);?></select>
												</td>
											</tr>
											<tr>
												<th valign="top">人数:</th>
												<td><select name="persons" class="styledselect_form_3"><?php Reset_Select_Key( $gPersons , $data['persons']);?></select></td>
											</tr>
										<?php if($data['type']==1 || $data['type']==32 || $data['type']==33){?>
											<tr>
												<th valign="top">所要時間:</th>
												<td><select name="length" class="styledselect_form_3"><?php Reset_Select_Key( $gLength , $data['length'] ? $data['length'] : 2);?></select></td>
											</tr>
										<?php } else { ?>
											<tr>
												<th valign="top">所要時間:</th>
												<td><select name="length" class="styledselect_form_3"><?php Reset_Select_Key( $gLength , $data['length'] ? $data['length'] : $course['length']);?></select></td>
											</tr>
										<?php } ?>
										<?php if($customer['big_flg']){?>
											<tr>
												<th valign="top">BIG:</th>
												<td><?php echo $gBig[$customer['big_flg']] ;?></td>
											</tr>
										<?php } ?>
										<?php if($course['type'] && $data['part']){?>
											<tr>
												<th valign="top">施術部位:</th>
												<td><?php echo $gPart[$data['part']] ;?></td>
											</tr>
										<?php } ?>

										<?php if(!$_POST['reservation_id'] && !$customer['id']){?>
											<tr>
												<th valign="top">会員タイプ:</th>
												<td><select name="ctype" class="styledselect_form_3"><?php Reset_Select_Key( $gCustomerType , $_POST['ctype'] ? $_POST['ctype'] : $customer['ctype']);?></td>
											</tr>
											<tr>
												<th valign="top">名前:</th>
												<td><input type="text" name="name" value="<?php echo TA_Cook($_POST['name'] ? $_POST['name'] : $customer['name']) ;?>" id="Name"  class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">名前(カナ):</th>
												<td><input type="text" name="name_kana" value="<?php echo TA_Cook($_POST['name_kana'] ? $_POST['name_kana'] : $customer['name_kana']) ;?>" id="NameKana" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">生年月日:</th>
												<td><input type="text" name="birthday" value="<?php echo TA_Cook($_POST['birthday'] ? $_POST['birthday'] : $customer['birthday']) ;?>" id="fm" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">年齢:</th>
												<td><input type="text" name="age" value="<?php echo TA_Cook($_POST['age'] ? $_POST['age'] : $age) ;?>" id="fm2" class="inp-form" /></td>
											</tr>

											<tr>
												<th valign="top">電話番号:</th>
												<td><input type="text" name="tel" value="<?php echo TA_Cook($_POST['tel'] ? $_POST['tel'] : $customer['tel']) ;?>" id="fm3" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">メールアドレス:</th>
												<td><input type="text" name="mail" value="<?php echo TA_Cook($_POST['mail'] ? $_POST['mail'] : $customer['mail']) ;?>" id="fm4" class="inp-form" /></td>
											</tr>

											<!--<tr>
												<th valign="top">反響:</th>
												<td><input  class="inp-form" name="echo" type="text" value="<?php echo $data['echo'];?>" /></td>
											</tr>-->
											<tr>
												<th valign="top">紹介者:</th>
												<td><input  class="inp-form" name="introducer" type="text" value="<?php echo $data['introducer'];?>" /></td>
											</tr>
											<tr>
												<th valign="top">紹介者タイプ:</th>
												<td>
													<select name="introducer_type" class="styledselect_form_3"><?php Reset_Select_Key( $gIntroducerType , $data['introducer_type']);?></select>
												</td>
											</tr>
										<?php if($authority_level<=1){?>
											<tr>
												<th valign="top">特別紹介者:</th>
												<td>
													<select name="special" class="styledselect_form_3"><?php Reset_Select_Key( $special_list , $data['special']);?></select>
												</td>
											</tr>

										<?php } } ?>
											<tr>
												<th valign="top">経由:</th>
												<td><select name="route" class="styledselect_form_3"><option>-</option><?php Reset_Select_Key( $gRoute , $route);?></select></td>
											</tr>
											<tr>
												<th valign="top">HPポイント:</th>
												<td><input type="text" name="point" value="<?php echo TA_Cook($_POST['point'] ? $_POST['point'] : $data['point'] ) ;?>" id="fm5" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">月額（HP）:</th>
													<td><select name="hp_flg" class="styledselect_form_3"><?php Reset_Select_Key( $gHP , $data['hp_flg']);?></select></td>
											</tr>
											<tr>
												<th valign="top">広告番号:</th>
												<td><select name="flyer_no" class="styledselect_form_3"><option>-</option><?php Reset_Select_Key( $flyer_no_list , $data['flyer_no']);?></select></td>
											</tr>
											<tr>
												<th valign="top">HP利用クーポン:</th>
												<td><select name="coupon" class="styledselect_form_3"><option>-</option><?php Reset_Select_Key( $coupon_list , $data['coupon']);?></select></td>
											</tr>
										<?php if(!empty($prize_list)){ ?>
											<tr>
												<th valign="top">当選賞品:</th>
												<td>
													<select name="prize" class="styledselect_form_3" <?php if($customer['prize'] && $authority_level>=1) echo "disabled" ?> >
														<option>-</option>
														<?php Reset_Select_Key( $prize_list , ($_POST['prize'] ? $_POST['prize'] : $customer['prize']));?>
													</select>
												</td>
											</tr>
										<?php } ?>
											<tr>
												<th valign="top">連絡希望時間帯:</th>
												<td><input class="inp-form" name="hope_time_range" type="text" value="<?php echo $data['hope_time_range'];?>" readonly /></td>
											</tr>
											<tr>
												<th valign="top">キャンペーン特典:</th>
												<td><select name="hope_campaign" class="styledselect_form_3"><?php Reset_Select_Name( $gHopeCapaign , ($data['hope_campaign'] ? $data['hope_campaign'] : (!$_POST['reservation_id'] && $_POST['mode']<>"new_rsv" ? "ハンド脱毛" : "") ));?></select></td>
											</tr>
											<tr>
												<th valign="top">学割プラン:</th>
												<td><select name="hopes_discount" class="styledselect_form_3"><?php Reset_Select_Key( $gHopesDiscount , $data['hopes_discount']);?></select></td>

											</tr>

										<?php if($data['type']==2){?>
											<tr>
												<th valign="top">施術主担当:</th>

												<td><select name="tstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['tstaff_id'],getDatalist5("shop", $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']));?></select></td>
											</tr>
											<tr>
												<th valign="top">施術サブ担当1:</th>

												<td><select name="tstaff_sub1_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['tstaff_sub1_id'],getDatalist5("shop", $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']));?></select></td>
											</tr>
											<tr>
												<th valign="top">施術サブ担当2:</th>
												<td><select name="tstaff_sub2_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['tstaff_sub2_id'],getDatalist5("shop", $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']));?></select></td>
											</tr>
										<?php } ?>

											<tr>
												<th valign="top">店舗受付担当:</th>
												<td><select name="staff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['staff_id'],getDatalist5("shop", $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']));?></select></td>
											</tr>
											<tr>
												<th valign="top">CC受付担当:</th>
												<td><select name="ccstaff_id" class="styledselect_form_3" ><?php Reset_Select_Key( $ccstaff_list , $data['ccstaff_id'] );?></select></td>
											</tr>
											<tr>
												<th valign="top">予約表記載:</th>
												<td><textarea name="memo2" class="form-textarea3"><?php echo TA_Cook($data['memo2'] ? $data['memo2'] : ($_POST['memo2'] ? $_POST['memo2'] : $pre_rsv['memo2']) ) ;?></textarea></td>
											</tr>
											<!-- <tr>
												<th valign="top">店舗依頼事項:</th>
												<td><select name="shop_request" class="styledselect_form_3"><?php Reset_Select_Key( $gShopRequest , ($_POST['shop_request'] ? $_POST['shop_request'] : $shop_request) );?></select></td>
											</tr>
											<tr>
												<th valign="top">返金額(店舗):</th>
												<td><input  class="inp-form" name="pay_back" type="text" value="<?php echo ($_POST['pay_back'] ? $_POST['pay_back'] : $shop_request_items['pay_back']);?>" id="fm5" /></td>
											</tr> -->
											<tr>
												<th valign="top">備考(店舗用):</th>
												<td><textarea name="memo_shop" class="form-textarea3"><?php echo TA_Cook($customer_memo['memo_shop'] ? $customer_memo['memo_shop'] : $_POST['memo_shop']) ;?></textarea></td>
											</tr>
											<!-- <tr>
												<th valign="top">CC依頼事項:</th>
												<td><select name="cc_request" class="styledselect_form_3"><?php Reset_Select_Key( $gCCRequest , ($_POST['cc_request'] ? $_POST['cc_request'] : $cc_request) );?></select></td>
											</tr>
											<tr>
												<th valign="top">返金額(CC):</th>
												<td><input class="inp-form" name="pay_back_cc" type="text" value="<?php echo ($_POST['pay_back_cc'] ? $_POST['pay_back_cc'] : $cc_request_items['pay_back_cc']);?>" id="fm5" /></td>
											</tr> -->
											<!--<tr>
												<th valign="top">引落ストップ依頼:</th>
												<td class="noheight">
													<table border="0" cellpadding="0" cellspacing="0">
														<tr  valign="top">
															<td><input  class="inp-form" name="stop_request_date" type="text" id="day2" value="<?php echo $stop_request_date ;?>" readonly /></td>
														</tr>
													</table>
												</td>
												<td></td>
											</tr>-->
											<!-- <tr>
												<th valign="top">(月額)最終来店月:</th>
												<td>
													<input style="width:55px;height:21px;" name="last_visit_ym" type="text" id="last_visit_ym" class="ympicker" value="<?php echo $last_visit_ym ;?>" />
												</td>
											</tr>
											<?php if($last_visit_ym){ ?>
											<tr>
												<th valign="top">(月額)最終引落月:</th>
												<td>
													<input type="text" style="width:55px;height:21px;" id="last_debit_ym" value="<?php echo date('Y/m',strtotime('-2 month',strtotime($last_visit_ym.'/1'))) ;?>" readonly />
												</td>
											</tr>
											<?php } ?> -->
											<tr>
												<th valign="top">備考(CC用):</th>
											  <?php if($authority_level<=6 || ($authority['id']==106 || $authority['id']==1449)){ ?>
												<td><textarea name="memo_cc" class="form-textarea3" ><?php echo TA_Cook($customer_memo['memo_cc'] ? $customer_memo['memo_cc'] : $_POST['memo_cc']) ;?></textarea></td>
												<?php }else{?>
													<td><textarea class="form-textarea3" disabled ><?php echo TA_Cook($customer_memo['memo_cc'] ? $customer_memo['memo_cc'] : $_POST['memo_cc']) ;?></textarea></td>
													<input type="hidden" name="memo_cc" value="<?php echo TA_Cook($customer_memo['memo_cc'] ? $customer_memo['memo_cc'] : $_POST['memo_cc']) ;?>" />
											  <?php } ?>
											</tr>
											<tr>
												<th valign="top">備考(ローン関連):</th>
											  <?php if($authority_level<=6){ ?>
												<td><textarea name="memo_loan" class="form-textarea3" ><?php echo TA_Cook($customer_memo['memo_loan'] ? $customer_memo['memo_loan'] : $_POST['memo_loan']) ;?></textarea></td>
												<?php }else{?>
													<td><textarea class="form-textarea3" disabled ><?php echo TA_Cook($customer_memo['memo_loan'] ? $customer_memo['memo_loan'] : $_POST['memo_loan']) ;?></textarea></td>
													<input type="hidden" name="memo_loan" value="<?php echo TA_Cook($customer_memo['memo_loan'] ? $customer_memo['memo_loan'] : $_POST['memo_loan']) ;?>" />
											  <?php } ?>
											</tr>
											<tr>
												<th valign="top">備考(本社用):</th>
											  <?php if($authority_level<=6){ ?>
												<td><textarea name="memo_head_office" class="form-textarea3" ><?php echo TA_Cook($customer_memo['memo_head_office'] ? $customer_memo['memo_head_office'] : $_POST['memo_head_office']) ;?></textarea></td>
												<?php }else{?>
													<td><textarea class="form-textarea3" disabled ><?php echo TA_Cook($customer_memo['memo_head_office'] ? $customer_memo['memo_head_office'] : $_POST['memo_head_office']) ;?></textarea></td>
													<input type="hidden" name="memo_head_office" value="<?php echo TA_Cook($customer_memo['memo_head_office'] ? $customer_memo['memo_head_office'] : $_POST['memo_head_office']) ;?>" />
											  <?php } ?>
											</tr>

											<?php if($introducer_memo){ ?>
											<tr class="attention_item_o">
												<th valign="top">備考(友達紹介用):</th>
											  	<td><textarea class="form-textarea3" disabled ><?php echo TA_Cook($introducer_memo) ;?></textarea></td>
											</tr>
											<?php } else if($ad_memo){ ?>
											<tr>
												<th valign="top">備考(広告用):</th>
											  	<td><textarea class="form-textarea3" disabled ><?php echo TA_Cook($ad_memo) ;?></textarea></td>
											</tr>
											<?php } ?>
											<tr>
												<th valign="top">登録日時</th>
												<td><?php echo TA_Cook($data['reg_date']) ;?></td>
											</tr>
											<tr>
												<th>&nbsp;</th>
												<td valign="top">
													<input type="submit" value="" class="form-submit" onclick="submit_check()" />
													<input type="reset" value="" class="form-reset" />
												</td>

											</tr>
										</table>
									</td>
									<td valign="top">
										<?php if($authority_level<20){?>
											<!-- <div style="padding-left:20px;"><iframe src="../main/shift_d2.php?shop_id=<?php echo $_POST['shop_id']?>&hope_date=<?php echo $_POST['hope_date']?>" height="59" width="100%"></iframe></div> -->
										<?php }?>
										<div class="center_box" style="height:80vh;max-height: 850px;"><object data="../main/vacant_room.php?type=reservation&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>&hope_date=<?php echo $_POST['hope_date'] ? $_POST['hope_date'] : $data['hope_date']; ?>&customer_id=<?php echo $customer["id"]; ?>" type="text/html" class="reservation_object" ></object></div>
										<?php if( $course['type']){?>
										<div class="center_box"><iframe src="../reservation/pay_monthly.php?id=<?php echo ($_POST['reservation_id'] ? $_POST['reservation_id'] : $data['id']);?>&customer_id=<?php echo $customer["id"];?>&contract_id=<?php echo $contract['id'];?>&hope_date=<?php echo $_POST['hope_date'] ? $_POST['hope_date'] : $data['hope_date']?>" scrolling=yes height=930 width=500></iframe></div>
										<?php }?>
									</td>
									<!-- end id-form  -->
								<!--右サイト-->
								<?php if($_POST['reservation_id'] || $_POST['customer_id'] || $_POST['contract_id']){?>
										<td class="edit_right">
										<!--  start related-activities -->
										<div id="related-activities">
											<!--  start related-act-top -->
											<div id="related-act-top">
												<div class="title"><a href="../customer/edit.php?customer_id=<?php echo $customer['id']?>" class="side_title" title="顧客詳細へ"><?php echo $customer['name_kana'] ? $customer['name_kana'] : ($customer['name'] ? $customer['name'] : "無名")?></a></div>
											</div>
											<!-- end related-act-top -->

											<!--  start related-act-bottom -->
											<div id="related-act-bottom">
												<!--  start related-act-inner -->
												<div id="related-act-inner">
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>会員番号 : <?php echo $customer['no']?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<!--<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>名前 : <?php echo ($customer['name'] ? $customer['name'] : $customer['name_kana']);?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>-->

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>会員タイプ : <?php echo TA_Cook($gCustomerType[$customer['ctype']])?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>年齢 : <?php  echo ($customer['birthday']!='0000-00-00' && $customer['birthday']!='') ? floor((date('Ymd')-str_replace('-','',$customer['birthday']))/10000)."歳" : "歳";?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

                                                    <div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>生年月日 : <?php  echo ($customer['birthday']!='0000-00-00' && $customer['birthday']!='') ? str_replace('-', '/', $customer['birthday']) : ""?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<?php if($age>0 && $age<20){?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5 <?php echo $customer['agree_status'] ? "" : "style='color:red;'";?>>親権者同意書 : <?php echo $gAgreeStatus[$customer['agree_status']];?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>

													<?php if($contract['payment_loan'] ){?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5 <?php echo $customer['attorney_status'] ? "" : "style='color:red;'";?>>委任状 : <?php echo $gAttorneyStatus[$customer['attorney_status']];?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>

													<?php if($customer['hopes_discount'] ){?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5 <?php echo $customer['student_id'] ? "" : "style='color:red;'";?>>学生証明 : <?php echo $gStudentID[$customer['student_id']];?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>
												<?php if($authority_level<=6 || ($authority['id']==106 || $authority['id']==1449) && $customer['ctype']<2){?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>電話番号 : <?php echo str_replace('-' , '' , $customer['tel']) ?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>メールアドレス : <?php echo $customer['mail']?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
												<?php } ?>
													<?php if( $contract['id']){
														//同じ日でカウンセリング、ローン取消、解約の場合に対応
														$contract_status = ($new_contract['status']) ? $new_contract['status'] : $contract['status'];
													?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>


													<div class="right">
														<h5>契約状況 : <?php echo ($course_type[$contract['course_id']] ? $gContractStatus3[$contract['status']] : $gContractStatus[$contract['status']])?>
															<?php if(isset($contract['id']) && $course_type[$contract['course_id']]==0){
																	// ルクサコース契約の場合、終了日の表示を変更する 2017/07/19 add by shimada
																	if(strpos($course_list[$contract['course_id']],'ルクサ') !== false){
																		$contract['end_date'] = '2017-09-30';
																	}
																	echo '<br />('.str_replace('-', '/', $contract['contract_date']).'～'.str_replace('-', '/', $contract['end_date']).')';
																  }  ?>
														</h5>
														<!-- 月額休会 -->
														<?php if ($monthly_pause_flg) { ?>
															<h5><?php echo '(休会開始:'.date('Y/m～',strtotime($monthly_pause['pause_start_date'])).')';?></h5>
														<!-- 月額で、未成年プランの場合表示させる -->
                                                        <?php } elseif ($course_type[$contract['course_id']] && $course['minor_plan_flg']) { ?>
                                                            <h6><?php echo '(施術可能期間 :<br />'.$start_term_month.','.$start_term_month_next.'～'.$end_term_month_prev.','.$end_term_month.')';?></h6>
                                                        <!-- 月額で施術開始予定年月が入っている場合表示させる 2016/10/19 add by shimada-->
                                                        <?php } elseif ($course_type[$contract['course_id']] && $contract['start_ym']) {?>
                                                            <h5><?php echo '(施術開始:'.date('Y/m～',strtotime($contract['start_ym'].'01')).')';?></h5>
                                                        <?php } ?>
                                                        <!-- 月額以外で2019-11-06以降販売開始の新プラン(SPプランは除く) -->
                                                        <?php if($course['interval_date'] != null && $course['sales_start_date'] >= '2019-11-06' && $course['zero_flg'] != "1") {
                                                            echo '<h5>(' . str_replace('-', '/', $guarantee_start_date) . '～' . str_replace('-', '/', $guarantee_last_date) . ')</h5>';
                                                        } ?>
                                                    </div>

													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right<?php echo $course_new_flg[$contract['course_id']]==1 ? ' new_monthly' : '' ?>"><h5>契約コース : <br /><?php echo $course_list[$contract['course_id']]?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>
													<?php if ($edit_reservation_flg) { ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5><a href="../reservation/edit_reservation.php<?php echo $edit_reservation_param; ?>" class="side">予約紐付け変更</a></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php }?>
													<?php if( $customer['introducer']){ ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>紹介者 : <?php echo $customer['introducer']?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>

													<?php if( $customer['introducer_type']){ ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>紹介者タイプ : <?php echo $gIntroducerType[$customer['introducer_type']]?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>

													<?php if( $customer['special']){ ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>特別紹介者 : <?php echo $special_list[$customer['special']]?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>

													<?php if( $contract['id'] && $data['type']<>1 && $data['type']<>32){ ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>消化(来店)回数 : <?php echo $contract['r_times']?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>

													<?php if( $contract['id'] && $contract['payment_loan']>0 && $contract['status']<> 2 && $contract['status']<>3 && $contract['status']<>6){ ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
														<!--ローン不備ステータス時のデザイン変更-->
														<div class="right <?php if($contract['loan_status']==5)echo 'attention_item_o'; ?>"><h5>ローン : <?php echo number_format($contract['payment_loan'])?>(<?php echo $gLoanStatus[$contract['loan_status']];?>)</h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>

												<?php if( $contract['balance']){ ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<?php if( $contract_status<>7){ ?>
													<div class="right"><h5 style="color:red;">売掛金 : ￥<?php echo number_format($contract['balance'])?></h5></div>
													<?php }else{ ?>
													<div class="right"><h5 style="color:red;">売掛金 : ￥(<?php echo number_format($contract['balance'])?>)</h5></div>
													<?php } ?>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
												<?php } ?>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<!--契約テーブル上の担当が優先,データ整理後の本番反映-->
													<!--<div class="right"><h5>カウンセリング担当 : <?php echo  $contract['staff_id'] ? $staff_list[$contract['staff_id']] : $staff_list[$data['cstaff_id']] ;?></h5></div>-->
													<div class="right"><h5>カウンセリング担当 : <?php echo  $data['cstaff_id'] ? $staff_list[$data['cstaff_id']] : $staff_list[$contract['staff_id']] ;?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>登録日時 : <?php echo ( $customer['reg_date'] );?></h5></div>
													<div class="clear"></div>
													<!--<div class="lines-dotted-short"></div>


											</div><!-- end related-act-inner -->
												<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->


										<!--  start related-activities -->
										<div id="related-activities">
											<!--  start related-act-top -->
											<div id="related-act-top">
												<!--<img src="../images/forms/header_related_act.gif" width="271" height="43" alt="" />-->
												<div class="title">関連書類</div>
											</div>
											<!-- end related-act-top -->
											<!--  start related-act-bottom -->
											<div id="related-act-bottom">
												<!--  start related-act-inner -->
												<div id="related-act-inner">
											<?php if($authority_level<22){?>
													<?php if($customer['id']<>184122){ ?>
													<div class="left"><a href="javascript:void(0)"><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
													<!--<div class="right"><h5><a href="../customer/sheet.php?customer_id=<?php echo $customer['id']?>" class="side" target="_blank">問診表</a></h5></div>-->
													<div class="right"><h5><a href="#" class="side" onclick="window.open('../customer/sheet.php?customer_id=<?php echo $customer['id']?>&reservation_id=<?php echo $data['id']?>', '_blank');window.open(window.location, '_self').close();">問診表</a><?php if( Get_Table_Col("sheet","id"," WHERE del_flg=0 and customer_id=".$customer['id']) ) echo "(済)"; ?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5><a href="karte_c.php?customer_id=<?php echo $customer['id']?>" class="side">カウンセリングカルテ</a><?php if( Get_Table_Col("karte_c","id"," WHERE del_flg=0 and customer_id=".$customer['id']) ) echo "(済)"; ?></h5></div>
													<div class="clear"></div>

												<?php if( $data['type']>=2 && $data['type']<>32 && $data['type']<>33){ ?>
													<div class="lines-dotted-short"></div>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5><a href="karte.php?reservation_id=<?php echo $_POST['reservation_id']?>" class="side">トリートメントカルテ</a><?php if($karte['id'])echo "(済)"?></h5></div>
													<div class="clear"></div>

												<?php } ?>
											<?php } ?>

													<div class="clear"></div>

												</div><!-- end related-act-inner -->
												<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->
										<?php //if($data['customer_id'] && $authority_level<=1){?>
										<?php if($data['customer_id'] && $_POST['reservation_id']){?>
										<!--  start related-activities -->
										<div id="related-activities">
											<!--  start related-act-top -->
											<div id="related-act-top">
												<!--<img src="../images/forms/header_related_act.gif" width="271" height="43" alt="" />-->
												<div class="title">店舗処理</div>
											</div>
											<!-- end related-act-top -->
											<!--  start related-act-bottom -->
											<div id="related-act-bottom">
												<!--  start related-act-inner -->
												<div id="related-act-inner">
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right">
														<!-- 当て漏れ、繰り越し レジ清算できない-->
														<?php if($data['type']==30 || $data['type']==31 || $data['type']==10){?>
															<h5>レジ精算</h5>
															区分：当て漏れ、繰り越し、プラン変更 はレジ精算<br>することができません。
														<?php } else {?>
															<h5>
																<!--来店前また来店なし,未契約が非表示-->
																<?php
																	if($authority_level<=6 || (($authority['id']==106 || $authority['id']==1449) && $data['type']<>10 && $data['status']<>1 && $data['status']<>2) || $data['hope_date']<=date('Y-m-d') ){
																 		if($data['type']==1 || $data['type']==32 || $data['type']==33){?>
																		<a href="../account/reg_detail.php?reservation_id=<?php echo $data['id'];?>" class="side">レジ精算</a><?php if($data['reg_flg'])echo "(済)"?>
																	<?php }else{ ?>
																		<a href="../service/detail.php?reservation_id=<?php echo $data['id'];?>" class="side">レジ精算</a><?php if($data['sales_id'])echo "(済)"?><?php if($sales['r_times'])echo "、役務消化(済)"?>
																<?php } } ?>
															</h5>
															初回入金、役務消化、売掛回収等
														<?php  } ?>
													</div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<!--プラン変更-->
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<?php if($data['type']!=10){ ?>
													<div class="right"><h5>プラン変更</h5><font size=-2>※区分がプラン変更以外の場合はプラン変更することができません。</font></div>
													<?php }elseif(empty($contract['id'])){ ?>
													<div class="right"><h5>プラン変更</h5><font size=-2>※変更可能な契約情報がありません。</font></div>
													<?php }else if($contract['status'] != 0 && $contract['status'] != 7){ ?>
													<div class="right"><h5>プラン変更</h5><font size=-2>※終了、解約者はプラン変更できません</font></div>
													<?php }else if(999 == $contract['course_id']){ ?>
												  	<div class="right"><h5>プラン変更</h5><font size=-2>※特別補償コースはプラン変更できません</font></div>
													<?php }else if(1000 < $contract['course_id']){ ?>
													<div class="right"><h5>プラン変更</h5><font size=-2>※返金保証が終了したコースはプラン変更できません</font></div>
													<?php }else if($contract['conversion_flg']==1 ){ ?>
													<div class="right"><h5>プラン変更</h5><font size=-2>※プラン組替済みです。</font></div>
													<?php }else if($course['type'] == 0 && $contract['r_times'] >= $contract['times']){ ?>
													<div class="right"><h5>プラン変更</h5><font size=-2>※消化回数がプラン回数以上のためプラン変更できません。</font></div>
													<?php }else if($course['interval_date'] != null && $course['sales_start_date'] >= '2019-11-06' && $data['hope_date'] > $contract['end_date']){ ?>
													<div class="right"><h5>プラン変更</h5><font size=-2>※返金保障期間外のためプラン変更できません</font></div>
													<?php }else if($cooling_off_flg){ ?>
													<div class="right"><h5>プラン変更</h5><font size=-2>※一度クーリングオフをするとプラン変更できません。</font></div>
													<?php }else{?>
													<div class="right"><h5><a href="../service/change.php?contract_id=<?php echo $contract['id'];?>&shop_id=<?php echo $data['shop_id'];?>"　onclick="return confirm('プラン変更をしますか？')" class="side">プラン変更</a></h5></div>
													<?php }	?>
													<div class="clear"></div>

													<!--プラン組換-->
													<div class="lines-dotted-short"></div>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<?php if($data['type']!=10){ ?>
													<div class="right"><h5>プラン組替</h5><font size=-2>※区分がプラン変更以外の場合はプラン組替することができません。</font></div>
													<?php }elseif($course['type'] == 1 || empty($contract['id']) || $contract['course_id'] >= 999 || ($course['type'] == 0 && $contract['r_times'] >= $contract['times']) || ($course['interval_date'] != null && $course['sales_start_date'] >= '2019-11-06' && $data['hope_date'] > $contract['end_date']) || ($contract['status'] != 0 && $contract['status'] != 7)){?>
													<div class="right"><h5>プラン組替</h5><font size=-2>※組替可能な契約情報がありません。</font></div>
													<?php } elseif($cooling_off_flg){?>
												  	<div class="right"><h5>プラン組替</h5><font size=-2>※一度クーリングオフをするとプラン組替できません。</font></div>
													<?php } else {?>
													<div class="right"><h5><a href="../service/conversion.php?contract_id=<?php echo $contract['id'];?>&shop_id=<?php echo $data['shop_id'];?>"　onclick="return confirm('プラン組替をしますか？')" class="side">プラン組替</a></a></h5></div>
													<?php } ?>
													<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->

										<?php if($loan_info_id){?>
										<!--  start related-activities -->
										<div id="related-activities">
											<!--  start related-act-top -->
											<div id="related-act-top">
												<div class="title">オンラインベリファイ</div>
											</div>
											<!-- end related-act-top -->
											<!--  start related-act-bottom -->
											<div id="related-act-bottom">
												<!--  start related-act-inner -->
												<div id="related-act-inner">
													<p>
													  <?php if($authority_level==0){ ?>
														<select name="verify_status" class="styledselect_form_3"><?php Reset_Select_Key( $gVerifyStatus , ($_POST['verify_status'] ? $_POST['verify_status'] : $loan_verify_status) );?></select>
													  <?php }else{ ?>
														<?php echo TA_Cook($gVerifyStatus[$loan_verify_status]) ;?>
													  <?php } ?>
													</p>
													<div class="clear"></div>
												</div><!-- end related-act-inner -->
												<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->
										<?php } ?>

										<?php } ?>
									</td>
								<?php } ?>
							</tr>
						</table>
						</form>
					</div>
					<!--  end content-table-inner  -->
		</div>
	</div>
	<!--  end content -->
</div>
<!--  end content-outer -->
<!-- <div id="loading_box">
  <div id="loading">
    <div class="cssload-loading">
      <i></i>
      <i></i>
      <i></i>
      <i></i>
    </div>
  </div>
</div> -->
<script type="text/javascript">
new AutoKana('Name', 'NameKana', {katakana: true, toggle: false});
	new AutoKana('FirstName', 'FirstNameKana', {katakana: true, toggle: false});
	new AutoKana('LastName', 'LastNameKana', {katakana: true, toggle: false});
  function room_load(form_id,target_name){
    var $target,loading;
      $target = $("[name='" + target_name + "']");
      // loading = document.getElementById("loading_box");
      // loading.style.display = "block";
      var form,data1,full_url;
      form = document.getElementById(form_id),
      data1 = new FormData(form);
      $.ajax({
        url:"../library/main/vacant_room.php",
        type:"post",
        dataType:"html",
        data:data1,
        processData: false,
        contentType: false
      }).done(function(response){
        // loading.style.display = "none";
        $target.html(response);
      }).fail(function(){
        // loading.style.display = "none";
        $target.html("読み込みに失敗しました。");
      })
  }
	$("#shop_id").on("change",function(){
		room_load('form1','room_id');
	});
 //カウンセリングアンケートセレクトボックス風表示
	$("#c_q_inner1").on('click',function() {
		$(".c_q_inner").slideToggle("fast");
	});
	function select_jump(target){
		target.preventDefault();
		window.open(this.href, '_blank');
		window.open(window.location, '_self').close();
	}
	$(".select_jump").on('click',select_jump);

</script>
<?php include_once("../include/footer.html");?>
