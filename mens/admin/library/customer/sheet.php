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
if( $_POST['action'] <> "edit" ) include_once( "../../lib/auth.php" );

if($authority_level>6){
	session_start();
	session_destroy();
}

$table = "sheet";

$_POST['customer_id'] = $_POST['customer_id'] ? $_POST['customer_id'] : $_GET['customer_id'];

if($_POST['name']) 		$_POST['name'] 		                = str_replace(" ", "　", $_POST['name']);//半角スペースを全角スペースに統一
if($_POST['name']) 		$_POST['name'] 		                = str_replace("　　", "　", $_POST['name']);//2スペースを1スペースに統一
if($_POST['parent_name']) 		$_POST['parent_name'] 		= str_replace(" ", "　", $_POST['parent_name']);//半角スペースを全角スペースに統一
if($_POST['parent_name']) 		$_POST['parent_name'] 		= str_replace("　　", "　", $_POST['parent_name']);//2スペースを1スペースに統一
if($_POST['name_kana']) $_POST['name_kana']                 = str_replace(" ", "　", $_POST['name_kana']);//半角スペースを全角スペースに統一
if($_POST['name_kana'])	$_POST['name_kana']                 = str_replace("　　", "　", $_POST['name_kana']);//2スペースを1スペースに統一

// if($_POST['tel']) $_POST['tel'] = sepalate_tel($_POST['tel']); //電話番号整形

