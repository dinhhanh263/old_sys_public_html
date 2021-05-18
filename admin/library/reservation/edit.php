<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}

// $DOC_ROOT = empty($_SERVER['DOCUMENT_ROOT']) ? str_replace('/admin/library/reservation', '', dirname(__FILE__)) : $_SERVER['DOCUMENT_ROOT'];

require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'auth.php';
include_once( "../../lib/classes/encryption.php" );

if($authority['id']=="5"){
    header("Location: ../adcode/");
    exit();
}

// memo--------------------------------------------------------------------------------
// 次回予約新規、customer_id
// 来店回数
// dialog from jquery

// 設定--------------------------------------------------------------------------------

$table = "reservation";

// 区分リスト制御(パラメーターにtype設定時、typeの値 又はキャンセル以外選択不可にする)
if ($_POST['type']) {
	foreach($gResType4 as $key => $value) {
		if ($key != $_POST['type'] && $key != 3) {
			unset($gResType4[$key]);
		}
	}
}

// 半角スペースを全角スペースに統一
if($_POST['name']) 		$_POST['name'] 		= str_replace(" ", "　", $_POST['name']);

// 2スペースを1スペースに統一
if($_POST['name']) 		$_POST['name'] 		= str_replace("　　", "　", $_POST['name']);

// 半角スペースを全角スペースに統一
if($_POST['name_kana']) $_POST['name_kana'] = str_replace(" ", "　", $_POST['name_kana']);

// 2スペースを1スペースに統一
if($_POST['name_kana'])	$_POST['name_kana'] = str_replace("　　", "　", $_POST['name_kana']);

// 電話番号整形
if($_POST['tel']) $_POST['tel'] = sepalate_tel($_POST['tel']);


// 詳細を取得-----------------------------------------------------------------------------

