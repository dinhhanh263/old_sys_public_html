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

//memo--------------------------------------------------------------------------------
//次回予約新規、customer_id
//来店回数
//dialog from jquery

//設定--------------------------------------------------------------------------------
$table = "reservation";


if($_POST['name']) 		$_POST['name'] 		= str_replace(" ", "　", $_POST['name']);//半角スペースを全角スペースに統一
if($_POST['name']) 		$_POST['name'] 		= str_replace("　　", "　", $_POST['name']);//2スペースを1スペースに統一
if($_POST['name_kana']) $_POST['name_kana'] = str_replace(" ", "　", $_POST['name_kana']);//半角スペースを全角スペースに統一
if($_POST['name_kana'])	$_POST['name_kana'] = str_replace("　　", "　", $_POST['name_kana']);//2スペースを1スペースに統一

if($_POST['tel']) $_POST['tel'] = sepalate_tel($_POST['tel']); //電話番号整形


//編集or新規---------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
	//空き確認.他の予約との重なり、21時前に終了できるか
	if($_POST['id']) $where_id =" AND id<>".$_POST['id'];
	$sql = " WHERE del_flg=0 and ( type<3 or type>6 ) ".$where_id . " AND hope_date='".addslashes($_POST['hope_date'])."' AND shop_id=".$_POST['shop_id']." AND room_id=".$_POST['room_id'];
	$sql .= " AND (hope_time<".$_POST['hope_time'] ." AND hope_time+length>".$_POST['hope_time'] ;										//予約開始時間と比較(重なりあり)
	$sql .= " OR hope_time<".($_POST['hope_time']+$_POST['length']) ." AND hope_time+length>".($_POST['hope_time']+$_POST['length']) ;	//予約終了時間と比較(重なりあり)
	$sql .= " OR hope_time>=".$_POST['hope_time'] ." AND hope_time+length<=".($_POST['hope_time']+$_POST['length']) . ")";				//中にあり

	//名前スペース入れ確認
	if( $_POST['name'] && !strpos($_POST['name'], "　") || $_POST['name_kana'] && !strpos($_POST['name_kana'], "　") )
		$gMsg = "<font color='red' size='-1'>※姓と名の間にスペースを入れてください。</font>";
	//日程未入力確認
	elseif(!$_POST['hope_date'])
		$gMsg = "<font color='red' size='-1'>※予約日程を指定してください。</font>";

	elseif(!$_POST['shop_id'])
		$gMsg = "<font color='red' size='-1'>※店舗を指定してください。</font>";
	//重複確認
	elseif( Get_Table_Row($table,$sql)){
		$gMsg = "<font color='red' size='-1'>※他の予約との重なりがあります。予約変更ができませんでした。</font>";
	//店舗側に、カウンセリングルーム４にカウンセリング予約以外をいれない
	}elseif( ($_POST['room_id']==14 && $_POST['type']<>1) && ($authority_level>1) ){
		$gMsg = "<font color='red' size='-1'>※トリートメントルームを選択してください。</font>";

	//終了確認
	}elseif(($_POST['hope_time']+$_POST['length'])>21){
		$gMsg = "<font color='red' size='-1'>※終了時間がオーバーします。予約変更ができませんでした。</font>";

	}else{

		//編集------------------------------------------------------------------------
		if($_POST['id'] != "" ){
			$_POST['edit_date'] = date("Y-m-d H:i:s");
			$data_ID =  Input_Update_Data($table);

		//次の予約新規（施術）------------------------------------------------------------
		}elseif($_POST['mode']=="new_rsv" && $_POST['customer_id']){
			$_POST['reg_date'] = date("Y-m-d H:i:s");
			$reservation_field = array("contract_id","customer_id","shop_id","staff_id","cstaff_id","room_id","course_id","type","hope_date","hope_time","length","persons","echo","introducer","introducer_type","route","special","memo","memo2","reg_date");
			$data_ID = Input_New_Data($table ,$reservation_field);

		//カウンセリング新規--------------------------------------------------------------
		}else{	

			//旧会員番号自動付与
			// $shop_code = Get_Table_Col("shop","code"," WHERE id={$_POST['shop_id']} ");
			
			// if($pre_sn_shop = Get_Table_Col("customer","sn_shop"," WHERE shop_id={$_POST['shop_id']} ORDER BY sn_shop DESC LIMIT 1")){
			// 	$_POST['sn_shop'] = $pre_sn_shop + 1 ;
			// 	$_POST['no'] = $shop_code.str_repeat("0",(5-strlen($_POST['sn_shop']))).$_POST['sn_shop'];			
			// }else{
			// 	$_POST['sn_shop'] = 1 ;
			// 	$_POST['no'] = $shop_code."00001";
			// }

			//顧客新規
			$_POST['password'] = generateID(6,'smallalnum');
			$_POST['reg_date'] = date("Y-m-d H:i:s");

			$customer_field = array("no","sn_shop","cstaff_id","password","ctype","name","name_kana","age","tel","mail","shop_id","introducer","introducer_type","route","special","reg_date");
			$_POST['customer_id'] = Input_New_Data("customer",$customer_field);
	
			//会員番号自動付与
			$shop_code = Get_Table_Col("shop","code"," WHERE id={$_POST['shop_id']} ");

			$result  = $GLOBALS['mysqldb']->query( "select * from customer ORDER BY id desc limit 1" );
			if($result){
				while ( $row = $result->fetch_assoc()){
					$GLOBALS['mysqldb']->query('update customer set no="' . $shop_code . $row['id'] . '" where id=' . $row['id']);
				}
			}

			//予約新規
			$reservation_field = array("contract_id","customer_id","shop_id","staff_id","cstaff_id","room_id","course_id","type","hope_date","hope_time","length","persons","echo","introducer","introducer_type","route","special","memo","memo2","reg_date");
			$data_ID = Input_New_Data($table ,$reservation_field);

			//レジ用データ登録?
		}
		if( $data_ID ) 	header( "Location: ../main/?id=".$data_ID."&shop_id=".$_POST['shop_id']."&hope_date=".$_POST['hope_date']);
		else $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
	}
}

