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

$table = "contract";

//編集----------------------------------------------------------------------------
if( $_POST['action'] == "edit" ) {
  $contract = Get_Table_Row("contract", " WHERE del_flg=0 AND id = '" . addslashes($_POST['contract_id']) . "' ");

  $editable_date = date("Y-m-d",mktime(0, 0, 0, date("m")-1 , date("d")-0, date("Y")));

  if ($contract['conversion_flg'] == 1) {
    $gMsg = "<font color='red' size='-1'>※プラン組替後のクーリングオフ処理はシステム部に依頼してください。</font>";
  }elseif($authority_level>0 && $_POST['cancel_date']<$editable_date){
    $gMsg = "<span style='color:red;font-size:13px;'>※一ヶ月前のデータが編集不可です。</span>";
  }elseif($_POST['confirm_payment'] && !($_POST['payment_cash']+$_POST['payment_card']+$_POST['payment_transfer']+$_POST['payment_loan'])){
  	$gMsg = "<font color='red' size='-1'>※入金額が入力していません。</font>";
  }else{

    // 以前の契約を取得する
    $col = null;
    $where = null;

    $col = "id";
    $where = " WHERE del_flg = 0 AND status = 4 AND conversion_flg = 0 AND (SELECT type FROM course WHERE id = course_id) = 0 AND customer_id = '" . addslashes($contract['customer_id'] ) . "' ";

    $before_data = Get_Table_Array($table, $col, $where);

    if (count($before_data) <= 1) {
        //データ取得-------------------
        //status=0:クーリングオフ新規；　status=2：クーリングオフ編集；　status=5：ローン取消後クーリングオフ新規
        // $contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($contract['customer_id'] )."' and (status=0 or status=2 or status=5 or status=7)  order by id DESC");
        $sales = Get_Table_Row("sales"," WHERE del_flg=0 and type=4 and contract_id = '".addslashes($contract['id'])."'");

        //POST INPUT--------------
	   $_POST['status'] = 2; // cooling off
	   $_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
	   $_POST['balance'] = 0; //売掛金
	   //$_POST['pay_date'] = $_POST['cancel_date'] = $contract['cancel_date'] ? $contract['cancel_date'] : date('Y-m-d');
	   $_POST['pay_date'] = $_POST['cancel_date'];
	   //if(!$_POST['payment'])$_POST['payment'] = $_POST['payment_cash'] + $_POST['payment_card'] + $_POST['payment_transfer'] + $_POST['payment_loan'] ; //支払金額＝現金+カード+銀行振込+ローン
	   $_POST['payment'] = $_POST['payment_cash'] + $_POST['payment_card'] + $_POST['payment_transfer'] + $_POST['payment_loan'] ; //支払金額＝現金+カード+銀行振込+ローン

	   //将来の施術予約をキャンセル処理,再契約の人がどうする？初回のみ処理?----------------------------------------------------------------
	   if($contract['status']==0 || $contract['status']==7) $GLOBALS['mysqldb']->query("update reservation set type=3 where type=2 and del_flg=0 and contract_id=".$contract['id']." and hope_date>'".$_POST['cancel_date']."'");

	       //売上tableに反映-------------
	       $_POST['type'] = 4; // cooling off
	       $_POST['contract_id'] = $contract['id'];
	       $sales_field  = array("contract_id","type","customer_id","shop_id","staff_id","rstaff_id","course_id","fixed_price","discount","price","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","pay_date","cancel_date","reg_date","edit_date","terminate_day");
	       $sales_field2 = array("contract_id","type","customer_id","shop_id","staff_id","rstaff_id","course_id","fixed_price","discount","price","payment","payment_cash","payment_card","payment_transfer","payment_loan","balance","memo","pay_date","cancel_date","edit_date","terminate_day");
	       //更新 or 新規
	       if($sales['id']) $_POST['sales_id'] = Update_Data("sales",$sales_field2,$sales['id']);//再度精算、前回精算の取り消し
	       else $_POST['sales_id'] = Input_New_Data("sales",$sales_field);//売上計上（新規）

	       if ($_POST['sales_id']) {
	           //契約tableに反映--------------
	           $contract_field2 = array("status","sales_id","balance","cancel_date","edit_date","memo","terminate_day");
	           //ローン取消後のクーリングオフ
	           if($contract['status']==7){
                 $_POST['wait_flg']=1;
                 array_push($contract_field2 , "wait_flg");
               }

                //更新
                if($contract['id']) $_POST['contract_id'] = Update_Data("contract",$contract_field2,$contract['id']);

                // 現時点のキャンセルdateを格納
                $cancel_date = $_POST['cancel_date'];

                // プラン変更前のデータが存在した場合
                if (count($before_data) == 1) {
                    // プラン変更前のデータを契約中の状態にする

                    // 更新する値を設定
                    $_POST['status']            = 0;
                    $_POST['cancel_date']       = '0000-00-00';
                    $_POST['contract_id']       = $before_data[0];

                    $contract_field3 = array("status","cancel_date","edit_date");

                    // 更新
                    if($_POST['contract_id']) $_POST['contract_id'] = Update_Data("contract",$contract_field3,$_POST['contract_id']);
                }

                //Msg-----------------------
                if( $_POST['contract_id']) {
                    $gMsg = '（完了）';
                    $complete_flg = 1;
                    header("location: ../service/cancel.php?cancel_date=".$cancel_date);
                    exit;
                }else           $gMsg = '（登録しませんでした。)';
            } else {
               $gMsg = '（登録しませんでした。)';
            }
    } else {
        $gMsg = '（以前の契約が複数存在するため、登録しませんでした。)';
    }
  }
}

