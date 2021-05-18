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

// CSVインポート
if( $_POST['mode'] == "csv_import" ){

	// $pay_date = trim($_POST['pay_date']) ;
	// if ( !$_POST['shop_id'] ) $gMsg .=  "<font color='red'>※　店舗を指定してください。</font><br>\n";
	if ( !$_POST['title'] ) $gMsg .=  "<span style='color:red;'>※　タイトルを入力してください。</span><br>\n";
	// if ( !$_POST['shop_id'] ) $gMsg .=  "<font color='red'>※　店舗を指定してください。</font><br>\n";

	// 年のエラーチェック 20151008 shimada
	if($_POST['option_year'] ==='-'){
		$gMsg .=  "<span id='g_error' style='color:red;'>※　何年分支払代金を選択してください。</span><br>\n";
	} 

	// 月のエラーチェック,月のnull判定（0で未入力判定されてしまうため）
	$month_null_flg = ($_POST['option_month'] ===null || $_POST['option_month'] =='') ? true:false; 
	if($month_null_flg===true){
		$gMsg .=  "<span style='color:red;'>※　何月分支払代金を入力してください。</span><br>\n";
	} else {
		
		// 入力データのチェック,数字を整形する
		$_POST['option_month'] = Che_Num3($_POST['option_month']); 
		if(!is_numeric($_POST['option_month'])){
		$gMsg .=  "<span style='color:red;'>※　何月分支払代金は数値のみで入力してください。</span><br>\n";
		} else {
			
			// 過去未来日チェック（3ヶ月前以上、1ヶ月後以上はエラー）
			$date_flg = checkTerm($_POST['option_year'], $_POST['option_month'],'',3,1,'m');
			
			// 月の形式チェック
			if($date_flg ==='m'){
				$gMsg .=  "<span style='color:red;'>※　何月分支払代金は1～12の形式で入力してください。</span><br>\n";
			} elseif($date_flg ==='p'){
				$gMsg .= "<span style='color:red;'>※　3ヶ月以上過去に入力できません。</span><br>\n";
			} elseif($date_flg ==='f'){
				$gMsg .= "<span style='color:red;'>※　1ヶ月以上未来に入力できません。</span><br>\n";
			}
		}
	}

	if ( $_FILES["import_file"]["size"] === 0 )	$gMsg .=  "<span style='color:red;'>※　インポートするファイルを指定してください。</span><br>\n";
	// if ( !$_POST['option_month'] ) $gMsg .=  "<font color='red'>※　何月分支払代金を入力してください。</font><br>\n";
	$import_file = $_FILES['import_file']['tmp_name'];
	$import_date = date("Y-m-d H:i:s");

	if(file_exists($import_file) && !$gMsg){
		$contents = file_get_contents ($_FILES['import_file']['tmp_name']);
		$contents = str_replace("\r\n", "\n", $contents); 
		$contents = str_replace("\r", "\n", $contents); 
		$lines = preg_split ('/[\n]+/', $contents);

		/*	※result: 振替結果コードについて 
			0-振替済 , 				A-振替結果未判明 , 	1-振替不能(資金不足) ,	  2-振替不能(取引なし) 
			3-振替不能(預金者都合) ,	4-振替不能(口座なし) , 8-振替不能(委託者都合) , 9-振替不能(その他)
			ユカとユウカ、イュ：ユウ、ユ、ヨコとヨウコが両方あり
		*/
		$total = 0 ;

		// 月額コースID取得
		$month_id = implodeArray("course","id"," WHERE del_flg=0 AND type=1");

		$search =  array("イト",	"エンド",	"カト",	"クド",	"コノ",	"クノ",	"ゴト",	"コンド",	"サト",	"サイト",	"オッ",	"オフ",	"ソマ",	"トヤマ",		"ズカ",	"m",	"t",	"ショ",	"イョ",	"イャ",		"イュ",	"ネッ"); 
		$replace = array("イトウ",	"エンドウ",	"カトウ",	"クドウ",	"コウノ",	"クノウ",	"ゴトウ",	"コンドウ",	"サトウ",	"サイトウ",	"オオ",	"オオウ",	"ソウマ",	"トウヤマ",	"ヅカ",	"ッ",	"ヨ",	"ショウ",	"ヨ",	"ヤ",		"ユ",	"ネス");

		foreach($lines as $val){
			$val = trim($val);
			list($option_date,$trade,$name,$payment,$card_name,$card_no) = explode(",",$val);
			$pay_date = date("Y-m-d"); // 処理日基準
			$pay_date = str_replace("/", "-", $pay_date);
			$option_date = str_replace("/", "-", $option_date);

			if(mb_detect_encoding($trade, "auto")=='SJIS')	$trade = mb_convert_encoding($trade, 'UTF-8','shift-jis'); // if import file code is shift-jis
			if(mb_detect_encoding($name, "auto")=='SJIS')	$name = mb_convert_encoding($name, 'UTF-8','shift-jis'); // if import file code is shift-jis

			$name = mb_convert_kana($name,"SKV", "UTF-8");
			$name = str_replace($search,$replace,$name);

			$search2  = array("ジュウン",	"ウウ",	"サトウミ",	"ヨウンッ",	"Ｌイッ",	"オヒャマ",	"ミズホ　ハタ",	"ユコ",	"サトウコ",		"ヨコ",	"エＬＬイ",	"アズサ");
			$replace2 = array("ジュン",	"ウ",	"サトミ",	"ヨン",	"イム",	"オオヤマ",	"ミヅホ",		"ユウコ",	"サトコ",		"ヨウコ",	"エリ",	"アヅサ");
			$name = str_replace($search2,$replace2,$name);

			// 全角に変換後、スペースを削除
			$name1 = str_replace("　","",$name); 
			list($name11,$name22)  = explode("　",$name);
			$name2 = $name22.$name11; // 姓名を逆に

			// 条件：振込済
			if( $trade=="カード毎月自動課金売上" && $pay_date<>"" && strstr($pay_date, "-") && $payment){
				$result_sql = "insert pay_monthly set title='".$_POST['title']."',shop_id='".$_POST['shop_id']."',pay_type=2,pay_date='".$pay_date."',reg_date='".$import_date."', name='".$name."', card_name='".$card_name."', card_no='".$card_no."', payment='".$payment."', result=0";
				
				// 条件：会員番号が部分一致、かつ、名前またカナが部分一致,カード名義があればカード名義から検索
				if( $customer_array = Get_Table_Array("customer","id"," WHERE del_flg=0 and  card_name='".$card_name."' and card_name<>'' and card_no='".$card_no."' and card_no<>'' " ) ){

				  //$customer_exists = fales;
				  foreach ($customer_array as $key => $customer_id) {

					// 条件：月額契約で、契約日が取引日より古い
					if( $contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".$customer_id."' and contract_date<='".$pay_date."' and course_id in(1,5,15,16,18,19,44,45,46,47,48,49,50,51,55,56,63,64,70 ) order by id desc limit 1") ){
						$result_sql .= ",customer_id='".$customer_id."'";
						$result_sql .= ",contract_id='".$contract['id']."'";
						
						list($y,$m,$d) = explode("-",$pay_date);
						// if($m<10) $m = "0".$m ;
						$ym = $y."-".$m;
						
						// 返金の場合、同日重複チェック
						if($payment<0) $where_date = " and pay_date='".$pay_date."'";
						
						// 入金の場合、同月重複チェック
						else $where_date = " and substr(pay_date, 1, 7)='".$ym."'";
						
						// 条件：指定振替日にオプション名（=月額支払）のデータがまだない
						if( !$sales = Get_Table_Row("sales"," WHERE del_flg=0 and customer_id =  '".$customer_id."' and contract_id =  '".$contract['id']."' ".$where_date." and option_name=4 " ) ){
							$result_sql .= ",existed_flg=2";
							$sql = "insert sales set type=8, option_name=4,
												 contract_id 	= ".$contract['id']." ,
												 customer_id 	= ".$customer_id." ,
												 shop_id 		= ".$contract['shop_id']." , 
												 course_id 		= ".$contract['course_id']." , 
												 times 			= ".$contract['times']." , 
												 fixed_price 	= ".$contract['fixed_price']." , 
												 discount 		= ".$contract['discount']." , 
												 pay_type 		= 2 , 
												 pay_date 		= '".$pay_date."' , 
												 option_date 	= '".$option_date."' ,
												 option_year 	= '".$_POST['option_year']."' ,
												 option_month 	= '".$_POST['option_month']."' ,
												 reg_date 		= '".$import_date."' , 
												 edit_date 		= '".$import_date."' ,  ";
							$sql .= "option_card=".$payment ;

							if($GLOBALS['mysqldb']->query($sql)) {
								$result_sql .= ",success_flg=1";
								$total++;
							}
						}else $result_sql .= ",existed_flg=1";
						// $customer_exists = true;
					}
					// if($customer_exists )break;//同じデータ一回しかインポートしない
				  }
				}
				// 処理結果をテーブルに格納
				$GLOBALS['mysqldb']->query($result_sql);

			}elseif( $trade=="カード毎月自動課金エラー" && $payment){
				$result_sql = "insert pay_monthly set title='".$_POST['title']."',shop_id='".$_POST['shop_id']."',pay_type=2,pay_date='".$pay_date."',reg_date='".$import_date."', name='".$name."', card_name='".$card_name."', card_no='".$card_no."', payment='".$payment."', result=0";
				if( $customer_array = Get_Table_Array("customer","id"," WHERE del_flg=0 and  card_name='".$card_name."' and card_name<>'' and card_no='".$card_no."' and card_no<>'' " ) ){

				  foreach ($customer_array as $key => $customer_id) {

					// 条件：月額契約で、契約日が取引日より古い
					if( $contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".$customer_id."' and contract_date<='".$pay_date."' and course_id in(".$month_id." ) order by id desc limit 1") ){
						$result_sql .= ",customer_id='".$customer_id."'";
						$result_sql .= ",contract_id='".$contract['id']."'";
						
						$sql = "UPDATE customer SET digicat_ng_flg = 1,edit_date='".date('Y-m-d H:i:s')."'";
						$sql .= " WHERE id = '".addslashes($customer_id)."'";
						
						if($GLOBALS['mysqldb']->query($sql)) {
							$result_sql .= ",success_flg=1";
							$total++;
						}
						
					}

				  }
				}
				// 処理結果をテーブルに格納
				$GLOBALS['mysqldb']->query($result_sql);
			}
		}
		$gMsg = '<font color="red">※ '.$total.'件が処理しました。</font>';
	}
}

$dSql = "SELECT * FROM pay_monthly WHERE reg_date='".$import_date."'";
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist_shop();
// $mensdb = changedb();

?>