// 編集------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {

	// 質問内容の受け取り
	// 未成年・保護者住所チェック
	if($_POST['parent_address_check'])$_POST['parent_address_check']  = '1'; // 上記住所と同じ（保護者、本人と同居）
	if(!$_POST['parent_address_check'])$_POST['parent_address_check'] = '0'; // 上記住所と違う
	
	// １．KIREIMOをどこで知りましたか？（複数回答可）
	$knowledge1 = (isset($_POST['knowledge1'])) ? implode(",",$_POST['knowledge1']).',' : '';
	$knowledge2 = (isset($_POST['knowledge2'])) ? implode(",",$_POST['knowledge2']).',' : '';
	$_POST['knowledge'] = $knowledge1.$knowledge2;
	unset($_POST['knowledge1']);
	unset($_POST['knowledge2']);
	if($_POST['knowledge'])$_POST['knowledge'] = $_POST['knowledge'];

	// ２．KIREIMOへお越し頂いたきっかけを教えてください。（複数回答可）
	if($_POST['seeing'])$_POST['seeing'] = implode(",",$_POST['seeing']);

	// ３．脱毛のご経験（複数回答可）
	// if($_POST['experience'])$_POST['experience'] = implode(",",$_POST['experience']); // 脱毛のご経験 20160427 チェックボックスに変わったのでコメントアウト
	if($_POST['experience_facility'])$_POST['experience_facility'] = implode(",",$_POST['experience_facility']); // 脱毛経験あり
	
	// ４．希望するパーツにチェックをつけてください
	$suggested_part1 = (isset($_POST['suggested_part1'])) ? implode(",",$_POST['suggested_part1']).',' : '';
	$suggested_part2 = (isset($_POST['suggested_part2'])) ? implode(",",$_POST['suggested_part2']).',' : '';
	$suggested_part3 = (isset($_POST['suggested_part3'])) ? implode(",",$_POST['suggested_part3']) : '';
	$_POST['suggested_part'] = $suggested_part1.$suggested_part2.$suggested_part3;
	unset($_POST['suggested_part1']);
	unset($_POST['suggested_part2']);
	unset($_POST['suggested_part3']);

	// ５－１．上半身の自己処理方法について（複数回答可）
	if($_POST['self_face'])$_POST['self_face'] = implode(",",$_POST['self_face']);

	// ５－２．下半身の自己処理方法について（複数回答可）
	if($_POST['self_body'])$_POST['self_body'] = implode(",",$_POST['self_body']);

	// １１．ケロイド体質、または白斑と診断を受けたことがある。または、自覚がありますか？
	if($_POST['keloid_check'])$_POST['keloid_check'] = implode(",",$_POST['keloid_check']); // ケロイド、白斑

	// １６．無料カウンセリングを受けようと思った理由を教えてください。（複数回答可）
	$beginning1 = (isset($_POST['beginning1'])) ? implode(",",$_POST['beginning1']).',' : '';
	$beginning2 = (isset($_POST['beginning2'])) ? implode(",",$_POST['beginning2']) : '';
	$_POST['beginning'] = $beginning1.$beginning2;
	unset($_POST['beginning1']);
	unset($_POST['beginning2']);
	if($_POST['beginning'])$_POST['beginning'] = $_POST['beginning'];	
	if($_POST['beginning_place'])$_POST['beginning_place'] = implode(",",$_POST['beginning_place']);
	
	// １７．KIREIMOで脱毛でわからないこと、不安に感じることはありますか？（複数回答可）
	// if($_POST['anxiety'])$_POST['anxiety'] = implode(",",$_POST['anxiety']);

	// １８．KIREIMO以外に脱毛を検討した、カウンセリングに行った、またはその予定があるお店はありますか？（複数回答可）
	// if($_POST['externalshop'])$_POST['externalshop'] = implode(",",$_POST['externalshop']);


	// バリチェック処理
	$errmsg = array();

	// 前後の空白を取り除き
	foreach ($_POST as $key => $val) {
		$val = preg_replace('/^[ 　]+/u', '', $val);
		$_POST[$key] = preg_replace('/[ 　]+$/u', '', $val);
	}

	// ご記入日 チェック
	if(!strptime( $_POST["input_date"], '%Y-%m-%d')){
		$errmsg["input_date"] = "存在しない日付です<br>『yyyy-mm-dd』の形式で入力してください";
	} else if(strtotime("today") < strtotime($_POST["input_date"])){
		$errmsg["input_date"] = "存在しない日付です";
	}

	// お名前 チェック
	if(!isset($_POST["name"]) || $_POST["name"] == "") {
		$errmsg["name"] = "お名前を入力してください";
	} else if(!strpos($_POST["name"], "　")){
		$errmsg["name"] = "姓と名の間にスペースを入れてください";
	} else if($_POST["name"]){
		// 特殊文字チェック #256 2017/06/15 add by shimada
		if(Invalid_Characters_Check($_POST["name"])) {
		   	// $errmsg["name"] = "ご利用いただけない文字(旧字体、アラビア数字、記号など)が含まれています";
			$errmsg["name"] = "ご利用いただけない文字「".Invalid_Characters_Check($_POST['name'])."」が含まれています";
		} 
	}
	
	// フリガナ チェック
	if(!isset($_POST["name_kana"]) || $_POST["name_kana"] == "") {
		$errmsg["name_kana"] = "フリガナを入力してください";
	} else if(!preg_match("/^([　 \t\r\n]|[ァ-ヶー])+$/u", $_POST["name_kana"])){
		$errmsg["name_kana"] = "全角カタカナを入力してください";
	} else if(!strpos($_POST["name_kana"], "　")){
		$errmsg["name_kana"] = "姓と名の間にスペースを入れてください";
	}

    // 電話番号 チェック
	if(!isset($_POST["tel"]) || $_POST["tel"] == "") {
		$errmsg["tel"] = "電話番号を入力してください";
	} else {
		if($_POST['tel']) {
			$tel = sepalate_tel($_POST['tel']);
			if($tel != 0) {
				$_POST['tel'] = $tel;
			} else {
				$errmsg['tel'] = "電話番号が正しくありません";
			}
		}
	}

	// メールアドレス チェック
	if(!isset($_POST["mail"]) || $_POST["mail"] == "") {
		$errmsg["mail"] = "メールアドレスを入力してください";
	} else {
		$rec = Check_Email2($_POST["mail"]);

		if($rec["flg"] == 1) {
			$errmsg["mail"] = $rec["error"];
		}
	}

	// 職業 チェック 20160125 shimada
	if(isset($_POST["job"]) || $_POST["job"] == "") {
		if($_POST["job"] === "0"){
			$errmsg["job"] = "ご職業を選択してください";
		}
		if($_POST["job"] === "3" || $_POST["job"] === "1" || $_POST["job"] === "2"  ){ // 3.学生、1.会社員・公務員、2.経営者・役員
			if($_POST["job_sub"] === "0"){
				$errmsg["job_sub"] = "ご職業(中分類)を選択してください";
			}
		}
		if($_POST["job"] === "8"){ // 8.その他
			if($_POST["job_other"] == ""){
				$errmsg["job_other"] = "ご職業(その他)を入力してください";
			}
		}
	}

	// 住所 チェック
	if (!isset($_POST["zip1"]) || $_POST["zip1"] == "") {
		$zipFlg1 = 1;
	} 
	if(!isset($_POST["zip2"]) || $_POST["zip2"] == ""){
		$zipFlg2 = 1;
	}
	if($zipFlg1 === 1 || $zipFlg2 === 1){
		$errmsg["zip"] = "郵便番号を入力してください";
	} else {
		// 郵便番号の桁数チェック
		if(mb_strlen($_POST["zip1"])<>3){
			$errmsg["zip1"] = "郵便番号(左)は3桁で入力してください";
		}
		if(mb_strlen($_POST["zip2"])<>4){
			$errmsg["zip2"] = "郵便番号(右)は4桁で入力してください";
		}
	}

	if(isset($_POST["pref"]) || $_POST["pref"] == "") {
		if($_POST["pref"] === "0"){
			$errmsg["pref"] = "都道府県が未選択です";
		}
	}
	if (!isset($_POST["address"]) || $_POST["address"] == "") {
		$errmsg["address"] = "住所を入力してください";
	} else if($_POST["address"]){
		$_POST["address"] = Address_Check($_POST["address"]);
		// 特殊文字チェック #256 2017/06/15 add by shimada
		if(Invalid_Characters_Check($_POST["address"])) {
		   	// $errmsg["address"] = "ご利用いただけない文字(旧字体、アラビア数字、記号など)が含まれています";
			$errmsg["address"] = "住所にご利用いただけない文字「".Invalid_Characters_Check($_POST['address'])."」が含まれています";
		} 
	} 

	// 住所 チェック(勤務先)
	if(isset($_POST["work_address"]) && $_POST["work_address"] != "") {
		if($_POST["work_pref"] === "0"){
			$errmsg["work_pref"] = "都道府県が未選択です";
		}
	}

	// 生年月日 チェック
	if(!isset($_POST["birthday_y"]) || $_POST["birthday_y"] == "") {
		$errmsg["birthday_y"] = "年を入力してください";
	}
	if(!isset($_POST["birthday_m"]) || $_POST["birthday_m"] == "") {
		$errmsg["birthday_m"] = "月を入力してください";
	}
	if(!isset($_POST["birthday_d"]) || $_POST["birthday_d"] == "") {
		$errmsg["birthday_d"] = "日を入力してください";
	}

	// 年の入力チェック
	// 数値かどうか OR 5歳-85歳以外の人を除外する
	$nowYearUnder = date("Y") - 86;
	$nowYearYounger = date("Y") - 4;
	if(!preg_match("/\d{4}/", $_POST["birthday_y"])) {
		$errmsg["birthday"] = "正しい形式で入力してください";
	} else if(($_POST["birthday_y"] <= $nowYearUnder) || ($nowYearYounger <= $_POST["birthday_y"])){
		$errmsg["birthday"] = "正しい年を入力してください";
	}
	$_POST["birthday"] = $_POST["birthday_y"].'-'.$_POST["birthday_m"].'-'.$_POST["birthday_d"];
	unset($_POST["birthday_y"]);
	unset($_POST["birthday_m"]);
	unset($_POST["birthday_d"]);
	

	// 身長 チェック
	if(isset($_POST["height"]) && $_POST["height"] != "") {
		$height = mb_convert_kana($_POST["height"], "a", "UTF-8");
		// 数値チェック
		if(Che_Num($height)){
			if(($height < 100) || ($height > 250)){
				$errmsg["height"] = "正しい数値を入力してください。";
			} else {
				// 数値が正しい場合は、半角数字を設定
				$_POST["height"] = $height;
			}
		} else {
			$errmsg["height"] = "数値を入力してください";
		}
	}

	// 体重 チェック
	if(isset($_POST["weight"]) && $_POST["weight"] != "") {
		$weight = mb_convert_kana($_POST["weight"], "a", "UTF-8");
		// 数値チェック
		if(Che_Num($weight)){
			if(($weight < 20) || ($weight > 200)){
				$errmsg["weight"] = "正しい数値を入力してください。";
			} else {
				// 数値が正しい場合は、半角数字を設定
				$_POST["weight"] = $weight;
			}
		} else {
			$errmsg["weight"] = "数値を入力してください。";
		}
	}

	// 電話番号 チェック(勤務先)
	if($_POST['work_tel']) {
		$tel = sepalate_tel($_POST['work_tel']);
		if($tel != 0) {
			$_POST['work_tel'] = $tel;
		} else {
			$errmsg['work_tel'] = "電話番号が正しくありません";
		}
	}

	// 年収　チェック（勤務先）
	if(isset($_POST["work_annual_income"]) && $_POST["work_annual_income"] != "") {
		$work_annual_income = mb_convert_kana($_POST["work_annual_income"], "a", "UTF-8");
		// 数値チェック
		if(Che_Num($work_annual_income)){
			if($work_annual_income == 0){
				$errmsg["work_annual_income"] = "正しい数値を入力してください。";
			} else {
				// 数値が正しい場合は、半角数字を設定
				$_POST["work_annual_income"] = $work_annual_income;
			}
		} else {
			if(Che_Num2($work_annual_income) == false){
				$errmsg["work_annual_income"] = "数値を入力してください";
			}
			
		}
	}

	// 年齢を計算
	$now = date('Ymd');
	$birthday = $_POST["birthday"];
	$birthday = str_replace("-", "", $birthday);
	if($_POST["birthday"] && $_POST["birthday"]<>"0000-00-00")$_POST['age'] = floor(($now-$birthday)/10000);
	$customer_field = array();
	// 20歳未満 保護者情報入力(必須)
	if(intval($_POST['age']) < 20){
		// お名前 チェック(保護者)
		if(!isset($_POST["parent_name"]) || $_POST["parent_name"] == "") {
			$errmsg["parent_name"] = "保護者のお名前を入力してください";
		} else if(!strpos($_POST["parent_name"], "　")){
			$errmsg["parent_name"] = "姓と名の間にスペースを入れてください";
		}

		if(!isset($_POST["parent_tel"]) || $_POST["parent_tel"] == "") {
			$errmsg["parent_tel"] = "保護者の電話番号を入力してください";
		} else {
			if($_POST['parent_tel']) {
				$tel = sepalate_tel($_POST['parent_tel']);
				if($tel != 0) {
					$_POST['parent_tel'] = $tel;
				} else {
					$errmsg['parent_tel'] = "電話番号が正しくありません";
				}
			}
		}

		// 住所 チェック(保護者)
		if($_POST['parent_address_check'] === '1'){
			// 本人と住所が同じ
			$_POST['parent_pref']     = $_POST['pref'];
			$_POST['parent_zip1']     = $_POST['zip1'];
			$_POST['parent_zip2']     = $_POST['zip2'];
			$_POST['parent_address']  = $_POST['address'];

		} else {
			// 「上記住所と同じ」にチェックがない場合のみ保護者の住所情報のエラーチェックを行う
			if (!isset($_POST["parent_zip1"]) || $_POST["parent_zip1"] == "") {
				$zipPFlg1 = 1;
			} 
			if(!isset($_POST["parent_zip2"]) || $_POST["parent_zip2"] == ""){
				$zipPFlg2 = 1;
			}
			if($zipPFlg1 === 1 || $zipPFlg2 === 1){
				$errmsg["parent_zip"] = "保護者の郵便番号を入力してください";
			}else {
				// 郵便番号の桁数チェック
				if(mb_strlen($_POST["parent_zip1"])<>3){
					$errmsg["parent_zip1"] = "保護者の郵便番号(左)は3桁で入力してください";
				}
				if(mb_strlen($_POST["parent_zip2"])<>4){
					$errmsg["parent_zip2"] = "保護者の郵便番号(右)は4桁で入力してください";
				}
			}
			if(isset($_POST["parent_pref"]) || $_POST["parent_pref"] == "") {
				if($_POST["parent_pref"] === "0"){
					$errmsg["parent_pref"] = "保護者の都道府県が未選択です";
				}
			}
			if (!isset($_POST["parent_address"]) || $_POST["parent_address"] == "") {
				$errmsg["parent_address"] = "保護者の住所を入力してください";
			} else if($_POST["parent_address"]){
				$_POST["parent_address"] = Address_Check($_POST["parent_address"]);
				// 特殊文字チェック #256 2017/06/15 add by shimada
				if(Invalid_Characters_Check($_POST["parent_address"])) {
				   	// $errmsg["parent_address"] = "ご利用いただけない文字(旧字体、アラビア数字、記号など)が含まれています";
					$errmsg["parent_address"] = "保護者の住所にご利用いただけない文字「".Invalid_Characters_Check($_POST['parent_address'])."」が含まれています";
				} 
			}
			
            // 本人と住所が違う
			if($_POST['parent_tel']) array_push($customer_field,  "parent_tel");
			if($_POST['parent_address']) array_push($customer_field,  "parent_address");
			if($_POST['parent_pref']) array_push($customer_field,  "parent_pref");	
		}
		
		if($_POST['parent_name']) array_push($customer_field,  "parent_name");

	}

	// 質問内容の必須チェック

	$question_error ="選択してください";

	// 希望するパーツエリア
	if ($_POST["face_area"] == "" && $_POST["upper_area"] == "" && $_POST["lower_area"] == "" 
		&& $_POST["vio_area"] == "" && $_POST["whole_area"] == "") {
		$errmsg["suggested_area"] = $question_error;
	}
	// 希望するパーツ部位の必須はメンズでは使わない想定
	// if (!isset($_POST["suggested_part"]) || $_POST["suggested_part"] == "") {
	// 	$errmsg["suggested_part"] = $question_error;
	// }

	if (!isset($_POST["skin_type"]) || $_POST["skin_type"] == "") {
		$errmsg["skin_type"] = $question_error;
	}
	if (!isset($_POST["self_face"]) || $_POST["self_face"] == "") {
		$errmsg["self_face"] = $question_error;
	}
	if (!isset($_POST["self_body"]) || $_POST["self_body"] == "") {
		$errmsg["self_body"] = $question_error;
	}
	if (!isset($_POST["experience"]) || $_POST["experience"] == "") {
		$errmsg["experience"] = $question_error;
	}
	if (!isset($_POST["cm"]) || $_POST["cm"] == "") {
		$errmsg["cm"] = $question_error;
	}
	if (!isset($_POST["oc"]) || $_POST["oc"] == "") {
		$errmsg["oc"] = $question_error;
	}
	if (!isset($_POST["drug"]) || $_POST["drug"] == "") {
		$errmsg["drug"] = $question_error;
	}
	if (!isset($_POST["allergie"]) || $_POST["allergie"] == "") {
		$errmsg["allergie"] = $question_error;
	}
	if (!isset($_POST["keloid"]) || $_POST["keloid"] == "") {
		$errmsg["keloid"] = $question_error;
	}
	if (!isset($_POST["kabure"]) || $_POST["kabure"] == "") {
		$errmsg["kabure"] = $question_error;
	}
	if (!isset($_POST["alien"]) || $_POST["alien"] == "") {
		$errmsg["alien"] = $question_error;
	}
	if (!isset($_POST["tattoo"]) || $_POST["tattoo"] == "") {
		$errmsg["tattoo"] = $question_error;
	}
	if (!isset($_POST["infection"]) || $_POST["infection"] == "") {
		$errmsg["infection"] = $question_error;
	}


	// チェックでエラーがない場合は、DB登録
	if(count($errmsg) == 0) {

		$_POST["zip"] = $_POST["zip1"]."-".$_POST["zip2"];
		unset($_POST["zip1"]);
		unset($_POST["zip2"]);
		$_POST["work_zip"] = $_POST["work_zip1"]."-".$_POST["work_zip2"];
		unset($_POST["work_zip1"]);
		unset($_POST["work_zip2"]);

		// 20歳未満のみ
		$_POST["parent_zip"] = $_POST["parent_zip1"]."-".$_POST["parent_zip2"];
		unset($_POST["parent_zip1"]);
		unset($_POST["parent_zip2"]);

		//顧客編集
		if($_POST['id'] != "" ){
			$_POST['edit_date'] = date("Y-m-d H:i:s");
			$data_ID =  Input_Update_Data($table);
		}else{
			//顧客新規
			$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
			$data_ID =  Input_Data($table);
		}

		$customer_field = array("name","name_kana","edit_date");
		if($_POST['birthday']) array_push($customer_field,  "birthday");
		// if($_POST['birthday'] && $_POST["birthday"]<>"0000-00-00") array_push($customer_field,  "birthday");
		if($_POST['age']) array_push($customer_field,  "age");
		if($_POST['tel']) array_push($customer_field,  "tel");
		if($_POST['mail']) array_push($customer_field,  "mail");
		if($_POST['pref']) array_push($customer_field,  "pref");	
		if($_POST['address']) array_push($customer_field,  "address");
		
		// 勤務先用
		if($_POST['work_tel']) array_push($customer_field,  "work_tel");
		if($_POST['work_address']) array_push($customer_field,  "work_address");
		if($_POST['work_pref']) array_push($customer_field,  "work_pref");
		if($_POST['work_annual_income']) array_push($customer_field,  "work_annual_income");

		// データを更新する(問診票の情報→顧客情報に書き換える)
		$customer_field_base = array("name","name_kana","edit_date");
		if($_POST['birthday']) array_push($customer_field_base,  "birthday");
		if($_POST['age']) array_push($customer_field_base,  "age");
		if($_POST['tel']) array_push($customer_field_base,  "tel");
		if($_POST['mail']) array_push($customer_field_base,  "mail");
		if($_POST['job']) array_push($customer_field_base,  "job");
		if($_POST['job_sub']) array_push($customer_field_base,  "job_sub");
		if($_POST['zip']) array_push($customer_field_base,  "zip");
		if($_POST['pref']) array_push($customer_field_base,  "pref");
		if($_POST['address']) array_push($customer_field_base,  "address");
		if($_POST['height']) array_push($customer_field_base,  "height");
		if($_POST['weight']) array_push($customer_field_base,  "weight");
		Update_Data("customer",$customer_field_base,$_POST['customer_id']);

		if( $data_ID ) {
			$gMsg = '<div style="font-size:28px;line-height:40px;text-align:center;padding:80px;">ご記入ありがとうございました。<br />只今スタッフが参りますので、<br />CALLボタンを押してお待ち下さいませ。</div>';
		} else { 
			$gMsg = '登録出来ませんでした。<br><b><a href="javascript:history.back();">戻る</a></b>';
		}
	}
	$data = $_POST;


} else {
	// 詳細を取得
	if( $_POST['customer_id'] != "" )  {
		$data = Get_Table_Row($table," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."'");
		$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($_POST['customer_id'])."'");
		if($customer['birthday'] != '0000-00-00'){
			$data['birthday'] = $customer['birthday'];
		}	
	}
}

// birthdayの項目を作る
list($birthday_y, $birthday_m, $birthday_d) = explode('-', $data['birthday']);






// 店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist_shop();

//$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0  AND status=2 order by id" );
//$shop_list[0] = "-";
//while ( $result = $shop_sql->fetch_assoc() ) {
//	$shop_list[$result['id']] = $result['name'];
//	$shop_code[$result['id']] = $result['code'];
//}

//staff list
if($shop) $where_shop = " AND shop_id=".$shop['id'];
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY type,id" );
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

if($data['media'])$data['media'] = explode(",",$data['media']);

if($data["zip"]){
	$zip = explode("-", $data["zip"]);
	$data["zip1"] = $zip[0];
	$data["zip2"] = $zip[1];
}

if($data["parent_zip"]){
	$zip = explode("-", $data["parent_zip"]);
	$data["parent_zip1"] = $zip[0];
	$data["parent_zip2"] = $zip[1];
}


if($data["work_zip"]){
	$zip = explode("-", $data["work_zip"]);
	$data["work_zip1"] = $zip[0];
	$data["work_zip2"] = $zip[1];
}

?>
