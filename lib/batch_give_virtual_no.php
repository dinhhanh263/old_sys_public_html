<?php
/*前日契約者にバーチャル番号付与*/
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once $DOC_ROOT . '/lib/function.php';

//DBに接続してデータ登録
$conn = mysql_connect(HOST_NAME, DB_UESR, DB_PW);
mysql_select_db(DB_NAME,$conn);
mysql_query('SET NAMES utf8');

$dSql = 'SELECT t.customer_id
		 FROM  contract t,customer c
		 WHERE c.id=t.customer_id AND c.ctype=1 AND c.del_flg=0 AND t.del_flg =0 AND
		 NOT EXISTS (SELECT b.id FROM virtual_bank b WHERE c.id=b.customer_id AND b.del_flg=0 AND b.give_flg=1)
		 GROUP BY t.customer_id HAVING MIN(SUBSTRING(t.reg_date,1,10))>=DATE_SUB(CURRENT_DATE(),interval 1 day)';

$dRtn3 = mysql_query($dSql) or die('query error'.mysql_error());

if ( $dRtn3 ) {
    $i = 1;
    while ( $data = mysql_fetch_assoc($dRtn3) ) {
    	// 未使用バーチャル番号取得
    	$virtual_id = Get_Table_Col("virtual_bank","min(id)"," WHERE del_flg=0 AND give_flg=0");

		// 番号付与
		if($virtual_id){
			query( "UPDATE virtual_bank SET give_flg=1,give_date=now(),customer_id=".$data['customer_id']." WHERE del_flg=0 AND give_flg=0 AND id=".$virtual_id );
        	$i++;
		}
    }
}

// 重複確認
$dupli_cnt = Get_Table_Col("virtual_bank","count(id)"," WHERE del_flg=0 AND give_flg=1 GROUP BY customer_id HAVING count(id)>1");
if($dupli_cnt){
	$body =$dupli_cnt."件重複がありました。";
	// 自動送信
	mb_language("ja");
	mb_internal_encoding("UTF-8");

	$from = "キレイモチェックメール";
	$from_email = "test@kireimo.jp";
	$to = "ka@vielis.co.jp";
	$subject = 'バーチャル番号重複発生メール';

	mb_send_mail($to, $subject, $body, "From:".mb_encode_mimeheader($from)."<".$from_email.">");
}
?>