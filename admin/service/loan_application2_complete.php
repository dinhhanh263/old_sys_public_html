<?php
/**
*
* �����E������s�v��(�����N�|�C���g��) �T���v���v���O����
*
* Date: 2017.12.12
*
* �x����� 1(�����̂�) �̗�
*
* �� ���̃v���O������ Shift_JIS �ŋL�q����Ă�����̂Ƃ��܂��B
*
**/
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

$table = "loan_info2";

// �ڍׂ��擾----------------------------------------------------------------------------
$data = array();
$customer = array();
$contract = array();
$course   = array();
$shop = array();
$staff = '';

// if($_REQUEST['loan_info2_id'])		 $data = 	 Get_Table_Row($table," WHERE del_flg=0 AND id = '".addslashes($_REQUEST['loan_info2_id'])."'");
if ($_REQUEST['id']) {
    $data = Get_Table_Row($table, " WHERE del_flg=0 AND id = '" . addslashes($_REQUEST['id']) . "'");
}
if($data['customer_id']) $customer = Get_Table_Row("customer"," WHERE del_flg=0 AND id = '".addslashes($data['customer_id'])."'");
if($data['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 AND id = '".addslashes($data['contract_id'])."'");
if($contract['id']) 	 $course   = Get_Table_Row("course"," WHERE del_flg=0 AND id = '".addslashes($contract['course_id'])."'");
if($data['shop_id']) 	 $shop = 	 Get_Table_Row("shop"," WHERE del_flg=0 AND id = '".addslashes($data['shop_id'])."'");
if($data['staff_id']) 	 $staff = 	 Get_Table_Col("staff","name"," WHERE del_flg=0 AND id = '".addslashes($data['staff_id'])."'");

list($name1,$name2) = explode("�@",trim(mb_convert_encoding( $customer['name'], "SJIS", "UTF-8" )));
list($kana1,$kana2) = explode("�@",trim(mb_convert_encoding( $customer['name_kana'], "SJIS", "UTF-8" )));
list($zip1,$zip2) = explode("-",trim(mb_convert_encoding( $customer['zip'], "SJIS", "UTF-8" )));
$pref = mb_convert_encoding( $gPref2[$customer['pref']], "SJIS", "UTF-8" );

$shop_memo = '1.�a�����z('.$data['save_amount'].'���~)�A2.���������('.mb_convert_encoding($array_side_job[$data['side_job']], "SJIS", "UTF-8" ).')�A3.�������̔N�z('.$data['side_income'].'���~)�A4.�ƒ����S('.mb_convert_encoding($array_payment_lent[$data['payment_lent']], "SJIS", "UTF-8" ).')';

// ���C�t�e�B�V�X�e���A�g��---------------------------------------------------------------------

mb_internal_encoding('Shift_JIS');

/**
* �ݒ荀��
*/
// �e�X�g�p
if (strstr( $_SERVER['SCRIPT_FILENAME'],"demo")) {
	// �A�N�Z�X����w��
	define('RYFETY_LINKPOINT_API_URL', 'https://reg18.smp.ne.jp/regist/is?SMPFORM=mclh-pemfn-03a01d5763f6275b5c064af08e208c20' );

	// ���C�t�e�B���甭�s���ꂽ�����XAPI�p�X���[�h
	define('MERCHANT_API_PASSWORD', '0fbf03afbdbf6201e902bef33cac4d8e24a583accea0d03096d36876765880cd');

	// �x�XID
	$branchId = '004754076002';

// �{�ԗp
}else{
	// �A�N�Z�X����w��
	define('RYFETY_LINKPOINT_API_URL', 'https://reg18.smp.ne.jp/regist/is?SMPFORM=meoj-peohl-f9b913b5f1625a3c1448358715726f61' );

	// ���C�t�e�B���甭�s���ꂽ�����XAPI�p�X���[�h
	define('MERCHANT_API_PASSWORD', '5144a87ee4389b5d6ae742c4c92b6f30b1c2ca91a8be0b30546e41d4a693ac3f');

	// �x�XID
	$branchId = $shop['ryfety_id'];
}

// ���C�t�e�B���甭�s���ꂽ�����XID
define('MERCHANT_ID', '00475');

/**
* ���M�p�����[�^�̍쐬
*/

// 57. ��������
$dealDt = date('YmdHis');

// 59. �V�O�j�`��
$signatureSource = array(
	MERCHANT_ID,
	$branchId,
	$dealDt,
	MERCHANT_API_PASSWORD
);
$signatureString1 = implode(',', $signatureSource);
$signatureString2 = hash('sha256', $signatureString1, true);
$signatureString3 = base64_encode( $signatureString2 );
$signature = $signatureString3;

// ���M�p�����[�^
$requestParam = array(
	// 1. �o�[�W����
	'version' => 1,
	// 2. �����R�[�h����
	'detect' => '����',
	// 3. �����XID
	'shop_id' => MERCHANT_ID,
	// 4. �����X�x�XID
	'branch_id' => $branchId,
	// 5. �\������ID(���[���e�[�u��ID)
	'customer_cd' => $data['id'],
	// 6. �S����
	'staff_name' => mb_convert_encoding( $staff, "SJIS", "UTF-8" ),
	// 7. ���i���P
	'item1_name' => mb_convert_encoding( $course['name'], "SJIS", "UTF-8" ),
	// 8. ���ʂP
	'item1_qty' => '1',
	// 9. �P���P
	'item1_price' => $contract['price'],
	// 10. ���z�P
	'item1_amount' => $contract['price'],
	// 27. �̔��`��
	'sale_form' => '4',
	// 28. �x�����
	'sale_pattern' => '1',
	// 29. �𖱊��ԁi���j
	// 'service_start' => date('Ym',strtotime($data['service_start'])),
	 'service_start' => date('Ymd',strtotime($data['service_start'])),
	// 30. �𖱊��ԁi���j
	// 'service_end' => date('j',strtotime($data['service_start']))==1 ?
	//				 date('Ym',strtotime($data['service_end'])) :
	//				 date('Ym',strtotime($data['service_end'].' -1 month')),

	'service_end' => date('Ymd',strtotime($data['service_end'])),

	// 32. ���i���z���v
	'total_amount' => $contract['price'],
	// 33. �����i���\�����j
	'initial_payment' => ($contract['price']-$contract['payment_loan']),
	// 34. �c��
	'left_payment' => $contract['payment_loan'],
	// 37. �x����
	'pay_times' => $data['number_of_payments'],
	// 41. �����O�i���j
	'name1' => $name1,
	// 42. �����O�i���j
	'name2' => $name2,
	// 43. �t���K�i�i�Z�C�j
	'kana1' => mb_convert_kana($kana1,k).' ',
	// 44. �t���K�i�i���C�j
	'kana2' => mb_convert_kana($kana2,k).' ',
	// 45. ���N�����i����j
	'birth_year' => date('Y',strtotime($customer['birthday'])),
	// 46. ���N�����i���j
	'birth_month' => date('m',strtotime($customer['birthday'])),
	// 47. ���N�����i���j
	'birth_day' => date('d',strtotime($customer['birthday'])),
	// 48. ����
	'gender' => ($data['sex'] ? 1 : 2),
	// 49. �X�֔ԍ��P
	'postcode1' => $zip1,
	// 50. �X�֔ԍ�2
	'postcode2' => $zip2,
	// 51. �Z���P
	'addr1' => $pref,
	// 52. �Z���Q
	'addr2' => mb_convert_encoding( $customer['address'], "SJIS", "UTF-8" ),
	// 55. �g�ѓd�b�ԍ�
	'cell' => $customer['tel'],
	// 56. ���[���A�h���X
	'email' => $customer['mail'],
	// 57. ��������
	'deal_date' => $dealDt,
	// 58. �㗝�X����
	'shop_memo' => $shop_memo,
	// 59. �V�O�j�`��
	'sig' => $signature
	);

/**
* �t�H�[����HTML�o��
*/
$formAction = RYFETY_LINKPOINT_API_URL;

$inputHidden = '';
foreach( $requestParam as $item => $value ) {
	$value = htmlspecialchars($value, ENT_QUOTES, 'SJIS');
	$inputHidden .= "<input type=\"hidden\" name=\"$item\" value=\"$value\" >\n";
}

header('Content-Type: text/html; charset=Shift_JIS');
print <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="Shift_JIS">
<title>�����\��</title>
</head>
<body>
<div align="center">
<p>�����āA�u���ցv�{�^���������Ď��̉�ʂɐi�݂܂��B</p>
<form method="post" action="$formAction">
$inputHidden
<input type="submit" name="submit" value="����">
</form>
</div>
</body>
</html>
HTML;
?>