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
require_once LIB_DIR . 'KagoyaSendMail.php';


$sql = "SELECT * FROM mail_history ";
$sql.= "WHERE plan_date BETWEEN date_add(now(),INTERVAL -15 minute) AND date_add(now(),INTERVAL 15 minute) AND sent_flg = 0  AND end_date IS NULL ORDER BY id ASC, start_date ASC";

//----　30分毎にCronが送信時間データ確認
$mailmg_data = Get_Result_Sql_Array($sql);

//未送信メルマガの数
$dRtn = $GLOBALS['mysqldb']->query($sql);
$count = $dRtn->fetch_row();

//未送信メルマガがあれば送信処理
if( $count > 0 ){
	foreach ( $mailmg_data as $key=>$val ) {

		//テーブルの確定
		$table = $val['scenario_id'] ? "mail_scenario_data" : "user" ;
	
		// 条件の抽出
		if( $val['status']){
			$status = explode(",",$val['status']);
			$dWhere .= " and ( ";
			foreach($status as $key=>$vals) {
				$dWhere .= $or1."status =".$vals; $or1 = " or "; 
			}
			$dWhere .= " ) ";
		}
		if( $val['mo_agent']){
			$mo_agent = explode(",",$val['mo_agent']);
			$dWhere .= " and ( ";
			foreach($mo_agent as $key=>$vals) {
				$dWhere .= $or2."mo_agent =".$vals;$or2 = " or "; 
			}
			$dWhere .= " ) ";
		}
		//本サイトなら
		if( !$val['scenario_id']) $dWhere .= "and reg_flg!=0 ";
		else  $dWhere .= "and scenario_id=".$val['scenario_id'];
		
		//受信者数取得
		$dSql = "SELECT count(id) FROM " . $table . " WHERE err_flg=0 " . $dWhere;
		$dRtn2 = $GLOBALS['mysqldb']->query( $dSql );
		$dGet_Cnt = $dRtn2->fetch_row()[0];

		//受信者メール取得
		$dSql = "SELECT mail FROM " . $table . " WHERE id!='' " . $dWhere  ;
		$dRtn = $GLOBALS['mysqldb']->query( $dSql );

		//メール配信
		$body = $val['header'].$val['body'].$val['footer'];
		
		//tabel_mail_history:id標識
		$body .= "___".$val['id']."___";

		$cnt = 0;
		$err_cnt = 0;
		
		$start_date = date('Y-m-d H:i:s');
		while ( $data = $dRtn->fetch_assoc() ) {
			$body2 = str_replace("%%name%%",$data['name'],$body);
			if( $data['format'] && sendMailHtml( $data['mail'], $val['subject'], $body2, $val['sender'] , $cc="", $bcc=$_POST['sender'] , MAIL_RETURN ) ) {
				$send_flg = true; $cnt +=1;
			}
			elseif( sendMail( $data['mail'], $val['subject'], $body2, $val['sender'] , $cc="", $bcc=$_POST['sender'] , MAIL_RETURN ) ) {
				$send_flg = true; $cnt +=1;
			}
			sleep(10);//10秒待ち
		}
		$end_date = date('Y-m-d H:i:s');
		
		if($send_flg){
			$err_cnt = $dGet_Cnt-$cnt;
			$sql = "update mail_history set total={$dGet_Cnt}, ";
			$sql.= "start_date='{$start_date}',end_date='{$end_date}',success_cnt={$cnt},err_cnt={$err_cnt},sent_flg=1";
			$sql.= " where id='{$val['id']}'";
			$GLOBALS['mysqldb']->query($sql);
		}
	}
}
?>