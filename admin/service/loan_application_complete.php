<?php
// サクシードシステム連携
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'auth.php';

$table = "loan_info";

// 詳細を取得---------------------------------------------------
$data = array();
if($_REQUEST['loan_info_id'])	$data = Get_Table_Row($table," WHERE del_flg=0 AND id = '".addslashes($_REQUEST['loan_info_id'])."'");

// サクシードシステム連携へ----------------------------------------------

mb_internal_encoding('UTF-8');

// アクセス先を指定
define('SUCCEAD_API_URL','https://www.succead.co.jp/kireimo/regist/');
define('MERCHANT_API_PASSWORD','Lvok4aV9pEi5S3uffB1e2YZ87Q4RJBAP3mS1L5Fc7Og8gDxqQlWeiStm2HBS5t04');

$signatureString = hash('sha256', MERCHANT_API_PASSWORD.",".date('YmdHis'), true);
$signature = base64_encode($signatureString);

// 送信パラメータ
$requestParam= array(
	'apl_id' => $data['id'],
	'customer_id' => $data['customer_id'],
	'no' => $data['no'],
	'pw' => $data['pw'],
	'name' => $data['name'],
	'name_kana' => $data['name_kana'],
	'mail' => $data['mail'],
	'tel' => $data['tel'],
	'birthday' => $data['birthday'],
	'zip' => $data['zip'],
	'pref' => $data['pref'],
	'address' => $data['address'],
	'contract_id' => $data['contract_id'], // 不要?
	'contract_date' => $data['contract_date'],
	'end_date' => $data['end_date'],
	'course_id' => $data['course_id'],
	'loan_company_id' => $data['loan_company_id'], // 不要?
	'shop_id' => $data['shop_id'],
	'staff_id' => $data['staff_id'], // 不要?
	'application_date' => $data['application_date'],
	'price' => $data['price'],
	'initial_payment' => $data['initial_payment'],
	'amount' => $data['amount'],
	'number_of_payments' => $data['number_of_payments'],
	'first_payment_year' => $data['first_payment_year'],
	'first_payment_month' => $data['first_payment_month'],
	'expected_end_year' => $data['expected_end_year'],
	'expected_end_month' => $data['expected_end_month'],
	'transfer_status' => $data['transfer_status'],
	'total_installment_commission' => $data['total_installment_commission'],
	'amount_of_installments' => $data['amount_of_installments'],
	'installment_amount_1st' => $data['installment_amount_1st'],
	'installment_amount_2nd' => $data['installment_amount_2nd'],
	'annual_amount' => $data['annual_amount'],
	'payment_lent' => $data['payment_lent'],
	'house_type' => $data['house_type'],
	'living_grant' => $data['living_grant'],
	'same_living_count' => $data['same_living_count'],
	'annual_income' => $data['annual_income'],
	'identification_type' => $data['identification_type'],
	'identification_number' => $data['identification_number'],
	'call_timezone' => $data['call_timezone'],
	'credit_app_agree' => $data['credit_app_agree'],
	'privacy_agree' => $data['privacy_agree'],
	'sig' => $signature
);

// フォームのHTML出力
$formAction = SUCCEAD_API_URL;

$inputHidden = '';
foreach ( $requestParam as $item => $value ) {
	$value = htmlspecialchars($value ,ENT_QUOTES | ENT_SUBSTITUTE,'UTF-8');
	$inputHidden .= "<input type=\"hidden\" name=\"$item\" value=\"$value\" >\n";
}
?>

<!DOCTYPE html>
<html>
 <head>
  <meta charset="UTF-8">
  <title>サクシードローン申込</title>
 </head>
 <body>
  <div align="center">
   <p>※まだ申込は完了していません！！<br />
		続けて、下の「完了」ボタンを押して申込を完了させてください。</p>
   <form method="post" action="<?php echo $formAction;?>">
    <?php echo $inputHidden;?>
    <input type="submit" name="submit" value="完了">
   </form>
  </div>
 </body>
</html>

