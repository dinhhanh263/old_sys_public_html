<?php
$DOC_ROOT = empty($_SERVER['DOCUMENT_ROOT']) ? str_replace('/admin/library/main', '', dirname(__FILE__)) : $_SERVER['DOCUMENT_ROOT'];

require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'auth.php';

// 会計用のアカウントなら、売上一覧へ
if($authority['id']=="23"){
    header("Location: ../sales/");
    exit();
}

if(!$_POST['shop_id']) $_POST['shop_id'] = 1;
$table = "reservation";
$_POST['hope_date']=$_POST['hope_date'] ? $_POST['hope_date'] : date("Y-m-d");
$pre_date = date("Y-m-d", strtotime($_POST['hope_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['hope_date']." +1day"));

$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($_POST['shop_id'] ? $_POST['shop_id'] : 1)."'");

// 店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist2("shop");
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);

// courseリスト
$course_list  = getDatalist("course");

// staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);

$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// ad list
$adcode_sql = $GLOBALS['mysqldb']->query( "select * from adcode order by id" ) or die('query error'.$GLOBALS['mysqldb']->error);

$adcode_list[0] = "-";
while ( $result = $adcode_sql->fetch_assoc() ) {
	$adcode_list[$result['id']] = $result['name'];
	$adcode_memo[$result['id']] = $result['memo'];
}

// room_list
if($_POST['hope_date']=="2014-02-28") $counseling_rooms = $CounselingRoomsMax;
elseif($_POST['hope_date']>="2014-03-01" && $_POST['hope_date']<="2014-03-03") $counseling_rooms = 6;
elseif($_POST['hope_date']>="2014-03-04" && $_POST['hope_date']<="2014-03-09") $counseling_rooms = 5;
elseif($_POST['hope_date']=="2014-03-16") $counseling_rooms = 4;

else $counseling_rooms = $shop['counseling_rooms'] ? $shop['counseling_rooms'] : $CounselingRooms;

// $medical_rooms = $shop['medical_rooms'] ? $shop['medical_rooms'] : $MedicalRooms;
$vip_rooms = $shop['vip_rooms'] ? $shop['vip_rooms'] : $VIPRooms;
// 新宿本店VIPルーム
$vip_rooms = ($_POST['shop_id']==1 && $_POST['hope_date']<="2016-09-30") ? 1 : $vip_rooms;


$ninety_time_rooms = $shop['ninety_time_rooms'];
$sixty_time_rooms = $shop['sixty_time_rooms'];
$thirty_time_rooms = $shop['thirty_time_rooms'];
$special_rooms = $shop['special_rooms'];

$room_availability_url = $_SERVER['DOCUMENT_ROOT'].'/admin/library/main/room_availability.php';
include ($room_availability_url);

for ($i = 1; $i <= $counseling_rooms; $i++) {
	$no = "1".$i;
	$room_list[$no] = $CounselingRoomName.$i;
}
if($vip_rooms){
	for ($i = 1; $i <= $vip_rooms; $i++) {
		$no = "2".$i;
		$room_list[$no] = $VIPRoomName.$i."<br />パック";
	}
}

// 新宿店施術ルームを４に
$m_room2="3".($shop['pack_rooms']+2);

/* if($_POST['shop_id']==9){
	$room_list[35] = "<br />ホットペッパー<br />";
	$m_room2="3".($shop['pack_rooms']+3);
} */

for ($i = 1; $i <= $ninety_time_rooms; $i++) {
	// if($_POST['shop_id']==9 && $i==5) continue;
	$no = "3".$i;
	$room_list[$no] = $ninetyTimeRoomsName.$i;
	// if($i<=$shop['pack_rooms']) $room_list[$no] .= "<br />パック";
	// else 						$room_list[$no] .= "<br />新規枠+旧月額";
}

for ($i = 1; $i <= $sixty_time_rooms; $i++) {
	$no = "5" . $i;
	$room_list[$no] = $sixtyTimeRoomsName . $i;
}
for ($i = 1; $i <= $thirty_time_rooms; $i++) {
	$no = "6" . $i;
	$room_list[$no] = $thirtyTimeRoomsName . $i;
}

if ($shop['chiropractic_flg'] == 1) {
    for ($i = 1; $i <= $special_rooms; $i++) {
        $no = "7" . $i;
        $room_list[$no] = $specialRoomsName . $i;
    }
}

// その他ルーム4
for ($i = 1; $i <= $OtherRooms; $i++) {
	$no = "4".$i;
	// if($i == 1){
	//	$room_list[$no] = "新規枠";
	// }else{
		$room_list[$no] = $OtherRoomName.$i;
	// }
	
}

// 検索条件の設定------------------------------------------------------------------------

$dWhere .= " AND  r.hope_date='".$_POST['hope_date']."'";
$dWhere .= " AND  r.shop_id='".($_POST['shop_id'] ? $_POST['shop_id'] : 1)."'";

// データの取得,同ルームの複数予約,時間順に格納(type:1.カウンセリング、2.施術、7.売掛回収, 8.月額支払,c.specialより？
$dSql = "SELECT r.*,c.id as customer_id,
				c.no as no,c.name as name,
				c.name_kana as name_kana,
				c.agree_status as agree_status,
				c.attorney_status as attorney_status,
				c.student_id as student_id,
				c.mail as mail,
				c.ctype as ctype,
				c.adcode as adcode,
				c.special as special,
				c.tel as tel,
				c.birthday as birthday,
				c.age as age,
				c.referer_url as referer_url,
				c.sv_flg as sv_flg,
				c.loan_delay_flg as loan_delay_flg,
				c.digicat_ng_flg as digicat_ng_flg,
				c.nextpay_end_ng_flg as nextpay_end_ng_flg,
				c.nextpay_op_ng_flg as nextpay_op_ng_flg,
				c.bank_ng_flg as bank_ng_flg,
				c.onelife_flg as onelife_flg,
				c.caution as caution,
				r.adcode as rsv_adcode,
				r.hope_date as rsv_hope_date 
		FROM " . $table . " as r,customer as c WHERE r.customer_id=c.id AND c.del_flg = 0 AND r.del_flg = 0 AND r.type<>3 AND r.type<>14 ".$dWhere." ORDER BY r.room_id,r.hope_time"; // cancel: status = 3

$dRtn = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
$i=1;
while ( $result = $dRtn->fetch_assoc() ) {
 if($result['contract_id']){
	$contract = array();
	$contract = Get_Table_Row("contract"," where id=".$result['contract_id']); // change to 'left join'?

	if($result['type'] == 2 && $contract['balance']){
		// 残金提示
		$data[$result['room_id']][$i]['balance'] = $contract['balance'];
	}

	if($contract['payment_loan']){
		// ローン申請状態提示
		$data[$result['room_id']][$i]['loan_status'] = "<br>ローン".$gLoanStatus[$contract['loan_status']];
		$data[$result['room_id']][$i]['loan_status_no'] = $contract['loan_status'];// ローンステータス番号 20160906 shimada
	}
	// 委任状なしにアラーム
	if($contract['status']==0 && $contract['payment_loan'] && $result['attorney_status']<>1){
		$staff_data[$staff_id][$i]['attorney_status'] = $data[$result['room_id']][$i]['attorney_status'] = "<font color=red>委任状：".$gAttorneyStatus[$result['attorney_status']]."</font>";
	}

	if($result['type'] == 2 && $contract['course_id'] ){
		$course = Get_Table_Row("course"," where id=".$contract['course_id']);
		$data[$result['room_id']][$i]['course_id'] = $contract['course_id']; // course_id
		$data[$result['room_id']][$i]['course_group'] = $course['group_id'];
		$data[$result['room_id']][$i]['course_type'] = $course['type'];
		$data[$result['room_id']][$i]['course_status'] = $course['status'];
		$data[$result['room_id']][$i]['course_new_flg'] = $course['new_flg'];
		$data[$result['room_id']][$i]['course_del_flg'] = $course['del_flg'];
		$data[$result['room_id']][$i]['course_minor_plan_flg'] = $course['minor_plan_flg'];
		$data[$result['room_id']][$i]['course_weekdays_plan_type'] = $course['weekdays_plan_type'];
		$data[$result['room_id']][$i]['dis_type'] = $contract['dis_type'];
		$data[$result['room_id']][$i]['pay_type'] = $contract['pay_type'];
		$data[$result['room_id']][$i]['times'] = $contract['times'];
		$data[$result['room_id']][$i]['r_times'] = $contract['r_times'];
		$data[$result['room_id']][$i]['sales_id'] = $result['sales_id'];
		$data[$result['room_id']][$i]['payinfo_del_flg'] = $contract['payinfo_del_flg']; // リトライ決済で強制ST　20171124
		$data[$result['room_id']][$i]['course_interval_date'] = $course['interval_date'];
		$data[$result['room_id']][$i]['course_sales_start_date'] = $course['sales_start_date'];
		$data[$result['room_id']][$i]['course_zero_flg'] = $course['zero_flg'];
		$data[$result['room_id']][$i]['course_treatment_type'] = $course['treatment_type'];
		$data[$result['room_id']][$i]['course_length'] = $course['length'];
	}
  }

 	// 施術予約で来店有り役務消化無しの場合アラーム,30分体験脱毛等を除外
  	$r_times_alarm = false ;
	if($result['type']==2 && $result['status']==11 && $result['length']>1){
		if(!$result['sales_id']) $r_times_alarm = true ;
		elseif( !Get_Table_Col("sales","r_times"," where del_flg=0 and r_times<>0 and reservation_id=".$result['id'] ) ) $r_times_alarm = true ;

 	}

	// 担当者毎：カウンセリング予約ー＞カウンセリング担当、施術予約ー＞施術主担当、その他ー>受付担当
	if($result['type']==1 && $result['cstaff_id']) {
		// 担当者毎：カウンセリング予約ー＞カウンセリング担当
		$staff_id = $result['cstaff_id'];

	}elseif($result['type']==2 && $result['tstaff_id']){
		// 担当者毎：施術予約ー＞施術主担当
		$staff_id = $result['tstaff_id'];

	}else{
		$staff_id = 0 ;
	}

	// 年齢計算
	$now = date('Ymd');
	if($result['birthday'] && $result['birthday']<>"0000-00-00"){
		if(strstr($result['birthday'], "/")){
			list($year,$month,$day) = explode("/", $result['birthday']);
			if($month<10) $month = "0".$month;
			if($day<10) $day = "0".$day;
		}else{
			list($year,$month,$day) = explode("-", $result['birthday']);
		}
		$birthday = $year.$month.$day;
   		$staff_data[$staff_id][$i]['age'] = $data[$result['room_id']][$i]['age'] = floor(($now-$birthday)/10000);
	}elseif($result['age']){
		$staff_data[$staff_id][$i]['age'] = $data[$result['room_id']][$i]['age'] = $result['age'];
	}

	// 未成年、同意書なしにアラーム(カウンセリング)
	if($result['type'] == 1 && getAgeByTargetDate($result['birthday'], $result['rsv_hope_date']) < 20 && $result['agree_status']==0){
		$staff_data[$staff_id][$i]['agree_status'] = $data[$result['room_id']][$i]['agree_status'] = "<font color=red>同意書：☓</font>";
	}
	// 未成年、同意書なしにアラーム(トリートメント)
	if($result['type'] == 2 && $contract['contract_date'] !="0000-00-00" && getAgeByTargetDate($result['birthday'], $contract['contract_date']) < 20 && $result['agree_status']==0){
		$staff_data[$staff_id][$i]['agree_status_color'] = $data[$result['room_id']][$i]['agree_status_color'] =  $result['agree_status'];
	}

	// 学割証明なしにアラーム
	if($result['hopes_discount']){
		$staff_data[$staff_id][$i]['student_id'] = $data[$result['room_id']][$i]['student_id'] = "学割希望";
		if($result['hopes_discount'] && !$result['student_id']){
			$staff_data[$staff_id][$i]['student_id'] 	.= "<font color=red>(学生証明：☓)</font>";
			$data[$result['room_id']][$i]['student_id'] .= "<font color=red>(学生証明：☓)</font>";
		}
	}

	// 友達紹介利用者判定
	$introducer_customer_id = Get_Table_Col("introducer","customer_id"," where del_flg=0 and introducer_customer_id=".$result['customer_id']);
	if($introducer_customer_id){
		$staff_data[$staff_id][$i]['introducer_customer_id'] 			= $data[$result['room_id']][$i]['introducer_customer_id'] 		= $result['introducer_customer_id'] = "友達紹介";
	}
	
	// 月額休会履歴取得
	$monthly_pause_flg = false;
	$monthly_pause = Get_Table_Col("monthly_pause","count(*)"," WHERE del_flg=0 AND contract_id !=0 AND contract_id = ".$result['contract_id']);
	if($monthly_pause > 0){
		$monthly_pause_flg = true;
	}
	$customer_memo = Get_Table_Row("customer_memo", " where customer_id=" . $result['customer_id']. " and del_flg = 0");

	// 担当毎、ルーム毎格納
	$staff_data[$staff_id][$i]['id'] 				= $data[$result['room_id']][$i]['id'] 			= $result['id'];
	$staff_data[$staff_id][$i]['no'] 				= $data[$result['room_id']][$i]['no'] 			= $result['no'];
	$staff_data[$staff_id][$i]['name'] 				= $data[$result['room_id']][$i]['name'] 		= $result['name'];
	$staff_data[$staff_id][$i]['name_kana'] 		= $data[$result['room_id']][$i]['name_kana'] 	= $result['name_kana'] ? $result['name_kana'] : $result['name']; // なければ、name表示
	$staff_data[$staff_id][$i]['mail'] 				= $data[$result['room_id']][$i]['mail'] 		= $result['mail'];
	$staff_data[$staff_id][$i]['status0'] 			= $data[$result['room_id']][$i]['status0'] 		= $result['status'] ;//来店状況
	$staff_data[$staff_id][$i]['status'] 			= $data[$result['room_id']][$i]['status'] 		= $result['status'] ? "(".$gBookStatus[$result['status']].")" : ''; // 来店状況
	$staff_data[$staff_id][$i]['con_status'] 		= $data[$result['room_id']][$i]['con_status'] 	= $result['con_status'] ? "(".$gConfirmStatus[$result['con_status']].")" : ''; // 確認状況
	$staff_data[$staff_id][$i]['rsv_status'] 		= $data[$result['room_id']][$i]['rsv_status'] 	= $result['rsv_status'] ; // 予約状況

	$staff_data[$staff_id][$i]['3dmail_status'] 	= $data[$result['room_id']][$i]['3dmail_status'] = $result['3dmail_status'] ? "(".$g3DMailStatus[$result['3dmail_status']].")" : ''; // 確認状況
	$staff_data[$staff_id][$i]['premail_status'] 	= $data[$result['room_id']][$i]['premail_status'] = $result['premail_status'] ? "(".$gPreMailStatus[$result['premail_status']].")" : ''; // 確認状況
	$staff_data[$staff_id][$i]['preday_status'] 	= $data[$result['room_id']][$i]['preday_status'] = $result['preday_status'] ? "(".$gPreDayStatus[$result['preday_status']].")" : ''; // 確認状況
	$staff_data[$staff_id][$i]['today_status'] 		= $data[$result['room_id']][$i]['today_status'] = $result['today_status'] ? $gTodayStatus[$result['today_status']] : ''; // 確認状況

	$staff_data[$staff_id][$i]['adcode'] 			= $data[$result['room_id']][$i]['adcode'] 		= $adcode_list[$result['adcode']];
	$staff_data[$staff_id][$i]['ad_memo'] 			= $data[$result['room_id']][$i]['ad_memo'] 		= $adcode_memo[$result['adcode']];
	$staff_data[$staff_id][$i]['hope_time'] 		= $data[$result['room_id']][$i]['hope_time'] 	= $result['hope_time'];
	$staff_data[$staff_id][$i]['length'] 			= $data[$result['room_id']][$i]['length'] 		= $result['length'];
	$staff_data[$staff_id][$i]['special'] 			= $data[$result['room_id']][$i]['special'] 		= $result['special'];
	$staff_data[$staff_id][$i]['type'] 				= $data[$result['room_id']][$i]['type'] 		= $result['type'];
	$staff_data[$staff_id][$i]['ctype'] 			= $data[$result['room_id']][$i]['ctype'] 		= $result['ctype'];
	$staff_data[$staff_id][$i]['caution'] 			= $data[$result['room_id']][$i]['caution'] 		= $result['caution'];
	$staff_data[$staff_id][$i]['rsv_adcode'] 		= $data[$result['room_id']][$i]['rsv_adcode'] 	= $result['rsv_adcode'];
if($authority_level<=6 || ($authority['id']==106 || $authority['id']==1449) && $result['ctype']<2)$staff_data[$staff_id][$i]['tel'] = $data[$result['room_id']][$i]['tel']		= $result['tel'] ? "<br>".$result['tel'] : "";
	$staff_data[$staff_id][$i]['referer_url'] 		= $data[$result['room_id']][$i]['referer_url'] 	= $result['referer_url'];
	$staff_data[$staff_id][$i]['rebook_flg'] 		= $data[$result['room_id']][$i]['rebook_flg'] 	= $result['rebook_flg'];
	$staff_data[$staff_id][$i]['reg_date'] 			= $data[$result['room_id']][$i]['reg_date'] 	= $result['reg_date'];

	$staff_data[$staff_id][$i]['hope_campaign'] 	= $data[$result['room_id']][$i]['hope_campaign']= $result['hope_campaign'] ? "(".$result['hope_campaign'].")" : "";
	$staff_data[$staff_id][$i]['memo'] 				= $data[$result['room_id']][$i]['memo'] 		= $result['memo'] ? "(".$result['memo'].")" : "";
	$staff_data[$staff_id][$i]['memo2'] 			= $data[$result['room_id']][$i]['memo2'] 		= $result['memo2'] ? "(".$result['memo2'].")" : "";
	$staff_data[$staff_id][$i]['memo3'] 			= $data[$result['room_id']][$i]['memo3'] 		= $result['memo3'] ? "(".$result['memo3'].")" : "";
	$staff_data[$staff_id][$i]['memo4'] 			= $data[$result['room_id']][$i]['memo4'] 		= $result['memo4'] ? "(".$result['memo4'].")" : "";
	$staff_data[$staff_id][$i]['memo5'] 			= $data[$result['room_id']][$i]['memo5'] 		= $result['memo5'] ? "(".$result['memo5'].")" : "";
	$staff_data[$staff_id][$i]['memo_head_office'] 			= $data[$result['room_id']][$i]['memo_head_office'] 		= $customer_memo['memo_head_office'] ? "(".$customer_memo['memo_head_office'].")" : "";
	$staff_data[$staff_id][$i]['r_times_alarm'] 	= $data[$result['room_id']][$i]['r_times_alarm']= $r_times_alarm;
	$staff_data[$staff_id][$i]['sv_flg'] 			= $data[$result['room_id']][$i]['sv_flg'] 		= $result['sv_flg'];
	$staff_data[$staff_id][$i]['loan_delay_flg'] 	= $data[$result['room_id']][$i]['loan_delay_flg'] 	= $contract['loan_delay_flg'];
	$staff_data[$staff_id][$i]['digicat_ng_flg'] 	= $data[$result['room_id']][$i]['digicat_ng_flg'] 	= $result['digicat_ng_flg'];
	$staff_data[$staff_id][$i]['nextpay_end_ng_flg']= $data[$result['room_id']][$i]['nextpay_end_ng_flg'] 	= $result['nextpay_end_ng_flg'];
	$staff_data[$staff_id][$i]['nextpay_op_flg'] 	= $data[$result['room_id']][$i]['nextpay_op_flg'] 	= $result['nextpay_op_flg'];
	$staff_data[$staff_id][$i]['bank_ng_flg'] 		= $data[$result['room_id']][$i]['bank_ng_flg'] 	= $result['bank_ng_flg'];
	$staff_data[$staff_id][$i]['onelife_flg'] 		= $data[$result['room_id']][$i]['onelife_flg'] 	= $result['onelife_flg']; // ワンライフの一時対応　20171116
	$staff_data[$staff_id][$i]['monthly_pause_flg'] = $data[$result['room_id']][$i]['monthly_pause_flg']= $monthly_pause_flg;
	$staff_data[$staff_id][$i]['delay_time_status'] = $data[$result['room_id']][$i]['delay_time_status'] 	= $result['delay_time_status'];


	if($result['type']==2 && $result['tstaff_id']) 		$data[$result['room_id']][$i]['cstaff_id'] 	= "担当：".$staff_list[$result['tstaff_id']];
elseif($result['type']==1 && $result['cstaff_id']) 		$data[$result['room_id']][$i]['cstaff_id'] 	= "担当：".$staff_list[$result['cstaff_id']];

	if($result['type']==2 && $result['tstaff_sub1_id']) $staff_data[$staff_id][$i]['tstaff_sub1_id'] = "サブ担当1：".$staff_list[$result['tstaff_sub1_id']];
	if($result['type']==2 && $result['tstaff_sub2_id']) $staff_data[$staff_id][$i]['tstaff_sub2_id'] = "サブ担当2：".$staff_list[$result['tstaff_sub2_id']];

	if($result['type']==2 && $result['tstaff_sub1_id'] ){

		$staff_data[$result['tstaff_sub1_id']][$i]['id'] 				= $result['id'];
		$staff_data[$result['tstaff_sub1_id']][$i]['no'] 				= $result['no'];
		$staff_data[$result['tstaff_sub1_id']][$i]['name'] 				= $result['name'];
		$staff_data[$result['tstaff_sub1_id']][$i]['name_kana'] 		= $result['name_kana'] ? $result['name_kana'] : $result['name']; // なければ、name表示
		$staff_data[$result['tstaff_sub1_id']][$i]['hope_time'] 		= $result['hope_time'];
		$staff_data[$result['tstaff_sub1_id']][$i]['length'] 			= $result['length'];
		$staff_data[$result['tstaff_sub1_id']][$i]['type'] 				= $result['type'];
		$staff_data[$result['tstaff_sub1_id']][$i]['status'] 			= $result['status'] ? "(".$gBookStatus[$result['status']].")" : ''; // 来店状況
		$staff_data[$result['tstaff_sub1_id']][$i]['status0'] 			= $result['status'] ;//来店状況
		$staff_data[$result['tstaff_sub1_id']][$i]['tstaff_sub1_id'] 	= "サブ担当1";
	}
	if($result['type']==2 && $result['tstaff_sub2_id'] ){

		$staff_data[$result['tstaff_sub2_id']][$i]['id'] 				= $result['id'];
		$staff_data[$result['tstaff_sub2_id']][$i]['no'] 				= $result['no'];
		$staff_data[$result['tstaff_sub2_id']][$i]['name'] 				= $result['name'];
		$staff_data[$result['tstaff_sub2_id']][$i]['name_kana'] 		= $result['name_kana'] ? $result['name_kana'] : $result['name']; // なければ、name表示
		$staff_data[$result['tstaff_sub2_id']][$i]['hope_time'] 		= $result['hope_time'];
		$staff_data[$result['tstaff_sub2_id']][$i]['length'] 			= $result['length'];
		$staff_data[$result['tstaff_sub2_id']][$i]['type'] 				= $result['type'];
		$staff_data[$result['tstaff_sub2_id']][$i]['tstaff_sub2_id'] 	= "サブ担当2";

	}
	$i++;
}

?>