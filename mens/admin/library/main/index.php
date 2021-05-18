<?php

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth.php" );

// 会計用のアカウントなら、売上一覧へ
if($authority['id']=="23"){
    header("Location: ../sales/");
    exit();
}

if(!$_POST['shop_id']) $_POST['shop_id'] = 1010;
$table = "reservation";
$_POST['hope_date']=$_POST['hope_date'] ? $_POST['hope_date'] : date("Y-m-d");
$pre_date = date("Y-m-d", strtotime($_POST['hope_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['hope_date']." +1day"));

$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($_POST['shop_id'] ? $_POST['shop_id'] : 1010)."'");

// 店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist_shop("");

//staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

//courseリスト
$course_list  = getDatalistMens("course");

// ad list
$adcode_sql = $GLOBALS['mysqldb']->query( "select * from adcode order by id" );
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

for ($i = 1; $i <= $counseling_rooms; $i++) {
	$no = "1".$i;
	$room_list[$no] = $CounselingRoomName.$i;
}

// その他ルーム4
for ($i = 1; $i <= $OtherRooms; $i++) {
	$no = "4".$i;
	$room_list[$no] = $OtherRoomName.$i;
}

// 検索条件の設定------------------------------------------------------------------------

$dWhere .= " AND  r.hope_date='".$_POST['hope_date']."'";
$dWhere .= " AND  r.shop_id='".($_POST['shop_id'] ? $_POST['shop_id'] : 1010)."'";

// データの取得,同ルームの複数予約,時間順に格納(type:1.カウンセリング、2.施術、7.売掛回収, 8.月額支払,c.specialより？
//cancel: status= 3.キャンセル、 21.カウンセリング/キャンセル、 22.トリートメント/キャンセル
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
				c.bank_ng_flg as bank_ng_flg 
		FROM " . $table . " as r,customer as c WHERE r.customer_id=c.id AND c.del_flg = 0 AND r.del_flg = 0 AND r.type<>3 AND r.type<>21 AND r.type<>22 AND r.type<>14 ".$dWhere." ORDER BY r.room_id,r.hope_time ";

$dRtn = $GLOBALS['mysqldb']->query( $dSql );
$i=1;
while ( $result = $dRtn->fetch_assoc() ) {
// 修正前 #499 表示情報「売掛有り」の文字色未反映について ///////////////////////////////////////////////////////
 // if($result['contract_id']){	
	// $contract = array();
	// $contract = Get_Table_Row("contract"," where id=".$result['contract_id']);//change to 'left join'?

	// if($result['type'] == 2 && $contract['balance']){
	// 	// 残金提示
	// 	$data[$result['room_id']][$i]['balance'] = $contract['balance'];
	// }

	// if($contract['payment_loan']){
	// 	// ローン申請状態提示
	// 	$data[$result['room_id']][$i]['loan_status'] = "<br>ローン".$gLoanStatus[$contract['loan_status']];
	// }
	// // 委任状なしにアラーム
	// if($contract['status']==0 && $contract['payment_loan'] && $result['attorney_status']<>1){
	// 	$staff_data[$staff_id][$i]['attorney_status'] = $data[$result['room_id']][$i]['attorney_status'] = "<font color=red>委任状：".$gAttorneyStatus[$result['attorney_status']]."</font>";
	// }

	// if($result['type'] == 2 && $contract['course_id'] ){
	// 	$course = Get_Table_Row("course"," where id=".$contract['course_id']);
	// 	$data[$result['room_id']][$i]['contract_part'] = $contract['contract_part'];
	// 	$data[$result['room_id']][$i]['course_group'] = $course['group_id'];
	// 	$data[$result['room_id']][$i]['course_type'] = $course['type'];
	// 	$data[$result['room_id']][$i]['dis_type'] = $contract['dis_type'];
	// 	$data[$result['room_id']][$i]['pay_type'] = $contract['pay_type'];
	// 	$data[$result['room_id']][$i]['r_times'] = $contract['r_times'];
	// 	$data[$result['room_id']][$i]['sales_id'] = $result['sales_id'];
	// }
 //  }
////////////////////////////////////////////////////////////////////////////////////////////////////////

// ここから下をすべて書き換え 2017/04/27 modify by shimada ////////////////////////////////////////////////////
// 契約IDあり
 if($result['multiple_contract_id']){	
	// 配列を初期化
	$contract = array();
	$contract_p = array();
	// 予約した契約IDから、契約データの配列を作成する
	$contract_array = Get_Table_Array("contract","*"," where id IN(".$result['multiple_contract_id'].")");
	// 判別フラグの初期化
	$balance_flg       =false; // 売掛なし
	$loan_payment_flg  =false; // ローン支払いなし
	$under_contract_flg=false; // 契約中ではない
	// 親契約のレコードを検索し、結果が1つでも返ってくる場合、フラグを有効化する
	foreach ($contract_array as $value) {
		// 売掛金が残っている親契約情報
		$contract_p = Get_Table_Row("contract_P"," where del_flg=0 AND balance >0 AND id=".$value['pid']);
		// ローン支払い(複数ある場合は最新のレコード)がある親契約情報
		$contract_p_loan = Get_Table_Row("contract_P"," where del_flg=0 AND payment_loan >0 AND id=".$value['pid']." ORDER BY id DESC");
		// 判別フラグを有効化する
		if($value['status']==0)$under_contract_flg=true; // 契約中フラグ
		if($contract_p)$balance_flg               =true; // 売掛ありフラグ
		if($contract_p_loan)$loan_payment_flg     =true; // ローン支払いフラグ
	}

	// 区分：トリートメント OR トリートメント/売掛回収 のとき、売掛金に値を入れる
	if(($result['type'] == 2 || $result['type'] == 27 ) && $balance_flg==true){
		// 残金提示
		$data[$result['room_id']][$i]['balance'] = $contract_p['balance'];
	}

	// ローン支払い(複数ある場合は最新のレコード)がある場合はローン状況を表示する
	if($contract_p_loan['payment_loan'] && $loan_payment_flg==true){
		// ローン申請状態提示
		$data[$result['room_id']][$i]['loan_status'] = "<br>ローン".$gLoanStatus[$contract_p_loan['loan_status']]."(契約番号:".$contract_p_loan['id'].")";
	}
	// 委任状なしにアラーム
	if($under_contract_flg==true && $contract_p_loan['payment_loan'] && $result['attorney_status']<>1){
		$staff_data[$staff_id][$i]['attorney_status'] = $data[$result['room_id']][$i]['attorney_status'] = "<font color=red>委任状：".$gAttorneyStatus[$result['attorney_status']]."</font>";
	}

	if($result['type'] == 2 && $contract['course_id'] ){
		$course = Get_Table_Row("course"," where id=".$contract['course_id']);
		$data[$result['room_id']][$i]['contract_part'] = $contract['contract_part'];
		$data[$result['room_id']][$i]['course_group'] = $course['group_id'];
		$data[$result['room_id']][$i]['course_type'] = $course['type'];
		$data[$result['room_id']][$i]['dis_type'] = $contract['dis_type'];
		$data[$result['room_id']][$i]['pay_type'] = $contract['pay_type'];
		$data[$result['room_id']][$i]['r_times'] = $contract['r_times'];
		$data[$result['room_id']][$i]['sales_id'] = $result['sales_id'];
	}
  }
// ここまで書き換え 2017/04/27 modify by shimada ////////////////////////////////////////////////////

 	// 施術予約で来店有り役務消化無しの場合アラーム,30分体験脱毛等を除外
  	$r_times_alarm = false ;
	if(($result['type']==2 || $result['type']==20) && $result['status']==11 && $result['length']>1){
		if(!$result['sales_id']) $r_times_alarm = true ;
		//elseif( !Get_Table_Col("sales","r_times"," where del_flg=0 and r_times<>0 and reservation_id=".$result['id'] ) ) $r_times_alarm = true ; // 既存
		elseif( !Get_Table_Col("sales","r_times_flg"," where del_flg=0 and r_times_flg<>0 and reservation_id=".$result['id'] ) ) $r_times_alarm = true ;

 	}

	//　担当者毎：カウンセリング予約ー＞カウンセリング担当、施術予約ー＞施術主担当、その他ー>受付担当
	if($result['type']==1 && $result['cstaff_id']) {
		//　担当者毎：カウンセリング予約ー＞カウンセリング担当
		$staff_id = $result['cstaff_id'];			
	
	}elseif($result['type']==2 && $result['tstaff_id']){
		//　担当者毎：施術予約ー＞施術主担当
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

	// 未成年、同意書なしにアラーム
	if($data[$result['room_id']][$i]['age']>0 && $data[$result['room_id']][$i]['age']<20 && $result['agree_status']==0){
		$staff_data[$staff_id][$i]['agree_status'] = $data[$result['room_id']][$i]['agree_status'] = "<font color=red>同意書：☓</font>";
	}

	// 学割証明なしにアラーム
	if($result['hopes_discount']){
		$staff_data[$staff_id][$i]['student_id'] = $data[$result['room_id']][$i]['student_id'] = "学割希望";
		if($result['hopes_discount'] && !$result['student_id']){
			$staff_data[$staff_id][$i]['student_id'] 	.= "<font color=red>(学生証明：☓)</font>";
			$data[$result['room_id']][$i]['student_id'] .= "<font color=red>(学生証明：☓)</font>";
		}
	}
	

	// 担当毎、ルーム毎格納
	$staff_data[$staff_id][$i]['id'] 				= $data[$result['room_id']][$i]['id'] 			= $result['id'];
	$staff_data[$staff_id][$i]['no'] 				= $data[$result['room_id']][$i]['no'] 			= $result['no'];
	$staff_data[$staff_id][$i]['name'] 				= $data[$result['room_id']][$i]['name'] 		= $result['name'];
	$staff_data[$staff_id][$i]['name_kana'] 		= $data[$result['room_id']][$i]['name_kana'] 	= $result['name_kana'] ? $result['name_kana'] : $result['name'];//なければ、name表示
	$staff_data[$staff_id][$i]['mail'] 				= $data[$result['room_id']][$i]['mail'] 		= $result['mail'];
	$staff_data[$staff_id][$i]['status0'] 			= $data[$result['room_id']][$i]['status0'] 		= $result['status'] ;//来店状況
	$staff_data[$staff_id][$i]['status'] 			= $data[$result['room_id']][$i]['status'] 		= $result['status'] ? "(".$gBookStatus[$result['status']].")" : '';//来店状況
	$staff_data[$staff_id][$i]['con_status'] 		= $data[$result['room_id']][$i]['con_status'] 	= $result['con_status'] ? "(".$gConfirmStatus[$result['con_status']].")" : '';//確認状況
	$staff_data[$staff_id][$i]['multiple_contract_id'] = $data[$result['room_id']][$i]['multiple_contract_id'] = $result['multiple_contract_id'] ;//複数契約情報 20160115 shimada

	$staff_data[$staff_id][$i]['3dmail_status'] 	= $data[$result['room_id']][$i]['3dmail_status'] = $result['3dmail_status'] ? "(".$g3DMailStatus[$result['3dmail_status']].")" : '';//確認状況
	$staff_data[$staff_id][$i]['premail_status'] 	= $data[$result['room_id']][$i]['premail_status'] = $result['premail_status'] ? "(".$gPreMailStatus[$result['premail_status']].")" : '';//確認状況
	$staff_data[$staff_id][$i]['preday_status'] 	= $data[$result['room_id']][$i]['preday_status'] = $result['preday_status'] ? "(".$gPreDayStatus[$result['preday_status']].")" : '';//確認状況
	$staff_data[$staff_id][$i]['today_status'] 		= $data[$result['room_id']][$i]['today_status'] = $result['today_status'] ? $gTodayStatus[$result['today_status']] : '';//確認状況

	$staff_data[$staff_id][$i]['adcode'] 			= $data[$result['room_id']][$i]['adcode'] 		= $adcode_list[$result['adcode']];
	$staff_data[$staff_id][$i]['ad_memo'] 			= $data[$result['room_id']][$i]['ad_memo'] 		= $adcode_memo[$result['adcode']];
	$staff_data[$staff_id][$i]['hope_time'] 		= $data[$result['room_id']][$i]['hope_time'] 	= $result['hope_time'];
	$staff_data[$staff_id][$i]['length'] 			= $data[$result['room_id']][$i]['length'] 		= $result['length'];
	$staff_data[$staff_id][$i]['special'] 			= $data[$result['room_id']][$i]['special'] 		= $result['special'];
	$staff_data[$staff_id][$i]['type'] 				= $data[$result['room_id']][$i]['type'] 		= $result['type'];
	$staff_data[$staff_id][$i]['ctype'] 			= $data[$result['room_id']][$i]['ctype'] 		= $result['ctype'];
if($authority_level<=6 || ($authority['id']==106 || $authority['id']==1449) && $result['ctype']<2)$staff_data[$staff_id][$i]['tel'] = $data[$result['room_id']][$i]['tel']		= $result['tel'] ? "<br>".$result['tel'] : "";
	$staff_data[$staff_id][$i]['referer_url'] 		= $data[$result['room_id']][$i]['referer_url'] 	= $result['referer_url'];
	$staff_data[$staff_id][$i]['rebook_flg'] 		= $data[$result['room_id']][$i]['rebook_flg'] 	= $result['rebook_flg'];
	$staff_data[$staff_id][$i]['reg_date'] 			= $data[$result['room_id']][$i]['reg_date'] 	= $result['reg_date'];

	$staff_data[$staff_id][$i]['hope_campaign'] 	= $data[$result['room_id']][$i]['hope_campaign']= $result['hope_campaign'] ? "(".$result['hope_campaign'].")" : "";
	$staff_data[$staff_id][$i]['memo'] 				= $data[$result['room_id']][$i]['memo'] 		= $result['memo'] ? "(".$result['memo'].")" : "";
	$staff_data[$staff_id][$i]['memo2'] 			= $data[$result['room_id']][$i]['memo2'] 		= $result['memo2'] ? "(".$result['memo2'].")" : "";
	$staff_data[$staff_id][$i]['memo3'] 			= $data[$result['room_id']][$i]['memo3'] 		= $result['memo3'] ? "(".$result['memo3'].")" : "";
	$staff_data[$staff_id][$i]['memo4'] 			= $data[$result['room_id']][$i]['memo4'] 		= $result['memo4'] ? "(".$result['memo4'].")" : "";
	$staff_data[$staff_id][$i]['r_times_alarm'] 	= $data[$result['room_id']][$i]['r_times_alarm']= $r_times_alarm;
	$staff_data[$staff_id][$i]['sv_flg'] 			= $data[$result['room_id']][$i]['sv_flg'] 		= $result['sv_flg'];
	$staff_data[$staff_id][$i]['loan_delay_flg'] 	= $data[$result['room_id']][$i]['loan_delay_flg'] 	= $result['loan_delay_flg'];
	$staff_data[$staff_id][$i]['digicat_ng_flg'] 	= $data[$result['room_id']][$i]['digicat_ng_flg'] 	= $result['digicat_ng_flg'];
	$staff_data[$staff_id][$i]['nextpay_end_ng_flg']= $data[$result['room_id']][$i]['nextpay_end_ng_flg'] 	= $result['nextpay_end_ng_flg'];
	$staff_data[$staff_id][$i]['nextpay_op_flg'] 	= $data[$result['room_id']][$i]['nextpay_op_flg'] 	= $result['nextpay_op_flg'];
	$staff_data[$staff_id][$i]['bank_ng_flg'] 		= $data[$result['room_id']][$i]['bank_ng_flg'] 	= $result['bank_ng_flg'];

	if($result['type']==2 && $result['tstaff_id']) 		$data[$result['room_id']][$i]['cstaff_id'] 	= "担当：".$staff_list[$result['tstaff_id']];
elseif($result['type']==1 && $result['cstaff_id']) 		$data[$result['room_id']][$i]['cstaff_id'] 	= "担当：".$staff_list[$result['cstaff_id']];
	
	if($result['type']==2 && $result['tstaff_sub1_id']) $staff_data[$staff_id][$i]['tstaff_sub1_id'] = "サブ担当1：".$staff_list[$result['tstaff_sub1_id']];
	if($result['type']==2 && $result['tstaff_sub2_id']) $staff_data[$staff_id][$i]['tstaff_sub2_id'] = "サブ担当2：".$staff_list[$result['tstaff_sub2_id']];

	if($result['type']==2 && $result['tstaff_sub1_id'] ){
	
		$staff_data[$result['tstaff_sub1_id']][$i]['id'] 				= $result['id'];
		$staff_data[$result['tstaff_sub1_id']][$i]['no'] 				= $result['no'];
		$staff_data[$result['tstaff_sub1_id']][$i]['name'] 				= $result['name'];
		$staff_data[$result['tstaff_sub1_id']][$i]['name_kana'] 		= $result['name_kana'] ? $result['name_kana'] : $result['name'];//なければ、name表示
		$staff_data[$result['tstaff_sub1_id']][$i]['hope_time'] 		= $result['hope_time'];
		$staff_data[$result['tstaff_sub1_id']][$i]['length'] 			= $result['length'];
		$staff_data[$result['tstaff_sub1_id']][$i]['type'] 				= $result['type'];
		$staff_data[$result['tstaff_sub1_id']][$i]['tstaff_sub1_id'] 	= "サブ担当1";
	}
	if($result['type']==2 && $result['tstaff_sub2_id'] ){
		
		$staff_data[$result['tstaff_sub2_id']][$i]['id'] 				= $result['id'];
		$staff_data[$result['tstaff_sub2_id']][$i]['no'] 				= $result['no'];
		$staff_data[$result['tstaff_sub2_id']][$i]['name'] 				= $result['name'];
		$staff_data[$result['tstaff_sub2_id']][$i]['name_kana'] 		= $result['name_kana'] ? $result['name_kana'] : $result['name'];//なければ、name表示
		$staff_data[$result['tstaff_sub2_id']][$i]['hope_time'] 		= $result['hope_time'];
		$staff_data[$result['tstaff_sub2_id']][$i]['length'] 			= $result['length'];
		$staff_data[$result['tstaff_sub2_id']][$i]['type'] 				= $result['type'];
		$staff_data[$result['tstaff_sub2_id']][$i]['tstaff_sub2_id'] 	= "サブ担当2";

	}
	$i++;
}

?>