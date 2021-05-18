<?php

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'KagoyaSendMail.php';


//include重複回避
if(!constant('CONFIG_DIR')){
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
	include_once( LIB_DIR."db.php" );
	include_once( LIB_DIR."function.php" );
}
@session_start();
$_GET['adcode'] = $_SESSION["MENS_AD_CODE"];
$subject = "【問合せ】";
$subject = "";
if($_GET['adcode']){
	if($adcode = Get_Table_Row("adcode"," WHERE adcode = '".addslashes($_GET['adcode'])."'")){
		$subject = $adcode['name']."から";
	}else{
		$subject = "その他から" ;
	}
}

$error_str = array();
if($_POST){
	if ($_POST['お名前']=="" || preg_match("/^( |　)+$/", $_POST['お名前']))$error_str['お名前'] = ERROR_IN;
	if ( !ereg("^[a-zA-Z0-9!$&*.=^`|~#%'+\/?_{}-]+@([a-zA-Z0-9_-]+\.)+[a-zA-Z]{2,4}$", $_POST['E-Mail']) )$error_str['E-Mail'] = ERROR_IN_M;
	if(!is_telnum($_POST['電話番号']))$error_str['電話番号'] = ERROR_IN_M;

	if( empty($error_str) ){
		mb_language('ja');
		mb_http_input('auto');
		mb_http_output('SJIS');
		mb_internal_encoding('UTF-8');
		
		$from = "From: ".mb_encode_mimeheader(MAIL_SUPPORT)."<".MAIL_SUPPORT.">";
		$subject .= "お問い合わせがありました。";
		foreach($_POST as $key => $val){
			if($key=="x" || $key=="y") continue;
			$body .= "\n■ ".$key." : ".$val;
		}
		$body .="\n■ 端末情報: ".$ua."\n";
		$body.="■ 日時: ".date("Y/m/d H:i:s")."\n";
		// if (mb_send_mail(MAIL_SUPPORT, $subject, $body, $from)) {
		$kagoya = new KagoyaSendMail();
		if ($kagoya->send(MAIL_SUPPORT, $subject, $body, $from)) {
			$msg = '
<p><font color="red"><b>送信が完了しました。</b></font></p>
<p>送信いただきました個人情報は弊社が大切にお預かり致します。<br />
後ほど、担当からご連絡致します。</p>
<p>携帯電話の場合、何らかの拒否設定を行っている方は<br />
下記番号、及びメールアドレスの解除設定をお願いいたします。</p>
<p>'.TEL.'<br />
<font color="red">'.MAIL_ADMIN.'</font></p>



';
		}
	}
}
?>