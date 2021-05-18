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
	
	$data_name = trim($_POST['data_name']) ;
	if ( !$data_name ) $gMsg .=  "<font color='red'>※　データ名を指定してください。</font><br>\n";
	if ( Get_Table_Row("mail_scenario_info"," WHERE name = '".addslashes($data_name)."'") ) $gMsg .=  "<font color='red'>※　データ名が既に存在しています。</font><br>\n";
	if ( $_FILES["import_file"]["size"] === 0 )	$gMsg .=  "<font color='red'>※　インポートするファイルを指定してください。</font><br>\n";

	$import_file = $_FILES['import_file']['tmp_name'];
	$import_date = date("Y-m-d H:i:s");
	
	if(file_exists($import_file)&& !$gMsg){
		$contents = file_get_contents ($_FILES['import_file']['tmp_name']);
		$contents = str_replace("\r\n", "\n", $contents); 
		$contents = str_replace("\r", "\n", $contents); 
		$lines = preg_split ('/[\n]+/', $contents);
		
	
		// データ名新規
		$GLOBALS['mysqldb']->query("insert mail_scenario_info set name = '$data_name', date='$import_date', type='{$_POST['type']}', genre='{$_POST['genre']}'");
		$data_name_detail = Get_Table_Row("mail_scenario_info"," WHERE name = '$data_name'");
		$data_name_id = $data_name_detail['id'];
		
		// CSVファイル処理
		$total = 0 ;
		foreach($lines as $val){
			$val = trim($val);
			
			list($mail,$name,$status,$mo_agent) = explode(",",$val);
			// if import file code is shift-jis
			if(mb_detect_encoding($name, "auto")=='SJIS')	$name = mb_convert_encoding($name, 'UTF-8','shift-jis');
			
			// $status,$mo_agentが文字から数字変換
			$search  = array('空メール', '仮登録', '本登録', '非課金者', 'TEL ○', 'TEL ×', 'NG', '課金者',"'",',');
			$replace = array('0', '1', '2', '0', '1','2', '3', '4','',"','");
			$status = str_replace($search, $replace, $status);
			
			if (ereg("^[a-zA-Z0-9!$&*.=^`|~#%'+\/?_{}-]+@([a-zA-Z0-9_-]+\.)+[a-zA-Z]{2,4}$", $mail))	{
				if (ereg("docomo.ne.jp",$mail )) $mo_agent =1;
				elseif (ereg( "ezweb.ne.jp",$mail )) $mo_agent =2;
				elseif (ereg("softbank.ne.jp",$mail ) || ereg("disney.ne.jp",$mail ) || ereg( "vodafone.ne.jp",$mail )) $mo_agent =3;
				elseif (ereg("willcom.com",$mail ) || ereg("pdx.ne.jp",$mail ) ) $mo_agent =4;
				else $mo_agent =0;
					
				if($GLOBALS['mysqldb']->query("insert mail_scenario_data set mail = '$mail' , name = '$name' , status = '$status' , mo_agent = '$mo_agent' , scenario_id='$data_name_id',  date='$import_date'")){
					$total++;
				}
			}
		}
		if($total){
			$GLOBALS['mysqldb']->query("update mail_scenario_info set total='$total' where id='$data_name_id'");
		}else{
			// 処理件数がなければ、データ名を削除
			Delete_Table_Row("mail_scenario_info","id",$data_name_id) ;
		}
		$gMsg = '<font color="red">※ '.$total.'件がインポートしました。</font>';
	}
}
?>