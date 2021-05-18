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

// CSVインポート
if( $_POST['mode'] == "csv_import" ){

	$option_date = trim($_POST['option_date']) ;
	$pay_date = date("Y-m-d") ;
	if ( !$_POST['title'] ) $gMsg .=  "<span style='color:red;'>※　タイトルを入力してください。</span><br>\n";
	if ( !$option_date ) $gMsg .=  "<span style='color:red;'>※　日付を指定してください。</span><br>\n";
	if ( !$_POST['bank_flg'] ) $gMsg .=  "<span style='color:red;'>※　代行会社を指定してください。</span><br>\n";
	
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
			
			// 過去未来日チェック（3ヶ月前以上、3ヶ月後以上はエラー）
			$date_flg = checkTerm($_POST['option_year'], $_POST['option_month'],'',3,3,'m');
			
			// 月の形式チェック
			if($date_flg ==='m'){
				$gMsg .=  "<span style='color:red;'>※　何月分支払代金は1～12の形式で入力してください。</span><br>\n";
			} elseif($date_flg ==='p'){
				$gMsg .= "<span style='color:red;'>※　3ヶ月以上過去に入力できません。</span><br>\n";
			} elseif($date_flg ==='f'){
				$gMsg .= "<span style='color:red;'>※　3ヶ月以上未来に入力できません。</span><br>\n";
			}
		}
	}
	
	if ( $_FILES["import_file"]["size"] === 0 )	$gMsg .=  "<span style='color:red;'>※　インポートするファイルを指定してください。</span><br>\n";

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
		*/
		$total = 0 ;

		// 月額コースID取得
		$month_id = implodeArray("course","id"," WHERE del_flg=0 AND type=1");
		$month_id2 = implodeArray("course","id"," WHERE del_flg=0 AND type=1 AND new_flg=0");

		foreach($lines as $val){
			$contract = array();

			$val = trim($val);
			list($no,$name,$payment,$result) = explode(",",$val);
			if(mb_detect_encoding($name, "auto")=='SJIS')	$name = mb_convert_encoding($name, 'UTF-8','shift-jis'); // if import file code is shift-jis
			
			// 全角に変換後、スペースを削除
			$name1=str_replace("　","",mb_convert_kana($name,"SKV", "UTF-8"));

			if(!$no) continue;
			
			// 条件：振込済
			if( $result==0){
				$result_sql = "INSERT pay_monthly SET title='".$_POST['title']."',option_date='".$option_date."',pay_type=".$_POST['pay_type'].",pay_date='".$pay_date."',reg_date='".$import_date."',no='".$no."', name='".$name."', payment='".$payment."', result='".$result."'";
				// 条件：会員番号が部分一致、かつ、名前またカナが部分一致
				// if( $customer = Get_Table_Row("customer"," WHERE del_flg=0 AND no LIKE '%".$no."%' AND ( ( bank_account_name<>'' AND REPLACE(bank_account_name, '　', '') LIKE '%".$name1."%' ) OR REPLACE(name, '　', '') LIKE '%".$name1."%' OR REPLACE(name_kana, '　', '') LIKE '%".$name1."%' )" ) ){
				// if( $customer = Get_Table_Row("customer"," WHERE del_flg=0 AND no = '".$no."'")  ){
				if( $customer = Get_Table_Row("customer"," WHERE del_flg=0 AND no = '".$no."' AND ( ( bank_account_name<>'' AND REPLACE(bank_account_name, '　', '') LIKE '%".$name1."%' ) OR REPLACE(name, '　', '') LIKE '%".$name1."%' OR REPLACE(name_kana, '　', '') LIKE '%".$name1."%' )" ) ){

					$result_sql .= ",customer_id='".$customer['id']."'";

					// 条件：月額契約中である
					$contract = Get_Table_Row("contract"," WHERE del_flg=0 AND customer_id = '".$customer['id']."' AND course_id in(".$month_id." ) order by id desc limit 1");
					if( $contract['contract_date'] > $option_date && substr($contract['contract_date'], 0,7) <> substr($option_date, 0,7) ){
						if(!is_array($contract = Get_Table_Row("contract"," WHERE del_flg=0 AND customer_id = '".$customer['id']."' AND course_id in(".$month_id2." ) order by id desc limit 1"))){
							$contract = array();
						}
					}

					if( $contract['id'] ){
						$result_sql .= ",contract_id='".$contract['id']."'";

						// 返金の場合、同日重複チェック
						if($payment<0) $where_date = " AND pay_date='".$pay_date."'";
						
						// 入金の場合、同月重複チェック
						else $where_date = " AND substr(pay_date, 1, 7)='".substr($pay_date, 0,7)."'";
						
						// 条件：指定振替日にオプション名（=月額支払）のデータがまだない
						if( !$sales = Get_Table_Row("sales"," WHERE del_flg=0 AND customer_id =  '".$customer['id']."' AND contract_id =  '".$contract['id']."' ".$where_date." AND option_name=4 " ) ){
							$result_sql .= ",existed_flg=2";
							$sql = "INSERT sales SET type=8, option_name=4,
												 contract_id 	= ".$contract['id']." ,
												 customer_id 	= ".$customer['id']." ,
												 shop_id 		= ".$contract['shop_id']." , 
												 course_id 		= ".$contract['course_id']." , 
												 times 			= ".$contract['times']." , 
												 fixed_price 	= ".$contract['fixed_price']." , 
												 discount 		= ".$contract['discount']." , 
												 pay_type 		= ".$_POST['pay_type']." , 
												 pay_date 		= '".$pay_date."' , 
												 option_date 	= '".$option_date."' ,
												 option_year 	= '".$_POST['option_year']."' ,
												 option_month 	= '".$_POST['option_month']."' ,
												 reg_date 		= '".$import_date."' , 
												 edit_date 		= '".$import_date."' ,  ";
							$sql .= $_POST['pay_type']==3 ? "option_transfer=".$payment : "option_card=".$payment ;

							if($GLOBALS['mysqldb']->query($sql)) {
								$result_sql .= ",success_flg=1";
								$total++;
							}
						}else $result_sql .= ",existed_flg=1";

					}
				}
				// 処理結果をテーブルに格納
				$GLOBALS['mysqldb']->query($result_sql) ;

			}else{
				$result_sql = "INSERT pay_monthly SET title='".$_POST['title']."',option_date='".$option_date."',pay_type=".$_POST['pay_type'].",pay_date='".$pay_date."',reg_date='".$import_date."',no='".$no."', name='".$name."', payment='".$payment."', result='".$result."'";
				
				// 条件：会員番号が部分一致、かつ、名前またカナが部分一致
				// if( $customer = Get_Table_Row("customer"," WHERE del_flg=0 AND no LIKE '%".$no."%' AND ( ( bank_account_name<>'' AND REPLACE(bank_account_name, '　', '') LIKE '%".$name1."%' ) OR replace(name, '　', '') LIKE '%".$name1."%' or replace(name_kana, '　', '') LIKE '%".$name1."%' )" ) ){
				// if( $customer = Get_Table_Row("customer"," WHERE del_flg=0 AND no = '".$no."'" ) ){
				if( $customer = Get_Table_Row("customer"," WHERE del_flg=0 AND no = '".$no."' AND ( ( bank_account_name<>'' AND REPLACE(bank_account_name, '　', '') LIKE '%".$name1."%' ) OR replace(name, '　', '') LIKE '%".$name1."%' or replace(name_kana, '　', '') LIKE '%".$name1."%' )" ) ){


					$result_sql .= ",customer_id='".$customer['id']."'";

					// 条件：月額契約中である
					$contract = Get_Table_Row("contract"," WHERE del_flg=0 AND customer_id = '".$customer['id']."' AND course_id in(".$month_id." ) order by id desc limit 1");
					if( $contract['contract_date'] > $option_date && substr($contract['contract_date'], 0,7) <> substr($option_date, 0,7) ){
						if(!($contract = Get_Table_Row("contract"," WHERE del_flg=0 AND customer_id = '".$customer['id']."' AND course_id in(".$month_id2." ) order by id desc limit 1"))){
							$contract = array();
						}
					}

					if( $contract['id'] ){
						$result_sql .= ",contract_id='".$contract['id']."'";
						
						$sql = "UPDATE customer SET bank_ng_flg = ".$_POST['bank_flg'].",edit_date='".date('Y-m-d H:i:s')."'";
						$sql .= " WHERE id = '".addslashes($customer['id'])."'";
						
						if($GLOBALS['mysqldb']->query($sql)) {
							$result_sql .= ",success_flg=1";
							$total++;
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
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) ;
?>