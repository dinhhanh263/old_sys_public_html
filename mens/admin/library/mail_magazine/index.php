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

// テーブル設定
//$table = $_REQUEST['scenario_id'] ? "mail_scenario_data" : "user" ;
$table = "mail_scenario_data" ;

// Template
if($_REQUEST['tid']) $template = Get_Table_Row("mail_template"," WHERE id = '".addslashes($_REQUEST['tid'])."'");
$tid = str_replace("./?tid=","",$_REQUEST['tid']);

// 条件の設定
if( $_POST['status']){
	$dWhere .= " and ( ";
	foreach($_POST['status'] as $key=>$val) {
		$dWhere .= $or1."status =".$val;	$or1 = " or "; 
	}
	$dWhere .= " ) ";
	$status = implode(",",$_POST['status']);
}
if( $_POST['mo_agent']){
	$dWhere .= " and ( ";
	foreach($_POST['mo_agent'] as $key=>$val) {
		$dWhere .= $or2."mo_agent =".$val;$or2 = " or "; 
	}
	$dWhere .= " ) ";
	$mo_agent = implode(",",$_POST['mo_agent']);
}

// 本サイトだけ
if( !$_POST['scenario_id']) $dWhere .= "and reg_flg!=0 ";
else  $dWhere .= "and scenario_id=".$_POST['scenario_id'];

// 受信者数取得
$dSql = "SELECT count(id) FROM " . $table . " WHERE err_flg=0 " . $dateLimit .$dWhere;
$dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
$dGet_Cnt = $dRtn2->fetch_row()[0];

// 受信者メール取得
$dSql = "SELECT mail,name FROM " . $table . " WHERE err_flg=0 " . $dateLimit . $dWhere  ;
$dRtn = $GLOBALS['mysqldb']->query( $dSql );

// メール配信
$body = $_POST['header'].$_POST['body'].$_POST['footer'];

if($_POST['mode']=="test" && $_POST['test_mail']){
	if( $_POST['format'] && sendMailHtml( $_POST['test_mail'], $_POST['subject'], $body, $_POST['sender'] ) )$msg = "テスト送信が完了しました";
	elseif( sendMail( $_POST['test_mail'], $_POST['subject'], $body, $_POST['sender'] ) )$msg = "テスト送信が完了しました";
}elseif( $_POST['mode'] == "send" ) {
	$sql = "insert mail_history set template_id='{$tid}',scenario_id='{$_POST['scenario_id']}',status = '{$status}' ,mo_agent = '{$mo_agent}' ,format = '{$_POST['format']}' ,total={$dGet_Cnt}, ";
	$sql.= "sender = '{$_POST['sender']}' ,title = '{$_POST['subject']}' , header='{$_POST['header']}', body='{$_POST['body']}' , footer='{$_POST['footer']}'";
	// 即時送信
	if($_POST['send_now']){
		// 新規登録
		$GLOBALS['mysqldb']->query($sql);
		
		// メールマガid取得
		$mailmg = Get_Table_Row("mail_history"," WHERE id!='' order by id DESC limit 1");
		$id = $mailmg['id'];
		
		// tabel_mail_history:id標識
		// $body .= "___".$id."___";
		
		$cnt = 0;
		$start_date = date('Y-m-d H:i:s');
		while ( $data = $dRtn->fetch_assoc() ) {
			$body = str_replace("%%name%%",$data['name'],$body);
			if( $_POST['format'] && sendMailHtml( $data['mail'], $_POST['subject'], $body, $_POST['sender'], $cc="", $bcc=$_POST['sender'] , MAIL_RETURN ) ) {
				$send_flg = true;$cnt +=1;
			}
			
			elseif( sendMail( $data['mail'], $_POST['subject'], $body, $_POST['sender'], $cc="", $bcc=$_POST['sender'] , MAIL_RETURN ) ) {
				$send_flg = true;$cnt +=1;
			}
			sleep(10); // 10秒まち
		}
		$end_date = date('Y-m-d H:i:s');
		if($send_flg){
			$err_cnt = $dGet_Cnt-$cnt;
			$sql = "update mail_history set total={$dGet_Cnt}, ";
			$sql.= "plan_date='{$start_date}',start_date='{$start_date}',end_date='{$end_date}',success_cnt={$cnt},err_cnt={$err_cnt},sent_flg=1";
			$sql.= " where id='{$id}'";
			$GLOBALS['mysqldb']->query($sql);
			$msg = "送信が完了しました";
		}
	
	// 予約送信、lib/sendmail.phpより
	}elseif($_POST['plan_date']){
		$plan_date = $_POST['plan_date']." ".$_POST['plan_time'];
		$sql.= ",plan_date='{$plan_date}'";
		$GLOBALS['mysqldb']->query($sql);
		$msg = "予約送信が完了しました";
	}

}

?>