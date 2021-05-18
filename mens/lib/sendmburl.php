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


if($_POST['mode']=="sendurl"){
	$mail = $_POST['address']."@".$_POST['domain'];
	if ( !ereg("^[a-zA-Z0-9!$&*.=^`|~#%'+\/?_{}-]+@([a-zA-Z0-9_-]+\.)+[a-zA-Z]{2,4}$", $mail) )$msg_sendurl_err = "メールアドレスが正しく入力ください。";

	if( !$msg_sendurl_err ){
		mb_language('ja');
		mb_http_input('auto');
		mb_http_output('SJIS');
		mb_internal_encoding('Shift_JIS');
		
		$from = "From: ".mb_encode_mimeheader(MAIL_SENDER_EMAIL)."<".MAIL_SENDER_EMAIL.">";
		$subject = "";
		$body = HOME_URL_M;
		// mb_send_mail($mail, $subject, $body, $from);
        $kagoya = new KagoyaSendMail();
		$kagoya->send($mail, $subject, $body, $from);
	}
}
?>