// 詳細を取得----------------------------------------------------------------------------
if( $_POST['contract_id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and id = '".addslashes($_POST['contract_id'])."'");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	//$staff = Get_Table_Row("staff"," WHERE del_flg=0 and id = '".addslashes($data['staff_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");

}elseif( $_POST['customer_id'] != "" )  {
	$data = Get_Table_Row($table," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."' order by id desc");
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($data['customer_id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");
	//$staff = Get_Table_Row("staff"," WHERE id = '".addslashes($data['staff_id'])."'");
	if($data['sales_id'] != 0 ) $sales = Get_Table_Row("sales"," WHERE del_flg=0 and id = '".addslashes($data['sales_id'])."'");
}

//ローンは既収金額ではない
$data['payment'] = $data['payment'] - $data['payment_loan'] ;
//店舗リスト----------------------------------------------------------------------------
$shop_list = getDatalist("shop");

//staff list
//if($shop) $where_shop = " AND shop_id=".$shop['id'];
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

//tax
if($data['contract_date']<"2014-04-01"){
	$tax = 0.05;
	$tax2 = 1.05;
}elseif ($data['contract_date']<"2019-10-01") {
    $tax = 0.08;
    $tax2 = 1.08;
}else{
	$tax = Get_Table_Row("basic"," WHERE id = 1");
	$tax2 = 1+$tax['value'];
}


//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by group_id,name" ) or die('query error'.$GLOBALS['mysqldb']->error);
$course_list[0] = "-";
$course_price[0] = "0";
$course_name[0] = "-";
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_price[] = round(($result['price'] * (1+$tax['value'])),0);//税込
	$course_name[] = $result['name'];

}

//JSに渡すため、配列を文字列化----------------------------------------------------------------------------
$course_prices = implode(",",$course_price);
$course_names = implode(",",$course_name);
//var_dump($sales);
$shop_address = str_replace("　", " ", $shop['address']);//全角から半角へ
list($shop_address1,$shop_address2) = explode(" ", $shop_address);

$pdf_param = "?shop_name=".$shop['name']."&shop_zip=".$shop['zip']."&shop_address1=".$shop_address1."&shop_address2=".$shop_address2."&shop_tel=".$shop['tel']."&name=".$customer['name']."&course_name=".$course_list[$sales['course_id']]."&tax=".$tax['value']."&tax2=".$tax2;
$pdf_param.= "&fixed_price=".$sales['fixed_price']."&discount=".$sales['discount']."&price=".$sales['price']."&payment=".$data['payment'];
$pdf_param.= "&option_name=".$gOption[$sales['option_name']]."&option_price=".$sales['option_price']."&balance=".$sales['balance'];


?>
