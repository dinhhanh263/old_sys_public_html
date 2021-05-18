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
if( $_POST['action'] <> "edit" ) require_once LIB_DIR . 'auth.php';

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
	if($_POST['parent_address_check'])$_POST['parent_address_check']  = '1'; // 上記住所と同じ（保護者、本人と同居）
	if(!$_POST['parent_address_check'])$_POST['parent_address_check'] = '0'; // 上記住所と違う
	// if($_POST['self'])$_POST['self'] = implode(",",$_POST['self']);// 自己処理
	if($_POST['experience'])$_POST['experience'] = implode(",",$_POST['experience']); // 経験
	if($_POST['media'])$_POST['media'] = implode(",",$_POST['media']); // メディア
	if($_POST['skincare'])$_POST['skincare'] = implode(",",$_POST['skincare']); // スキンケア用品
	if($_POST['care'])$_POST['care'] = implode(",",$_POST['care']); // ケア用品
	if($_POST['soapbar'])$_POST['soapbar'] = implode(",",$_POST['soapbar']); // 石鹸
	if($_POST['buy'])$_POST['buy'] = implode(",",$_POST['buy']);// スキンケア用品を購入した理由（効果）
	if($_POST['experience_facility'])$_POST['experience_facility'] = implode(",",$_POST['experience_facility']);// 脱毛経験あり
	if($_POST['keloid_check'])$_POST['keloid_check'] = implode(",",$_POST['keloid_check']);// ケロイド、白斑

	// ２．脱毛サロンを選ぶポイントは何ですか？（複数回答可） 2016/07/05追加 shimada
	if($_POST['point'])$_POST['point'] = implode(",",$_POST['point']);

	// １６．KIREIMOを知ったきっかけは何ですか？（複数回答可）
	$knowledge1 = (isset($_POST['knowledge1'])) ? implode(",",$_POST['knowledge1']).',' : '';
	$knowledge2 = (isset($_POST['knowledge2'])) ? implode(",",$_POST['knowledge2']).',' : '';
	$knowledge3 = (isset($_POST['knowledge3'])) ? implode(",",$_POST['knowledge3']).',' : '';
	$knowledge4 = (isset($_POST['knowledge4'])) ? implode(",",$_POST['knowledge4']).',' : '';
	$knowledge5 = (isset($_POST['knowledge5'])) ? implode(",",$_POST['knowledge5']).',' : '';
	$knowledge6 = (isset($_POST['knowledge6'])) ? implode(",",$_POST['knowledge6']) : '';
	$_POST['knowledge'] = $knowledge1.$knowledge2.$knowledge3.$knowledge4.$knowledge5.$knowledge6;
	unset($_POST['knowledge1']);
	unset($_POST['knowledge2']);
	unset($_POST['knowledge3']);
	unset($_POST['knowledge4']);
	unset($_POST['knowledge5']);
	unset($_POST['knowledge6']);
	if($_POST['knowledge'])$_POST['knowledge'] = $_POST['knowledge'];

	// １７．KIREIMOへお越し頂いたきっかけを教えてください。（複数回答可）
	// $seeing1 = (isset($_POST['seeing1'])) ? implode(",",$_POST['seeing1']).',' : '';
	// $seeing2 = (isset($_POST['seeing2'])) ? implode(",",$_POST['seeing2']).',' : '';
	// $seeing3 = (isset($_POST['seeing3'])) ? implode(",",$_POST['seeing3']).',' : '';
	// $seeing4 = (isset($_POST['seeing4'])) ? implode(",",$_POST['seeing4']).',' : '';
	// $seeing5 = (isset($_POST['seeing5'])) ? implode(",",$_POST['seeing5']) : '';
	// $_POST['seeing'] = $seeing1.$seeing2.$seeing3.$seeing4.$seeing5;
	// unset($_POST['seeing1']);
	// unset($_POST['seeing2']);
	// unset($_POST['seeing3']);
	// unset($_POST['seeing4']);
	// unset($_POST['seeing5']);
	// if($_POST['seeing'])$_POST['seeing'] = $_POST['seeing'];
	if($_POST['seeing'])$_POST['seeing'] = implode(",",$_POST['seeing']);

	// １８．無料カウンセリングを受けようと思った理由を教えてください。（複数回答可）
	// $beginning1 = (isset($_POST['beginning1'])) ? implode(",",$_POST['beginning1']).',' : '';
	// $beginning2 = (isset($_POST['beginning2'])) ? implode(",",$_POST['beginning2']) : '';
	// $_POST['beginning'] = $beginning1.$beginning2;
	// unset($_POST['beginning1']);
	// unset($_POST['beginning2']);
	// if($_POST['beginning'])$_POST['beginning'] = $_POST['beginning'];
	if($_POST['beginning'])$_POST['beginning'] = implode(",",$_POST['beginning']);
	if($_POST['beginning_place'])$_POST['beginning_place'] = implode(",",$_POST['beginning_place']);

	// １９．KIREIMOで脱毛でわからないこと、不安に感じることはありますか？（複数回答可）
	if($_POST['anxiety'])$_POST['anxiety'] = implode(",",$_POST['anxiety']);

	// ２０．KIREIMO以外に脱毛を経験した、カウンセリングに行った、またはその予定があるお店はありますか？（複数回答可）
	if($_POST['externalshop'])$_POST['externalshop'] = implode(",",$_POST['externalshop']);


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
	} else if(Invalid_Characters_Kana_Check($_POST["name_kana"])){
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
		if($_POST["job"] === "3" || $_POST["job"] === "1" ){ // 3.学生、1.社会人
			if($_POST["job_sub"] === "0"){
				$errmsg["job_sub"] = "ご職業(中分類)を選択してください";
			}
		} else {
			$_POST["job_sub"] = ""; // 学生・社会人以外選択時、ご職業(中分類)を削除
		}
		if($_POST["job"] === "8"){ // 8.その他
			if($_POST["job_other"] == ""){
				$errmsg["job_other"] = "ご職業(その他)を入力してください";
			}
		} else {
			$_POST["job_other"] = ""; // その他以外選択時、ご職業(その他)を削除
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
		$errmsg["birthday"] = "正しい形式で年を入力してください";
	} else if(($_POST["birthday_y"] <= $nowYearUnder) || ($nowYearYounger <= $_POST["birthday_y"])){
		$errmsg["birthday"] = "正しい年を入力してください";
	}
	$_POST["birthday"] = ($_POST["birthday_y"] ? $_POST["birthday_y"] : "0000").'-'.($_POST["birthday_m"] ? $_POST["birthday_m"] : "00").'-'.($_POST["birthday_d"] ? $_POST["birthday_d"] : "00");
	unset($_POST["birthday_y"]);
	unset($_POST["birthday_m"]);
	unset($_POST["birthday_d"]);
	// 契約済みの場合、生年月日の変更不可
	$contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."'");
	if($contract['id']) {
		$birthday_before = Get_Table_Col("customer","birthday"," WHERE del_flg=0 and id = '".addslashes($_POST['customer_id'])."'"); // 変更前の生年月日
		if($birthday_before != $_POST["birthday"]) {
			$errmsg["birthday"] = "契約済みのため生年月日を変更できません";
		}
	}
	$err_birthday = ($errmsg["birthday"] || $errmsg["birthday_y"] || $errmsg["birthday_m"] || $errmsg["birthday_d"]) ? true : false;

	// 身長 チェック
	if(isset($_POST["height"]) && $_POST["height"] != "") {
		$height = mb_convert_kana($_POST["height"], "a", "UTF-8");
		// 数値チェック
		if(Che_Num($height)){
			if(($height < 100) || ($height > 250)){
				$errmsg["height"] = "身長に正しい数値を入力してください";
			} else {
				// 数値が正しい場合は、半角数字を設定
				$_POST["height"] = $height;
			}
		} else {
			$errmsg["height"] = "身長に数値を入力してください";
		}
	}

	// 体重 チェック
	if(isset($_POST["weight"]) && $_POST["weight"] != "") {
		$weight = mb_convert_kana($_POST["weight"], "a", "UTF-8");
		// 数値チェック
		if(Che_Num($weight)){
			if(($weight < 20) || ($weight > 200)){
				$errmsg["weight"] = "体重に正しい数値を入力してください";
			} else {
				// 数値が正しい場合は、半角数字を設定
				$_POST["weight"] = $weight;
			}
		} else {
			$errmsg["weight"] = "体重に数値を入力してください";
		}
	}

	// 電話番号 チェック(勤務先)
	// if($_POST['work_tel']) {
	// 	$tel = sepalate_tel($_POST['work_tel']);
	// 	if($tel != 0) {
	// 		$_POST['work_tel'] = $tel;
	// 	} else {
	// 		$errmsg['work_tel'] = "電話番号が正しくありません";
	// 	}
	// }

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
	// $now = date('Ymd');
	$now = date('Ymd', strtotime($_POST['input_date'])); // ご記入日
	$birthday = $_POST["birthday"];
	$birthday = str_replace("-", "", $birthday);
	if($_POST["birthday"] && $_POST["birthday"]<>"0000-00-00")$_POST['age'] = floor(($now-$birthday)/10000);
	// $customer_field = array();
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
			// if($_POST['parent_tel']) array_push($customer_field,  "parent_tel");
			// if($_POST['parent_address']) array_push($customer_field,  "parent_address");
			// if($_POST['parent_pref']) array_push($customer_field,  "parent_pref");
		}

		// if($_POST['parent_name']) array_push($customer_field,  "parent_name");

	}

	// 質問内容の必須チェック

	$question_error ="選択してください";

	// 2016/07/05 問診票短縮のため非表示対応とする shimada
	// if (!isset($_POST["skin_type"]) || $_POST["skin_type"] == "") {// 肌質について
	// 	$errmsg["skin_type"] = $question_error;
	// }
	// if (!isset($_POST["self"]) || $_POST["self"] == "") {// 自己処理方法について
	// 	$errmsg["self"] = $question_error;
	// }
	if (!isset($_POST["experience"]) || $_POST["experience"] == "") {// 脱毛のご経験
		$errmsg["experience"] = $question_error;
	}
	if (!isset($_POST["cm"]) || $_POST["cm"] == "") {// 過去の病歴
		$errmsg["cm"] = $question_error;
	}
	if (!isset($_POST["oc"]) || $_POST["oc"] == "") {// 現在治療中及び定期検査のご予定
		$errmsg["oc"] = $question_error;
	}
	if (!isset($_POST["drug"]) || $_POST["drug"] == "") {// 現在お薬の服用や、軟膏・湿布等の塗布はありますか？
		$errmsg["drug"] = $question_error;
	}
	// if (!isset($_POST["allergie"]) || $_POST["allergie"] == "") {// アレルギーをお持ちですか？
	// 	$errmsg["allergie"] = $question_error;
	// }
	if (!isset($_POST["pregnancy"]) || $_POST["pregnancy"] == "") {// 現在妊娠中、または可能性がありますか？
		$errmsg["pregnancy"] = $question_error;
	}
	if (!isset($_POST["keloid"]) || $_POST["keloid"] == "") {// ケロイド体質、または白斑と診断を受けたことがある。または、自覚がありますか？
		$errmsg["keloid"] = $question_error;
	}
	// 2016/07/05 問診票短縮のため非表示対応とする shimada
	// if (!isset($_POST["kabure"]) || $_POST["kabure"] == "") {// 化粧品によるカブレを起こしたことがありますか？
	// 	$errmsg["kabure"] = $question_error;
	// }
	if (!isset($_POST["alien"]) || $_POST["alien"] == "") {// 脱毛希望箇所に異物は入っていますか？（医療用ボルト・シリコンなど）
		$errmsg["alien"] = $question_error;
	}
	// 2016/07/05 問診票短縮のため非表示対応とする shimada
	// if (!isset($_POST["menstruation"]) || $_POST["menstruation"] == "") {// 月経周期について
	// 	$errmsg["menstruation"] = $question_error;
	// }
	if (!isset($_POST["tattoo"]) || $_POST["tattoo"] == "") {// タトゥーは入っていますか？
		$errmsg["tattoo"] = $question_error;
	}
	// 2016/07/05 問診票短縮のため非表示対応とする shimada
	// if (!isset($_POST["sunburn"]) || $_POST["sunburn"] == "") {// 日焼けはされていますか？もしくは、日焼けのご予定はありますか？
	// 	$errmsg["sunburn"] = $question_error;
	// }
	// if (!isset($_POST["infection"]) || $_POST["infection"] == "") {// 感染症である、または感染症の疑いがありますか？
	// 	$errmsg["infection"] = $question_error;
	// }

	// チェックでエラーがない場合は、DB登録
	if(count($errmsg) == 0) {

		// エスケープ処理(暫定対応)
		foreach ($_POST as $key => $val) {
			// $_POST[$key] = addslashes($val);
			$_POST[$key] = $GLOBALS['mysqldb']->real_escape_string($val);
		}

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
			$sheet_field2 = array(
				// customerテーブルと共通のカラムを除外(staff_id除く)
				"staff_id",
				"input_date",
				"parent_name",
				"parent_tel",
				"parent_address_check",
				"parent_zip",
				"parent_pref",
				"parent_address",
				// "work_tel",
				// "work_zip",
				// "work_pref",
				// "work_address",
				// "work_annual_income",
				// "skin_type",
				"self",
				"self_other",
				"experience",
				"ex_history",
				"ex_period",
				"experience_facility",
				"experience_other",
				"cm",
				"cm_name",
				"oc",
				"oc_name",
				"drug",
				"drug_name",
				// "allergie",
				// "allergie_name",
				"pregnancy",
				"keloid",
				"keloid_check",
				// "keloid_type",
				// "kabure",
				// "cosme_name",
				"alien",
				"alien_palce",
				// "menstruation",
				// "m_period",
				"tattoo",
				"tattoo_place",
				"tattoo_size",
				// "knowledge",
				// "knowledge_magazine",
				// "knowledge_freepaper",
				// "knowledge_event",
				// "knowledge_news",
				// "knowledge_blog",
				// "knowledge_other",
				"seeing",
				// "seeing_intro",
				// "seeing_blog",
				// "seeing_magazine",
				// "seeing_freepaper",
				"seeing_other",
				"beginning",
				// "beginning_place",
				"beginning_other",
				// "anxiety",
				// "anxiety_other",
				"point",
				"point_other",
				// "externalshop",
				// "externalshop_other",
				// "skincare",
				// "skincare_other",
				// "care",
				// "money",
				// "buy",
				// "soapbar",
				// "stress",
				// "s_cause",
				// "sunburn",
				// "s_place",
				// "s_history",
				// "infection",
				// "media",
				// "intro",
				// "blog",
				// "mag",
				// "homepage",
				// "free_paper",
				// "other",
				"memo",
				"edit_date",
			);
			if (isset($_POST["height"]) && $_POST["height"] == "") array_push($sheet_field2,  "height");
			if (isset($_POST["weight"]) && $_POST["weight"] == "") array_push($sheet_field2,  "weight");
			// $data_ID =  Input_Update_Data($table);
			$data_ID = Update_Data($table, $sheet_field2, $_POST['id']);
		}else{
			//顧客新規
			$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
			$sheet_field = array(
				// customerテーブルと共通のカラムを除外(staff_id除く)
				"customer_id",
				"staff_id",
				"input_date",
				"parent_name",
				"parent_tel",
				"parent_address_check",
				"parent_zip",
				"parent_pref",
				"parent_address",
				// "work_tel",
				// "work_zip",
				// "work_pref",
				// "work_address",
				// "work_annual_income",
				// "skin_type",
				"self",
				"self_other",
				"experience",
				"ex_history",
				"ex_period",
				"experience_facility",
				"experience_other",
				"cm",
				"cm_name",
				"oc",
				"oc_name",
				"drug",
				"drug_name",
				// "allergie",
				// "allergie_name",
				"pregnancy",
				"keloid",
				"keloid_check",
				// "keloid_type",
				// "kabure",
				// "cosme_name",
				"alien",
				"alien_palce",
				// "menstruation",
				// "m_period",
				"tattoo",
				"tattoo_place",
				"tattoo_size",
				// "knowledge",
				// "knowledge_magazine",
				// "knowledge_freepaper",
				// "knowledge_event",
				// "knowledge_news",
				// "knowledge_blog",
				// "knowledge_other",
				"seeing",
				// "seeing_intro",
				// "seeing_blog",
				// "seeing_magazine",
				// "seeing_freepaper",
				"seeing_other",
				"beginning",
				// "beginning_place",
				"beginning_other",
				// "anxiety",
				// "anxiety_other",
				"point",
				"point_other",
				// "externalshop",
				// "externalshop_other",
				// "skincare",
				// "skincare_other",
				// "care",
				// "money",
				// "buy",
				// "soapbar",
				// "stress",
				// "s_cause",
				// "sunburn",
				// "s_place",
				// "s_history",
				// "infection",
				// "media",
				// "intro",
				// "blog",
				// "mag",
				// "homepage",
				// "free_paper",
				// "other",
				"memo",
				"reg_date",
				"edit_date",
			);
			// $data_ID =  Input_Data($table);
			$data_ID = Input_New_Data($table, $sheet_field);
		}

		// $customer_field = array("name","name_kana","edit_date");
		// if($_POST['birthday']) array_push($customer_field,  "birthday");
		// if($_POST['birthday'] && $_POST["birthday"]<>"0000-00-00") array_push($customer_field,  "birthday");
		// if($_POST['age']) array_push($customer_field,  "age");
		// if($_POST['tel']) array_push($customer_field,  "tel");
		// if($_POST['mail']) array_push($customer_field,  "mail");
		// if($_POST['pref']) array_push($customer_field,  "pref");
		// if($_POST['address']) array_push($customer_field,  "address");

		// 勤務先用
		// if($_POST['work_tel']) array_push($customer_field,  "work_tel");
		// if($_POST['work_address']) array_push($customer_field,  "work_address");
		// if($_POST['work_pref']) array_push($customer_field,  "work_pref");
		// if($_POST['work_annual_income']) array_push($customer_field,  "work_annual_income");

		// データを更新する(問診票の情報→顧客情報に書き換える)
		$customer_caution = Get_Table_Col("customer","caution"," WHERE del_flg=0 and id = '".addslashes($_POST['customer_id'])."'");
		$customer_field_base = array("name","name_kana","job","job_sub","job_other","height","weight","edit_date");
		if($_POST['birthday']) array_push($customer_field_base,  "birthday");
		// if($_POST['age']) array_push($customer_field_base,  "age");
		if($_POST['tel']) array_push($customer_field_base,  "tel");
		if($_POST['mail']) array_push($customer_field_base,  "mail");
		// if($_POST['job']) array_push($customer_field_base,  "job");
		// if($_POST['job_sub']) array_push($customer_field_base,  "job_sub");
		// if($_POST['job_other']) array_push($customer_field_base,  "job_other");
		if($_POST['zip']) array_push($customer_field_base,  "zip");
		if($_POST['pref']) array_push($customer_field_base,  "pref");
		if($_POST['address']) array_push($customer_field_base,  "address");
		// if($_POST['height']) array_push($customer_field_base,  "height");
		// if($_POST['weight']) array_push($customer_field_base,  "weight");
		if($_POST['id'] == "" || $customer_caution==0){
			if($_POST['tattoo']==1){
				$_POST['caution'] = $_POST['tattoo'] = 0;
				array_push($customer_field_base,  "caution");
			} else {
				$_POST['caution'] = $_POST['tattoo'] = 1;
				array_push($customer_field_base,  "caution");
			}
			if($_POST['tattoo_place'] || $_POST['tattoo_size']) {
				$_POST['caution_place'] = $_POST['tattoo_place'];
				$_POST['caution_size'] = $_POST['tattoo_size'];
				array_push($customer_field_base,  "caution_place",  "caution_size");
			}
		}
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
		if(!$data['input_date']) $data['input_date'] = date("Y-m-d");
		if($customer['birthday'] != '0000-00-00'){
			$data['birthday'] = $customer['birthday'];
		}
		if($customer['job'] != ""){
			$data['job'] = $customer['job'];
			$data['job_sub'] = $customer['job_sub'];
			$data['job_other'] = $customer['job_other'];
		}
		if ($_POST['reservation_id'] != "") {
			$reservation = Get_Table_Row("reservation"," WHERE del_flg=0 and id = '".addslashes($_POST['reservation_id'])."'");
			if ($reservation['type'] == 1 && $reservation['cstaff_id'] != 0) $data['staff_id'] = $reservation['cstaff_id'];
		}
	}
}

// birthdayの項目を作る
list($birthday_y, $birthday_m, $birthday_d) = explode('-', $data['birthday']);
if ($data['birthday'] == "0000-00-00") $birthday_y = "";





// 店舗リスト------------------------------------------------------------------------

$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0  AND status=2 order by id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$shop_list[0] = "-";
while ( $result = $shop_sql->fetch_assoc() ) {
	$shop_list[$result['id']] = $result['name'];
	$shop_code[$result['id']] = $result['code'];
}

//staff list
if($shop) $where_shop = " AND shop_id=".$shop['id'];
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY type,id" ) or die('query error'.$GLOBALS['mysqldb']->error);
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

if($customer["zip"]){
	$zip = explode("-", $customer["zip"]);
	$customer["zip1"] = $zip[0];
	$customer["zip2"] = $zip[1];
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
