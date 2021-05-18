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

// 複数契約IDの設定
if($_POST['multiple_contract_id']) $_POST['multiple_contract_id'] = implode(",", $_POST['multiple_contract_id']);


// 詳細を取得-----------------------------------------------------------------------------

if( $_POST['id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
}
elseif( $_POST['customer_id'] != "" )  {
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($_POST['customer_id'])."'");

	// 次回予約新規時、区分を施術に暫定
	if(!$_POST['type'] && !$_POST['new_flg']) $_POST['type'] = 2;	

	// 次回予約新規時、施術ルーム１に暫定		
	if(!$_POST['room_id'] && !$_POST['new_flg']) $_POST['room_id'] = 31;	
	if( $_POST['shop_id'] != "" )$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($_POST['shop_id'])."'");
	
}
elseif( $_POST['shop_id'] != "" ){
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($_POST['shop_id'])."'");
}elseif($authority_shop)$shop = $authority_shop;
else $shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = 1");

// 複数契約情報 契約中のデータのみ取得(次の予約時)
$data['hope_date'] = $data['hope_date'] ? $data['hope_date'] : date('Y-m-d');// 予約日付が入っていない場合は当日の日付を入れる 2017/04/19 add by shimada
if($_POST['hope_date'])$where_contract = " and (cancel_date >= '".$data['hope_date']."' OR cancel_date='0000-00-00') and contract_date <= '".$data['hope_date']."'";
if($_POST['customer_id'] != 0 )$all_contract = Get_Table_Array("contract","*", " WHERE del_flg=0 and status in (0,7) and customer_id = '".addslashes($_POST['customer_id'])."'".$where_contract); 
if($_POST['customer_id'] != 0 )$all_contract_p_whole = Get_Table_Array("contract_P","*", " WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."'"); //複数親契約情報取得
if($_POST['contract_id'] != 0 )$current_contract_p = Get_Table_Row("contract_P"," WHERE customer_id <>0 and del_flg=0 and id = '".addslashes($data['pid'])."'");


// 編集or新規---------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {

	// 空き確認.他の予約との重なり、21時前に終了できるか
	if($_POST['id']) $where_id =" AND id<>".$_POST['id'];
	$sql = " WHERE del_flg=0 and  type<>3 and type<>21 and type<>22 and type<>14 ".$where_id . " AND hope_date='".addslashes($_POST['hope_date'])."' AND shop_id=".$_POST['shop_id']." AND room_id=".$_POST['room_id'];
	
	// 予約開始時間と比較(重なりあり)
	$sql .= " AND (hope_time<".$_POST['hope_time'] ." AND hope_time+length>".$_POST['hope_time'] ;										
	
	//予約終了時間と比較(重なりあり)
	$sql .= " OR hope_time<".($_POST['hope_time']+$_POST['length']) ." AND hope_time+length>".($_POST['hope_time']+$_POST['length']) ;	
	$sql .= " OR hope_time>=".$_POST['hope_time'] ." AND hope_time+length<=".($_POST['hope_time']+$_POST['length']) . ")";

	// 他の人が変更があった場合、排他制御
	if( $data['id'] && $_POST['edit_date'] && ($data['edit_date'] <> $_POST['edit_date']) ){
		$gMsg = "<font color='red' size='-1'>※予約詳細情報が更新されています。現在の画面を閉じてもう一度画面を開いてください。</font>";
	}
	
	// 名前が必須確認
	elseif( !$_POST['customer_id'] && !$_POST['name'] && !$_POST['name_kana'] )
		$gMsg = "<font color='red' size='-1'>※名前また名前（カナ）が必須です。</font>";

	//名前不正文字チェック #256不正文字チェック 2017/06/16 add by shimada
	elseif( Invalid_Characters_Check($_POST["name"]) )
	$gMsg = "<font color='red' size='-1'>※名前にご利用いただけない文字「".Invalid_Characters_Check($_POST['name'])."」が含まれています。</font>";

	// 本社とCC以外、過去変不可
	elseif( ( $authority_level>6 && $authority['id']<>"106") && $_POST['id'] && date("Y-m-d",strtotime("-1 month")) >$data['hope_date'])
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

	// 日程未入力確認
	elseif(!$_POST['hope_date'])
		$gMsg = "<font color='red' size='-1'>※予約日程を指定してください。</font>";

	// メールアドレス確認
	// "/^([a-zA-Z0-9\])===>"/^([a-zA-Z0-9\._-]) に変更 2017/07/10 add by shimada
	elseif( $_POST['mail'] && (!preg_match("/^([a-zA-Z0-9._-])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['mail'])) )
		$gMsg = "<font color='red' size='-1'>※正しいメールアドレスを入力してください。</font>";

	// mail重複確認
	elseif(!$_POST['id'] && $_POST['mail'] && ($existed_cid = Get_Table_Col("customer","id"," WHERE del_flg=0 AND mail ='".$_POST['mail']."'" ) ))
		$gMsg = "<font color='red' size='-1'>※メールアドレスが既に存在しています。<a href='edit.php?mode=new_rsv&type=1&customer_id=".$existed_cid."&shop_id=".$_POST['shop_id']."&room_id=".$_POST['room_id']."&hope_date=".$_POST['hope_date']."&hope_time=".$_POST['hope_time']."'>こちらより予約してください。</a></font>";
	
	// tel重複確認
	elseif(!$_POST['id'] && $_POST['tel'] && ($existed_cid = Get_Table_Col("customer","id"," WHERE del_flg=0 AND tel ='".sepalate_tel($_POST['tel'])."'" ) ))
		$gMsg = "<font color='red' size='-1'>※電話番号が既に存在しています。<a href='edit.php?mode=new_rsv&type=1&customer_id=".$existed_cid."&shop_id=".$_POST['shop_id']."&room_id=".$_POST['room_id']."&hope_date=".$_POST['hope_date']."&hope_time=".$_POST['hope_time']."'>こちらより予約してください。</a></font>";
	
	// 全店舗に予約可能のが7/17 19:00まで
	elseif( $authority_level>1 && $_POST['hope_date']=="2014-07-17" && ($_POST['hope_time']+$_POST['length'])>17)
		$gMsg = "<font color='red' size='-1'>※達成会のため、予約不可です。</font>";

	// お正月、一周年記念日
	//elseif( $_POST['hope_date']>='2014-12-29' && $_POST['hope_date']<='2015-01-03' )
	//	$gMsg = "<font color='red' size='-1'>※全日予約不可です。</font>";
	
	// 日付移動禁止
	elseif( $authority_level>0 && $_POST['id'] && $_POST['hope_date']<>$data['hope_date'])
	//elseif( $_POST['id'] && $_POST['hope_date']<>$data['hope_date'])
		$gMsg = "<font color='red' size='-1'>※日付変更が不可です。「次の予約」で新規してください。</font>";

	elseif(!$_POST['shop_id'])
		$gMsg = "<font color='red' size='-1'>※店舗を指定してください。</font>";
	
	// 重複確認
	elseif( ($_POST['type']<3 || $_POST['type']>6) && Get_Table_Row($table,$sql)){
		// // プラン変更の場合、部屋の競合はエラーとしない
		// if($_POST['type']==10){
		// 	$gMsg = "test";
		// } else {
			$gMsg = "<font color='red' size='-1'>※他の予約との重なりがあります。予約変更ができませんでした。</font>";
		// }

	// 店舗側に、カウンセリングルーム４にカウンセリング予約以外をいれない //新規のみとなった
	//}elseif( !$_POST['id'] && $_POST['room_id']==14 && !($_POST['type']==1 || $_POST['type']==3 ) && ($authority_level>1) ){
	//	$gMsg = "<font color='red' size='-1'>※トリートメントルームを選択してください。</font>";

	// 終了確認
	}elseif(($_POST['hope_time']+$_POST['length'])>21){
		$gMsg = "<font color='red' size='-1'>※終了時間がオーバーします。予約変更ができませんでした。</font>";

	// 担当者必須確認
	}elseif($authority_level>6 && !($_POST['staff_id'] || $_POST['ccstaff_id'] || $_POST['cstaff_id']  || $_POST['tstaff_id'] )){
		$gMsg = "<font color='red' size='-1'>※いずれの担当が必要です。</font>";

	}else{

		// 編集------------------------------------------------------------------------

		if($_POST['id'] != "" ){
			if($_POST['route']==7){
				// HotPepper
				$_POST['adcode'] = 121 ;
			}
			$_POST['edit_date'] = date("Y-m-d H:i:s");
			$data_ID =  Input_Update_Data($table);

			// HotPepper
			if($data['reg_date']==$customer['reg_date'] && $customer['adcode']<>'' && $_POST['route']==7){
				$GLOBALS['mysqldb']->query('update customer set adcode="121",edit_date="' . $_POST['edit_date'] . '" where id=' . $data['customer_id']);
			}

			// 変更したカウンセリング担当が契約、施術テーブルに反映
			if($_POST['cstaff_id'] && $_POST['cstaff_id']<>$data['cstaff_id'] && $data['contract_id']){
				// 契約テーブルに反映
				$GLOBALS['mysqldb']->query('update contract set staff_id="' . $_POST['cstaff_id'] . '",edit_date="' . $_POST['edit_date'] . '" where id=' . $data['contract_id']) or die('query error'.$GLOBALS['mysqldb']->error);
				// 施術テーブルへ反映
				if($_POST['type']==1 && $data['sales_id'])	{
					$GLOBALS['mysqldb']->query('update sales set staff_id="' . $_POST['cstaff_id'] . '",edit_date="' . $_POST['edit_date'] . '" where id=' . $data['sales_id']) or die('query error'.$GLOBALS['mysqldb']->error);
				}		
			}

		// 次の予約新規（施術）------------------------------------------------------------

		}elseif($_POST['mode']=="new_rsv" && $_POST['customer_id']){
			$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
			$reservation_field = array("multiple_contract_id","contract_id","customer_id","shop_id","staff_id","cstaff_id","ccstaff_id","tstaff_id","tstaff_sub1_id","tstaff_sub2_id","room_id","course_id","type","rsv_status","hope_date","hope_time","length","persons","echo","introducer","introducer_type","route","point","hp_flg","coupon","flyer_no","special","cc_request","memo","memo2","memo3","memo4","reg_date","edit_date");
			
			// 新規契約フラッグ。20141205
			if($_POST['new_flg']) array_push($reservation_field, "new_flg");

			// プラン変更時メンズの場合、「ルーム」「所要時間」には値を入れない
			// if($_POST['type']==10){
			// 	$_POST['room_id'] =0; // ルーム
			// 	$_POST['length']  =0; // 所要時間
			// }

			$data_ID = Input_New_Data($table ,$reservation_field);

		// カウンセリング新規--------------------------------------------------------------

		}else{	
			
			// 顧客新規
			$_POST['password'] = generateID(6,'smallalnum');
			$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");

			$customer_field = array("no","sn_shop","cstaff_id","password","ctype","name","name_kana","age","birthday","tel","mail","shop_id","introducer","introducer_type","route","flyer_no","special","hope_campaign","hopes_discount","hope_time_range","reg_date","edit_date");
			if($_POST['route']==7){
				// HotPepper
				$_POST['adcode'] = 121 ; 
				array_push($customer_field, 'adcode');
			}

			$_POST['customer_id'] = Input_New_Data("customer",$customer_field);

			// 会員番号自動付与
			$shop_code = Get_Table_Col("shop","code"," WHERE id={$_POST['shop_id']} ");

			$result  = $GLOBALS['mysqldb']->query( "select * from customer ORDER BY id desc limit 1" );
			if($result){
				while ( $row = $result->fetch_assoc()){
					$GLOBALS['mysqldb']->query('update customer set no="' . $shop_code . str_repeat("0",(5-strlen($row['id']))).$row['id'] . '" where id=' . $row['id']);
				}
			}

			// 予約新規
			$_POST['new_flg'] = 1 ;
			// 体験キャンペーン希望/希望しない
			if($_POST['hope_campaign_checked'] ==1){
				$_POST['hope_campaign'] = "体験キャンペーン希望しない";
			} elseif($_POST['hope_campaign_checked'] ==2){
				$_POST['hope_campaign'] = "体験キャンペーン希望";
			}
			$reservation_field = array("contract_id","customer_id","shop_id","staff_id","cstaff_id","ccstaff_id","room_id","course_id","type","rsv_status","status","preday_status","preday_staff_id","today_status","today_staff_id","hope_date","hope_time","length","persons","hope_campaign_checked","hope_campaign","hopes_discount","hope_time_range","echo","introducer","introducer_type","route","flyer_no","special","cc_request","memo","memo2","memo3","memo4","reg_date","edit_date","new_flg");
			if($_POST['route']==7){
				// HotPepper
				$_POST['adcode'] = 121 ; 
				array_push($reservation_field, 'adcode');
			}

			$data_ID = Input_New_Data($table ,$reservation_field);

			//レジ用データ登録?
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

if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");

// 契約詳細取得-----------------------------------------------------------------------------

// if($_POST['hope_date'])$where_contract = " and (contract_date <= '".$_POST['hope_date']."' or contract_date='0000-00-00')";
if($_POST['hope_date'])$where_contract = " and (cancel_date >= '".$data['hope_date']."' OR cancel_date='0000-00-00') and contract_date <= '".$data['hope_date']."'";
if($data['pid'] != 0 ){
	$contract = Get_Table_Row("contract"," WHERE customer_id <>0 and del_flg=0 and id = '".addslashes($data['contract_id'])."'");
	if($data['pid'] != 0 ) $current_contract_p = Get_Table_Row("contract_P"," WHERE customer_id <>0 and del_flg=0 and id = '".addslashes($data['pid'])."'");
	elseif($_POST['contract_id'] != 0 ) $contract = Get_Table_Row("contract"," WHERE customer_id <>0 and del_flg=0 and id = '".addslashes($_POST['contract_id'])."'");
	elseif(!$_POST['new_flg'] && !$data['new_flg']) $contract = Get_Table_Row("contract"," WHERE customer_id <>0 and customer_id = '".addslashes($data['customer_id'] ? $data['customer_id'] : $customer['id'])."' and del_flg=0 and (status=0 or status=5 or status=6 or status=7) ".$where_contract." order by contract_date desc,id desc"); // 契約中コース指定status=0,5
} 
if($data['customer_id'] != 0 ){
	// 契約中の情報すべて
	$all_contract     = Get_Table_Array("contract","*", " WHERE del_flg=0 and customer_id = '".addslashes($data['customer_id'])."'".$where_contract); //複数契約情報 契約中のデータのみ取得
	$all_pack_contract= Get_Table_Array("contract","*", " WHERE del_flg=0 and times<>1 and customer_id = '".addslashes($data['customer_id'])."'".$where_contract); //複数契約情報 契約中の1回コース以外取得
	$all_contract_p   = Get_Table_Array("contract_P","*", " WHERE del_flg=0 and customer_id = '".addslashes($data['customer_id'])."'"); //複数親契約情報取得
	$all_contract_p_whole = Get_Table_Array("contract_P","*", " WHERE del_flg=0 and customer_id = '".addslashes($data['customer_id'])."'".$where_contract); //複数親契約情報取得
	// このページで契約した情報（pidごと）
	$current_contract = Get_Table_Array("contract","*", " WHERE del_flg=0 and old_contract_id=0 and pid = '".addslashes($data['pid'])."'".$where_contract);
	$current_one_contract = Get_Table_Array("contract","*", " WHERE del_flg=0 and times=1 and pid = '".addslashes($data['pid'])."'".$where_contract); // 1回コース契約時
}

// 表示条件検索
$loan = Search_Contract_Status($all_contract_p_whole);				    // ローン支払情報
$cooling_off_flg = Search_Contract_Status($all_contract_p_whole,2);		// 1.クーリングオフがある
$cancel_flg = Search_Contract_Status($all_contract_p_whole,3);			// 1.中途解約がある
$auto_cancel_flg = Search_Contract_Status($all_contract_p_whole,6);		// 1.自動解約がある
$change_flg = Search_Contract_Status($all_contract,4);				    // 1.プラン変更がある

// 2016/4/23 下記、使わない方針になるためコメントアウト
// 契約中コースチェック欄に表示する契約情報リスト
// プラン変更区分・プラン変更データありの場合、当日適応は新コースを出し、翌日適応は旧コースを出す
//if($change_flg['status_flg']==1 && $data['type']==10){
	//$change_list_where = " WHERE t.del_flg=0 and t.customer_id = '".addslashes($data['customer_id'])."'";
	//$change_list_where .= " and ((t.if_cancel_date=0  and t.cancel_date='".$data['hope_date']."' and t.status = 4 )or (t.if_cancel_date=0  and  t.status = 0 ))";
	//$change_list_where .= " and t.id NOT IN (SELECT t2.new_contract_id FROM contract t2 WHERE t2.if_cancel_date=0 and t2.cancel_date='".$data['hope_date']."' and t2.status = 4)";
	//$change_list_where .= " and (contract_date <= '".$_POST['hope_date']."')";
	//$all_contract  = Get_Table_Array_Multi("contract t","t.* ", $change_list_where);
//}

// 複数契約数
$all_contract_count = count($all_contract);
$all_pack_contract_count = count($all_pack_contract);

// ** 今回の契約数（パック、および1回コースの契約数によってフラグをたてる処理 **
// もしも今回の契約コースに1回コースが含まれていないときは1回コースのみの契約書を出したくない、
// という場合は、下記のコメントアウトを解いて使用してください。 20160525 shimada
// $one_course_only_flg = false;
// $current_contract_count = count($current_contract); // 今回のパック+1回コース
// $current_one_contract_count = count($current_one_contract); // 1回コースの契約数
// $one_course_diff = $current_contract_count -$current_one_contract_count;
// if($one_course_diff==0)$one_course_only_flg = true; // 1回コースのみの契約フラグ

// 新契約詳細取得-----------------------------------------------------------------------------

if($contract['new_contract_id'] != 0 ) $new_contract = Get_Table_Row("contract"," WHERE customer_id <>0 and del_flg=0 and id = '".addslashes($contract['new_contract_id'])."'");
// プラン変更済のコース(1回コース以外)
$change_contract = Get_Table_Array("contract","*", " WHERE del_flg=0 and status =4 and times<>1 and customer_id = '".addslashes($data['customer_id'])."'"); 
// $change_contract = Get_Table_Array("contract","*", " WHERE del_flg=0 and status =4 and times<>1 and customer_id = '".addslashes($data['customer_id'])."' and contract_date <= '".addslashes($data['hope_date'])."'"); 
// $new_change_contract = Get_Table_Array("contract","*", " WHERE del_flg=0 and new_contract_id=0 and old_contract_id<>0 and times<>1 and customer_id = '".addslashes($data['customer_id'])."' and contract_date= '".addslashes($data['hope_date'])."'"); 
// 予約日と同じ日にプラン変更した契約データ
if(is_array($change_contract)){
	foreach ($change_contract as $key => $value) {
		$new_contract_id[] = $value['new_contract_id'];	
	}
}
// 新契約情報を取得する
$new_contract_id = implode(",", $new_contract_id);
// 予約日に契約した新契約IDのコース情報のみ取得する
if($new_contract_id)$new_change_contract = Get_Table_Array("contract","*", " WHERE customer_id <>0 and del_flg=0 and id in (".addslashes($new_contract_id).") and contract_date='".$data['hope_date']."'");

// トリートメントカルテ取得-----------------------------------------------------------------------------
if($data['id']) $karte = Get_Table_Row("karte"," WHERE del_flg=0 and reservation_id = '".addslashes($data['id'])."'");

// トリートメントカルテ取得-----------------------------------------------------------------------------
if($data['id']) $karte_c = Get_Table_Row("karte_c"," WHERE del_flg=0 and customer_id = '".addslashes($data['customer_id'])."'");

// カウンセリングカルテ取得-----------------------------------------------------------------------------
if($data['id']) $sheet = Get_Table_Row("sheet"," WHERE del_flg=0 and customer_id = '".addslashes($data['customer_id'])."'");


// 入力したデータを保つ------------------------------------------------------------------------------

if($_POST){
	foreach ($_POST as $key => $value) {
		$data[$key] = $value;
	}
}

// 店舗リスト-------------------------------------------------------------------------------

$shop_list = getDatalist_shop();

// courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 ORDER BY id" );
while ( $result2 = $course_sql->fetch_assoc() ) {
	$course_list[$result2['id']] = $result2['name'];
	$course_type[$result2['id']] = $result2['type'];
}

$cancel_name = $course_type[$contract['course_id']] ? "月額退会" : "中途解約";

// staff list
if($data['id'])$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND (status=2 or status=1 and end_day<>'0000-00-00' and end_day>='".$data['hope_date']."')".$where_shop." ORDER BY id" );
else $staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// ccstaff list
if($data['id'])$ccstaff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND (status=2 or status=1 and end_day<>'0000-00-00' and end_day>='".$data['hope_date']."') AND shop_id=999 ORDER BY id" );
else $ccstaff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 AND shop_id=999 ORDER BY id" );
$ccstaff_list[0] = "-";
while ( $result1 = $ccstaff_sql->fetch_assoc() ) {
	$ccstaff_list[$result1['id']] = $result1['name'];
}

// courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 ORDER BY id" );
while ( $result2 = $course_sql->fetch_assoc() ) {
	$course_list[$result2['id']] = $result2['name'];
	$course_type[$result2['id']] = $result2['type'];
}

$cancel_name = $course_type[$contract['course_id']] ? "月額退会" : "中途解約";

// room_list
$counseling_rooms = $shop['counseling_rooms'] ? $shop['counseling_rooms'] : $CounselingRooms;

for ($i = 1; $i <= $counseling_rooms; $i++) {
	$no = "1".$i;
	$room_list[$no] = $CounselingRoomName.$i;
}
for ($i = 1; $i <= $vip_rooms; $i++) {
	$no = "2".$i;
	$room_list[$no] = $VIPRoomName.$i;
}


// その他ルーム4
for ($i = 1; $i <= $OtherRooms; $i++) {
	$no = "4".$i;
	$room_list[$no] = $OtherRoomName.$i;
}

// 紹介者リスト
$introducer_sql = $GLOBALS['mysqldb']->query( "select id,name from customer WHERE del_flg = 0 AND status=2 AND id<>{$_POST['id']} order by name" );
if ($introducer_sql) {
	$introducer_list[0] = "-";
	while ( $result = $introducer_sql->fetch_assoc() ) {
		$introducer_list[$result['id']] = $result['name'];
	}
}

// specialリスト
$special_sql = $GLOBALS['mysqldb']->query( "select * from special WHERE del_flg = 0 AND status=0 order by id" );
if ($special_sql) {
	$special_list[0] = "-";
	while ( $result = $special_sql->fetch_assoc() ) {
		$special_list[$result['id']] = $result['name'];
	}
}
if($contract['course_id']) $course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($contract['course_id'])."'");
if($contract['times']){
	$per_fixed_price = round($contract['fixed_price']/$contract['times']);
	$per_price = round(($contract['fixed_price']-$contract['discount'])/$contract['times']);
	if($contract['discount'])$per_discount = round($contract['discount']/$contract['times']);
}
if($contract['payment_cash']) $pay_type = "現金";
elseif($contract['payment_card']) 	$pay_type = "カード";

// 2016/2/16 メンズでは下記処理は不要と思われるためコメントアウト
// 新規契約の場合、前の入金を合算しない
// プラン変更4とローン取消5の場合
// if($contract['new_contract_id'] || $contract['old_contract_id']){ 
// 	$contract_sql = $GLOBALS['mysqldb']->query( "select * from contract WHERE del_flg=0 and customer_id = '".addslashes($data['customer_id'])."' and reg_date <= '".$contract['reg_date']."' order by id desc limit 2");
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

// if(!is_array($contract_sum)){
// 	$contract_sum[0]['payment_card']=$contract['payment_card'];
// 	$contract_sum[0]['payment_cash']=$contract['payment_cash'];
// 	$contract_sum[0]['payment_transfer']=$contract['payment_transfer'];
// 	$contract_sum[0]['payment_loan']=$contract['payment_loan'];
// 	$contract_sum[0]['balance']=$contract['balance'];
// }

$contract['memo'] = '';

// 契約書に店舗情報が契約店舗基準
$customer_shop_id = $current_contract_p['shop_id'] ? $current_contract_p['shop_id'] : $_POST['shop_id']; // 20160622 基本はそのとき契約した店舗を表示。表示するものがなければPOSTの店舗を表示
$customer_shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($customer_shop_id)."'");
// $mensdb = changedb();
// マイページ出力用（パスワードのカナ変換対応）
// $str_kana_fields = preg_split("//u", $customer['password'], -1, PREG_SPLIT_NO_EMPTY);
// foreach ($str_kana_fields as $key => $s_val) {
	
// 	// str_kanaテーブルの対応表から検索する
// 	$kana = Get_Table_Row("str_kana"," WHERE str = '".addslashes($str_kana_fields[$key])."'");
// 	$kanas[] = $kana['kana'];
// }

// マイページのパスワード（カタカナ）
// $kanas = implode(' ', $kanas);

// 契約書 PDF
if($data['pid']<>0 || ($new_change_contract && $data['type']==10)){ // 親契約IDがある場合のみ、契約書を出力する
	
// 契約数からコースの数を取得し、配列に入れる（契約書表示用）
if($new_change_contract && $data['type']==10){ // 予約日にプラン変更したコース、区分：プラン変更
	$new_change_contract_count = count($new_change_contract)-1; //契約数
	for($i=0;$i<=$new_change_contract_count;$i++){
		$course_id[$i]              =$new_change_contract[$i]['course_id'];	// コースID
		// コース回数を取得
		$course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($course_id[$i])."'");		
		// 表示項目をセット
		$contract_date              = $new_change_contract[$i]['contract_date'];                                 // 契約日
		$end_date[$i]               = $new_change_contract[$i]['end_date'];                                      // 役務提供終了日(有効期間)
		$fixed_price[$i]            = $new_change_contract[$i]['fixed_price'];                                   // コース金額(割引前)
		$discount_fixed_price[$i]   = $new_change_contract[$i]['fixed_price']-$new_change_contract[$i]['discount'];  // コース金額（割引後）
		$fixed_per_price[$i]        = Division($fixed_price[$i],$new_change_contract[$i]['times']);	                 // 単価（割引前）
		$discount_per_price[$i]     = $new_change_contract[$i]['unit_price'];	                                 // 単価（割引後）
		$discount[$i]               = $new_change_contract[$i]['discount'];	                                     // 割引金額
		$times[$i]                  = $new_change_contract[$i]['times'];                                         // 回数
		$length[$i]                 = $gLengthNum[$course['length']];		                                	 // 所要時間
		// 契約部位の表示
		if($new_change_contract[$i]['contract_part']){
			$part = array();  // 部位の配列
			$parts = "";      // 部位（カンマ区切り） 
			$part  = explode(",", $new_change_contract[$i]['contract_part']);
			// 部位名を取得する
			foreach ($part as $key => $value) {
				$parts[] = $gContractParts[$value];
			}
			$contract_part[$i]          = implode(",", $parts); // 部位(カンマ区切り)

		}
		// 選べるヶ所を選んだらパーツの合計金額を売上テーブルから取得する
		if($course_id[$i] == 49){
			$course = Get_Table_Row("sales"," WHERE del_flg=0 and pid = '".addslashes($new_change_contract[$i]['pid'])."'");	
			$fixed_price[$i] = $new_change_contract[$i]['fixed_price'];// コース金額(割引前)
		}
	}

	// 共通項目をセット
	$contract_p['discount'] 		= $new_change_contract['discount'];
	$contract_p['price'] 			= $new_change_contract['price'];
	$contract_p['payment_cash'] 	= $new_change_contract['payment_cash'];
	$contract_p['payment_card'] 	= $new_change_contract['payment_card'];
	$contract_p['payment_transfer'] = $new_change_contract['payment_transfer'];
	$contract_p['payment_loan'] 	= $new_change_contract['payment_loan'];
	$contract_p['payment_coupon'] 	= $new_change_contract['payment_coupon'];
	$contract_p['balance'] 			= $new_change_contract['balance'];
	$contract_p['contract_date'] 	= $contract_date;
	$contract_p['memo'] 			= $new_change_contract['memo'];
	$contract_p['staff_id']			= $new_change_contract['staff_id'];

}elseif($current_contract){ // 現在契約しているコース

	$current_contract_count = count($current_contract)-1; //契約数
	for($i=0;$i<=$current_contract_count;$i++){
		$course_id[$i]              =$current_contract[$i]['course_id'];	// コースID
		// コース回数を取得
		$course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($course_id[$i])."'");		
		// 表示項目をセット
		$contract_date              = $current_contract[$i]['contract_date'];                                // 契約日
		$end_date[$i]               = $current_contract[$i]['end_date'];                                     // 役務提供終了日(有効期間)
		$fixed_price[$i]            = $current_contract[$i]['fixed_price'];                                  // コース金額(割引前)
		$discount_fixed_price[$i]   = $current_contract[$i]['fixed_price']-$current_contract[$i]['discount'];// コース金額（割引後）
		$fixed_per_price[$i]        = Division($fixed_price[$i],$current_contract[$i]['times']);	                             // 単価（割引前）
		$discount_per_price[$i]     = $current_contract[$i]['unit_price'];	                                 // 単価（割引後）
		$discount[$i]               = $current_contract[$i]['discount'];	                                 // 割引金額
		$times[$i]                  = $current_contract[$i]['times'];                                        // 回数
		$length[$i]                 = $gLengthNum[$course['length']];		                                 // 所要時間
		// 契約部位の表示
		if($current_contract[$i]['contract_part']){
			$part = array();  // 部位の配列
			$parts = "";      // 部位（カンマ区切り） 
			$part  = explode(",", $current_contract[$i]['contract_part']);
			// 部位名を取得する
			foreach ($part as $key => $value) {
				$parts[] = $gContractParts[$value];
			}
			$contract_part[$i]          = implode(",", $parts); // 部位(カンマ区切り)

		}
		// 選べるヶ所を選んだらパーツの合計金額を売上テーブルから取得する
		if($course_id[$i] == 49){
			$course = Get_Table_Row("sales"," WHERE del_flg=0 and pid = '".addslashes($current_contract[$i]['pid'])."'");	
			$fixed_price[$i] = $current_contract[$i]['fixed_price'];// コース金額(割引前)
		}
	}

	// 共通項目をセット
	$contract_p['discount'] 		= $current_contract_p['discount'];
	$contract_p['price'] 			= $current_contract_p['price'];
	$contract_p['payment_cash'] 	= $current_contract_p['payment_cash'];
	$contract_p['payment_card'] 	= $current_contract_p['payment_card'];
	$contract_p['payment_transfer'] = $current_contract_p['payment_transfer'];
	$contract_p['payment_loan'] 	= $current_contract_p['payment_loan'];
	$contract_p['payment_coupon'] 	= $current_contract_p['payment_coupon'];
	$contract_p['balance'] 			= $current_contract_p['balance'];
	$contract_p['contract_date'] 	= $contract_date;
	$contract_p['memo'] 			= $current_contract_p['memo'];
	$contract_p['staff_id']			= $current_contract_p['staff_id'];

}
}


// 基本情報
$mpdf_contract = "?shop_id=".$customer_shop['id']."&shop_name=".$customer_shop['name']."&shop_address=".$customer_shop['address']."&shop_tel=".$customer_shop['tel']."&no=".$customer['no'];
$mpdf_contract.= "&name=".($customer['name'] ? $customer['name'] : $customer['name_kana'])."&name_kana=".$customer['name_kana'];
$mpdf_contract.= "&birthday=".$customer['birthday']."&address=".$customer['address']."&tel=".$customer['tel'];
// コース1
$mpdf_contract.= "&course_name=".$course_list[$course_id[0]]."&times=".$times[0]."&fixed_price=".$fixed_price[0];
$mpdf_contract.= "&discount_fixed_price=".$discount_fixed_price[0]."&length=".$length[0];
$mpdf_contract.= "&price=".$contract_p['price'];
$mpdf_contract.= "&payment_cash=".$contract_p['payment_cash']."&payment_card=".$contract_p['payment_card'];
$mpdf_contract.= "&payment_transfer=".$contract_p['payment_transfer']."&payment_loan=".$contract_p['payment_loan']."&payment_coupon=".$contract_p['payment_coupon'];
$mpdf_contract.= "&balance=".$contract_p['balance'];
// 2016/2/16 下記パラメータは契約書に渡していないように見えるのでいったんコメントアウト
// $mpdf_contract.= "&option_name=".$gOption[$contract['option_name']]."&option_price=".$contract['option_price']."&balance=".$current_contract_p['balance'];
$mpdf_contract.= "&hope_date=".$contract_p['contract_date']."&contract_date=".$contract_p['contract_date']."&end_date=".$end_date[0];
$mpdf_contract.= "&memo=".$contract_p['memo']."&mp=".$customer['password']."&staff=".$staff_list[$contract_p['staff_id']]."&pay_type=".$pay_type; //"&mp_kana=".$kanas;
if($contract_part[0]) $mpdf_contract.= "&contract_part=".$contract_part[0];
if($fixed_per_price[0]) $mpdf_contract.= "&fixed_per_price=".$fixed_per_price[0];
if($discount[0]) $mpdf_contract.= "&discount_per_price=".$discount_per_price[0]."&discount=".$discount[0];
else $mpdf_contract.= "&discount_per_price=".$fixed_per_price[0];// 割引金額がなかったら、割引後金額の単価に「定価」の単価を入れる（女性の仕様と合わせる）20160707shimada
// コース2
$mpdf_contract.= "&course_name2=".$course_list[$course_id[1]]."&times2=".$times[1]."&fixed_price2=".$fixed_price[1];
$mpdf_contract.= "&discount_fixed_price2=".$discount_fixed_price[1]."&length2=".$length[1]."&end_date2=".$end_date[1];
if($contract_part[1]) $mpdf_contract.= "&contract_part2=".$contract_part[1];
if($fixed_per_price[1]) $mpdf_contract.= "&fixed_per_price2=".$fixed_per_price[1];
if($discount[1]) $mpdf_contract.= "&discount_per_price2=".$discount_per_price[1]."&discount2=".$discount[1];
else $mpdf_contract.= "&discount_per_price2=".$fixed_per_price[1];
// コース3
$mpdf_contract.= "&course_name3=".$course_list[$course_id[2]]."&times3=".$times[2]."&fixed_price3=".$fixed_price[2];
$mpdf_contract.= "&discount_fixed_price3=".$discount_fixed_price[2]."&length3=".$length[2]."&end_date3=".$end_date[2];
if($contract_part[2]) $mpdf_contract.= "&contract_part3=".$contract_part[2];
if($fixed_per_price[2]) $mpdf_contract.= "&fixed_per_price3=".$fixed_per_price[2];
if($discount[2]) $mpdf_contract.= "&discount_per_price3=".$discount_per_price[2]."&discount3=".$discount[2];
else $mpdf_contract.= "&discount_per_price3=".$fixed_per_price[2];
// コース4
$mpdf_contract.= "&course_name4=".$course_list[$course_id[3]]."&times4=".$times[3]."&fixed_price4=".$fixed_price[3];
$mpdf_contract.= "&discount_fixed_price4=".$discount_fixed_price[3]."&length4=".$length[3]."&end_date4=".$end_date[3];
if($contract_part[3]) $mpdf_contract.= "&contract_part4=".$contract_part[3];
if($fixed_per_price[3]) $mpdf_contract.= "&fixed_per_price4=".$fixed_per_price[3];
if($discount[3]) $mpdf_contract.= "&discount_per_price4=".$discount_per_price[3]."&discount4=".$discount[3];
else $mpdf_contract.= "&discount_per_price4=".$fixed_per_price[3];
// コース5
$mpdf_contract.= "&course_name5=".$course_list[$course_id[4]]."&times5=".$times[4]."&fixed_price5=".$fixed_price[4];
$mpdf_contract.= "&discount_fixed_price5=".$discount_fixed_price[4]."&length5=".$length[4]."&end_date5=".$end_date[4];
if($contract_part[4]) $mpdf_contract.= "&contract_part5=".$contract_part[4];
if($fixed_per_price[4]) $mpdf_contract.= "&fixed_per_price5=".$fixed_per_price[4];
if($discount[4]) $mpdf_contract.= "&discount_per_price5=".$discount_per_price[4]."&discount5=".$discount[4];
else $mpdf_contract.= "&discount_per_price5=".$fixed_per_price[4];

// 1回コース契約書 PDF
// 契約数からコースの数を取得し、配列に入れる 1回コース契約書表示用）
if($current_one_contract){
	$current_one_contract_count = count($current_one_contract)-1; //契約数
	$one_discount =0; // 割引金額合計（初期化）
	for($i=0;$i<=$current_one_contract_count;$i++){
		$one_course_id[$i]              = $current_one_contract[$i]['course_id'];     // コースID
		$one_fixed_price[$i]            = $current_one_contract[$i]['fixed_price'];   // コース金額(割引前)	
		$one_discount                  += $current_one_contract[$i]['discount'];      // 割引金額合計	
		//$one_contract_date              = $current_one_contract[$i]['contract_date']; // 役務提供開始日(有効期間)
		$one_end_date[$i]               = $current_one_contract[$i]['end_date'];      // 役務提供終了日(有効期間)
		// 契約部位の表示
		if($current_one_contract[$i]['contract_part']){
			$one_part = array();  // 部位の配列
			$one_parts = "";      // 部位（カンマ区切り） 
			$one_part  = explode(",", $current_one_contract[$i]['contract_part']);
			// 部位名を取得する
			foreach ($one_part as $key => $value) {
				$one_parts[] = $gContractParts[$value];
			}
			$one_contract_part[$i]          = implode(",", $one_parts); // 部位(カンマ区切り)

		}
		// 選べるヶ所を選んだらパーツの合計金額を売上テーブルから取得する
		if($course_id[$i] == 49){
			$one_course = Get_Table_Row("sales"," WHERE del_flg=0 and pid = '".addslashes($current_one_contract[$i]['pid'])."'");	
			$one_fixed_price[$i] = $current_one_contract[$i]['fixed_price'];// コース金額(割引前)
		}
	}
}
// 基本情報
$mpdf_one_contract = "?shop_id=".$customer_shop['id']."&shop_name=".$customer_shop['name']."&shop_address=".$customer_shop['address']."&shop_tel=".$customer_shop['tel']."&no=".$customer['no'];
$mpdf_one_contract.= "&name=".($customer['name'] ? $customer['name'] : $customer['name_kana'])."&name_kana=".$customer['name_kana'];
$mpdf_one_contract.= "&birthday=".$customer['birthday']."&address=".$customer['address']."&tel=".$customer['tel'];
$mpdf_one_contract.= "&hope_date=".$contract_date."&contract_date=".$contract_date."&end_date=".$one_end_date[0]."&staff=".$staff_list[$current_contract_p['staff_id']];
// コース別情報（選べるヶ所を選ぶ可能性もあるため4個分配列を用意する）
$mpdf_one_contract.= "&discount=".$one_discount; // 割引合計
$mpdf_one_contract.= "&course_name=".$course_list[$one_course_id[0]]."&contract_part=".$one_contract_part[0]."&fixed_price=".$one_fixed_price[0];
if($one_fixed_price[1]) $mpdf_one_contract.= "&course_name2=".$course_list[$one_course_id[1]]."&contract_part2=".$one_contract_part[1]."&fixed_price2=".$one_fixed_price[1]."&end_date2=".$one_end_date[1];
if($one_fixed_price[2]) $mpdf_one_contract.= "&course_name3=".$course_list[$one_course_id[2]]."&contract_part3=".$one_contract_part[2]."&fixed_price3=".$one_fixed_price[2]."&end_date3=".$one_end_date[2];
if($one_fixed_price[3]) $mpdf_one_contract.= "&course_name4=".$course_list[$one_course_id[3]]."&contract_part4=".$one_contract_part[3]."&fixed_price4=".$one_fixed_price[3]."&end_date4=".$one_end_date[3];
if($one_fixed_price[4]) $mpdf_one_contract.= "&course_name5=".$course_list[$one_course_id[4]]."&contract_part5=".$one_contract_part[4]."&fixed_price5=".$one_fixed_price[4]."&end_date5=".$one_end_date[4];

// 特例トリートメント同意書 PDF
$mpdf_special_case_treatment = "?name=".$customer['name']."&zip=".$customer['zip']."&address=".$customer['address']."&customer_no=".$customer['no'];

// 保証期間延長申請書 PDF
//$mpdf_assurance_extension = "?name=".$customer['name']."&zip=".$customer['zip']."&address=".$customer['address']."&customer_no=".$customer['no'];
//$mpdf_assurance_extension.= "&tel=".$customer['tel']."&course_name=".$course_list[$contract['course_id']]."&p_start=".$contract['end_date']."&shop_name=".$shop_list[$data['shop_id']];
//$mpdf_assurance_extension.= "&staff_name=".$staff_list[$data['tstaff_id']];


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
$flyer_no_sql = $GLOBALS['mysqldb']->query( "select * from adcode WHERE del_flg = 0 AND flyer_no<>'' order by flyer_no" );
if ($flyer_no_sql) {
	while ( $result = $flyer_no_sql->fetch_assoc() ) {
		$flyer_no_list[$result['id']] = $result['flyer_no'];
	}
}

// クーポンリスト
$hope_date = $_POST['hope_date'] ? $_POST['hope_date'] : date("Y-m-d");
$coupon_sql = $GLOBALS['mysqldb']->query( "select * from item_coupon WHERE del_flg = 0 AND start_date<='".$hope_date."' AND end_date>='".$hope_date."' order by id" );
if ($coupon_sql) {
	while ( $result = $coupon_sql->fetch_assoc() ) {
		$coupon_list[$result['id']] = $result['name'];
	}
}

if( !$_POST['id'] && $_POST['customer_id'] != "" )  {
	$pre_rsv = Get_Table_Row($table," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."' order by id desc limit 1");
}

if( $customer['adcode'] != "" ) {
$ad_memo = Get_Table_Col("adcode","memo"," WHERE del_flg=0 and id = '".addslashes($customer['adcode'])."' ");
}

?>