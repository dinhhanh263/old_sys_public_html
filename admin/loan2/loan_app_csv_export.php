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

$table = "loan_info";

$_POST['application_date'] = $_POST['application_date']  ? $_POST['application_date']  : ($_POST['application_date2'] ? $_POST['application_date2'] : date("2016-09-01"));
$_POST['application_date2']=$_POST['application_date2'] ? $_POST['application_date2'] : date("Y-m-d");

$pre_date  = date("Y-m-d", strtotime($_POST['application_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['application_date2']." +1day"));


// 検索条件の設定-------------------------------------------------------------------
$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(l.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(l.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or c.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(l.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or l.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}

$dWhere .= " AND l.application_date>='".$_POST['application_date']."'";
$dWhere .= " AND l.application_date<='".$_POST['application_date2']."'";

// if($_POST['verify_status'])   $dWhere .= " AND l.verify_status='".$_POST['verify_status'] ."'";
if($_POST['contract_status']) $dWhere .= " AND l.contract_status='".$_POST['contract_status'] ."'";
if($_POST['support_status'])  $dWhere .= " AND l.support_status='".$_POST['support_status'] ."'";
if($_POST['cor_request'])     $dWhere .= " AND l.cor_request='".$_POST['cor_request'] ."'";
// 契約終了
if($_POST['process_category'] ){
	$dWhere .= " AND l.process_category in(".Post_To_DB_Cook2($_POST['process_category']) .")";
}
// 受付終了
if($_POST['regist_category'] ){
	$dWhere .= " AND l.regist_category in(".Post_To_DB_Cook2($_POST['regist_category']) .")";
}
// 同意書リカバー
if($_POST['consent_recovery'] ){
	$dWhere .= " AND l.consent_recovery in(".Post_To_DB_Cook2($_POST['consent_recovery']) .")";
}
// ベリファイ確認状況
if($_POST['verify_status'] ){
	$dWhere .= " AND l.verify_status in(".Post_To_DB_Cook2($_POST['verify_status']) .")";
}
// 経過日数
if($_POST['pass_days'] ){
	// 30日以上
	if(in_array(1,$_POST['pass_days'])){
		$dWhere .= " AND datediff(current_date(),l.application_date)>30";
	// 60日以上
	}else{
		$dWhere .= " AND datediff(current_date(),l.application_date)>60";
	}
}
// 契約番号有無
if($_POST['if_contract_no'] ){
	// 付与済
	if(in_array(2,$_POST['if_contract_no']) && count($_POST['if_contract_no'])==1){
		$dWhere .= " AND l.loan_contract_no<>'' ";
	// 未付与
	}elseif(in_array(1,$_POST['if_contract_no']) && count($_POST['if_contract_no'])==1){
		$dWhere .= " AND l.loan_contract_no='' ";
	}
}
// 契約日有無
if($_POST['if_contract_date'] ){
	// 有
	if(in_array(2,$_POST['if_contract_date']) && count($_POST['if_contract_date'])==1){
		$dWhere .= " AND l.loan_contract_date<>'' AND l.loan_contract_date<>'0000-00-00' ";
	// 無
	}elseif(in_array(1,$_POST['if_contract_date']) && count($_POST['if_contract_date'])==1){
		$dWhere .= " AND (l.loan_contract_date='' OR l.loan_contract_date='0000-00-00') ";
	}
}
// 契約終了日有無
if($_POST['if_contract_end_date'] ){
	// 有
	if(in_array(2,$_POST['if_contract_end_date']) && count($_POST['if_contract_end_date'])==1){
		$dWhere .= " AND l.loan_end_date<>'' AND l.loan_end_date<>'0000-00-00' ";
	// 無
	}elseif(in_array(1,$_POST['if_contract_end_date']) && count($_POST['if_contract_end_date'])==1){
		$dWhere .= " AND (l.loan_end_date='' OR l.loan_end_date='0000-00-00') ";
	}
}
// 支払方法
if($_POST['transfer_status'] ){
	$dWhere .= " AND l.transfer_status in(".Post_To_DB_Cook2($_POST['transfer_status']) .")";
}

// データの取得----------------------------------------------------------------------
$dSql = "select l.id 'ID',
case
 when l.contract_status=1 then '申込（未審査）'
 when l.contract_status=2 then '審査中'
 when l.contract_status=3 then '仮承認'
 when l.contract_status=4 then '本承認'
 when l.contract_status=5 then '契約中'
 when l.contract_status=6 then '契約終了'
 when l.contract_status=7 then '受付終了'
end 'ステータス',
case
 when l.process_category=1 then '解約'
 when l.process_category=2 then '自動解約'
 when l.process_category=3 then '早期完済'
 when l.process_category=4 then '満了'
end '契約終了区分',
case
 when l.regist_category=1 then '提出前キャンセル'
 when l.regist_category=2 then '45日期限切れ'
end '受付終了区分',
case
 when l.consent_recovery=1 then '同意書'
 when l.consent_recovery=2 then 'リカバー'
 when l.consent_recovery=3 then '同意者拒否'
end '同意書リカバー',
l.recept_no '受付番号',
l.loan_contract_no '契約番号',
c.no '会員番号',
l.id '顧客ID',
l.application_date '申込日',
case
 when h.name='東京本店' then 'メンズキレイモ東京本店'
 else h.name
end '申込店舗',
l.name '名前',
l.name_kana '名前カナ',
l.mail 'メールアドレス',
l.tel '電話番号',
l.birthday '生年月日',
l.zip '郵便番号',
p.name '都道府県',
l.address '住所',
case
 when l.course_id<>0 then u.name
 else l.product
end '申込商品',
case
 when l.course_id<>0 then u.times
 else l.product_times
end 'コース回数',
case
 when l.amount<>0 then l.amount
 else t.payment_loan
end '申込金額',
l.number_of_payments '支払回数',
l.amount_of_installments '分割支払金合計',
l.installment_amount_1st '第１回支払額',
l.installment_amount_2nd '第２回支払額',
concat(l.first_payment_year,'/',l.first_payment_month)  '支払初月',
concat(l.asp_start_year,'/',l.asp_start_month)  'ASP開始月',
l.claim_irregular '入金請求イレギュラー',
concat(l.expected_end_year,'/',l.expected_end_month) '支払終了予定年月',
case
 when l.loan_contract_date='0000-00-00' then ''
 else l.loan_contract_date
end '契約日',
case
 when l.loan_end_date='0000-00-00' then ''
 else l.loan_end_date
end '契約終了日',
case
 when l.transfer_status=1 then '口座振替'
 when l.transfer_status=3 then 'コンビニ'
 else '-'
end '支払方法',
case
 when l.transfer_mailing_date='0000-00-00' then ''
 else l.transfer_mailing_date
end '口振依頼書みずほへの発送日',
l.verify_complete_datetime 'オンラインベリ確認日時',
s.smartpit_no 'スマートピット番号',
l.identification_number '運転免許証番号'

from loan_info l
LEFT JOIN customer c ON c.id=l.customer_id AND l.customer_id<>0 AND c.del_flg=0
LEFT JOIN contract t ON t.id=l.contract_id AND l.contract_id<>0 AND t.del_flg=0
LEFT JOIN course u ON u.id=l.course_id AND l.course_id<>0 AND u.del_flg=0
LEFT JOIN prefectures p ON p.id=l.pref AND l.pref<>0
LEFT JOIN shop h ON h.id=l.shop_id AND l.shop_id<>0 AND h.del_flg=0
left join smartpit s on s.id=c.smartpit_id and s.del_flg=0

where l.del_flg=0
".$dWhere."
order by l.recept_no";

$dRtn3 = $GLOBALS['mysqldb']->query($dSql) or die('query error'.$GLOBALS['mysqldb']->error);

//csv export----------------------------------------------------------------------
$filename = "loan_app_export.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3 ) {
	echo mb_convert_encoding("LoanID,ステータス,契約終了区分,受付終了区分,同意書リカバー,受付番号,契約番号,会員番号,顧客ID,申込日,申込店舗,名前,名前カナ,メールアドレス,生年月日,電話番号,郵便番号,都道府県,住所,申込商品,コース回数,申込金額,支払回数,分割支払金合計,第１回支払額,第２回支払額,支払初月,ASP開始月,入金請求イレギュラー,支払終了予定年月,契約日,契約終了日,支払方法,口振依頼書みずほへの発送日,オンラインベリ確認日時,スマートピット番号,運転免許証番号\n","SJIS-win", "UTF-8");
	while ( $data = $dRtn3->fetch_assoc() ) {
		echo mb_convert_encoding($data['ID'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['ステータス'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['契約終了区分'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['受付終了区分'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['同意書リカバー'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['受付番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['契約番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['会員番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['顧客ID'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['申込日'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['申込店舗'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding(data_format($data['名前']),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding(data_format($data['名前カナ']),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding(data_format($data['メールアドレス']),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding(data_format($data['生年月日']),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding(data_format($data['電話番号']),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding(data_format($data['郵便番号']),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding(data_format($data['都道府県']),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding(data_format($data['住所']),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['申込商品'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['コース回数'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['申込金額'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['支払回数'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['分割支払金合計'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['第１回支払額'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['第２回支払額'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['支払初月'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['ASP開始月'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding(data_format($data['入金請求イレギュラー']),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['支払終了予定年月'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['契約日'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['契約終了日'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['支払方法'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['口振依頼書みずほへの発送日'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['オンラインベリ確認日時'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['スマートピット番号'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['運転免許証番号'],"SJIS-win", "UTF-8")  . ",";

		echo "\n";
	}
	//CSV Export Log
	setCSVExportLog($_POST['csv_pw'],$filename);
}
