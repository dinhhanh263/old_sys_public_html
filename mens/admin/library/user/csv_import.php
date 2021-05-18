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
define_approot($_SERVER['SCRIPT_FILENAME'],2);
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth3.php" );
include_once( "../library/menu.php" );

// CSVインポート-----------------------------------------------------------------------------------
if( $_POST['mode'] == "csv_import" ){
	if ( $_FILES["import_file"]["size"] === 0 ) {
		$gMsg .=  "<font color='red'>※　インポートするファイルを指定してください。</font><br>\n";
	}
	$import_file = $_FILES['import_file']['tmp_name'];

	if(file_exists($import_file)){
		$contents = file_get_contents ($_FILES['import_file']['tmp_name']);
		$contents = str_replace("\r\n", "\n", $contents); 
		$contents = str_replace("\r", "\n", $contents); 
		$lines = preg_split ('/[\n]+/', $contents);
		
		$total = 0;
		$complete = 0;
		
		foreach($lines as $val){
			if($val == "ID,名前,登録状態,会員状態,配信状態,電話,メール,広告コード,端末,応募日") continue;//項目名
			$val = trim($val);
			list($id,$name,$reg_flg,$status,$err_flg,$phone,$mail,$adcode,$mo_agent,$reg_date) = explode(",",$val);
			if(!$id){
				$id = generateID();
				//重複あった場合、再生成
				while(Get_Table_Row("user"," WHERE id = '".$id."'"))$id = generateID();
			}
			if(!$reg_date) $reg_date = date('Y-m-d H:i:s');
			if($reg_flg=='') $reg_flg = 3;//外部csvデータ
			if($mail){
				if (ereg("^[a-zA-Z0-9!$&*.=^`|~#%'+\/?_{}-]+@([a-zA-Z0-9_-]+\.)+[a-zA-Z]{2,4}$", $mail)){
					if (ereg("docomo.ne.jp",$mail )) $mo_agent =1;
					elseif (ereg( "ezweb.ne.jp",$mail )) $mo_agent =2;
					elseif (ereg("softbank.ne.jp",$mail ) || ereg("disney.ne.jp",$mail ) || ereg( "vodafone.ne.jp",$mail )) $mo_agent =3;
					elseif (ereg("willcom.com",$mail ) || ereg("pdx.ne.jp",$mail ) ) $mo_agent =4;
					else $mo_agent =0;
					
					foreach($gReg as $key =>$val){
						if($val==$reg_flg) {
							$reg_flg = $key;break;
						}
					}
					foreach($gStatus as $key =>$val){
						if($val==$status) {
							$status = $key;break;
						}
					}
					foreach($gSendStatus as $key =>$val){
						if($val==$err_flg) {
							$err_flg = $key;break;
						}
					}
					
					$val = "'".$id."','".$name."',".$reg_flg.",".$status.",".$err_flg.",'".$phone."','".$mail."','".$adcode."',".$mo_agent.",'".$reg_date."'";
					$names = "id,name,reg_flg,status,err_flg,phone,mail,adcode,mo_agent,reg_date";
					//$search  = array('空メール', '仮登録', '本登録', '非課金者', 'TEL ○', 'TEL ×', 'NG', '課金者',"'",',');
					//$replace = array('0', '1', '2', '0', '1','2', '3', '4','',"','");
					//$val = str_replace($search, $replace, $val);
					$sql = "INSERT INTO user ( " . $names . "  ) VALUES( " . $val . " );";
					if($rtn = $GLOBALS['mysqldb']->query( $sql ))$complete +=1;
				}
				else $incorrect_mail +=1;
			}

			$total +=1;
		}
		if($total) $gMsg .= '総件数：　'.$total."<br />";
		if($complete) $gMsg .= '処理件数：　'.$complete."<br />";
		if($incorrect_mail) $gMsg .= '不正件数：　'.$incorrect_mail."<br />";
		$duplicate = $total-$complete-$incorrect_mail;
		if($duplicate) $gMsg .= '重複及び空メール件数：　'.$duplicate."<br />";
	}
}

?>