if( $_POST['reservation_id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 AND id = '".addslashes($_POST['reservation_id'])."'");

	// 区分リスト制御(typeの値 又はキャンセル以外選択不可にする)
	if ($data['type']) {
		foreach ($gResType4 as $key => $value) {
			if ($key != $data['type'] && $key != 3) {
				unset($gResType4[$key]);
			}
		}
	}
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 AND id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 AND id = '".addslashes($data['shop_id'])."'");
	$customer_memo = Get_Table_Row("customer_memo"," WHERE del_flg=0 AND customer_id = '".addslashes($data['customer_id'])."'");
	if ($_POST['action'] != "edit") $_POST['shop_id'] = $shop['id']; // 店舗アカウントでのvacant_roomの表示対策

	// クーリングオフ歴チェック
	$cooling_off_flg = false;
	$dSql = "SELECT * FROM contract WHERE customer_id = " . $data['customer_id'] . " and del_flg = 0 and status = 2 ";
	$dRtn = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
	if ($dRtn->num_rows >= 1) {
		$cooling_off_flg = true;
	}

	// ローン申込済判定
	$loan_info_id = '';
	if( $data['contract_id'] ){
		$loan_info_id = Get_Table_Col("loan_info","id"," WHERE del_flg=0 AND contract_id=".$data['contract_id']);
		if(!$loan_info_id){
			$loan_info_id = Get_Table_Col("loan_info2","id"," WHERE del_flg=0 AND contract_id=".$data['contract_id']);
		}
	}
	// CC依頼事項
	// $cc_request = 0;
	// $last_visit_ym = '';
	// $stop_request_date = '';
	// // $cc_request_items = array();
	// if($cc_request_items = Get_Table_Row("request_items"," WHERE type=1 AND del_flg=0 AND end_flg=0 AND customer_id=".$customer['id']." ORDER BY id DESC LIMIT 1")){
	// 	$cc_request = $cc_request_items['status'];
	// 	$last_visit_ym = $cc_request_items['last_visit_ym'];
	// 	// $stop_request_date = (!$cc_request_items['stop_request_date'] || $cc_request_items['stop_request_date']=='0000-00-00') ? '' : $cc_request_items['stop_request_date'];
	// }
	//店舗依頼事項
	// $shop_request = 0;
	// $shop_request_items = array();
	// if($shop_request_items = Get_Table_Row("request_items"," WHERE type=2 AND del_flg=0 AND end_flg=0 AND customer_id=".$customer['id']." ORDER BY id DESC LIMIT 1")){
	// 	$shop_request = $shop_request_items['status'];
	// }
}
elseif( $_POST['contract_id'] || $_POST['customer_id'] != "" ) {
	$contract = Get_Table_Row("contract"," WHERE del_flg=0 AND id = '".addslashes($_POST['contract_id'])."'");
	$customer_id="";
	if(!$_POST['contract_id'] && $_POST['customer_id'] !="") {
		$customer_id = $_POST['customer_id'];
	} else if($_POST['contract_id']) {
		$customer_id = $contract['customer_id'];
	}
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 AND id = '".addslashes($customer_id)."'");
	$customer_memo = Get_Table_Row("customer_memo"," WHERE del_flg=0 AND customer_id = '".addslashes($customer_id)."'");

	// 次回予約新規時、区分を施術に暫定
	if(!$_POST['type'] && !$_POST['new_flg']) $_POST['type'] = 2;

	$course = Get_Table_Row("course", " WHERE del_flg=0 AND id = '" . addslashes($contract['course_id']) . "'");
	
	if( $_POST['shop_id'] != "" )$shop = Get_Table_Row("shop"," WHERE del_flg=0 AND id = '".addslashes($_POST['shop_id'])."'");

	// 次回予約新規時、コースのlengthにあったルームを選択
	if (!$_POST['room_id'] && !$_POST['new_flg']) {
		//整体コースは特殊部屋へ
        if ($shop['chiropractic_flg'] == 1 && $shop['special_rooms'] >= 1 && $course['treatment_type'] == 2) {
            $data['room_id'] = 71;
            //整体コース以外はcourse.lengthで部屋を選択
        } else if($course['length'] == 1 && $shop['thirty_time_rooms'] >= 1) {
			$data['room_id'] = 61;
		} else if ($course['length'] == 2 && $shop['sixty_time_rooms'] >= 1) {
			$data['room_id'] = 51;
		} else if ($course['length'] == 3 && $shop['ninety_time_rooms'] >= 1) {
			$data['room_id'] = 31;
		} else {
            //例外は31を選択
            $data['room_id'] = 31;
        }
	}

}
elseif( $_POST['shop_id'] != "" ){
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 AND id = '".addslashes($_POST['shop_id'])."'");
}elseif($authority_shop)$shop = $authority_shop;
else $shop = Get_Table_Row("shop"," WHERE del_flg=0 AND id = 1");

// ページ取得時の現在日時
$now_date = date('Y-m-d'); //現在の日付を取得
$now_time = intval(date('Gi')); // 1130の形で現在時刻を取得
$get_hope_time = $gTime[$data['hope_time']]; //予約時間を取得
$for_comparison_time = intval(mb_substr($get_hope_time,0,2).mb_substr($get_hope_time,3,2)); //文字列の予約時間を1130の形で数値に変換
$limit_time = $now_time - $for_comparison_time; //現在時刻 - 予約時刻の差を取得

// 編集or新規---------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {

	$post_shop_info = Get_Table_Row("shop"," WHERE del_flg=0 AND id = '".addslashes($_POST['shop_id'])."'");
	// end_date以降の予約は予約不可とする
	$contract_latest_array	= array();
	$course_latest_array	= array();
	$contract_end_date		= null;
	$hope_date_tmp			= null;
	$reseve_pos_flg			= true;

	// トリートメントの場合のみ
	if ($_POST['type'] == 2) {
		// $dataが取得されていない場合はpost値を使用する。（$data:更新する場合、$_POST:新規予約する場合）
		$contract_latest_id = !empty($data) ? $data['contract_id'] : $_POST['contract_id'];
		// 現在開いている予約に紐づくcontractの情報を取得
		$contract_latest_array = Get_Table_Row("contract", " WHERE del_flg=0 AND id = '" . $contract_latest_id . "'");
		// end_date
		$contract_end_date = strtotime($contract_latest_array['end_date']);
		// 予約日付
		$hope_date_tmp = strtotime($_POST['hope_date']);
		// コース情報を取得
		$course_latest_array = Get_Table_Row("course", " WHERE id = '".addslashes($contract_latest_array['course_id'])."' AND type = 0 AND (zero_flg = 0 OR zero_flg IS NULL) ");

		if (!empty($course_latest_array) && $contract_end_date < $hope_date_tmp) {
			if ($course_latest_array['id'] >= 1000 && $course_latest_array['times'] == 0 || $course_latest_array['sales_start_date'] >= '2019-11-06') {
				// コースIDが1000以上かつtimesが0の場合は除外、sales_start_dateが2019-11-06以降のコースを一時的に除外
				$reseve_pos_flg = true;
			} else {
				// 予約可能フラグ
				$reseve_pos_flg = false;
			}
		}
	}

	// 空き確認.他の予約との重なり、21時前に終了できるか
	if($_POST['reservation_id']) $where_id =" AND id<>".$_POST['reservation_id'];
	$sql = " WHERE del_flg=0 AND  type<>3 AND type<>14 ".$where_id . " AND hope_date='".addslashes($_POST['hope_date'])."' AND shop_id=".$_POST['shop_id']." AND room_id=".$_POST['room_id'];

	// 予約開始時間と比較(重なりあり)
	$sql .= " AND (hope_time<".$_POST['hope_time'] ." AND hope_time+length>".$_POST['hope_time'] ;

	// 予約終了時間と比較(重なりあり)
	$sql .= " OR hope_time<".($_POST['hope_time']+$_POST['length']) ." AND hope_time+length>".($_POST['hope_time']+$_POST['length']) ;
	$sql .= " OR hope_time>=".$_POST['hope_time'] ." AND hope_time+length<=".($_POST['hope_time']+$_POST['length']) . ")";

	// 他の人が変更があった場合、排他制御
	if( $data['id'] && $_POST['edit_date'] && ($data['edit_date'] <> $_POST['edit_date']) ){
		$gMsg = "<font color='red' size='-1'>※予約詳細情報が更新されています。現在の画面を閉じてもう一度画面を開いてください。</font>";
	}

	// end_date以降の予約
	elseif( !$reseve_pos_flg )
		$gMsg = "<font color='red' size='-1'>契約期間終了後のお手入れのご予約は取得来ません。</font>";
	// 名前が必須確認
	elseif( !$_POST['customer_id'] && !$_POST['name'] && !$_POST['name_kana'] )
		$gMsg = "<font color='red' size='-1'>※名前また名前（カナ）が必須です。</font>";

	//名前不正文字チェック #256不正文字チェック 2017/06/16 add by shimada
	elseif( Invalid_Characters_Check($_POST["name"]) )
	$gMsg = "<font color='red' size='-1'>※名前にご利用いただけない文字「".Invalid_Characters_Check($_POST['name'])."」が含まれています。</font>";

	// 本社とCC以外、「予約不可」の予約新規禁止
	elseif( ( $authority_level>6 && $authority['id']<>"106" && $authority['id']<>1449 ) && $_POST['reservation_id'] == "" && $customer['ctype']==101)
		$gMsg = "<font color='red' size='-1'>※店舗側で「予約不可」の新規予約ができません。</font>";

	// 本社とCC以外、過去変不可
	elseif( ( $authority_level>6 && $authority['id']<>"106" && $authority['id']<>1449 ) && $_POST['reservation_id'] && date("Y-m-d",strtotime("-1 month")) >$data['hope_date'])
		$gMsg = "<font color='red' size='-1'>※一ヶ月前の予約内容が変更不可です。</font>";

	// 名前スペース入れ確認
	elseif( $_POST['name'] && !strpos($_POST['name'], "　") || $_POST['name_kana'] && !strpos($_POST['name_kana'], "　") )
		$gMsg = "<font color='red' size='-1'>※姓と名の間にスペースを入れてください。</font>";

	//名前カナ文字チェック #256不正文字チェック 2017/06/16 add by shimada
	elseif( $_POST["name_kana"] && Invalid_Characters_Kana_Check($_POST["name_kana"]) )
	$gMsg = "<font color='red' size='-1'>※全角カタカナを入力してください。</font>";

	// shop未入力確認
	elseif(!$_POST['shop_id'])
		$gMsg = "<font color='red' size='-1'>※店舗を指定してください。</font>";
	// shop開店前予約不可
	// elseif ($post_shop_info['open_date'] && $post_shop_info['open_date'] > $_POST['hope_date'])
	// 	$gMsg = "<font color='red' size='-1'>" . $post_shop_info['open_date'] . "以降の日付を指定してください。</font>";
	// shop閉店後予約不可
	elseif ($post_shop_info['close_date'] && $post_shop_info['close_date'] < $_POST['hope_date'])
		$gMsg = "<font color='red' size='-1'>" . $post_shop_info['close_date']. "以前の日付を指定してください。</font>";
	// 日程未入力確認
	elseif(!$_POST['hope_date'])
		$gMsg = "<font color='red' size='-1'>※予約日程を指定してください。</font>";

	// メールアドレス確認
	// "/^([a-zA-Z0-9\])===>"/^([a-zA-Z0-9\._-]) に変更 2017/07/10 add by shimada
	elseif( $_POST['mail'] && (!preg_match("/^([a-zA-Z0-9._-])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['mail'])) )
		$gMsg = "<font color='red' size='-1'>※正しいメールアドレスを入力してください。</font>";

	// mail重複確認
	elseif(!$_POST['reservation_id'] && $_POST['mail'] && ($existed_cid = Get_Table_Col("customer","id"," WHERE del_flg=0 AND mail ='".$_POST['mail']."'" ) ))
		$gMsg = "<font color='red' size='-1'>※メールアドレスが既に存在しています。</font>";

	// tel重複確認
	elseif(!$_POST['reservation_id'] && $_POST['tel'] && ($existed_cid = Get_Table_Col("customer","id"," WHERE del_flg=0 AND tel ='".sepalate_tel($_POST['tel'])."'" ) ))
		$gMsg = "<font color='red' size='-1'>※電話番号が既に存在しています。</font>";

	// 全店舗に予約可能のが7/17 19:00まで
	elseif( $authority_level>1 && $_POST['hope_date']=="2014-07-17" && ($_POST['hope_time']+$_POST['length'])>17)
		$gMsg = "<font color='red' size='-1'>※達成会のため、予約不可です。</font>";

	// お正月、一周年記念日
	//elseif( $_POST['hope_date']>='2014-12-29' && $_POST['hope_date']<='2015-01-03' )
	//	$gMsg = "<font color='red' size='-1'>※全日予約不可です。</font>";

	// 日付移動禁止
	elseif( $authority_level>0 && $_post['reservation_id'] && $_POST['hope_date']<>$data['hope_date'])
	//elseif( $_post['reservation_id'] && $_POST['hope_date']<>$data['hope_date'])
		$gMsg = "<font color='red' size='-1'>※日付変更が不可です。「次の予約」で新規してください。</font>";

	// 未来日に「来店状況」変更禁止 2017/07/25 add by shimada
	// elseif( $authority_level>0 && $_POST['status']<>0 && date('Y-m-d')<$_POST['hope_date'])
	// 	$gMsg = "<font color='red' size='-1'>※未来日の来店状況を変更できません。</font>";

	elseif(!$_POST['shop_id'])
		$gMsg = "<font color='red' size='-1'>※店舗を指定してください。</font>";

	// 重複確認
	elseif( ($_POST['type']<3 || $_POST['type']>6) && Get_Table_Row($table,$sql)){
		$gMsg = "<font color='red' size='-1'>※他の予約との重なりがあります。予約変更ができませんでした。</font>";

	// 店舗側に、カウンセリングルーム４にカウンセリング予約以外をいれない //新規のみとなった
	//}elseif( !$_post['reservation_id'] && $_POST['room_id']==14 && !($_POST['type']==1 || $_POST['type']==3 ) && ($authority_level>1) ){
	//	$gMsg = "<font color='red' size='-1'>※トリートメントルームを選択してください。</font>";

	// 終了確認
	}elseif(($_POST['hope_time']+$_POST['length'])>21){
		$gMsg = "<font color='red' size='-1'>※終了時間がオーバーします。予約変更ができませんでした。</font>";

	// 店舗権限で担当者の必須確認、テストユーザーが対象外
	}elseif($authority_level>6 && $customer['ctype']<>101 && !($_POST['staff_id'] || $_POST['ccstaff_id'] || $_POST['cstaff_id']  || $_POST['tstaff_id'] )){
		$gMsg = "<font color='red' size='-1'>※いずれの担当が必要です。</font>";

	// (月額)最終来店月の形式確認
	// }elseif( $_POST['last_visit_ym'] && !preg_match("|^\d{4}\/\d{2}$|", $_POST['last_visit_ym']) ){
	// 	$gMsg = "<font color='red' size='-1'>※(月額)最終来店月の形式が不正です。</font>";

	// 引落ストップ依頼の必須確認
	// }elseif( $_POST['cc_request'] ==7 && (!$_POST['stop_request_date'] || $_POST['stop_request_date']=='0000-00-00') ){
	//	$gMsg = "<font color='red' size='-1'>※引落ストップ依頼日が必要です。</font>";

	}else{
		// POST処理
		$post_content = $_POST['content'];
		$post_verify_status = $_POST['verify_status'];
		// $post_cc_request = $_POST['cc_request'];
		// $post_shop_request = $_POST['shop_request'];
		// $post_last_visit_ym = $_POST['last_visit_ym'];
		$post_bad_debt_flg = htmlspecialchars($_POST['bad_debt_flg'], ENT_QUOTES);
		$post_terminate_pending_status = htmlspecialchars($_POST['terminate_pending_status'], ENT_QUOTES);

		/* if($_POST['cc_request'] ==7){
			$post_stop_request_date = $_POST['stop_request_date'];
		}else{
			$post_stop_request_date ='';
		} */
		// if($post_shop_request){
		// 	$post_pay_back = $_POST['pay_back'] ? $_POST['pay_back'] : 0;
		// }else{
		// 	$post_pay_back =0;
		// }
		// if($post_cc_request){
		// 	$post_pay_back_cc = $_POST['pay_back_cc'] ? $_POST['pay_back_cc'] : 0;
		// }else{
		// 	$post_pay_back_cc =0;
		// }
		$post_verify_complete_datetime = $_POST['verify_status'] == 2 ? date('Y-m-d H:i:s') : '';

		unset($_POST['content']);
		unset($_POST['verify_status']);
		// unset($_POST['cc_request']);
		// unset($_POST['shop_request']);
		// unset($_POST['last_visit_ym']);
		// unset($_POST['stop_request_date']);
		// unset($_POST['pay_back']);
		// unset($_POST['pay_back_cc']);
		unset($_POST['bad_debt_flg']);
		unset($_POST['terminate_pending_status']);

		// 編集------------------------------------------------------------------------

		if($_POST['reservation_id'] != "" ){
			if($_POST['route']==7){
				// HotPepper
				$_POST['adcode'] = 2335 ;
			}elseif($_POST['route']==9){
				// TGA
				$_POST['adcode'] = 4583 ;
			}
			// 前日架電回数確認
			if( $_POST['preday_status'] && !$_POST['preday_cnt'] ) $_POST['preday_cnt'] = 1;
			elseif( !$_POST['preday_status'] && $_POST['preday_cnt'] ) $_POST['preday_cnt'] = 0;

			// 予約時架電回数確認
			if( $_POST['today_status'] && !$_POST['today_cnt'] ) $_POST['today_cnt'] = 1;
			elseif( !$_POST['today_status'] && $_POST['today_cnt'] ) $_POST['today_cnt'] = 0;

			$_POST['edit_date'] = date("Y-m-d H:i:s");

			if ($_POST['type'] == 3 && $data['type'] != 3) {
			    $_POST['cancel_before_type'] = $data['type'];
			}

			// $reservation_field = array('shop_id','hope_date','edit_date','type','rsv_status','status','room_id','hope_time','persons','length','route','point','hp_flg','flyer_no','coupon','hope_time_range','hope_campaign','hopes_discount','staff_id','cstaff_id','memo2','memo','memo4','memo5','memo3',);
			
			//カウンセリング編集用
			if($_POST['type'] == 1 ) {
				$reservation_field = array("shop_id", "staff_id", "cstaff_id", "ccstaff_id", "room_id", "type", "rsv_status", "status", "preday_status","preday_cnt", "preday_staff_id", "today_status","today_cnt", "today_staff_id", "hope_date", "hope_time", "length", "persons", "hope_campaign", "hopes_discount", "hope_time_range", "echo", "introducer", "introducer_type", "route", "point","hp_flg","coupon", "flyer_no", "special", "edit_date","memo2","cancel_before_type");
			} else { 
			    // トリートメント・その他編集用
			    $reservation_field = array("shop_id", "staff_id", "cstaff_id", "ccstaff_id", "tstaff_id", "tstaff_sub1_id", "tstaff_sub2_id", "room_id", "type", "rsv_status", "status", "hope_date", "hope_time", "length", "persons", "echo", "introducer", "introducer_type", "route", "point", "hp_flg", "coupon", "flyer_no", "special", "edit_date","memo2","cancel_before_type");
			}

			$data_ID = Update_Data($table,$reservation_field,$_POST['reservation_id']);
						
			// HotPepper & TGA
			if($data['reg_date']==$customer['reg_date'] && $customer['adcode']<>'' && $_POST['route']==7){
				$GLOBALS['mysqldb']->query('UPDATE customer SET adcode="2335",edit_date="' . $_POST['edit_date'] . '" WHERE id=' . $data['customer_id']) or die('query error'.$GLOBALS['mysqldb']->error);
			}elseif($data['reg_date']==$customer['reg_date'] && $customer['adcode']<>'' && $_POST['route']==9){
				$GLOBALS['mysqldb']->query('UPDATE customer SET adcode="4583",edit_date="' . $_POST['edit_date'] . '" WHERE id=' . $data['customer_id']) or die('query error'.$GLOBALS['mysqldb']->error);
			}

			// 当選賞品 20171122
			if($_POST['prize']) {
				$GLOBALS['mysqldb']->query('UPDATE customer SET prize="' . $_POST['prize'] . '",edit_date="' . $_POST['edit_date'] . '" WHERE id=' . $data['customer_id']) or die('query error'.$GLOBALS['mysqldb']->error);
			}

			// 変更したカウンセリング担当が契約、施術テーブルに反映
			if($_POST['cstaff_id'] && $_POST['cstaff_id']<>$data['cstaff_id'] && $data['contract_id']){
				// 契約テーブルに反映
				$GLOBALS['mysqldb']->query('UPDATE contract SET staff_id="' . $_POST['cstaff_id'] . '",edit_date="' . $_POST['edit_date'] . '" WHERE id=' . $data['contract_id']) or die('query error'.$GLOBALS['mysqldb']->error);
				// 施術テーブルへ反映
				if($_POST['type']==1 && $data['sales_id'])	{
					$GLOBALS['mysqldb']->query('UPDATE sales SET staff_id="' . $_POST['cstaff_id'] . '",edit_date="' . $_POST['edit_date'] . '" WHERE id=' . $data['sales_id']) or die('query error'.$GLOBALS['mysqldb']->error);
				}
			}

			// 変更した施術担当が施術テーブルに反映
			if($_POST['tstaff_id'] && $_POST['tstaff_id']<>$data['tstaff_id'] && $data['sales_id']){
				$GLOBALS['mysqldb']->query('UPDATE sales SET staff_id="' . $_POST['tstaff_id'] . '",edit_date="' . $_POST['edit_date'] . '" WHERE id=' . $data['sales_id']) or die('query error'.$GLOBALS['mysqldb']->error);
			}

			// 変更した区分が施術テーブルに反映
			if(($_POST['type'] && $_POST['type']<>$data['type'] || $_POST['rsv_status'] && $_POST['rsv_status']<>$data['rsv_status']) && $data['sales_id']){
			    $s_type = !empty($_POST['rsv_status']) ? $_POST['rsv_status'] : $_POST['type'];
			    $GLOBALS['mysqldb']->query('UPDATE sales SET type="' . $s_type . '",edit_date="' . $_POST['edit_date'] . '" WHERE id=' . $data['sales_id']) or die('query error'.$GLOBALS['mysqldb']->error);
			}

			// 貸し倒れの有無をテーブルに反映
			if( is_numeric($post_bad_debt_flg) && ($post_bad_debt_flg == 0 || $post_bad_debt_flg == 1)) {
				$GLOBALS['mysqldb']->query('UPDATE contract SET bad_debt_flg=' . $post_bad_debt_flg . ' WHERE id=' . $data['contract_id']) or die('query error'.$GLOBALS['mysqldb']->error);
			}

			// 自動解約状況をテーブルに反映
			if( is_numeric($post_terminate_pending_status) ){
				$GLOBALS['mysqldb']->query('UPDATE contract SET terminate_pending_status=' . $post_terminate_pending_status . ' WHERE id=' . $data['contract_id']) or die('query error'.$GLOBALS['mysqldb']->error);
			}

		// 次の予約新規（施術）------------------------------------------------------------

		}elseif($_POST['mode']=="new_rsv" && ($_POST['customer_id'] || $_POST['contract_id'])){

			$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
			$reservation_field = array("contract_id",
										"customer_id",
										"shop_id",
										"staff_id",
										"cstaff_id",
										"ccstaff_id",
										"tstaff_id",
										"tstaff_sub1_id",
										"tstaff_sub2_id",
										"room_id",
										"course_id",
										"type",
										"rsv_status",
										"status",
										"hope_date",
										"hope_time",
										"length",
										"persons",
										"echo",
										"introducer",
										"introducer_type",
										"route",
										"point",
										"hp_flg",
										"coupon",
										"flyer_no",
										"special",
										"memo2",
										"reg_date",
										"edit_date"
									);

			// 新規契約フラッグ。20141205
			if($_POST['new_flg']) array_push($reservation_field, "new_flg");
			// 当選賞品 20171122
			if($_POST['prize']) array_push($reservation_field, "prize");

			$data_ID = Input_New_Data($table ,$reservation_field);

		// カウンセリング新規--------------------------------------------------------------

		}else{

			// 顧客新規
			$_POST['password'] = generateID(6,'smallalnum');
			$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");

			$customer_field = array("no",
									"sn_shop",
									"cstaff_id",
									"password",
									"ctype",
									"name",
									"name_kana",
									"age",
									"birthday",
									"tel",
									"mail",
									"shop_id",
									"introducer",
									"introducer_type",
									"route",
									"flyer_no",
									"special",
									"hope_campaign",
									"hopes_discount",
									"hope_time_range",
									"reg_date",
									"edit_date",
								);
			if($_POST['route']==7){
				// HotPepper
				$_POST['adcode'] = 2335 ;
				array_push($customer_field,
									 'adcode');
			}elseif($_POST['route']==9){
				// TGA
				$_POST['adcode'] = 4583 ;
				array_push($customer_field,
									 'adcode');
			}

			// 当選賞品 20171122
			if($_POST['prize']) array_push($customer_field, "prize");

			$_POST['customer_id'] = Input_New_Data("customer",$customer_field);

			// 会員番号自動付与
			$shop_code = Get_Table_Col("shop","code"," WHERE id={$_POST['shop_id']} ");

			$result  = $GLOBALS['mysqldb']->query( "SELECT * FROM customer ORDER BY id DESC LIMIT 1" ) or die('query error'.$GLOBALS['mysqldb']->error);
			if($result){
				while ( $row = $result->fetch_assoc()){
					$GLOBALS['mysqldb']->query('UPDATE customer SET no="' . $shop_code . $row['id'] . '" WHERE id=' . $row['id']) or die('query error'.$GLOBALS['mysqldb']->error);
				}
			}

			// 予約新規
			$_POST['new_flg'] = 1 ;
			$reservation_field = array("contract_id",
										"customer_id",
										"shop_id",
										"staff_id",
										"cstaff_id",
										"ccstaff_id",
										"room_id",
										"course_id",
										"type",
										"rsv_status",
										"status",
										"preday_status",
										"preday_staff_id",
										"today_status",
										"today_staff_id",
										"hope_date",
										"hope_time",
										"length",
										"persons",
										"hope_campaign",
										"hopes_discount",
										"hope_time_range",
										"echo",
										"introducer",
										"introducer_type",
										"route",
										"point",
										"hp_flg",
										"coupon",
										"flyer_no",
										"special",
										"memo2",
										"reg_date",
										"edit_date",
										"new_flg"
									);
			if($_POST['route']==7){
				// HotPepper
				$_POST['adcode'] = 2335 ;
				array_push($reservation_field, 'adcode');
			}elseif($_POST['route']==9){
				// TGA
				$_POST['adcode'] = 4583 ;
				array_push($reservation_field, 'adcode');
			}
			// 当選賞品 20171122
			if($_POST['prize']) array_push($reservation_field, "prize");

			$data_ID = Input_New_Data($table ,$reservation_field);

      		//vip・モデル用新規登録後、無料コース登録 add by ueda 20160917
      		if($_POST['ctype']==3){
        	  	$_POST["reservation_id"] = $data_ID;
        	  	$_POST["end_date"] = '3000-01-01';
        	  	$_POST["course_id"] = '1003';
        	  	$contract_field = array("status","reservation_id","customer_id","course_id","end_date","reg_date","edit_date");
        	  	$contract_ID = Input_New_Data("contract" ,$contract_field);
        	  	unset($_POST["reservation_id"]);
        	  	unset($_POST["end_date"]);
        	  	unset($_POST["course_id"]);
      		}
      		
			//エステモデル系新規登録後、無料コース登録
			if($_POST['ctype']==6){
				$_POST["reservation_id"] = $data_ID;
				$_POST["end_date"] = '3000-01-01';
				$_POST["course_id"] = '1018';
				$contract_field = array("status","reservation_id","customer_id","course_id","end_date","reg_date","edit_date");
				$contract_ID = Input_New_Data("contract" ,$contract_field);
				unset($_POST["reservation_id"]);
				unset($_POST["end_date"]);
				unset($_POST["course_id"]);
			}
			//レジ用データ登録?
		}

		// Myapge連絡内容格納(sv権限以上)
        if( $customer['no'] && $authority_level<=6 ){
			$existed_content = Get_Table_Row("contact_info"," WHERE del_flg=0 AND no='".$customer['no'] ."' ORDER BY id DESC LIMIT 1");
			if( $existed_content['id'] ){
				// 連絡内容変更がある場合は格納
				if($existed_content['content']<>$post_content ){
					$GLOBALS['mysqldb']->query('UPDATE contact_info SET content="'.$post_content.'",edit_date="'.date("Y-m-d H:i:s").'" WHERE no="'.$customer['no'].'"') or die('query error'.$GLOBALS['mysqldb']->error);
				}
			// 新規の場合、記入内容ありのみ
			}elseif($post_content){
				$GLOBALS['mysqldb']->query('INSERT contact_info SET content="'.$post_content.'",no="'.$customer['no'].'" ') or die('query error'.$GLOBALS['mysqldb']->error);
			}
        }

        // ベリファイ確認状態格納

        if( $loan_info_id && $authority_level==0){
			$GLOBALS['mysqldb']->query('UPDATE loan_info SET verify_status="'.$post_verify_status.'",verify_complete_datetime="'.$post_verify_complete_datetime.'",edit_date="'.date('Y-m-d H:i:s').'" WHERE id="'.$loan_info_id.'" ') or die('query error'.$GLOBALS['mysqldb']->error);
		}

		// CC依頼事項格納
        // if( $post_cc_request<>$cc_request || $post_last_visit_ym<>$last_visit_ym || $cc_request_items['id'] && $post_pay_back_cc<>$cc_request_items['pay_back_cc'] ){
		// 	// 元依頼事項を終了させる
		// 	if($cc_request_items['id']){
		// 		$GLOBALS['mysqldb']->query('UPDATE request_items SET end_flg=1,end_date=now(),last_visit_ym="'.$post_last_visit_ym.'",pay_back_cc="'.$post_pay_back_cc.'"  WHERE id="'.$cc_request_items['id'].'" ') or die('query error'.$GLOBALS['mysqldb']->error);
		// 	}
		// 	if($post_cc_request){
		// 		$GLOBALS['mysqldb']->query('INSERT INTO request_items (type,customer_id,contract_id,shop_id,reservation_id,pay_back_cc,status,last_visit_ym)
		// 			values(1,'.$customer['id'].','.$_POST['contract_id'].','.$_POST['shop_id'].','.$data_ID.',"'.$post_pay_back_cc.'",'.$post_cc_request.',"'.$post_last_visit_ym.'")') or
		// 		die('query error'.$GLOBALS['mysqldb']->error);
		// 	}
		// }

		// 店舗依頼事項格納
        // if( $post_shop_request<>$shop_request || $shop_request_items['id'] && $post_pay_back<>$shop_request_items['pay_back']){
		// 	// 元依頼事項を終了させる
		// 	if($shop_request_items['id']){
		// 		$GLOBALS['mysqldb']->query('UPDATE request_items SET end_flg=1,end_date=now(),pay_back="'.$post_pay_back.'" WHERE id="'.$shop_request_items['id'].'" ') or die('query error'.$GLOBALS['mysqldb']->error);
		// 	}
		// 	if($post_shop_request){
		// 		$GLOBALS['mysqldb']->query('INSERT INTO request_items (type,customer_id,contract_id,shop_id,reservation_id,pay_back,status)
		// 			values(2,'.$customer['id'].','.$_POST['contract_id'].','.$_POST['shop_id'].','.$data_ID.',"'.$post_pay_back.'",'.$post_shop_request.')')
		// 		or die('query error'.$GLOBALS['mysqldb']->error);
		// 	}
		// }

		// 顧客備考格納
		if ($_POST['customer_id']) {

			// 更新
			if ($_POST['customer_memo_id']) {
				$_POST['edit_date'] = date("Y-m-d H:i:s");
				$customer_memo_field = array(
					"memo_shop",
					"memo_cc",
					"memo_loan",
					"memo_head_office",
					"edit_date",
				);
				$_POST['customer_memo_id'] = Update_Data("customer_memo",$customer_memo_field,$_POST['customer_memo_id']);
			// 新規
			} else {
				$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
				$customer_memo_field = array(
					"customer_id",
					"memo_shop",
					"memo_cc",
					"memo_loan",
					"memo_head_office",
					"reg_date",
					"edit_date",
				);
				$_POST['customer_memo_id'] = Input_New_Data("customer_memo",$customer_memo_field);
			}
		}

		if( $data_ID ){

			// 顧客一覧(CC)へ
			if($_POST['from_cc']) header( "Location: ../customer/cc.php?id=".$data_ID."&shop_id=".$_POST['shop_id']."&hope_date=".$_POST['hope_date']);

			// 予約表へ
			else header( "Location: ../main/?id=".$data_ID."&shop_id=".$_POST['shop_id']."&hope_date=".$_POST['hope_date']);
		}
		else $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
	}
}


// 売上詳細取得-----------------------------------------------------------------------------

if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 AND id = '".addslashes($data['sales_id'])."'");
else $sales= array();

// 契約詳細取得-----------------------------------------------------------------------------

if($_POST['hope_date'])$where_contract = " AND (contract_date <= '".$_POST['hope_date']."' OR contract_date='0000-00-00')";
if($data['contract_id'] != 0 ) $contract = Get_Table_Row("contract"," WHERE customer_id <>0 AND del_flg=0 AND id = '".addslashes($data['contract_id'])."'");
elseif($_POST['contract_id'] != 0 ) $contract = Get_Table_Row("contract"," WHERE customer_id <>0 AND del_flg=0 AND id = '".addslashes($_POST['contract_id'])."'");
elseif(!$_POST['new_flg'] && !$data['new_flg']) $contract = Get_Table_Row("contract"," WHERE customer_id <>0 AND customer_id = '".addslashes($data['customer_id'] ? $data['customer_id'] : $customer['id'])."' AND del_flg=0 ".$where_contract." ORDER BY status in (0,7) desc, contract_date desc, id desc");

// 新契約詳細取得-----------------------------------------------------------------------------

if($contract['new_contract_id'] != 0 ) {
	$new_contract = Get_Table_Row("contract"," WHERE customer_id <>0 AND del_flg=0 AND id = '".addslashes($contract['new_contract_id'])."'");
	// 変更後の契約内容取得
	$new_course = Get_Table_Row("course"," WHERE del_flg=0 AND id = '".$contract['new_course_id']."'");
} else {
	$new_contract=array();
}

if ($contract['old_contract_id'] != 0 && $contract['conversion_flg'] == 0) {
	$change_old_contract = Get_Table_Row("contract", " WHERE customer_id <>0 AND del_flg=0 AND status=4 AND id='" . addslashes($contract['old_contract_id']) . "'");
	if(isset($change_old_contract)) {
		$change_old_payed_price = $change_old_contract['price'] - $change_old_contract['balance'];
		$change_old_per_price = round(($change_old_contract['fixed_price'] - $change_old_contract['discount']) / $change_old_contract['times']);
	}
}

	// 旧契約情報取得---------------------------------------------------------------------------
	$old_customer_shop = Get_Table_Row("shop"," WHERE del_flg=0 AND id = '".addslashes($contract['shop_id'])."'");
	//単価
	$old_per_price = round(($contract['fixed_price']-$contract['discount'])/$contract['times']);
	// 未消化回数
	$remain_times = $contract['times']>$contract['r_times'] ? ($contract['times']-$contract['r_times']) : 0;
	$per_fixed_price = round($contract['fixed_price']);
	// 支払済金額
	$payed_price = $contract['price'] - $contract['balance'];
	//新契約単価
	$new_per_price = round(($new_contract['fixed_price']-$new_contract['discount'])/$new_contract['times']);


// トリートメントカルテ取得-----------------------------------------------------------------------------

if($data['id']) $karte = Get_Table_Row("karte"," WHERE del_flg=0 AND reservation_id = '".addslashes($data['id'])."'");
else $karte = array();

// 入力したデータを保つ------------------------------------------------------------------------------
if($_POST){
	foreach ($_POST as $key => $value) {
		$data[$key] = $value;
	}
}

// 店舗リスト-------------------------------------------------------------------------------

$shop_list = getDatalist("shop");
$shop_lists = getDatalistArray3("shop","area", $gShops_priority);

// courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM course WHERE del_flg = 0 AND status=2 ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$course_list = array();
$course_type = array();
$course_new_flg = array();
while ( $result2 = $course_sql->fetch_assoc() ) {
	$course_list[$result2['id']] = $result2['name'];
	$course_type[$result2['id']] = $result2['type'];// コース区分 0.パック、1.月額
	$course_new_flg[$result2['id']] = $result2['new_flg']; // 新月額フラグ 0.旧月額、1.新月額
	$course_minor_plan_flg[$result2['id']] = $result2['minor_plan_flg']; //未成年プランフラグ
	$course_treatment_type[$result2['id']] = $result2['treatment_type'];
}

$cancel_name = $course_type[$contract['course_id']] ? "月額退会" : "中途解約";

// staff list
if($data['id'])$staff_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM staff WHERE del_flg = 0 AND (status=2 OR status=1 AND end_day<>'0000-00-00' AND end_day>='".$data['hope_date']."')".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
else $staff_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// ccstaff list
if($data['id'])$ccstaff_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM staff WHERE del_flg = 0 AND (status=2 OR status=1 AND end_day<>'0000-00-00' AND end_day>='".$data['hope_date']."') AND shop_id=999 ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
else $ccstaff_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM staff WHERE del_flg = 0 AND status=2 AND shop_id=999 ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$ccstaff_list[0] = "-";
while ( $result1 = $ccstaff_sql->fetch_assoc() ) {
	$ccstaff_list[$result1['id']] = $result1['name'];
}

// room_list部屋情報設定
function room_lists($shop_id,$shop,$hope_date,$CounselingRoomName,$ninetyTimeRoomsName,$VIPRoomName,$OtherRoomName,$CounselingRooms,$VIPRooms,$OtherRooms,$sixtyTimeRoomsName,$thirtyTimeRoomsName,$specialRoomsName){ 

  $counseling_rooms = $shop['counseling_rooms'] ? $shop['counseling_rooms'] : $CounselingRooms;
  $vip_rooms = $shop['vip_rooms'] ? $shop['vip_rooms'] : $VIPRooms;
  $ninety_time_rooms = $shop['ninety_time_rooms'];
  $sixty_time_rooms = $shop['sixty_time_rooms'];
  $thirty_time_rooms = $shop['thirty_time_rooms'];
  $special_rooms = $shop['special_rooms'];

  // $_POST['shop_id'] = $shop_id;
  // $_POST['hope_date'] = $hope_date;
  // $room_availability_url = $_SERVER['DOCUMENT_ROOT'].'/admin/library/main/room_availability.php';
	// include($room_availability_url);

  for ($i = 1; $i <= $counseling_rooms; $i++) {
    $no = "1".$i;
    $room_list[$no] = $CounselingRoomName.$i;
  }

  for ($i = 1; $i <= $vip_rooms; $i++) {
      $no = "2".$i;
      $room_list[$no] = $VIPRoomName.$i;
	}

  for ($i = 1; $i <= $ninety_time_rooms; $i++) {
    $no = "3".$i;
    $room_list[$no] = $ninetyTimeRoomsName.$i;
  }

  if ($shop['esthetics_flg'] == 1) {
    for ($i = 1; $i <= $sixty_time_rooms; $i++) {
        $no = "5" . $i;
        $room_list[$no] = $sixtyTimeRoomsName . $i;
    }
    for ($i = 1; $i <= $thirty_time_rooms; $i++) {
        $no = "6" . $i;
        $room_list[$no] = $thirtyTimeRoomsName . $i;
    }
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
    $room_list[$no] = $OtherRoomName.$i;
  }
  return $room_list;
}
$room_list = room_lists($shop_id,$shop,$hope_date,$CounselingRoomName,$ninetyTimeRoomsName,$VIPRoomName,$OtherRoomName,$CounselingRooms,$VIPRooms,$OtherRooms,$sixtyTimeRoomsName,$thirtyTimeRoomsName,$specialRoomsName);

// specialリスト
$special_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM special WHERE del_flg = 0 AND status=0 ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$special_list = array();
if ($special_sql) {
	$special_list[0] = "-";
	while ( $result = $special_sql->fetch_assoc() ) {
		$special_list[$result['id']] = $result['name'];
	}
}

if($contract['course_id']) $course = Get_Table_Row("course"," WHERE del_flg=0 AND id = '".addslashes($contract['course_id'])."'");
else $course = array();

if($contract['times']){
	$per_fixed_price = round($contract['fixed_price']/$contract['times']);
	$per_price = round(($contract['fixed_price']-$contract['discount'])/$contract['times']);
	if($contract['discount'])$per_discount = round($contract['discount']/$contract['times']);
}
if($contract['payment_cash']) $pay_type = "現金";
elseif($contract['payment_card']) 	$pay_type = "カード";

//旧プラン月額、新プランパックでで新プランの消化がされていない場合、未来日の予約に紐つく契約を修正可能
if (isset($new_course) && isset($new_contract) && $course['type'] == 1 && $new_course['type'] == 0 && $new_contract['r_times'] == 0) {
	$edit_reservation = Get_Table_Row($table, " WHERE del_flg=0 AND type=2 AND hope_date>=CURDATE() AND sales_id=0 AND customer_id = '" . $customer['id'] . "'");
	if($edit_reservation!= "") {
		$edit_reservation_flg = true;
		$edit_reservation_param = "?customer_id=".$customer['id'].
		                          "&old_contract_id=".$contract['id'].
	 	                          "&new_contract_id=".$new_contract['id'];
	}
}

$old_course = Get_Table_Row("course", " WHERE del_flg=0 AND id = '" . $contract['old_course_id'] . "'");

if(isset($old_course) && $course['type'] == 0 && $old_course['type'] == 1 && $contract['r_times'] == 0) {
	$edit_reservation = Get_Table_Row($table, " WHERE del_flg=0 AND type=2 AND hope_date>=CURDATE() AND sales_id=0 AND customer_id = '" . $customer['id'] . "'");
	if ($edit_reservation != "") {
		$edit_reservation_flg = true;
        $edit_reservation_param = "?customer_id=" . $customer['id'] .
                                  "&old_contract_id=" . $contract['old_contract_id'] .
								  "&new_contract_id=" . $contract['id'];
	}
}

// 新規契約の場合、前の入金を合算しない
// ローン取消5の場合
// if(($contract['new_contract_id'] || $contract['old_contract_id'])){
// 	$contract_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM contract WHERE del_flg=0 AND customer_id = '".addslashes($data['customer_id'])."' AND reg_date <= '".$contract['reg_date']."' ORDER BY id DESC LIMIT 2") or die('query error'.$GLOBALS['mysqldb']->error);
// 	$i=0;

// 	// 二重プラン変更制御は？
// 	while ( $contract_result = $contract_sql->fetch_assoc() ) {
// 		if($contract['id']<>$contract_result['id'] && ($contract_result['times']<=2 && $contract_result['status']==4 || $contract_result['times']<=$contract_result['r_times'] ) ) continue; //月額からのプラン変更の場合、月額の合算しない。契約満了のデータがプラン変更の時合算しない。

// 		$contract_sum[0]['payment_card'] 	+= $contract_result['payment_card'];
// 		$contract_sum[0]['payment_cash'] 	+= $contract_result['payment_cash'];
// 		$contract_sum[0]['payment_transfer']+= $contract_result['payment_transfer'];
// 		if($contract_result['status']<>5)
// 		$contract_sum[0]['payment_loan'] 	+= $contract_result['payment_loan'];

// 		if(!$i)$contract_sum[0]['balance'] = $contract_result['balance'];
// 		$i++;
// 	}
// }

if(!is_array($contract_sum)){
	$contract_sum[0]['payment_card']=$contract['payment_card'];
	$contract_sum[0]['payment_cash']=$contract['payment_cash'];
	$contract_sum[0]['payment_transfer']=$contract['payment_transfer'];
	$contract_sum[0]['payment_loan']=$contract['payment_loan'];
	$contract_sum[0]['balance']=$contract['balance'];
}

$contract['memo'] = '';

// 契約書に店舗情報が契約店舗基準
$customer_shop = Get_Table_Row("shop"," WHERE del_flg=0 AND id = '".addslashes($contract['shop_id'])."'");

// マイページ出力用（パスワードのカナ変換対応）
$str_kana_fields = preg_split("//u", $customer['password'], -1, PREG_SPLIT_NO_EMPTY);
$kana = array();
foreach ($str_kana_fields as $key => $s_val) {

	// str_kanaテーブルの対応表から検索する
	$kana = Get_Table_Row("str_kana"," WHERE str = '".addslashes($str_kana_fields[$key])."'");
	$kanas[] = $kana['kana'];
}

// マイページのパスワード（カタカナ）
if (empty($kanas)) {
    $kanas = '';
} else {
    $kanas = implode(' ', $kanas);
}

//$customer_shop['name'] = $customer_shop['old_name'] ? $customer_shop['old_name']:$customer_shop['name'];
$customer_shop['name'] = $customer_shop['name'];

// 契約書
// 2016/10/18 「契約期間」の出しわけ機能を追加 add by shimada
// 月額契約者 && 施術開始予定(年月)が登録されている 場合に限り、下記の表示を変更する
//   ⇒契約書に表示する「契約期間」の内容： 開始日：契約日～終了日：施術開始予定(年月)の末日
// 新月額の方のみ下記の表示を変更する
//   ⇒契約書に表示する「回数」「単価」「割引後単価」の内容：
//     .回数：1回、単価：コース金額、割引後単価：請求金額(※請求金額を回数で割らないで表示する)
if($course_type[$contract['course_id']] && $contract['start_ym']<>0){
	// 施術開始年月
	$start_ym = $contract['start_ym'].'01';
	// 契約日の月と施術開始年月の月が同じ場合の契約期間は契約日～とする 2017/03/07 add by shimada
	if($contract['start_ym'] == date('Ym', strtotime($contract['contract_date']))){
		// 契約日：契約日～
		$contract_date = $contract['contract_date'];
	} else {
		// 契約日：施術開始年月の初日～
	$contract_date = date('Y-m-d', strtotime($start_ym.'-01'));
	}
	//未成年プランの時のend_dateはcontractのend_dateを使用
	if($course_minor_plan_flg[$contract['course_id']] == 1) {
        //end_dateの月の1日を取得
        $beginning_of_end_date = substr($contract['end_date'], 0, 8).'01';
        //施術期間ターム開始月・終了月
        $start_term_month = date('Y/m',strtotime($contract['start_ym'].'01'));
        $end_term_month_prev = date('Y/m',  strtotime($beginning_of_end_date.' -1 day'));
        //比較用
        $start_month = substr($start_term_month, 0, 4); //開始月
        $start_month_next = date('Y',  strtotime($contract['start_ym'].'01 +1 month')); //開始月翌月
        $end_month_prev = substr($end_term_month_prev, 0, 4); //終了月前月
        $end_month = date('Y',strtotime($contract['end_date'])); //終了月

        //施術開始月とその翌月が、年をまたいでいるかどうかで表示切り替え
        if ($start_month === $start_month_next) {
            $start_term_month_next = date('m',  strtotime($contract['start_ym'].'01 +1 month'));
        } else {
            $start_term_month_next = date('Y/m',  strtotime($contract['start_ym'].'01 +1 month'));
        }

        //施術終了月とその前月が年をまたいでいるかどうかで表示切り替え
        if ($end_month_prev === $end_month) {
            $end_term_month = date('m',strtotime($contract['end_date']));
        } else {
            $end_term_month = date('Y/m',strtotime($contract['end_date']));
        }

	} else {
		// 契約終了日 ※日付6桁を8桁に変換し、施術開始予定(年月)の末日を取得する
		$end_date = date('Y-m-d', strtotime('+1 month last day of ' . $start_ym));
	}
	$times = $contract['times']; // 回数
	// 新月額は回数1回として単価・割引後単価を表示させる
	if($course['new_flg']==1){
		$times = 1;// 回数
		$per_fixed_price = round($contract['fixed_price']);
		$per_price = round($contract['fixed_price']-$contract['discount']);
		if($contract['discount'])$per_discount = round($contract['discount']);
	}
} else {
	// 契約開始日
	$contract_date = $contract['contract_date'];
	// パック契約の方は契約データから終了日を取得する
	$end_date = $contract['end_date'];
	// パックの回数
	$times = $contract['times'];

	// SPプラン以外の場合、保証期間を設定
	if (!($course['zero_flg'] == "1" && $course['sales_start_date'] >= '2019-11-06')) {
		// 保証期間開始日
		$guarantee_start_date = date("Y-m-d", strtotime($end_date.'+1 day'));
		// 保証期間終了日
		$guarantee_last_date = $contract['extension_end_date'];
	}

}

$monthly_pause_flg = false;
if($contract['status'] == 11) {
	$monthly_pause = Get_Table_Row("monthly_pause", " WHERE del_flg=0 AND contract_id = '" . addslashes($contract['id']) . "' order by pause_start_date desc limit 1");
	$monthly_pause_flg = true;
}
$pdf_param = "?shop_id=".$customer_shop['id'].
			 "&shop_name=".$customer_shop['name'].
			 "&shop_address=".$customer_shop['address'].
			 "&shop_tel=".$customer_shop['tel'].
			 "&no=".$customer['no'].
			 "&name=".($customer['name'] ? $customer['name'] : $customer['name_kana']).
			 "&name_kana=".$customer['name_kana'].
			 "&birthday=".$customer['birthday'].
			 "&address=".$customer['address'].
			 "&tel=".$customer['tel'].
			 "&course_name=".$course_list[$contract['course_id']].
			 "&fixed_price=".$contract['fixed_price'].
			 "&discount=".$contract['discount'].
			 "&price=".$contract['price'].
			 "&payment_cash=".$contract_sum[0]['payment_cash'].
			 "&payment_card=".$contract_sum[0]['payment_card'].
			 "&payment_transfer=".$contract_sum[0]['payment_transfer'].
			 "&payment_loan=".$contract_sum[0]['payment_loan'].
			 "&payment_coupon=".$contract['payment_coupon'].
			 "&length=".str_replace("分", "", $gLength[$course['length']]).
			 //"&option_name=".$gOption[$contract['option_name']].
			 "&option_name=".$contract['option_name'].
			 "&option_price=".$contract['option_price'].
			 "&balance=".$contract_sum[0]['balance'].
			 "&hope_date=".$contract['contract_date'].
			 "&times=".$times.
			 "&contract_date=".$contract_date.
			 "&end_date=".$end_date.
			 "&memo=".$contract['memo'].
			 "&mp=".$customer['password'].
			 "&staff=".$staff_list[$contract['staff_id']].
			 "&pay_type=".$pay_type.
			 "&mp_kana=".$kanas.
			 "&guarantee_start_date=".$guarantee_start_date.
			 "&guarantee_last_date=".$guarantee_last_date;
if($per_fixed_price) $pdf_param.=  "&per_fixed_price=".$per_fixed_price;
if($per_price) $pdf_param.= "&per_price=".$per_price;
if ($change_old_contract) {
	$pdf_add = "&old_contract_r_times=" . $change_old_contract['r_times'] . "&old_per_price=" . $change_old_per_price . "&old_payed_price=" . $change_old_payed_price;
	$pdf_param .= $pdf_add;
}

// プラン組替通知書パラメーター
$pdf_param2 = "?shop_id=".$old_customer_shop['id'].
			 "&shop_name=".$old_customer_shop['name'].
			 "&shop_address1=".$old_customer_shop['address'].
			 "&shop_tel=".$old_customer_shop['tel'].
			 "&no=".$customer['no'].
			 "&name=".($customer['name'] ? $customer['name'] : $customer['name_kana']).
			 "&name_kana=".$customer['name_kana'].
			 "&birthday=".$customer['birthday'].
			 "&address=".$customer['address'].
			 "&tel=".$customer['tel'].
			 "&old_course_name=".$course_list[$contract['course_id']].
			 "&hope_date=".$contract['contract_date'].
			 "&times=".$contract['times'].
			 "&contract_date=".$contract['contract_date'].
			 "&end_date=".$contract['end_date'].
			 "&staff=".$staff_list[$new_contract['staff_id']].
			 "&cancel_date=".$contract['cancel_date'].
			 "&per_price=".$old_per_price.
			 "&payed_price=".$payed_price.
			 "&r_times=".$contract['r_times'].
			 "&remain_times=".$remain_times.
			 "&new_course_name=".$course_list[$new_contract['course_id']].
			 "&new_fixed_price=".$new_contract['fixed_price'].
			 "&new_discount=".$new_contract['discount'].
			 "&new_price=".$new_contract['price'].
			 "&new_times=".$new_contract['times'].
			 "&new_per_price=".$new_per_price.
			 "&new_balance=".$new_contract['balance'].
			 "&new_contract_date=".$new_contract['contract_date'].
			 "&new_end_date=".$new_contract['end_date'].
			 "&new_payment=".$new_contract['payment'].
			 "&new_payment_cash=".$new_contract['payment_cash'].
			 "&new_payment_card=".$new_contract['payment_card'].
			 "&new_payment_transfer=".$new_contract['payment_transfer'].
			 "&new_payment_loan=".$new_contract['payment_loan'].
			 "&option_name=".$new_contract['option_name'];

// 新月額のみコースタイプを送る 2017/06/30 add by shimada
if($course_type[$contract['course_id']] && $contract['start_ym']<>0)$pdf_param.= "&course_type=".$course_type[$contract['course_id']];

// 特例トリートメント同意書 PDF
$pdf_param5 = "?name=".$customer['name'].
			  "&zip=".$customer['zip'].
			  "&address=".$customer['address'].
			  "&customer_no=".$customer['no'];

// 保証期間延長申請書 PDF
$pdf_param6 ="?name=".$customer['name'].
			 "&zip=".$customer['zip'].
			 "&address=".$customer['address'].
			 "&customer_no=".$customer['no'].
			 "&tel=".$customer['tel'].
			 "&course_name=".$course_list[$contract['course_id']].
			 "&p_start=".$contract['contract_date'].
			 "&p_end=".$contract['end_date'].
			 "&shop_name=".$shop_list[$data['shop_id']].
			 "&course_id=".$contract['course_id'].
			 "&customer_id=".$customer['id'].
			 "&contract_id=".$contract['id'].
			 "&staff_name=".$staff_list[$data['tstaff_id']];
if($contract['extension_flg'] == 1){
	$pdf_param6.= "&extension_flg=1"."&extension_edit_date=".$contract['extension_edit_date'];;
}

// トリートメント時アンケート
if($data['id'] && $data['type'] !== 1){
	$anser_check = Get_Table_Col('q_answer','reservation_id',' WHERE del_flg=0 AND reservation_id = '.$data['id']);
	if($anser_check !== ""){
	  $q_flg = 1;
	}else{
		$q_dode = $encryption->encode($data['id']);
	}
}



// 年齢計算
$now = date('Ymd');
if($customer['birthday'] && $customer['birthday']<>"0000-00-00"){
	if(strstr($customer['birthday'], "/")){
		list($year,$month,$day) = explode("/", $customer['birthday']);
		if($month<10) $month = "0".$month;
		if($day<10) $day = "0".$day;
	}else{
		list($year,$month,$day) = explode("-", $customer['birthday']);
	}
	$birthday = $year.$month.$day;
	$age = floor(($now-$birthday)/10000);
}elseif($customer['age']){
	$age = $customer['age'];
}

// 経由設定
$route = $_POST['route'] ? $_POST['route'] : ($data['route'] ? $data['route'] : (($authority['id']==106 || $authority['id']==1449) ? 2 : ($authority_level>=7 ? 6 : 0)));


// チラシ番号リスト
$flyer_no_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM adcode WHERE del_flg = 0 AND flyer_no<>'' ORDER BY flyer_no" ) or die('query error'.$GLOBALS['mysqldb']->error);
if ($flyer_no_sql) {
	$flyer_no_list = array();
	while ( $result = $flyer_no_sql->fetch_assoc() ) {
		$flyer_no_list[$result['id']] = $result['flyer_no'];
	}
}

// クーポンリスト

$hope_date = $_POST['hope_date'] ? $_POST['hope_date'] : ( $data['hope_date'] ? $data['hope_date'] : date("Y-m-d") );
$coupon_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM item_coupon WHERE del_flg = 0 AND start_date<='".$hope_date."' AND end_date>='".$hope_date."' ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
if ($coupon_sql) {
	$coupon_list= array();
	while ( $result = $coupon_sql->fetch_assoc() ) {
		$coupon_list[$result['id']] = $result['name'];
		$coupon_type[$result['id']] = $result['type'];
	}
}

// 賞品リスト
// $prize_date = substr($data['reg_date'],0,10) ? substr($data['reg_date'],0,10) : date("Y-m-d") ;
$prize_date = ($customer['prize'] && $customer['prize']<6) ? $data['reg_date'] : date("Y-m-d H:i:s") ;
$prize_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM item_prize WHERE del_flg = 0 AND start_date<='".$prize_date."' AND end_date>='".$prize_date."' ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
if ($prize_sql) {
	$prize_list= array();
	while ( $result = $prize_sql->fetch_assoc() ) {
		$prize_list[$result['id']] = $result['name'];
	}
}

// 新規予約時は最後に登録した予約から備考を取得
if($_GET['mode']=="new_rsv")  {
	$pre_rsv = Get_Table_Row($table," WHERE del_flg=0 AND customer_id = '".addslashes($customer['id'])."' ORDER BY id DESC LIMIT 1");
}

// 広告の店舗表示文章
if( $customer['adcode'] != "" )  {
	$ad_memo = Get_Table_Col("adcode","memo"," WHERE del_flg=0 AND id = '".addslashes($customer['adcode'])."' ");

	// if(strstr($ad_memo,"当選。")) $lottery_flg = 1;
	// else $lottery_flg = 0;
}

// 友達紹介利用者判定
if( $_POST['reservation_id'] || $_POST['customer_id'] != "" ){
	$introducer_customer_id = Get_Table_Col("introducer","customer_id"," WHERE del_flg=0 AND introducer_customer_id=".$data['customer_id']);
	if($introducer_customer_id != ""){
		$introducer_memo = "友達紹介。友達紹介適応してください。備考記入不要です。" ;
	}
}

// ローンベリファイ確認状態
$loan_verify_status = '';
if( $loan_info_id ){
	$loan_verify_status = Get_Table_Col("loan_info","verify_status"," WHERE del_flg=0 AND id=".$loan_info_id);
	$loan_apl_flg = Get_Table_Col("loan_info","apl_flg"," WHERE del_flg=0 AND id=".$loan_info_id);
}

// 連絡板情報
$contact_detail = '';
if( $customer['no'] ){
	$contact_detail = Get_Table_Col("contact_info","content"," WHERE del_flg=0 AND no='".$customer['no']."' ORDER BY id DESC LIMIT 1");
}

// ローン会社リスト----------------------------------------------------------------------------
if($contract['loan_company_id']) $loan_company_list = getDatalist("loan_company");

// パック施術間隔アラート用
$baseDate = "";
if (!empty($course['id']) && $course['type'] == 0) {
	// 最終お手入れ履歴情報取得
    $reservLastInfo = Get_Table_Row("reservation"," where del_flg=0 and type in(2,30) and status=11 and length >=2 and customer_id=".$customer["id"]." and hope_date<=CURDATE() order by hope_date desc limit 1");
    $reservHopeDate = getItem("hope_date", $reservLastInfo);
	$baseDate = date("Y-m-d",getBaseDate($course['type'], $reservHopeDate, $contract['contract_date'],false,$course['newFlg'],$contract['r_times'],$course));
}

// 新月額クーリングオフ対応用
$monthlyBaseDate = "";
if (!empty($course['id']) && $course['type'] == 1 && $course['new_flg'] == 1) {
	// 最終お手入れ履歴情報取得
    $reservLastInfo = Get_Table_Row("reservation"," where del_flg=0 and type in(2,30) and status=11 and length >=2 and customer_id=".$customer["id"]." and hope_date<=CURDATE() order by hope_date desc limit 1");
	$reservHopeDate = getItem("hope_date", $reservLastInfo);

    if($reservHopeDate == "") {
		// 施術開始年月
		$startYm0 = getItem("start_ym", $contract);
		// 最新タム施術開始年月
		$startYm = get_currentYm($startYm0);
		$firstYm = strtotime($startYm."01");
		$delayDays = Get_Table_Col("basic","value"," where id = 4");          // 初回施術猶予期間
		$regDateAfterNext = strtotime("+$delayDays day",strtotime($contract['contract_date'])); // 契約日+8日(クーリングオフ期間)
		// 施術開始年月の初日 < 契約日+8日 なら、契約日+8日を設定する
		if($firstYm < $regDateAfterNext){
			// 契約日+8日を設定する
		    $monthlyBaseDate = $regDateAfterNext;
		} else {
			// 施術開始年月の月初日から予約をできるようにする
		    $monthlyBaseDate = $firstYm;
		}
		// 算出した予約可能開始日と当日のチェック
		if ($monthlyBaseDate < strtotime("0day")) {
		    $monthlyBaseDate = strtotime("0day");
		}
		$monthlyBaseDate = date("Y-m-d",$monthlyBaseDate);
	}
}

// 複数件予約アラート用
$feature_reservation_flg = 0;
if (empty($_GET['id']) && $_GET['mode'] == "new_rsv" && !empty($_GET['customer_id'])) {
    $feature_reservation = Get_Table_Row($table," WHERE del_flg=0 AND type=2 AND hope_date>CURDATE() AND customer_id = '".addslashes($_GET['customer_id'])."'");
    if ($feature_reservation != "") {
        $feature_reservation_flg = 1;
    }
}

// 現在の契約情報取得
if (!empty($customer['id'])) {
    $current_contract = Get_Table_Row("contract"," WHERE del_flg=0 AND status = 0 AND customer_id = '".addslashes($customer['id'])."' ORDER BY contract_date desc,id desc limit 1");
    if(!empty($current_contract['course_id'])) {
        $current_course = Get_Table_Row("course"," WHERE del_flg=0 AND id = '".addslashes($current_contract['course_id'])."'");
    }
}

$next_reserve_flg = false;
if($_GET['reservation_id'] != "" && $contract ) {
	$next_reserve_flg = true;
}
$holidayJsonList = json_encode($holidayList);
$weekdaysPlanTypeJSON = json_encode($weekdays_plan_type);

//未成年プラン残り半年のアラート用
$minor_plan_alert_flg = false;
if($course['minor_plan_flg'] == 1) {
	$twenty_birthday = strtotime('+20 year', strtotime($customer['birthday']));
	$minor_plan_alert_date = strtotime($minor_plan_alert_days, $twenty_birthday);

	if(strtotime($data['hope_date']) >= $minor_plan_alert_date) {
		$minor_plan_alert_flg=true;
	}
}
