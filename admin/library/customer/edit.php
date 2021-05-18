<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'auth.php';
//ini_set("display_errors", 1);
//error_reporting(E_ALL);

//契約書後、本会員になり、reg_flg=1

$table = "customer";

// 詳細を取得-----------------------------------------------

if( $_POST['customer_id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['customer_id'])."'");
	if ($data['birthday'] == "0000-00-00") $data['birthday'] = "";
	$data_introducer = Get_Table_Row("introducer"," WHERE del_flg=0 and introducer_customer_id = '".$GLOBALS['mysqldb']->real_escape_string($_POST['customer_id'])."'");
  $data_introducer_customer = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($data_introducer['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	$counseling = Get_Table_Row("reservation"," WHERE del_flg=0 and customer_id = '".addslashes($data['id'])."' order by hope_date desc limit 1");//type=1
  $contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($data['id'])."' order by contract_date desc, id DESC limit 1");
  $succead_loan = Get_Table_Array("contract","loan_company_id"," WHERE loan_company_id=6 and del_flg=0 and customer_id = '".addslashes($data['id'])."'");
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

if($_POST['inviteCode']) {
		// 半角空白・全角空白を除去する
		$_POST['inviteCode'] = str_replace(array(" ", "　"), "", $_POST['inviteCode']);

		// 全角英数字を半角英数字に変換する
		$_POST['inviteCode'] = mb_convert_kana($_POST['inviteCode'], 'rna');
}

// 郵便番号の前後スペースを除去する 2017/07/13 add by shimada
// 店舗権限なら、郵便番号が非表示なのに、郵便番号がリセットされてしまう。if判定追加。　20171218 edit by ka
if($_POST['zip'])$_POST['zip'] =Space_Delete($_POST['zip']);


// 編集------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {

	// 紹介元顧客情報取得
	if ( $_POST['inviteCode'] != "" ) {
		$introducerCustomerInfo = Get_Table_Row("customer"," WHERE del_flg=0 and no = '".$GLOBALS['mysqldb']->real_escape_string($_POST['inviteCode'])."'");
	}

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

	// 契約済みの場合、生年月日の変更不可
	elseif($contract['id'] && $data['birthday'] != $_POST["birthday"]) {
		$gMsg = "<font color='red' size='-1'>※契約済みのため生年月日を変更できません</font>";
	}

  // 契約ローン会社がサクシードか確認
  elseif($_POST['loan_delay_flg']==11 && count($succead_loan)==0)
  $gMsg = "<font color='red' size='-1'>サクシードの契約者のみ選択可能です。</font>";

  //メールアドレス確認
  // "/^([a-zA-Z0-9\])===>"/^([a-zA-Z0-9\._-]) に変更 2017/07/10 add by shimada
  elseif( $_POST['mail'] && (!preg_match("/^([a-zA-Z0-9._-])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['mail'])) )
	$gMsg = "<font color='red' size='-1'>※正しいメールアドレスを入力してください。</font>";

  //紹介企業
  elseif( $_POST['introducer_type']==5 && !$_POST['partner'] || $_POST['introducer_type']<>5 && $_POST['partner'] )
	$gMsg = "<font color='red' size='-1'>※紹介企業を正しく選択してください。</font>";

  //会員番号（紹介元）確認
  elseif( $_POST['inviteCode'] != "" && $introducerCustomerInfo == false )
    $gMsg = "<font color='red' size='-1'>※紹介元の顧客が見つかりませんでした。</font>";
  elseif( $data['no'] && $_POST['inviteCode'] == $data['no'] )
    $gMsg = "<font color='red' size='-1'>※会員番号（紹介元）が編集中の顧客の会員番号と同一です。</font>";
  elseif( $_POST['inviteCode'] != "" && $introducerCustomerInfo['id'] !== $data_introducer_customer['id'] && ($data_introducer['refund_request']>'0000-00-00' || $data_introducer['refund_contact']>'0000-00-00' || $data_introducer['refund_date']>'0000-00-00'))
    $gMsg = "<font color='red' size='-1'>※友達紹介の返金処理が進行しているため、更新できません。</font>";

  else{
	//顧客編集
	if($_POST['customer_id'] != "" ){

		//顧客編集
		$_POST['edit_date'] = date("Y-m-d H:i:s");
    // 郵便番号整形 2017/06/16 add by shimada
		if($_POST['zip'])$_POST['zip'] = preg_replace("/^(\d{3})(\d{4})$/", "$1-$2", $_POST['zip']);

		// customerテーブル更新カラム
		$customer_field2 = array(
			"agree_status",
			"attorney_status",
			"contract_send",
			"ctype",
			"name",
			"name_kana",
			"cbs_no",
			"card_no",
			"card_name",
			"card_name_kana",
			"birthday",
			"age",
			"big_flg",
			"introducer",
			"introducer_type",
			"partner",
			"special",
			"student_id",
			"memo",
			"edit_date",
			"sv_flg",
			// "loan_delay_flg",
			"digicat_ng_flg",
			"nextpay_end_ng_flg",
			"nextpay_op_ng_flg",
			"bank_ng_flg",
			"mail_status",
			"caution",
			"caution_place",
			"caution_size",
			"rejected_flg",
			"sugar_risk_id",
			"protein_risk_id",
			"fat_risk_id",
			"gene_type_reg_date"
		);

		// 下記カラムはPOSTされた場合のみ更新
		if (isset($_POST['zip'])) array_push($customer_field2, "zip");
		if (isset($_POST['pref'])) array_push($customer_field2, "pref");
		if (isset($_POST['address'])) array_push($customer_field2, "address");
		if (isset($_POST['tel'])) array_push($customer_field2, "tel");
		if (isset($_POST['mail'])) array_push($customer_field2, "mail");
		if (isset($_POST['special'])) array_push($customer_field2, "special");
		if (isset($_POST['pair_name_kana'])) array_push($customer_field2, "pair_name_kana");
		if (isset($_POST['pair_tel'])) array_push($customer_field2, "pair_tel");

		// 広告ID
		if ( isset($_POST['inviteCode']) && !empty($_POST['inviteCode']) ) {

            // 更新前に、広告ID（`adcode`）の存在チェック
            $tmp_adcode = Get_Table_Col("customer","adcode"," WHERE id = " . $_POST['customer_id'] . " AND del_flg = 0 ");
            // 存在しない場合のみ、お友達紹介キャンペーン専用広告ID（`adcode`）を入れる
            if(strlen($tmp_adcode) < 1){
                array_push($customer_field2, "adcode"); // $customer_field2が使っていない　20170215　ka
                $_POST['adcode'] = INTRODUCTION_ADCODE;     // 紹介者情報に挿入
            }

            // $introductionSelectSql = 'SELECT * FROM `introducer` WHERE `introducer_customer_id` = ' . $data['id'] . ' AND `customer_id` = "' . $data_introducer_customer['id'] . '" AND `del_flg` = 0';
            // $rtn1 = $GLOBALS['mysqldb']->query($introductionSelectSql) or die('query error'.$GLOBALS['mysqldb']->error);

            // 違う会員番号（紹介元）であれば更新する
            if ( $introducerCustomerInfo['id'] !== $data_introducer_customer['id'] && $data_introducer['refund_request']=='0000-00-00' && $data_introducer['refund_contact']=='0000-00-00' && $data_introducer['refund_date']=='0000-00-00') {

                // 紹介者情報が存在すれば削除フラグ更新
                $introducerCheckSql = 'UPDATE `introducer` introducer, '
                    . '( SELECT id FROM `customer` WHERE id = '
                    . $GLOBALS['mysqldb']->real_escape_string($data['id'])
                    . ' && del_flg = 0 ) customer SET introducer.del_flg = 1 WHERE introducer.introducer_customer_id = customer.id';
                $rtn2 = $GLOBALS['mysqldb']->query($introducerCheckSql) or die('query error'.$GLOBALS['mysqldb']->error);

                if ( $rtn2 !== false ) {

                    // 紹介者情報に挿入
                    $introducerInsertSql = 'INSERT INTO `introducer` (`customer_id`, `introducer_customer_id`, `reg_date`, `edit_date`, `del_flg`) VALUES ('.
                    $introducerInsertSql.=
                        '"' . $GLOBALS['mysqldb']->real_escape_string( $introducerCustomerInfo['id'] ) . '",'
                        . '"' . $GLOBALS['mysqldb']->real_escape_string( $data['id'] ) . '",'
                        . 'now(),'
                        . 'now(),'
                        . '0';
                    $introducerInsertSql.= ');';
                    $rtn3 = $GLOBALS['mysqldb']->query($introducerInsertSql) or die('query error'.$GLOBALS['mysqldb']->error);

                }
            }else if($data_introducer_customer['id']==""){//紹介元が無ければ新規登録する
                $introducerInsertSql = 'INSERT INTO `introducer` ( `customer_id`, `introducer_customer_id`, `reg_date`, `edit_date`, `del_flg`) VALUES ('.
                $introducerInsertSql.=
                    '"' . $GLOBALS['mysqldb']->real_escape_string( $introducerCustomerInfo['id'] ) . '",'
                  . '"' . $GLOBALS['mysqldb']->real_escape_string( $data['id'] ) . '",'
                  . 'now(),'
                  . 'now(),'
                  . '0';
                $introducerInsertSql.= ');';
                $rtn = $GLOBALS['mysqldb']->query($introducerInsertSql) or die('query error'.$GLOBALS['mysqldb']->error);
            }

		} else if ( empty($_POST['inviteCode']) ) {

			// フォーム未入力で紹介者情報削除（論理）
			$introducerDeleteSql = 'UPDATE `introducer` SET introducer.del_flg = 1 WHERE introducer_customer_id = "' . $GLOBALS['mysqldb']->real_escape_string($data['id']) . '"';
			$rtn3 = $GLOBALS['mysqldb']->query($introducerDeleteSql) or die('query error'.$GLOBALS['mysqldb']->error);

            //$_POST['adcode'] = ''; // 元存在していたadcodeがリセットされてしまうのでコメントアウト　comment out by ka 20170215
            if($data['adcode']==INTRODUCTION_ADCODE) $_POST['adcode'] = ''; // 友達紹介広告コードのみリセット add by ka 20170215

        }

        // Input_New_Dataでエラーを引き起こすため実行前にアンセット
        unset($_POST['inviteCode']);

		// $data_ID =  Input_Update_Data($table);
		$data_ID = Update_Data($table, $customer_field2, $_POST['customer_id']);

    if(is_null($contract['course_id']) && $_POST['ctype']==3){    //モデル系に更新後、無料コース登録 add by ueda 20161003
        // $_POST["customer_id"] = $_POST['id'];
        $_POST["end_date"] = '3000-01-01';
        $_POST["course_id"] = '1003';
        $_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
        $contract_field = array("status","customer_id","course_id","end_date","reg_date","edit_date");
        $contract_ID = Input_New_Data("contract" ,$contract_field);
        // unset($_POST["customer_id"]);
        unset($_POST["end_date"]);
        unset($_POST["course_id"]);
        unset($_POST["reg_date"]);
        unset($_POST["edit_date"]);
    }else if($contract['course_id']==1003 && $_POST['ctype']<>3){
        $_POST["del_flg"] = 1;
        $contract_field = array("del_flg","edit_date");
        $contract_ID = Update_Data("contract" ,$contract_field,$contract["id"]);
        unset($_POST["del_flg"]);
        if ($_POST['ctype'] == 6){
			$_POST["end_date"] = '3000-01-01';
			$_POST["course_id"] = '1018';
			$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
			$contract_field = array("status","customer_id","course_id","end_date","reg_date","edit_date");
			$contract_ID = Input_New_Data("contract" ,$contract_field);
			unset($_POST["end_date"]);
			unset($_POST["course_id"]);
			unset($_POST["reg_date"]);
			unset($_POST["edit_date"]);
		}
    }

	if(is_null($contract['course_id']) && $_POST['ctype']==6){    //エステのVIPコース登録
		$_POST["end_date"] = '3000-01-01';
		$_POST["course_id"] = '1018';
		$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
		$contract_field = array("status","customer_id","course_id","end_date","reg_date","edit_date");
		$contract_ID = Input_New_Data("contract" ,$contract_field);
		unset($_POST["end_date"]);
		unset($_POST["course_id"]);
		unset($_POST["reg_date"]);
		unset($_POST["edit_date"]);
	}else if($contract['course_id']==1018 && $_POST['ctype']<>6){
		$_POST["del_flg"] = 1;
		$contract_field = array("del_flg","edit_date");
		$contract_ID = Update_Data("contract" ,$contract_field,$contract["id"]);
		unset($_POST["del_flg"]);
		if ($_POST['ctype'] == 3){
			$_POST["end_date"] = '3000-01-01';
			$_POST["course_id"] = '1003';
			$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
			$contract_field = array("status","customer_id","course_id","end_date","reg_date","edit_date");
			$contract_ID = Input_New_Data("contract" ,$contract_field);
			unset($_POST["end_date"]);
			unset($_POST["course_id"]);
			unset($_POST["reg_date"]);
			unset($_POST["edit_date"]);
		}
	}

	}else{
		//顧客新規
		$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");

		// 広告ID
		$column_adcode_name = "";
		if ( isset($_POST['inviteCode']) && !empty($_POST['inviteCode']) ) {

			// 紹介者情報に挿入
			$introducerInsertSql = 'INSERT INTO `introducer` (`id`, `customer_id`, `introducer_customer_id`, `reg_date`, `edit_date`, `del_flg`) VALUES ('.
			$introducerInsertSql.=
					'"' . $GLOBALS['mysqldb']->real_escape_string( $introducerCustomerInfo['id'] ) . '",'
				. '"' . $GLOBALS['mysqldb']->real_escape_string( $data['id'] ) . '",'
				. 'now(),'
				. 'now(),'
				. '0';
			$introducerInsertSql.= ');';
			$rtn = $GLOBALS['mysqldb']->query($introducerInsertSql) or die('query error'.$GLOBALS['mysqldb']->error);

			$_POST['adcode'] = INTRODUCTION_ADCODE;
			$column_adcode_name = "adcode";
		}

    // Input_New_Dataでエラーを引き起こすため実行前にアンセット
    unset($_POST['inviteCode']);

		$customer_field = array("no","ctype","name","name_kana","zip","pref","address","birthday","tel","mail","shop_id","reg_date","edit_date");
		$data_ID = $_POST["customer_id"] = Input_New_Data("customer",$customer_field);

    //モデル系新規登録後、無料コース登録 add by ueda 20161003
    if($_POST['ctype']==3){
        // $_POST["customer_id"] = $_POST['id'];
        $_POST["end_date"] = '3000-01-01';
        $_POST["course_id"] = '1003';
        $contract_field = array("status","customer_id","course_id","end_date","reg_date","edit_date");
        $contract_ID = Input_New_Data("contract" ,$contract_field);
        // unset($_POST["customer_id"]);
        unset($_POST["end_date"]);
        unset($_POST["course_id"]);
    }

	//エステVIP新規登録後、無料コース登録
    if($_POST['ctype']==6){
        $_POST["end_date"] = '3000-01-01';
        $_POST["course_id"] = '1018';
        $contract_field = array("status","customer_id","course_id","end_date","reg_date","edit_date");
        $contract_ID = Input_New_Data("contract" ,$contract_field);
        unset($_POST["end_date"]);
        unset($_POST["course_id"]);
    }

	}
	if( $data_ID ) 	header( "Location: ../main/?shop_id=".$data['shop_id']."&hope_date=".$counseling['hope_date']);
	else $gMsg = 'エラーが発生しました。<br><b><a href="javascript:history.back();">戻る</a></b>';
  }
}


//店舗リスト------------------------------------------------------------------------

$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0  AND status=2 order by id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$shop_list[0] = "-";
while ( $result = $shop_sql->fetch_assoc() ) {
	$shop_list[$result['id']] = $result['name'];
	$shop_code[$result['id']] = $result['code'];
}

//staff list
//if($shop) $where_shop = " AND shop_id=".$shop['id'];
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

//courseリスト
$course_list = getDatalist("course");

//specialリスト
$special_sql = $GLOBALS['mysqldb']->query( "select * from special WHERE del_flg = 0 AND status=0 order by id" ) or die('query error'.$GLOBALS['mysqldb']->error);
if ($special_sql) {
	$special_list[0] = "-";
	while ( $result = $special_sql->fetch_assoc() ) {
		$special_list[$result['id']] = $result['name'];
	}
}

//紹介企業リスト
$partner_sql = $GLOBALS['mysqldb']->query( "select * from partner WHERE del_flg = 0 AND status=0 order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
if ($partner_sql) {
	$partner_list[0] = "-";
	while ( $result = $partner_sql->fetch_assoc() ) {
		$partner_list[$result['id']] = $result['name'];
	}
}
?>
