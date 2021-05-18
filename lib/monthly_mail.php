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

/* 日付データ */
$date =  date('Y-m-d', strtotime("-1 day"));
$lastmon = date('Y-m', strtotime(date('Y-m-1').' -1 month')); // 3/29,30,31で計算が狂って3月のままになるので修正　edit by ka 20170329
$mon = date('Y-m');

/* select内容 */

$counter = "SELECT count(*)
FROM `customer` AS c, contract AS t, course AS u,reservation AS r
WHERE c.del_flg =0
AND t.del_flg =0
AND c.id = t.customer_id
AND t.status =0
AND t.course_id = u.id
AND u.type =1
AND u.new_flg=0
AND c.ctype =1
AND c.del_flg =0
AND t.r_times>0
AND r.type = 2
AND substring( t.latest_date, 1, 7 )<> '0000-00'
AND substring( t.latest_date, 1, 7 )< '".$lastmon."'
AND substring( r.hope_date, 1, 7 ) >= '".$mon."'
AND c.id = r.customer_id
AND r.course_id = u.id
AND c.id <>ALL (
 SELECT DISTINCT s.customer_id
 FROM sales AS s, course AS c
 WHERE substring( s.pay_date, 1, 7 ) = '".$lastmon."'
 AND c.id = s.course_id
 AND c.type =1
 AND s.del_flg=0
 AND s.r_times >0
 AND c.new_flg=0
);";

// 手動で出力する場合は下記のSQLを使用
$pickup = "SELECT c.id,c.no,c.name,r.hope_date
FROM `customer` AS c, contract AS t, course AS u,reservation AS r
WHERE c.del_flg =0
AND t.del_flg =0
AND c.id = t.customer_id
AND t.status =0
AND t.course_id = u.id
AND u.type =1
AND u.new_flg=0
AND c.ctype =1
AND c.del_flg =0
AND t.r_times>0
AND r.type = 2
AND substring( t.latest_date, 1, 7 )<> '0000-00'
AND substring( t.latest_date, 1, 7 )< date_format( date_add( now( ) , INTERVAL-1 MONTH ) , '%Y-%m' )
AND substring( r.hope_date, 1, 7 ) >= date_format( now( ) , '%Y-%m' )
AND c.id = r.customer_id
AND r.course_id = u.id
AND c.id <>ALL (
 SELECT DISTINCT s.customer_id
 FROM sales AS s, course AS c
 WHERE substring( s.pay_date, 1, 7 ) = date_format( date_add( now( ) , INTERVAL-1 MONTH ) , '%Y-%m' )
 AND c.id = s.course_id
 AND c.type =1
 AND s.del_flg=0
 AND s.r_times >0
 AND c.new_flg=0
);";




/* SQL実行 */
$consql = $GLOBALS['mysqldb']->query($counter);
if (!$consql) {
    die('クエリーが失敗しました。'.$GLOBALS['mysqldb']->error;
}

$consql2 = $consql->fetch_assoc();

$to = "test@kireimo.jp";

$subject = "月額予約ずれ発生メール（旧月額用）";

$body = $consql2['count(*)']."件1ヶ月予約を飛ばした月額予約が発生しています。";

$from = "キレイモチェックメール";
$from_email = "test@kireimo.jp";

if($consql2['count(*)']> 0 ) {
    mb_language("ja");
    mb_internal_encoding("UTF-8");
    $kagoya = new KagoyaSendMail();
    $kagoya->send($to, $subject, $body, "From:".mb_encode_mimeheader($from)."<".$from_email.">");
}

// 月額予約ずれ発生メール（新月額用)
/* $new_mon = "SELECT s.customer_id,t.id AS contract_id,u.id AS course_id,u.name,t.start_ym,s.pay_date,
TRUNCATE( (PERIOD_DIFF(DATE_FORMAT(CURDATE(), '%Y%m'), t.start_ym))/2 ,0) AS '来るべき回数',
COUNT(s.pay_date) AS '来た回数',MAX(s.r_times) AS '最大消化回数' 
 FROM sales s
INNER JOIN contract t ON t.id=s.contract_id
INNER JOIN course u ON s.course_id=u.id
INNER JOIN customer c ON s.customer_id=c.id
WHERE
 s.del_flg=0
 AND s.r_times >0
 AND u.type=1
 AND u.new_flg=1
 AND t.start_ym<>0
 GROUP BY s.contract_id
 HAVING COUNT(s.pay_date)<TRUNCATE( (PERIOD_DIFF(DATE_FORMAT(CURDATE(), '%Y%m'), t.start_ym))/2 ,0);
"; */

// 新月額ターム内に2回予約取得発生メール
$new_mon = "SELECT t.customer_id,c.no,c.name,c.name_kana,u.id AS course_id,u.name AS course_name
FROM contract t
INNER JOIN course u ON t.course_id = u.id
INNER JOIN customer c ON t.customer_id = c.id
WHERE
 t.del_flg=0
 AND EXISTS(
     SELECT count(r2.id) FROM reservation r2
     WHERE
         r2.del_flg=0
         AND r2.customer_id = t.customer_id
         AND r2.contract_id = t.id
         AND DATE_FORMAT((r2.hope_date), '%Y%m')
         IN(";
  // 現在月が「偶数」
  if(date(n)%2==0){
    $new_mon .="
            (CASE WHEN t.start_ym%2=0 THEN DATE_FORMAT(now(), '%Y%m') ELSE DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y%m')  END),
            (CASE WHEN t.start_ym%2=0 THEN DATE_FORMAT(DATE_ADD(LAST_DAY(CURDATE()),INTERVAL 1 DAY), '%Y%m') ELSE DATE_FORMAT(now(), '%Y%m') END)
            ";
  // 現在月が「奇数」
  }else{
    $new_mon .="
            (CASE WHEN t.start_ym%2=0 THEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y%m') ELSE DATE_FORMAT(now(), '%Y%m')  END),
            (CASE WHEN t.start_ym%2=0 THEN DATE_FORMAT(now(), '%Y%m') ELSE DATE_FORMAT(DATE_ADD(LAST_DAY(CURDATE()),INTERVAL 1 DAY), '%Y%m') END)
            ";
  }
$new_mon .="
             )
        AND r2.type=2
        GROUP BY r2.contract_id
        HAVING 1 < count(r2.id)
 )
 AND t.status=0
 AND u.new_flg=1
 AND u.type=1";

/* SQL実行 */
$new_mon_sql = $GLOBALS['mysqldb']->query($new_mon);
if (!$new_mon_sql) {
    die('クエリーが失敗しました。'.$GLOBALS['mysqldb']->error);
}

$new_mon_rows = $new_mon_sql->num_rows;
// $subject_new = "月額予約ずれ発生メール（新月額用）";
// $body_new = $consql2['count(*)']."件1ヶ月予約を飛ばした月額予約が発生しています。";

$subject_new = "新月額ターム内に2回予約取得発生メール";
$body_new = $new_mon_rows."件新月額ターム内に2回予約取得発生しています。";

if($new_mon_rows> 0 ) {
    mb_language("ja");
    mb_internal_encoding("UTF-8");
    $kagoya = new KagoyaSendMail();
    $kagoya->send($to, $subject_new, $body_new, "From:".mb_encode_mimeheader($from)."<".$from_email.">");
}

?>
