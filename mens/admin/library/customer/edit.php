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
//ini_set("display_errors", 1);
//error_reporting(E_ALL);

//契約書後、本会員になり、reg_flg=1

$table = "customer";

// 詳細を取得-----------------------------------------------

if( $_POST['id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	$counseling = Get_Table_Row("reservation"," WHERE del_flg=0 and customer_id = '".addslashes($data['id'])."' order by hope_date desc limit 1");//type=1
	$contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($data['id'])."' order by contract_date desc, id DESC limit 1");
}

// 半角スペースを全角スペースに統一
if($_POST['name']) 		$_POST['name'] 		= str_replace(" ", "　", $_POST['name']);
// 2スペースを1スペースに統一
if($_POST['name']) 		$_POST['name'] 		= str_replace("　　", "　", $_POST['name']);
// 半角スペースを全角スペースに統一
if($_POST['name_kana']) $_POST['name_kana'] = str_replace(" ", "　", $_POST['name_kana']);
// 2スペースを1スペースに統一
if($_POST['name_kana'])	$_POST['name_kana'] = str_replace("　　", "　", $_POST['name_kana']);
// 「全角」英数字を「半角」に変換,「全角」スペースを「半角」に変換
if($_POST['card_no'])	$_POST['card_no'] 	= mb_convert_kana($_POST['card_no'], "as", "UTF-8");
// 「全角」英数字を「半角」に変換,「全角」スペースを「半角」に変換,大文字に
if($_POST['card_name'])	$_POST['card_name'] = strtoupper(mb_convert_kana(trim($_POST['card_name']), "as", "UTF-8"));
// 電話番号整形
if($_POST['tel']) $_POST['tel'] = sepalate_tel($_POST['tel']); 

// 郵便番号の前後スペースを除去する 2017/07/13 add by shimada
$_POST['zip'] =Space_Delete($_POST['zip']);