// 詳細を取得-----------------------------------------------------------------------------
if( $_POST['id'] != "" )  {
	$data = Get_Table_Row($table," WHERE id = '".addslashes($_POST['id'])."'");
	$customer = Get_Table_Row("customer"," WHERE id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($data['shop_id'])."'");
}
elseif( $_POST['customer_id'] != "" )  {
	$customer = Get_Table_Row("customer"," WHERE id = '".addslashes($_POST['customer_id'])."'");
	if(!$_POST['type']) $_POST['type'] = 2;			//次回予約新規時、区分を施術に暫定
	if(!$_POST['room_id']) $_POST['room_id'] = 31;	//次回予約新規時、施術ルーム１に暫定
	if($authority_shop)$shop = $authority_shop;
}

//売上詳細取得-----------------------------------------------------------------------------
if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE id = '".addslashes($data['sales_id'])."'");

//契約詳細取得-----------------------------------------------------------------------------
if($data['contract_id'] != 0 ) $contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($data['contract_id'])."'");
//else $contract = Get_Table_Row("contract"," WHERE customer_id = '".addslashes($data['customer_id'] ? $data['customer_id'] : $customer['id'])."' and status=0 and end_date>='".date("Y-m-d")."'");
else $contract = Get_Table_Row("contract"," WHERE customer_id = '".addslashes($data['customer_id'] ? $data['customer_id'] : $customer['id'])."' and del_flg=0 and status=0 "); // 契約中コース指定status=0

//トリートメントカルテ取得-----------------------------------------------------------------------------
if($data['id']) $karte = Get_Table_Row("karte"," WHERE reservation_id = '".addslashes($data['id'])."'");	

//入力したデータを保つ------------------------------------------------------------------------
if($_POST){
	foreach ($_POST as $key => $value) {
		$data[$key] = $value;
	}
}

//店舗リスト-------------------------------------------------------------------------------
$shop_list = getDatalist_shop();

//staff list
//if($shop) $where_shop = " AND shop_id=".$shop['id'];
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

//courseリスト
$course_list  = getDatalistMens("course");


//room_list
if($data['hope_date']=="2014-02-28") $counseling_rooms = $CounselingRoomsMax;
elseif($data['hope_date']>="2014-03-01" && $data['hope_date']<="2014-03-03") $counseling_rooms = 6;
elseif($data['hope_date']>="2014-03-04" && $data['hope_date']<="2014-03-10") $counseling_rooms = 5;
else $counseling_rooms = $shop['counseling_rooms'] ? $shop['counseling_rooms'] : $CounselingRooms;

