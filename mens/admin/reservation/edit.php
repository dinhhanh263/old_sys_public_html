<?php include_once("../library/reservation/edit.php");?>
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
      document.search.action = "csv_detail.php";
	  document.search.submit();
	  return fales;
  	}else{
    	return false;
  	}
}
</script>
<script type="text/javascript">
// ローンステータスが5.ローン不備 だった場合ポップアップ確認を表示させる add by 2016/08/30 shimada
var loan_status = <?php echo $contract['loan_status'] ? $contract['loan_status'] : 3; ?>;
var loan_delay = <?php echo $customer['loan_delay_flg']>0 ? $customer['loan_delay_flg'] : 0; ?>;
if(loan_status ==5){
	confirm('ローン不備があります。確認してください。');
}
if(loan_delay >0 && loan_delay !=11){
	confirm('ローン延滞しています。確認してください。');
}else if(loan_delay ==11){
	confirm('サクシードで自動解約になっています。確認してください。');
}
</script>
<!-- start content-outer -->
<div id="content-outer" <?php if($contract['loan_status']==5){
								echo "class='atenntion_y';";
							}elseif($customer['loan_delay_flg']==11){
								echo "class='atenntion_o';";
							}elseif($customer['loan_delay_flg']<>0){
								echo "class='atenntion_b';";
							} else {
								echo "";
							}?>>
	<!-- start content -->
	<div id="content">
		<div id="page-heading">
			<h1>
				予約詳細（<a rel="facebox" href="../customer/mini.php?id=<?php echo $customer['id'];?>">予約履歴</a>,
						<a href="../account/?customer_id=<?php echo $customer['id'];?>" target="_blank">売上詳細</a>,
						<a href="../account/remain.php?customer_id=<?php echo $customer['id'];?>" target="_blank">消化詳細</a>）
						<a class="button register_btn" href="../sales/register.php?customer_id=<?php echo $customer['id'];?>&hope_date=<?php echo $data['hope_date'] ?>" target="_blank">物販レジへ</a>&nbsp;
				<?php if($authority_level<=1){?>
			<span style="float:right; margin-right:25px;">
				<input type='button' value='　CSV　' onclick='csv_export();' style="height:25px;" />
			</span>
			<?php  }?>
			<input name="id" type="hidden" value="<?php echo $data['id'];?>" />
			</form>
				<span style="float:right;margin-right:25px;">
					<a href="./edit.php?mode=new_rsv&customer_id=<?php echo $customer['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>" onclick="return confirm('次の予約をしますか？')" class="side" title="次の予約" >次の予約へ</a>
				</span>
			</h1>
		</div><!--予約新規?次回予約新規?予約詳細?顧客新規以外顧客情報を右側に-->
	<!-- start id-form -->
	<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
		<input type="hidden" name="action" value="edit" />
		<input type="hidden" name="mode" value="<?php echo $_POST["mode"];?>" />
		<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
		<input type="hidden" name="customer_id" value="<?php echo $customer["id"];?>" />
		<input type="hidden" name="contract_id" value="<?php echo $contract["id"];?>" />
		<input type="hidden" name="course_id" value="<?php echo $contract["course_id"];?>" />
		<input type="hidden" name="from_cc" value="<?php echo $_POST["from_cc"];?>" />
			<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
				<tr>
					<td colspan="3">
						<div class="today_treatment">
						    <?php $contract_data_id = ""; ?>
							<!-- プラン変更データなし -->
							<?php foreach ($all_contract as $key => $contract_data): ?>
								<!-- プラン変更以外 -->
								<?php //if( $contract_data['status']<>4 ) { ?>
							<span class="c_course<?php if($data['type']==20 && $contract_data['times']==1 && $contract_data['r_times']==0)echo ' salon-foget';?>">
								<?php $ends = explode("-",$contract_data['end_date']);
											$ends = ($ends[0]*10000) + ($ends[1]*100) + ($ends[2]);//end_dateの日付を20001010の形にする
											$todays = (date('Y')*10000) + (date('m')*100) + (date('d'));//今日の日付を20001010の形にする
											if($ends <> 0  && $ends < $todays){ //end_dateが今日の日付より小さかったら
								?>
									<span class="warning">！この契約は有効期限が切れています！</span>
								<?php } ?>
										<!-- 消化テーブルのデータ参照 -->
										<?php // 契約状態・消化回数過去分情報取得
											if($data['hope_date'])$r_times_where = "and (pay_date <= '".addslashes($data['hope_date'])."')"; // 予約日が決定していなければ過去データを参照しない
											$r_times_data = Get_Table_Row("r_times_history"," WHERE del_flg=0 ". $r_times_where . " and contract_id = '".addslashes($contract_data['id'])."' ORDER BY pay_date DESC, id DESC");//消化データ
											if(1000<$contract_data['course_id'])$r_times_contract_data = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id ='".addslashes($contract_data['customer_id'])."' and course_id= '".addslashes($contract_data['course_id'])."'");//PREMIUM(返金保証回数終了コース)

											// 消化データの表示(消化テーブルにデータがないとき、初期表示をする)
											if(!$r_times_data || is_null($r_times_data)){
												// PREMIUM(返金保証回数終了コース)以外のコースは初期に0をセット
												// PREMIUM(返金保証回数終了コース)は旧コースの消化回数を表示
												if($contract_data['course_id']<=1000){
													//$r_times_data['status'] =0;  // 契約中(初期状態)
												$r_times_data['r_times'] =0; // 消化回数なし
													$r_times = $r_times_data['r_times'].' / '.$contract_data['times'];
												} else {
													//$r_times_data['status'] =0;  // 契約中(初期状態)
													$r_times =$r_times_contract_data['r_times']; // 旧コースの消化回数(契約テーブルのデータ)
												}
											} else {
												// PREMIUM(返金保証回数終了コース)以外のコースは分数で消化回数を表示
												// PREMIUM(返金保証回数終了コース)は消化回数のみ表示
												if($contract_data['course_id']<=1000){
													$r_times = $r_times_data['r_times'].' / '.$contract_data['times'];
												} else {
													$r_times = $r_times_data['r_times'];
												}
											}
											if($_GET['mode']=="new_rsv"){ // 次の予約時
												$r_times_data['status'] =$contract_data['status'];  // 契約中(現在の状態)
												$r_times_data['r_times'] =$contract_data['r_times']; // 消化回数(現在の回数)
											}
											// 役務提供期間（終了日）設定(PREMIUMコース時は設定しない)
											$end_date = date('y/n/j', strtotime($contract_data['end_date']));
											if(1000<$contract_data['course_id'])$end_date = ""; // PREMIUM(返金保証回数終了)
											// if($contract_data['course_id']>=52 && $contract_data['course_id']<=57)$end_date = ""; // PREMIUM
											// if($contract_data['course_id']>=59 && $contract_data['course_id']<=66)$end_date = ""; // 優待券
										?>
									<span class="course_cont1">契約番号:</span>
									<span class="course_cont2"><?php echo $contract_data['pid'];?></span>
									<?php if($contract_data['course_id']!=1010){ ?>
										<span class="course_cont1">役務提供期間:</span>
										<span class="course_cont2"><?php echo date('y/n/j', strtotime($contract_data['contract_date']));?>-<?php echo $end_date;?></span>
									<?php } ?>
									<span class="course_cont1">契約コース:</span>
									<span class="course_cont2"><input type="hidden" value="<?php echo $data['course_id'];?>"><?php echo $course_list[$contract_data['course_id']];?></span>
										<span class="course_cont1">契約状態:</span>
										<span class="course_cont2"><input type="hidden" value="<?php echo $data['status'];?>"><?php echo $gContractStatus[$contract_data['status']];?></span>
									<span class="course_cont1">消化回数:</span>
										<span class="course_cont2"><?php echo $r_times;?></span>
									<!-- 施術部位がある場合 -->
									<?php if($contract_data['contract_part']<>""){?>
										<span class="course_cont1">施術部位:</span>
										<span class="course_cont2">
											<?php $contract_data['contract_part'] = explode(",", $contract_data['contract_part']);?>
											<?php foreach ($contract_data['contract_part'] as $key => $part): ?>
												<?php
													echo $gContractParts[$part];
													if ($part <> end($contract_data['contract_part'])) { echo ',';}
												?>
											<?php endforeach; ?>
										</span>
									<?php } ?>
									<span class="course_cont1">施術規定時間:</span>
									<span class="course_cont2"><span class="one_time"><?php echo $contract_data['part_time_sum'];?></span>分</span>
								<!-- 単発で本日消化していない -->
								<?php if($data['type']==20 && $contract_data['times']==1 && $contract_data['r_times']==0){?>
									<span class="warning">本日消化していません</span>
								<?php } ?>
								<!-- 「次の予約」でカウンセリング ではない -->
								<?php if($data['type']<>1 ){?>
									<span class="course_cont1">予約する:</span>
									<span class="course_cont2">
											<!-- 契約中か契約待ちのステータスの時のみ、予約するチェックができる -->
											<input type="checkbox" name="multiple_contract_id[]" value="<?php echo $contract_data['id'];?>" <?php echo $checked = (strpos($data['multiple_contract_id'], $contract_data['id']) === FALSE) ? "" : "checked"; ?> <?php echo $disabled = ($contract_data['status']==0 || $contract_data['status']==7) ? "" : "disabled"; ?> class="form-checkbox">
									</span>
								<?php } ?>
							</span>
								<?php //} ?><!-- プラン変更以外 -->
							<?php endforeach; ?><!-- 現在契約しているコースすべて表示 -->
						</div>
					</td>
				</tr>
				<!-- カウンセリング/売掛支払ではない、「次の予約」で$_POST['customer_id']があるときだけ所要時間を表示する -->
				<?php if($data['type']<>1 && $data['type']<>7 && $_POST['customer_id']<>0){?>
				<tr>
					<td class="total_time">施術時間合計：
						<span class="under_line"></span>分<!-- 施術時間合計エリア -->
							<script type="text/javascript">
								$(".form-checkbox").on('change',function() {
									var retime,times;
									retime = 0;
									times = $(".form-checkbox:checked").parent().prev().prev().children('.one_time');
									for(var i=0; i<$(".form-checkbox:checked").length; i++){
										retime = retime + parseInt(times.eq(i).text(),10);
									};
									$(".under_line").html(retime);
								});
							</script>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td>
						<span id="time_table_area"></span><!-- 施術時間電卓エリア -->
						<!--  start content-table-inner -->
						<div id="content-table-inner">
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
								<tr valign="top">
									<td class="resev_form">
											<?php echo $gMsg;?>
											<?php echo "<font color='red' size='-1'>".$_REQUEST['gMsg'] ."</font>";?>
											<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
												<tr>
													<th valign="top">区分:</th>
													<td>
														<select name="type" class="styledselect_form_1 type_form guidance"><?php Reset_Select_Key( $gResType4 , $_POST['type'] ? $_POST['type'] : $data['type']);?></select>
														<a href="./one_course_length02.php" target="_blank" onclick="window.open('./one_course_length02.php', '', 'width=350,height=760,scrollbars=yes'); return false;" id="time_table_link" class="<?php if($data['type']<>20)echo('unnecessary') ?>">施術時間電卓</a>
													</td>
												</tr>
												<tr>
													<th valign="top">予約状況:</th>
													<td><select name="rsv_status" class="styledselect_form_3"><?php Reset_Select_Key( $gRsvStatus , $_POST['rsv_status'] ? $_POST['rsv_status'] : $data['rsv_status']);?></select></td>
												</tr>
												<tr>
													<th valign="top">来店状況:</th>
													<td><select name="status" class="styledselect_form_3"><?php Reset_Select_Key( $gBookStatus , $_POST['status'] ? $_POST['status'] : $data['status']);?></select></td>
												</tr>

												<!-- カウンセリング予約時、下記を確認 -->
												<?php if($_POST['id'] && $data['type']<=1){?>
												<tr>
													<th valign="top">確認状況(前日tel):</th>
													<td><select name="preday_status" class="styledselect_form_3"><?php Reset_Select_Key( $gPreDayStatus , $_POST['preday_status'] ? $_POST['preday_status'] : $data['preday_status']);?></select></td>

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
													<th valign="top">担当(予約時tel):</th>
													<td><select name="today_staff_id" class="styledselect_form_3" ><?php Reset_Select_Key( $ccstaff_list , $data['today_staff_id'] );?></select></td>
												</tr>
												<?php } ?>
												<!-- カウンセリング・1回コース当日のとき表示する -->
												<?php if($_POST['id'] && $data['type']==1 || $_POST['id'] && $data['type']==20){?>
												<tr>
													<th valign="top">カウンセリング担当:</th>
													<td><select name="cstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['cstaff_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>
												</tr>
												<?php } ?>
												<tr class="pre_line">
													<th valign="top">店舗:</th>
													<!--<td><select name="shop_id" class="styledselect_form_1" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : ($data['shop_id'] ? $data['shop_id'] : 1));?></select></td>-->
													<td><select name="shop_id" class="styledselect_form_3"><?php Reset_Select_Key( $shop_list , $data['shop_id']  ? $data['shop_id']  : ($_POST['shop_id'] ? $_POST['shop_id'] : ($authority_shop['id'] ? $authority_shop['id'] : 1010)  ));?></select></td>
												</tr>
												<!-- 区分：プラン変更 以外の時表示 プラン変更も部屋を取るため部屋を取る仕様に変更 -->
												<tr class="room_time <?php //if($data['type']==10)echo('unnecessary') ?>">
													<th valign="top">ルーム:</th>
													<td><select name="room_id" class="styledselect_form_3"><?php Reset_Select_Key( $room_list , $data['room_id']);?></select></td>
												</tr>
												<tr>
													<th valign="top">予約日程:</th>
													<td class="noheight">
														<table border="0" cellpadding="0" cellspacing="0">
															<tr  valign="top">
																<td><input  class="inp-form" name="hope_date" type="text" id="day" value="<?php echo $data['hope_date'];?>" placeholder="<?php echo date('Y-m-d');?>" readonly /></td>
															</tr>
														</table>
													</td>
													<td></td>
												</tr>
												<tr>
													<th valign="top">予約時間:</th>
													<td>
														<select size="1" name="hope_time" class="styledselect_form_3"><?php Reset_Select_Key( $gTime  , $data['hope_time']);?></select>
													</td>
												</tr>
												<tr class="pre_line">
													<th valign="top">人数:</th>
													<td><select name="persons" class="styledselect_form_3"><?php Reset_Select_Key( $gPersons , $data['persons']);?></select></td>
												</tr>
											<!-- 区分：プラン変更 以外の時表示 -->
												<tr class="room_time <?php if($data['type']==10)echo('unnecessary') ?>">
												<th valign="top">所要時間:</th>
												<td><select name="length" class="styledselect_form_1"><?php Reset_Select_Key( $gLength , $data['length'] ? $data['length'] : 2);?></select></td>
												</tr>
											<?php if($customer['big_flg']){?>
													<th valign="top">BIG:</th>
													<td><?php echo $gBig[$customer['big_flg']] ;?></td>
											<?php } ?>

											<?php if(!$_POST['id'] && !$_POST['customer_id']){?>
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
<!-- 												<tr>
													<th valign="top">月額（HP）:</th>
														<td><select name="hp_flg" class="styledselect_form_3"><?php Reset_Select_Key( $gHP , $data['hp_flg']);?></select></td>
												</tr> -->
												<tr>
													<th valign="top">広告番号:</th>
															<td><select name="flyer_no" class="styledselect_form_3"><option>-</option><?php Reset_Select_Key( $flyer_no_list , $data['flyer_no']);?></select></td>
												</tr>
												<tr>
													<th valign="top">HP利用クーポン:</th>
															<td><select name="coupon" class="styledselect_form_3"><option>-</option><?php Reset_Select_Key( $coupon_list , $data['coupon']);?></select></td>
												</tr>
												<tr>
													<th valign="top">連絡希望時間帯:</th>
													<td><input  class="inp-form" name="hope_time_range" type="text" value="<?php echo $data['hope_time_range'];?>" readonly /></td>
												</tr>
												<tr>
													<th valign="top">キャンペーン希望:</th>
													<td><select name="hope_campaign_checked" class="styledselect_form_3"><?php Reset_Select_Key( $gHopeTrial , $data['hope_campaign_checked']);?></select></td>
												</tr>
												<tr>
													<th valign="top">キャンペーン特典:</th>
													<td><select name="hope_campaign" class="styledselect_form_3"><?php Reset_Select_Name( $gHopeCapaign , ($data['hope_campaign'] ? $data['hope_campaign'] : (!$_POST['id'] && $_POST['mode']<>"new_rsv" ? "ハンド脱毛" : "") ));?></select></td>
												</tr>
												<tr>
													<th valign="top">学割プラン:</th>
													<td><select name="hopes_discount" class="styledselect_form_3"><?php Reset_Select_Key( $gHopesDiscount , $data['hopes_discount']);?></select></td>

												</tr>

											<!-- トリートメント・1回コース当日のみ表示する -->
											<?php if($data['type']==2 || $data['type']==20 ){?>
												<tr>
													<th valign="top">施術主担当:</th>

													<td><select name="tstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['tstaff_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>
												</tr>
												<tr>
													<th valign="top">施術サブ担当1:</th>

													<td><select name="tstaff_sub1_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['tstaff_sub1_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>
												</tr>
												<tr>
													<th valign="top">施術サブ担当2:</th>
													<td><select name="tstaff_sub2_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['tstaff_sub2_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>
												</tr>
											<?php } ?>
												<tr>
													<th valign="top">店舗受付担当:</th>
													<td><select name="staff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getStafflistArray("staff","shop_id",$data['hope_date'] ) , $data['staff_id'],getDatalist5("shop",$_POST['shop_id']));?></select></td>
												</tr>
												<tr>
													<th valign="top">CC受付担当:</th>
													<td><select name="ccstaff_id" class="styledselect_form_3" ><?php Reset_Select_Key( $ccstaff_list , $data['ccstaff_id'] );?></select></td>
												</tr>
												<tr>
													<th valign="top">予約表記載:</th>
													<td><textarea name="memo2" class="form-textarea3"><?php echo TA_Cook($data['memo2'] ? $data['memo2'] : ($_POST['memo2'] ? $_POST['memo2'] : $pre_rsv['memo2']) ) ;?></textarea></td>
												</tr>
												<tr>
													<th valign="top">備考:</th>
													<td><textarea name="memo" class="form-textarea3"><?php echo TA_Cook( $data['memo'] ? $data['memo'] : ($_POST['memo'] ? $_POST['memo'] : $pre_rsv['memo']) ) ;?></textarea></td>
												</tr>
												<tr>
												<th valign="top">CC依頼事項:</th>
												<td><select name="cc_request" class="styledselect_form_3"><?php Reset_Select_Key( $gCCRequest , ($_POST['cc_request'] ? $_POST['cc_request'] : $data['cc_request']) );?></select></td>
											</tr>
												<tr>
													<th valign="top">備考(CC用):</th>
											  	  <?php if($authority_level<=6 || ($authority['id']==106 || $authority['id']==1449)){ ?>
													<td><textarea name="memo4" class="form-textarea3" ><?php echo TA_Cook($data['memo4'] ? $data['memo4'] : ($_POST['memo4'] ? $_POST['memo4'] : $pre_rsv['memo4'])) ;?></textarea></td>
											      <?php }else{?>
											  		<td><textarea class="form-textarea3" disabled ><?php echo TA_Cook($data['memo4'] ? $data['memo4'] : ($_POST['memo4'] ? $_POST['memo4'] : $pre_rsv['memo4'])) ;?></textarea></td>
											  		<input type="hidden" name="memo4" value="<?php echo TA_Cook($data['memo4'] ? $data['memo4'] : ($_POST['memo4'] ? $_POST['memo4'] : $pre_rsv['memo4'])) ;?>" />
											      <?php } ?>
												</tr>
												<tr>
													<th valign="top">備考(本社用):</th>
												  <?php if($authority_level<=6){ ?>
													<td><textarea name="memo3" class="form-textarea3" ><?php echo TA_Cook($data['memo3'] ? $data['memo3'] : ($_POST['memo3'] ? $_POST['memo3'] : $pre_rsv['memo3'])) ;?></textarea></td>
												  <?php }else{?>
												  	<td><textarea class="form-textarea3" disabled ><?php echo TA_Cook($data['memo3'] ? $data['memo3'] : ($_POST['memo3'] ? $_POST['memo3'] : $pre_rsv['memo3'])) ;?></textarea></td>
												  	<input type="hidden" name="memo3" value="<?php echo TA_Cook($data['memo3'] ? $data['memo3'] : ($_POST['memo3'] ? $_POST['memo3'] : $pre_rsv['memo3'])) ;?>" />
												  <?php } ?>
												</tr>
												<tr>
													<th valign="top">備考(広告用):</th>
													<td><textarea class="form-textarea3" disabled ><?php echo TA_Cook($ad_memo) ;?></textarea></td>
												</tr>
												<tr>
													<th valign="top">登録日時</th>
													<td><?php echo TA_Cook($data['reg_date']) ;?></td>
												</tr>
												<tr>
													<th>&nbsp;</th>
													<td valign="top">
														<input type="submit" value="" class="form-submit" />
														<input type="reset" value="" class="form-reset" />
													</td>
												</tr>
											</table>
									</td>
									<td>
											<div id="shift_min">
												<div><iframe src="../main/mini.php?shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>&hope_date=<?php echo $_POST['hope_date'] ? $_POST['hope_date'] : $data['hope_date']?>" scrolling=no height=650 width="100%"></iframe></div>
												<!-- 月額支払表示 -->
												<!-- <?php if( $course['type']){?>
												<div style="padding-left:20px;"><iframe src="../reservation/pay_monthly.php?id=<?php echo ($_POST['id'] ? $_POST['id'] : $data['id']);?>&customer_id=<?php echo $customer["id"];?>&contract_id=<?php echo $contract['id'];?>&hope_date=<?php echo $_POST['hope_date'] ? $_POST['hope_date'] : $data['hope_date']?>" scrolling=yes height=930 width="100%"></iframe></div>
												<?php }?> -->
											</div>
									</td>
									<!--右サイド-->
									<?php if($_POST['id'] || $_POST['customer_id']){?>
										<td class="edit_right">
											<!--  start related-activities -->
											<div id="related-activities">
												<!--  start related-act-top -->
												<div id="related-act-top">
													<div class="title"><a href="../customer/edit.php?id=<?php echo $customer['id']?>" class="side_title" title="顧客詳細へ"><?php echo $customer['name_kana'] ? $customer['name_kana'] : ($customer['name'] ? $customer['name'] : "無名")?></a></div>
												</div>
												<!-- end related-act-top -->

												<!--  start related-act-bottom -->
												<div class="related-act-bottom">
													<!--  start related-act-inner -->
													<ul class="related-act-inner">
														<li class="right"><h5>会員番号 : <?php echo $customer['no']?></h5></li>
														<li class="right"><h5>会員タイプ : <?php echo TA_Cook($gCustomerType[$customer['ctype']])?></h5></li>
														<li class="right"><h5>年齢 : <?php  echo ($customer['birthday']!='0000-00-00' && $customer['birthday']!='') ? floor((date('Ymd')-str_replace('-','',$customer['birthday']))/10000)."歳" : $customer['age']."歳";?></h5></li>
														<?php if($age>0 && $age<20){?>
														<li class="right"><h5 <?php echo $customer['agree_status'] ? "" : "class='warning'";?>>親権者同意書 : <?php echo $gAgreeStatus[$customer['agree_status']];?></h5></li>
														<?php } ?>
														<?php if($contract['payment_loan'] ){?>
														<li class="right"><h5 <?php echo $customer['attorney_status'] ? "" : "class='warning'";?>>委任状 : <?php echo $gAttorneyStatus[$customer['attorney_status']];?></h5></li>
														<?php } ?>
														<?php if($customer['hopes_discount'] ){?>
														<li class="right"><h5 <?php echo $customer['student_id'] ? "" : "class='warning'";?>>学生証明 : <?php echo $gStudentID[$customer['student_id']];?></h5></li>
														<?php } ?>
														<?php if($authority_level<=6 || $authority['id']==106 && $customer['ctype']<2){?>
															<li class="right"><h5>電話番号 : <?php echo str_replace('-' , '' , $customer['tel']) ?></h5></li>
															<li class="right"><h5>メールアドレス : <?php echo $customer['mail']?></h5></li>
														<?php } ?>
														<?php if( $contract['id']){
															// 2/15 下記仕様が決まってないためいったん保留
															//同じ日でカウンセリング、ローン取消、解約の場合に対応
															//$contract_status = ($new_contract['status']) ? $new_contract['status'] : $contract['status'];
														?>
														<!-- <li class="right"><h5>契約状況 : <?php echo ($course_type[$contract['course_id']] ? $gContractStatus3[$contract['status']] : $gContractStatus[$contract['status']])?></h5></li> -->
														<!-- <li class="right"><h5>契約コース : <br /><?php echo $course_list[$contract['course_id']]?></h5></li> -->
														<?php } ?>
														<?php if( $customer['introducer']){ ?>
														<li class="right"><h5>紹介者 : <?php echo $customer['introducer']?></h5></li>
														<?php } ?>
														<?php if( $customer['introducer_type']){ ?>
														<li class="right"><h5>紹介者タイプ : <?php echo $gIntroducerType[$customer['introducer_type']]?></h5></li>
														<?php } ?>
														<?php if( $customer['special']){ ?>
														<li class="right"><h5>特別紹介者 : <?php echo $special_list[$customer['special']]?></h5></li>
														<?php } ?>
														<?php //if( $contract['id'] && $contract['payment_loan']>0 && $contract['status']<> 2 && $contract['status']<>3 && $contract['status']<>6){ ?>
														<!-- <li class="right"><h5>ローン : <?php echo number_format($contract['payment_loan'])?>(<?php echo $gLoanStatus[$contract['loan_status']];?>)</h5></li> -->
														<?php //} ?>
														<!-- 親契約IDごとのループ -->
														<?php foreach ($all_contract_p_whole as $key => $contract_p_data): ?>
															<!-- ローン支払いがある -->
															<?php if($contract_p_data['payment_loan']>0){ ?>
																<!-- 売掛金が残っている OR ローンがある -->
																<?php if($contract_p_data['balance']<>0 || $contract_p_data['payment_loan']>0){ ?>
																	<?php if( $contract_status<>7){ ?>
																		<li class="right"><h5 class="warning">[ 契約番号 <?php echo $contract_p_data['id']?> ]</li>
																		<?php if( $contract_p_data['id'] && $contract_p_data['payment_loan']>0 && $contract_p_data['status']<> 2 && $contract_p_data['status']<>3 && $contract_p_data['status']<>6){ ?>
																			<!-- ローン非承認/ローン取消 -->
																			<?php if( $contract_p_data['loan_status']==2||$contract_p_data['loan_status']==4){ ?>
																				<li class="right"><h5 class="warning">売掛金 : ￥(<?php echo number_format($contract_p_data['balance'])?>)</h5></li>
																			<?php }else{ ?>
																				<li class="right"><h5 class="warning">売掛金 : ￥<?php echo number_format($contract_p_data['balance'])?></h5></li>
																			<?php } ?>
																		<?php } ?>
																		<li class="right"><h5 class="warning">ローン : <?php echo number_format($contract_p_data['payment_loan'])?>(<?php echo $gLoanStatus[$contract_p_data['loan_status']];?>)</h5></li>
																	<?php } ?>
																<?php } ?>
															<!-- ローン支払いがない & ローンステータスが非承認/取消 -->
															<?php }elseif($contract_p_data['balance']<>0 && $contract_p_data['payment_loan']==0 && ($contract_p_data['loan_status']==2 || $contract_p_data['loan_status']==4) ){ ?>
																<li class="right"><h5 class="warning">[ 契約番号 <?php echo $contract_p_data['id']?> ]</li>
																<li class="right"><h5 class="warning">売掛金 : ￥(<?php echo number_format($contract_p_data['balance'])?>)</h5></li>
															<!-- 売掛金がある -->
															<?php }elseif($contract_p_data['balance']<>0){ ?>
																<li class="right"><h5 class="warning">[ 契約番号 <?php echo $contract_p_data['id']?> ]</li>
																<li class="right"><h5 class="warning">売掛金 : ￥<?php echo number_format($contract_p_data['balance'])?></h5></li>
															<?php } ?>
														<?php endforeach; ?>
														<!--契約テーブル上の担当が優先,データ整理後の本番反映-->
														<!--<li class="right"><h5>カウンセリング担当 : <?php echo  $contract['staff_id'] ? $staff_list[$contract['staff_id']] : $staff_list[$data['cstaff_id']] ;?></h5></li>-->
														<!-- 2016/3/28カウンセリング担当は複数契約があるため、出し方を考える必要がある。機能実装次第、コメントアウトを外す -->
														<!-- <li class="right"><h5>カウンセリング担当 : <?php echo  $data['cstaff_id'] ? $staff_list[$data['cstaff_id']] : $staff_list[$contract['staff_id']] ;?></h5></li> -->
														<li class="right"><h5>登録日時 : <?php echo ( $customer['reg_date'] );?></h5></li>
													</ul><!-- end related-act-inner -->
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
												<div class="related-act-bottom">
													<!--  start related-act-inner -->
													<div class="related-act-inner">
														<div class="left"><a href="javascript:void(0)"><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
														<!--<div class="right"><h5><a href="../customer/sheet.php?customer_id=<?php echo $customer['id']?>" class="side" target="_blank">問診表</a></h5></div>-->
														<div class="right"><h5><a href="#" class="side" onclick="window.open('../customer/sheet.php?customer_id=<?php echo $customer['id']?>', '_blank');window.open(window.location, '_self').close();">問診表</a><?php if( $sheet['id'] ) echo "(済)"; ?></h5></div>
														<div class="clear"></div>
														<div class="lines-dotted-short"></div>

														<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
														<div class="right"><h5><a href="karte_c.php?customer_id=<?php echo $customer['id']?>" class="side">カウンセリングカルテ</a><?php if( $karte_c['id'] ) echo "(済)"; ?></h5></div>
														<div class="clear"></div>

													<!--  トリートメント予約時 -->
													<?php if( $data['type']>=2 ){ ?>
														<div class="lines-dotted-short"></div>
														<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
														<div class="right"><h5><a href="karte.php?reservation_id=<?php echo $_POST['id']?>" class="side">トリートメントカルテ</a><?php if($karte['id'])echo "(済)"?></h5></div>
														<div class="clear"></div>

													<?php } ?>

													<?php //if( ($data['type']==1 || $data['type']==10 ) && $one_course_only_flg ==false){ ?><!-- 区分：カウンセリングまたはプラン変更時のみ -->
													<?php //if( $data['type']==1 || $data['type']==10 ){ ?>
														<div class="lines-dotted-short"></div>
														<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>

														<div class="right"><h5><a href="javascript:void(0);" onclick="window.open('../../mpdf_edit_contract.php<?php echo $mpdf_contract;?>', '_blank', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" class="side" >契約書類出力(通常)</a></h5></div>
														<?php $data['reg_flg'] ? $contract_flg = 1 : $contract_flg = 0;//guidance.jsへ渡す変数?>
														<div class="clear"></div>
														<div class="lines-dotted-short"></div>
													<?php //} ?>

													<!--  1回当日予約時 -->
													<?php //if( $one_course_only_flg ==true ){ ?>
														<!--  パック契約中(1回コース以外の契約があれば)の単発契約書 -->
														<?php if(0 < $all_pack_contract_count){ ?>
														<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
														<div class="right"><h5><a href="javascript:void(0);" onclick="window.open('../../mpdf_edit_one_contract.php<?php echo $mpdf_one_contract;?>', '_blank', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" class="side" >契約書類出力(1回コースのみ)</a></h5></div>
														<div class="clear"></div>
														<div class="lines-dotted-short"></div>
														<!--  パック契約なし(1回コース契約あり)の単発契約書 -->
														<?php } else {?>
														<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
														<div class="right"><h5><a href="javascript:void(0);" onclick="window.open('../../mpdf_edit_first_one_contract.php<?php echo $mpdf_one_contract;?>', '_blank', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" class="side" >契約書類出力(1回コースのみ)</a></h5></div>
														<div class="clear"></div>
														<div class="lines-dotted-short"></div>
														<?php } ?>
													<?php //} ?>


														<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
														<div class="right"><h5><a href="javascript:void(0);" onclick="window.open('../../mpdf_edit_special_case_treatment.php<?php echo $mpdf_special_case_treatment;?>', '_blank', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" class="side" >特例トリートメント同意書出力</a></h5></div>
														<div class="clear"></div>

														<div class="lines-dotted-short"></div>

														<!-- <div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
														<div class="right"><h5><a href="javascript:void(0);" onclick="window.open('../../mpdf_edit_assurance_extension.php<?php echo $mpdf_assurance_extension;?>', '_blank', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" class="side" >保証期間延長申請書</a></h5></div>
														<div class="clear"></div> -->

														<!-- <div class="lines-dotted-short"></div>
														<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
														<div class="right"><h5><a href="javascript:void(0);" onclick="window.open('../../pdf_mypass.php<?php echo $pdf_param;?>', '_blank', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" class="side" >マイページ情報出力</a></h5>
														<br />※<a rel="facebox" href="../service/mypass_sent.php?id=<?php echo $customer['id'];?>&reservation_id=<?php echo $_POST['id'];?>&hope_date=<?php echo $_POST['hope_date']?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>"　onclick="return confirm('アイパス発行済処理をしますか？')" class="side"　>アイパス発行済処理</a><?php if($customer['pw_sent_flg'])echo "(済)"?></div>

														<div class="clear"></div> -->


													</div><!-- end related-act-inner -->
													<div class="clear"></div>
												</div><!-- end related-act-bottom -->
											</div><!-- end related-activities -->
											<?php //if($data['customer_id'] && $authority_level<=1){?>
											<?php if($data['customer_id'] ){?>
											<!--  start related-activities -->
											<div id="related-activities">
												<!--  start related-act-top -->
												<div id="related-act-top">
													<!--<img src="../images/forms/header_related_act.gif" width="271" height="43" alt="" />-->
													<div class="title">店舗処理</div>
												</div>
												<!-- end related-act-top -->
												<!--  start related-act-bottom -->
												<div class="related-act-bottom">
													<!--  start related-act-inner -->
													<div class="related-act-inner">
														<!-- プラン変更区分の時、レジ清算リンクを非表示（プラン変更で他の機能のレジ清算を行う機能を追加するまで表示させない） -->
														<?php if($data['type']<>10 ){?>
														<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
														<div class="right">
															<?php if($data['type']==30 || $data['type']==31){?>
																<h5 class="register">レジ清算</h5>
																区分：当て漏れ、繰り越し はレジ清算することができません。
															<?php } else {?>
															<h5 class="register">
																<!--来店前また来店なし,未契約が非表示-->
																<?php //if($authority_level<=6 || $authority['id']==106 || ($data['hope_date']<=date('Y-m-d') && $data['type']<>10 && $data['status']<>1 && $data['status']<>2)){
																	if($authority_level<=6 || ($authority['id']==106 && $data['type']<>10 && $data['status']<>1 && $data['status']<>2) || $data['hope_date']<=date('Y-m-d') ){
																 		// 予約するチェックなし　2.トリートメント、20.1回コース当日、27.トリートメント/売掛回収、7.売掛回収のとき
																 		if($data['multiple_contract_id']=="" && ($data['type']==2 || $data['type']==20 || $data['type']==27 || $data['type']==7 )){ ?>
																 			レジ精算
																 		<?php } else { ?>
																 			<!-- 予約するチェックあり -->
																 				<!-- 区分：カウンセリング -->
																			<?php if($data['type']==1 || $data['type']==32){?>
																		<a href="../account/reg_detail.php?id=<?php echo $_GET['id'];?>" class="side">レジ精算</a><?php if($data['reg_flg'])echo "(済)"?>
																		<!--区分：単発-->
																	<?php }else if($data['type']==20){ ?>
																		<a href="../account/one_detail.php?id=<?php echo $_GET['id'];?>" class="side">レジ精算</a><?php if($data['reg_flg'])echo "(済)"?>
																		<!--区分：カウンセリング/単発 以外-->
																	<?php }else{ ?>
																		<a href="../service/detail.php?id=<?php echo $_GET['id'];?>" class="side">レジ精算</a><?php if($data['reg_flg'])echo "(済)"?><?php if($sales['r_times_flg'] ==1)echo "、役務消化(済)"?>
																		<?php } } }?>
															</h5>
															初回入金、役務消化、売掛回収等
															<?php  } ?>
															<!-- 予約するチェックなし 　2.トリートメント、20.1回コース当日、27.トリートメント/売掛回収、7.売掛回収のとき-->
															<?php if($data['multiple_contract_id']=="" && ($data['type']==2 || $data['type']==20 || $data['type']==27 || $data['type']==7 )){ ?>
															<span class="warning"><font size=-2>「予約する」にチェックを入れてsubmitしてからレジ清算してください</font></span>
															<?php } ?>
														</div>
														<?php } ?>
														<div class="clear"></div>
														<div class="lines-dotted-short"></div>

														<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
														<div class="right"><h5><a href="./edit.php?mode=new_rsv&customer_id=<?php echo $customer['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>" onclick="return confirm('次の予約をしますか？')" class="side" >次の予約</a></h5></div>
														<div class="clear"></div>
														<div class="lines-dotted-short"></div>

													<!-- 区分：プラン変更 のとき表示 -->
														<?php if($data['type']==10){?>
														<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
														<!-- ローン支払いあり、ローン取消ではない -->
														<?php if($loan['loan_flg']==1 && $loan["loan_cancel_flg"]==0){?><font size=-2>※ローンがある場合、本社でのローン取消処理が必要</font>
														<!-- ローン支払いなし OR (ローン支払いあり&ローン取消あり)-->
														<?php } elseif($loan['loan_flg']==0 || ($loan['loan_flg'] ==1 && $loan["loan_cancel_flg"]==1)){?>
																			<li class="right"><h5>
																<a rel="facebox" href="../service/change_course_select.php?customer_id=<?php echo $customer['id'];?>&reservation_id=<?php echo ($_POST['id'] ? $_POST['id'] : $data['id']);?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>"　onclick="return confirm('プラン変更をしますか？')" class="side">プラン変更</a></h5></div>
																				</a></h5>
																			</li>
																		<?php }	?>
															</div>
													<?php }	?>

	                                                    <!-- 20160324下記機能を実装したらコメントアウトを外す -->
	                                                    <!-- <div class="clear"></div>
	                                                    <div class="lines-dotted-short"></div>
	                                                    <div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
	                                                    <div class="right"><h5><a href="#" onclick="javascript:window.open('cal.php?customer_id=<?php echo $customer['id'];?>')" class="side">レジ電卓(解約用)</a></h5></div>
	                                                    <div class="clear"></div> -->

													</div><!-- end related-act-inner -->
													<div class="clear"></div>
												</div><!-- end related-act-bottom -->
											</div><!-- end related-activities -->

											<!--  start related-activities -->
											<div id="related-activities">
												<!--  start related-act-top -->
												<div id="related-act-top">
													<div class="title">本社処理</div>
												</div>
												<!-- end related-act-top -->
												<!--  start related-act-bottom -->
												<div class="related-act-bottom">
													<!--  start related-act-inner -->
													<div class="related-act-inner">
												<?php if($authority_level<=6 || $authority['id']==106 ){?>
														<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
														<div class="right"><h5><a href="./edit.php?new_flg=1&mode=new_rsv&customer_id=<?php echo $customer['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>" onclick="return confirm('新規契約をしますか？')" class="side" >新規契約予約<?php if($data['new_flg']) echo "(◎)"?></a></h5></div>
														<div class="clear"></div>

												<?php }?>
												<?php if($authority_level<=6){?>
														<div class="lines-dotted-short"></div>

														<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<!-- 本社クーリングオフ処理 -->
													<?php if($current_contract_p['status']==0 && $current_contract_p['payment_loan'] && $current_contract_p['loan_status']<>4){ ?>
														<div class="right"><h5>クーリングオフ</h5></div>
													<?php }else{?>
														<div class="right"><h5><a href="../service/cooling_off.php?pid=<?php echo $data['pid'];?>&customer_id=<?php echo $customer['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>" onclick="return confirm('クーリングオフ処理をしますか？')" class="side"　>クーリングオフ</a><?php if($current_contract_p['status']==2)echo "(済)"?></a></h5></div>
													<?php }?>

														<div class="clear"></div>
														<div class="lines-dotted-short"></div>
														<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<!-- ローン処理 ローンの支払いがある && ローンステータスが(2.非承認 OR 4.取消 以外の時、中途解約リンクを表示させない)2017/08/31 modify by shimada-->
													<?php if($loan['loan_flg']==1 && $loan['loan_cancel_flg']<>1 && $loan['loan_non_approval_flg']<>1 ){?>
														<!-- 中途解約リンクを表示しない -->
														<div class="right"><h5><?php echo $cancel_name;?></h5></div>
													<?php }else{?>
														<!-- 中途解約リンクを表示する -->
														<div class="right"><h5><a rel="facebox" href="../service/cancel_course_select.php?id=<?php echo ($contract['status']==3 ? $contract['id'] : $new_contract['id']);?>&customer_id=<?php echo $customer['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>"　onclick="return confirm('<?php echo $cancel_name;?>処理をしますか？')" class="side"　><?php echo $cancel_name;?></a><?php if($contract['status']==3 || $new_contract['status']==3 )echo "(済)"?></a></h5></div>
													<?php }?>
														<!-- 20160324下記機能を実装したらコメントアウトを外す -->
														<!-- <div class="clear"></div>
														<div class="lines-dotted-short"></div>

														<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
														<div class="right"><h5><a href="../service/cancel_auto.php?id=<?php echo ($contract['status']==6 ? $contract['id'] : $new_contract['id']);?>&customer_id=<?php echo $customer['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>"　onclick="return confirm('自動解約処理をしますか？')" class="side"　>自動解約</a><?php if($contract['status']==6 || $new_contract['status']==6 )echo "(済)"?></a></h5></div>
														<div class="clear"></div>
														<div class="lines-dotted-short"></div> -->

												<!-- ローン処理 ローンの支払いがある OR ローンステータスが(2.非承認 OR 4.取消 のとき表示させる)-->
												<?php if($loan['loan_flg']==1 || ($loan['loan_cancel_flg']==1 || $loan['loan_non_approval_flg']==1) ){?>
												<div class="clear"></div>
													  <?php //if($contract['status']<>5){?>
														<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
														<!--<div class="right"><h5><a rel="facebox" href="../service/confirm_loan.php?customer_id=<?php echo $customer['id'];?>&reservation_id=<?php echo $_POST['id'];?>&hope_date=<?php echo $_POST['hope_date']?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>"　onclick="return confirm('ローン承認処理をしますか？')" class="side"　>ローン承認処理</a>(<?php echo $gLoanStatus[$contract['loan_status']];?>)</h5></div>-->
														<div class="right">
															<h5>ローン処理</h5>
														</div>
														<!-- 親契約IDごとにローンステータス変更用のリンクを作成 -->
														<?php foreach ($all_contract_p_whole as $key => $contract_p_data): ?>
															<!--親契約IDのリンクを表示する条件 -->
															<!-- 1.ローン承認済 && ローン支払あり
															     2.ローン非承認
															     3.ローン承認中 && ローン支払あり
															     4.ローン取消 2017/06/06 add by shimada-->
															<?php if(($contract_p_data['loan_status']==1 && 0<$contract_p_data['payment_loan'])
															      || ($contract_p_data['loan_status']==2)
															      || ($contract_p_data['loan_status']==3 && 0<$contract_p_data['payment_loan'])
															      || ($contract_p_data['loan_status']==4)
															        ){?>
															<li class="right"><h5>
																<!-- ローンステータス 1.ローン承認中、3.ローン承認済 以外のステータス変更を制御する-->
																<?php if($contract_p_data['loan_status']==1 || $contract_p_data['loan_status']==3 ){?>
																契約番号:
																<a rel="facebox" href="../service/confirm_loan.php?pid=<?php echo $contract_p_data['id'];?>&reservation_id=<?php echo $_POST['id'];?>&hope_date=<?php echo $_POST['hope_date']?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>"　onclick="return confirm('ローン承認処理をしますか？')" class="side"　><?php echo $contract_p_data['id']?></a>
																	(<?php echo $gLoanStatus[$contract_p_data['loan_status']];?>)
															</h5></li>
																<?php }else{ ?>
																	契約番号:
																	<?php echo $contract_p_data['id']?>(<?php echo $gLoanStatus[$contract_p_data['loan_status']];?>)
																	<br><font size=-2>※ローンステータス変更の場合、本社でのローンステータス変更処理が必要</font>
																<?php } ?>
															<?php } ?>
														<?php endforeach?>
													  <?php //} ?>											　　
														<!-- <div class="clear"></div> -->
														<!-- <div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div> -->
														<!-- <div class="right">
															<h5>ローン取消</h5>
														</div> -->
														<!-- 親契約IDごとにローン取消用のリンクを作成 -->
														<?php //foreach ($all_contract_p as $key => $contract_p_data): ?>
															<!-- <li class="right"><h5> -->
																<!-- 契約番号：
																<a href="../service/cancel_loan.php?customer_id=<?php echo $customer['id'];?>&pid=<?php echo $contract_p_data['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>"　onclick="return confirm('ローン取消処理をしますか？')" class="side"　><?php echo $contract_p_data['id']?></a><?php if($contract_p_data['status']==5)echo "(済)"?></a> -->
															<!-- </h5></li> -->
														<?php //endforeach?>
														<div class="clear"></div>

												<!-- クーリングオフ この予約の契約に対してクーリングオフをする仕様とするため下記はコメントアウト。もし親契約ごとにクーリングオフする場合の出し方を考えるときは下記のコメントアウトを復活してください。-->
												<?php }//}elseif($cooling_off_flg['status_flg']==1){ ?>
														<!-- <div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
														<div class="right"><h5>クーリングオフ(済)</h5></div>
														<div class="clear"></div>
														<div class="lines-dotted-short"></div> -->
												<!-- 中途解約 -->
												<?php }elseif($cancel_flg['status_flg']==1){ ?>
														<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
														<div class="right"><h5><?php echo $cancel_name;?>(済)</h5></div>
														<div class="clear"></div>
														<div class="lines-dotted-short"></div>
												<!-- 自動解約 -->
												<?php }elseif($auto_cancel_flg['status_flg']==1){ ?>
														<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
														<div class="right"><h5>自動解約(済)</h5></div>
														<div class="clear"></div>
														<div class="lines-dotted-short"></div>
												<!-- ローン取消 -->
												<?php }//elseif($contract['status']==5){ ?>
														<!-- <div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
														<div class="right"><h5>ローン取消(済)</h5></div>
														<div class="clear"></div> -->

												<?php //} ?>
													</div><!-- end related-act-inner -->
												</div><!-- end related-act-bottom -->
											</div><!-- end related-activities -->


											<?php } ?>
										</td>
									<?php } ?>
								</tr>
							</table>
						</div>
						<!--  end content-table-inner  -->
					</td>
				</tr>
			</table>
	</form>
	<!-- end id-form  -->
	</div>
	<!--  end content -->
</div>
<!--  end content-outer -->
<script type="text/javascript">
new AutoKana('Name', 'NameKana', {katakana: true, toggle: false});
	new AutoKana('FirstName', 'FirstNameKana', {katakana: true, toggle: false});
	new AutoKana('LastName', 'LastNameKana', {katakana: true, toggle: false});
</script>
<script src="../js/jquery.guidance.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../css/jquery.guidance.css">
<script type="text/javascript" charset="utf-8" async defer>
<?php if($data['type'] ==20 && $data['status'] <> 11 && !$gMsg){ ?>
	$("body").guidance({guidanceAll : "来店状況を「来店（契約者）」にしてください。<br><span class='suppl'>（※他にプラン契約が無いお客様もこの区分にしてください。）</span>"});
<?php } ?>
		$(".guidance").guidance({
			SguidanceNo :{7:"<span class='emphasis'>支払希望の契約番号コースで「予約する」をチェックしてください。</span><br>契約番号ごとまとめての支払いです。コースごとの支払ではありません。<br><span class='suppl'>（※違う契約番号を同時に支払う場合はもう一度予約を取ってください。）</span>",
										20:"本日施術して消化します。ルーム・所要時間を予約して部屋を押さえてください。<br><span class='suppl'>（※消化を明日以降で複数回に分けたい場合は、「カウンセリング」を予約して別途購入してください）</span>",
										27:"<span class='emphasis'>トリートメント希望のコースのみ「予約する」をチェック！！！</span><br>契約番号ごとまとめての支払いです。コースごとの支払ではありません。<br><span class='suppl'>（※違う契約番号のトリートメントを同時に予約する場合は、この予約の次に「トリートメント」で予約を取ってください。）</span><br><span class='suppl'>（※違う契約番号を同時に支払う場合は「売掛回収」でもう一度予約を取ってください。）</span>",
										// 10:"ルーム・所要時間は不要です。"
									}
		});
		var $room_time,$time_table_link,$time_table_area,$content_table_inner;
		$room_time = $(".room_time"),
		$time_table_link = $("#time_table_link"),
		$time_table_area = $("#time_table_area"),
		$content_table_inner = $("#content-table-inner");
		/* 一回コース電卓の表示非表示 */
	$(".type_form").on("change",function(){
		$this = $(this);
		/*if($this.val() == 10){//プラン変更の場合
			$room_time.addClass('unnecessary');
			$time_table_link.addClass('unnecessary');
			$time_table_area.html("");
			$content_table_inner.css("display","");
		}else */if($this.val() == 20){//1回コース当日の場合
			$room_time.removeClass('unnecessary');
			$time_table_link.removeClass('unnecessary');
			$time_table_area.load("./one_course_length.php");
			$content_table_inner.css("display","inline-block");
			$("#day").datepicker("setDate","0"); //datepickerに今日の日付をセット
		}else{
			$room_time.removeClass('unnecessary');
			$time_table_link.addClass('unnecessary');
			$time_table_area.html("");
			$content_table_inner.css("display","");
		}
	});
</script>
<?php include_once("../include/footer.html");?>