// 編集------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {
  //名前が必須確認
	if( !$_POST['name'] || !$_POST['name_kana'] )
		$gMsg = "<font color='red' size='-1'>※名前また名前（カナ）が必須です。</font>";

  //名前スペース入れ確認
  elseif( $_POST['name'] && !strpos($_POST['name'], "　") || $_POST['name_kana'] && !strpos($_POST['name_kana'], "　") )
	$gMsg = "<font color='red' size='-1'>※姓と名の間にスペースを入れてください。</font>";

  //名前不正文字チェック #256不正文字チェック 2017/06/16 add by shimada
  elseif( Invalid_Characters_Check($_POST["name"]) )
  $gMsg = "<font color='red' size='-1'>※名前にご利用いただけない文字「".Invalid_Characters_Check($_POST['name'])."」が含まれています。</font>";

  //名前カナ文字チェック #256不正文字チェック 2017/06/16 add by shimada
  elseif( Invalid_Characters_Kana_Check($_POST["name_kana"]) )
  $gMsg = "<font color='red' size='-1'>※全角カタカナを入力してください。</font>";

  //住所があるのに都道府県無し確認
  elseif( $_POST['address'] && !$_POST['pref'] )
	$gMsg = "<font color='red' size='-1'>※都道府県を選択してください。</font>";
	
  //住所の不正文字チェック 2017/06/16 add by shimada
  elseif( Invalid_Characters_Check($_POST["address"]) )
  $gMsg = "<font color='red' size='-1'>※住所にご利用いただけない文字「".Invalid_Characters_Check($_POST['address'])."」が含まれています。</font>";

  // 郵便番号の桁数チェック 2017/06/16 add by shimada
  elseif((mb_strlen(str_replace('-','',$_POST["zip"]))<>7 || !is_numeric(str_replace('-','',$_POST["zip"]))) && $_POST["zip"])
  $gMsg = "<font color='red' size='-1'>※郵便番号は7桁の半角数字で入力してください</font>";

  //メールアドレス確認
  // "/^([a-zA-Z0-9\])===>"/^([a-zA-Z0-9\._-]) に変更 2017/07/10 add by shimada
  elseif( $_POST['mail'] && (!preg_match("/^([a-zA-Z0-9._-])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['mail'])) )
	$gMsg = "<font color='red' size='-1'>※正しいメールアドレスを入力してください。</font>";

  //紹介企業
  elseif( $_POST['introducer_type']==5 && !$_POST['partner'] || $_POST['introducer_type']<>5 && $_POST['partner'] )
	$gMsg = "<font color='red' size='-1'>※紹介企業を正しく選択してください。</font>";
  
  else{	
	//顧客編集
	if($_POST['id'] != "" ){
		
		// 顧客編集
		$_POST['edit_date'] = date("Y-m-d H:i:s");
    	// 郵便番号整形 2017/06/16 add by shimada
    	if($_POST['zip'])$_POST['zip'] = preg_replace("/^(\d{3})(\d{4})$/", "$1-$2", $_POST['zip']);
    	// 顧客情報更新
		$data_ID =  Input_Update_Data($table);

		if(is_null($contract['course_id']) && $_POST['ctype']==3){    //モデル系に更新後、無料コース登録
			$_POST["customer_id"] = $_POST['id'];
			$_POST["end_date"] = $ModelEndDate;
			$_POST["course_id"] = $_POST["multiple_course_id"] = $ModelCourseId;
			$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
			$contract_p_field  = array("customer_id","multiple_course_id","end_date","reg_date","edit_date");
			$_POST["pid"] = Input_New_Data("contract_P",$contract_p_field);
			$contract_field = array("pid","status","customer_id","course_id","end_date","reg_date","edit_date");
			$contract_ID = Input_New_Data("contract" ,$contract_field);
			unset($_POST["customer_id"]);
			unset($_POST["end_date"]);
			unset($_POST["course_id"]);
			unset($_POST["reg_date"]);
			unset($_POST["edit_date"]);
			unset($_POST["pid"]);
			unset($_POST["multiple_course_id"]);
		}else if($contract['course_id']==1010 && $_POST['ctype']<>3){
			$_POST["del_flg"] = 1;
			$contract_p_field2 = array("multiple_course_id","del_flg","edit_date");
			$contract_p_ID =  Update_Data("contract_P",$contract_p_field2,$contract['pid']);
			$contract_field = array("del_flg","edit_date");
			$contract_ID = Update_Data("contract" ,$contract_field,$contract["id"]);
			unset($_POST["del_flg"]);
		}

	}else{
		//顧客新規
		$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");

		$customer_field = array("no","ctype","name","name_kana","zip","pref","address","birthday","tel","mail","shop_id","reg_date","edit_date");
		$data_ID = Input_New_Data("customer",$customer_field);

		    //モデル系新規登録後、無料コース登録
			if($_POST['ctype']==3){
				$_POST["customer_id"] = $_POST['id'];
				$_POST["end_date"]  = $ModelEndDate;
				$_POST["course_id"] = $_POST["multiple_course_id"] = $ModelCourseId;
				$contract_p_field  = array("customer_id","multiple_course_id","end_date","reg_date","edit_date");
				$_POST["pid"] = Input_New_Data("contract_P",$contract_p_field);
				$contract_field = array("pid","status","customer_id","course_id","end_date","reg_date","edit_date");
				$contract_ID = Input_New_Data("contract" ,$contract_field);
				unset($_POST["customer_id"]);
				unset($_POST["end_date"]);
				unset($_POST["course_id"]);
				unset($_POST["pid"]);
				unset($_POST["multiple_course_id"]);
			}

	}
	if( $data_ID ) 	header( "Location: ../main/?shop_id=".$data['shop_id']."&hope_date=".$counseling['hope_date']);
	else $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
  }
}


//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist_shop();

//$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0  AND status=2 order by id" );
//$shop_list[0] = "-";
//while ( $result = $shop_sql->fetch_assoc() ) {
//	$shop_list[$result['id']] = $result['name'];
//	$shop_code[$result['id']] = $result['code'];
//}

//staff list
//if($shop) $where_shop = " AND shop_id=".$shop['id'];
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

//courseリスト
$course_list = getDatalistMens("course");


//紹介者リスト
$introducer_sql = $GLOBALS['mysqldb']->query( "select id,name from customer WHERE del_flg = 0 AND status=2 AND id<>{$_POST['id']} order by name" );
$introducer_list[0] = "-";
while ( $result = $introducer_sql->fetch_assoc() ) {
	$introducer_list[$result['id']] = $result['name'];
}
//specialリスト
$special_sql = $GLOBALS['mysqldb']->query( "select * from special WHERE del_flg = 0 AND status=0 order by id" );
if ($special_sql) {
	$special_list[0] = "-";
	while ( $result = $special_sql->fetch_assoc() ) {
		$special_list[$result['id']] = $result['name'];
	}
}

//紹介企業リスト
$partner_sql = $GLOBALS['mysqldb']->query( "select * from partner WHERE del_flg = 0 AND status=0 order by name" );
if ($partner_sql) {
	$partner_list[0] = "-";
	while ( $result = $partner_sql->fetch_assoc() ) {
		$partner_list[$result['id']] = $result['name'];
	}
}
?>
