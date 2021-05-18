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

$table = "bank";

// 編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
	$gMsg = validate();
	if( empty( $gMsg  ) ){
		$common_field = array(
			'customer_id',
			'bank_name',
			'bank_branch',
			'bank_account_type',
			'bank_account_no',
			'bank_account_name',
			'status'
		);
		// 更新
		if($_POST['id']){
			$_POST['edit_date'] = date('Y-m-d H:i:s');
			array_push($common_field, 'edit_date');
			$_POST['id'] = Update_Data($table,$common_field,$_POST['id']);
		}
		// 新規
		else{
			$_POST['reg_date']  = date('Y-m-d H:i:s');
			$_POST['edit_date'] = date('Y-m-d H:i:s');
			array_push($common_field, 'reg_date','edit_date');
			$_POST['id'] = Input_New_Data($table,$common_field);

		}

		// Msg----------------------------------------------------------------------------
		if( $_POST['id'] ) {
			$gMsg = '（登録完了）';
			$complete_flg = 1;
		}else $gMsg = '（登録しませんでした。)';
	}
}

// 詳細を取得----------------------------------------------------------------------------
if($_REQUEST['customer_id']) $customer = Get_Table_Row("customer"," WHERE del_flg=0 AND id = '".addslashes($_REQUEST['customer_id'])."'");
else 						 $customer = array();
if($_REQUEST['customer_id']) $data = Get_Table_Row($table," WHERE del_flg=0 AND customer_id = '".addslashes($_REQUEST['customer_id'])."'");
else $data = array();

// SV権限以下が当日編集のみ------------------------------------------------------------------------
if( $authority_level >= 7 && $data['id'] && substr($data['reg_date'],0,10)<date('Y-m-d') ) $disabled = ' disabled';
else $disabled = '';

// 関数：必須項目確認----------------------------------------------------------------------------
function validate(){
	$gMsg ="";

	// 入力チェック > 銀行名
    if( empty($_POST['bank_name']) ) 	    		$gMsg .= '<br />※銀行名を入力してください。';
    elseif( mb_strlen($_POST['bank_name']) > 64 )  	$gMsg .= '<br />※銀行名は64文字以下で入力してください。';

    // 入力チェック > 支店名：
    if( empty($_POST['bank_branch']) ) 				$gMsg .= '<br />※支店名を入力してください。';
    elseif( mb_strlen($_POST['bank_branch']) > 64 )	$gMsg .= '<br />※支店名は64文字以下で入力してください。';

    // 入力チェック > 口座種別
    if( empty($_POST['bank_account_type']) ) 		$gMsg .= '<br />※口座種別を選択してください。';
    elseif ( !preg_match("/^[1-3]$/", $_POST['bank_account_type']) )
    												$gMsg .= '<br />※口座種別の値が不正です。';

    // 入力チェック > 口座番号
    if( empty($_POST['bank_account_no']) ) 			$gMsg .= '<br />※口座番号を入力してください。';
    elseif ( !preg_match("/^[0-9]+$/", $_POST['bank_account_no']) )
    												$gMsg .= '<br />※口座番号の値が不正です。';
    elseif ( strlen($_POST['bank_account_no']) !=7 )$gMsg .= '<br />※口座番号は7桁で入力してください。';

    // 入力チェック > 口座名義
    if( empty($_POST['bank_account_name']) ) 		$gMsg .= '<br />※口座名義を入力してください。';
    elseif ( !preg_match("/^[ァ-ヶー　]+$/u", $_POST['bank_account_name']) )
      												$gMsg .= '<br />※口座名義は全角カナで入力してください。';
    elseif ( mb_strlen($_POST['bank_account_name']) > 64 )
      												$gMsg .= '<br />※口座名義は64文字以下で入力してください。';

    // 入力チェック > 口座状況
    if( $_POST['status'] == null || $_POST['status'] == ""  )
      												$gMsg .= '<br />※口座状況を選択してください。';
    elseif ( !preg_match("/^[0-2]$/", $_POST['status']) )
      												$gMsg .= '<br />※口座状況の値が不正です。';

	if($gMsg)										$gMsg = "<font color='red' size='-1'>".$gMsg."</font>";

	return $gMsg;
}

