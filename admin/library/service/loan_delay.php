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

	if ( !$_POST['title'] ) $gMsg .=  "<font color='red'>※　タイトルを入力してください。</font><br>\n";
	if ( !$_POST['loan_delay_flg'] ) $gMsg .=  "<font color='red'>※　ローン会社を選択してください。</font><br>\n";
	if ( $_FILES["import_file"]["size"] === 0 )	$gMsg .=  "<font color='red'>※　インポートするファイルを指定してください。</font><br>\n";

	$import_file = $_FILES['import_file']['tmp_name'];
	$import_date = date("Y-m-d H:i:s");

	if(file_exists($import_file) && !$gMsg){
		$contents = file_get_contents ($_FILES['import_file']['tmp_name']);
		$contents = str_replace("\r\n", "\n", $contents); 
		$contents = str_replace("\r", "\n", $contents); 
		$lines = preg_split ('/[\n]+/', $contents);

		//一旦リセット
		$reset_sql = "update customer set loan_delay_flg=0 where loan_delay_flg=".$_POST['loan_delay_flg'];
		$GLOBALS['mysqldb']->query($reset_sql) or die('query error'.$GLOBALS['mysqldb']->error);

		$total = 0 ;
		foreach($lines as $val){
			$val = trim($val);
			list($no,$name) = explode(",",$val);
			if(mb_detect_encoding($name, "auto")=='SJIS')	$name = mb_convert_encoding($name, 'UTF-8','shift-jis'); //if import file code is shift-jis
			$name = str_replace("　","",mb_convert_kana($name,"SKV", "UTF-8") );//S:「半角」スペースを「全角」に,K:「半角カタカナ」を「全角カタカナ」に,V:濁点付きの文字を一文字に

			//条件：
			if( $no){
				$result_sql = "insert pay_monthly set title='".$_POST['title']."',no='".$no."', name='".$name."',reg_date='".$import_date."'";

				//条件：会員番号が一致、かつ、名前が一致
				if( $customer = Get_Table_Row("customer"," WHERE del_flg=0 and no='".$no."' and replace(name, '　', '')='".$name."'") ){

					$result_sql .= ",customer_id='".$customer['id']."'";

					if( $contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".$customer['id']."' and payment_loan>0 order by id desc limit 1") ){
						$result_sql .= ",pay_date='".$contract['contract_date']."',payment='".$contract['payment_loan']."',contract_id='".$contract['id']."',existed_flg=2";
						$sql = "update customer set loan_delay_flg=".$_POST['loan_delay_flg']." where id=".$customer['id'];

						if($GLOBALS['mysqldb']->query($sql)) {
							$result_sql .= ",success_flg=1";
							$total++;
						}
					}else $result_sql .= ",existed_flg=1";
				}
				$GLOBALS['mysqldb']->query($result_sql) or die('query error'.$GLOBALS['mysqldb']->error);//処理結果をテーブルに格納
			}
		}
		$gMsg = '<font color="red">※ '.$total.'件が処理しました。</font>';
	}
}

$dSql = "SELECT * FROM pay_monthly WHERE reg_date='".$import_date."'";
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
?>