$medical_rooms = $shop['medical_rooms'] ? $shop['medical_rooms'] : $MedicalRooms;
$vip_rooms = $shop['vip_rooms'] ? $shop['vip_rooms'] : $VIPRooms;
for ($i = 1; $i <= $counseling_rooms; $i++) {
	$no = "1".$i;
	$room_list[$no] = $CounselingRoomName.$i;
}
for ($i = 1; $i <= $vip_rooms; $i++) {
	$no = "2".$i;
	$room_list[$no] = $VIPRoomName.$i;
}
for ($i = 1; $i <= $medical_rooms; $i++) {
	$no = "3".$i;
	$room_list[$no] = $MedicalRoomName.$i;
}
//紹介者リスト
$introducer_sql = $GLOBALS['mysqldb']->query( "select id,name from customer WHERE del_flg = 0 AND status=2 AND id<>{$_POST['id']} order by name" );
if ($introducer_sql) {
	$introducer_list[0] = "-";
	while ( $result = $introducer_sql->fetch_assoc() ) {
		$introducer_list[$result['id']] = $result['name'];
	}
}
//specialリスト
$special_sql = $GLOBALS['mysqldb']->query( "select * from special WHERE del_flg = 0 AND status=0 order by id" );
if ($special_sql) {
	$special_list[0] = "-";
	while ( $result = $special_sql->fetch_assoc() ) {
		$special_list[$result['id']] = $result['name'];
	}
}


if($sales['id']) $sales_sum0 = Get_Table_Array_Multi("sales","sum(payment) as payment,sum(payment_cash) as payment_cash,sum(payment_card) as payment_card,sum(payment_transfer) as payment_transfer,sum(payment_loan) as payment_loan,sum(payment_coupon) as payment_coupon"," WHERE del_flg=0 and customer_id = '".$data['customer_id']."' and pay_date<='".$sales['pay_date']."'");
$sales_sum = $sales_sum0[0];

if($data['course_id']) $course = Get_Table_Row("course"," WHERE id = '".addslashes($data['course_id'])."'");
if($sales['times']){
	$per_fixed_price = round($sales['fixed_price']/$sales['times']);
	$per_price = round(($sales_sum['payment']+$sales['balance'])/$sales['times']);
	if($sales['discount'])$per_discount = round($sales['discount']/$sales['times']);
}
if($sales['payment_cash']) $pay_type = "現金";
elseif($sales['payment_card']) 	$pay_type = "カード";


$pdf_param = "?shop_name=".$shop['name']."&shop_address=".$shop['address']."&no=".$customer['no']."&name=".($customer['name'] ? $customer['name'] : $customer['name_kana'])."&name_kana=".$customer['name_kana']."&birthday=".$customer['birthday']."&address=".$customer['address']."&tel=".$customer['tel']."&course_name=".$course_list[$sales['course_id']];
$pdf_param.= "&fixed_price=".$sales['fixed_price']."&discount=".$sales['discount']."&price=".($sales_sum['payment']+$sales['balance'])."&payment_cash=".$sales_sum['payment_cash']."&payment_card=".$sales_sum['payment_card']."&payment_transfer=".$sales_sum['payment_transfer']."&payment_loan=".$sales_sum['payment_loan']."&payment_coupon=".$sales_sum['payment_coupon']."&length=".str_replace("分", "", $gLength[$course['length']]);
$pdf_param.= "&option_name=".$gOption[$sales['option_name']]."&option_price=".$sales['option_price']."&balance=".$sales['balance']."&hope_date=".$data['hope_date']."&times=".$sales['times']."&contract_date=".$contract['contract_date']."&end_date=".$contract['end_date']."&memo=".$contract['memo'];
$pdf_param.= "&staff=".$staff_list[$contract['staff_id']]."&pay_type=".$pay_type;
if($per_fixed_price) $pdf_param.= "&per_fixed_price=".$per_fixed_price;
if($per_price) $pdf_param.= "&per_price=".$per_price;
//var_dump($pdf_param);
?>
