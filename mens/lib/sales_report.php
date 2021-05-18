<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
include_once( "./function.php" );
$conn = mysqli_connect(HOST_NAME, DB_UESR, DB_PW);
$GLOBALS['mysqldb']->select_db(DB_NAME,$conn);

$table = "sales";
//店舗リスト----------------------------------------------------------------------------
$shop_list = getDatalist_shop();
// $mensdb = changedb();


//courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" );
$course_list[0] = "全コース";
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];

}


foreach ($shop_list as $key => $value) {
  $total_fixed_price = 0;
  $total_discount = 0;
  $total_price = 0;
  $total_payment = 0;
  $total_option_price = 0;
  $total_sales = 0;
  $total_cash = 0;
  $total_card = 0;
  $total_transfer = 0;
  $total_loan = 0;
  $total_coupon = 0;
  $balance = array();
  $total_balance = 0;
  $data = array();
  $dWhere = 0;

  if($key) $dWhere .= " AND  sales.shop_id='".$key ."'";

  //if($_POST['pay_date']) $dWhere .= " AND sales.pay_date>='".substr($_POST['pay_date'],0,8) ."01' AND  sales.pay_date<='".substr($_POST['pay_date'],0,8) ."31'";

  $pre_date = date("Y-m-d",mktime(0, 0, 0, date("m") , date("d")-1, date("Y")));
  $dWhere .= " AND sales.pay_date>='2014-02-28' AND sales.pay_date<='". $pre_date ."'";

  $dSql = "SELECT sales.*,customer.no as no,customer.name as name,customer.name_kana as name_kana FROM " . $table . ",customer WHERE sales.customer_id=customer.id and customer.del_flg=0 AND sales.del_flg = 0".$dWhere." ORDER BY sales.pay_date,sales.reg_date ";
  $dRtn3 = $GLOBALS['mysqldb']->query( $dSql  );

  if ( $dRtn3->num_rows >= 1 ) {
	$i = 1;
	$cnt_monthly = 0;
	$cnt_pack = 0;
	while ( $data = $dRtn3->fetch_assoc() ) {
		//ローン取消除外
		//if($data['type']==9) continue;
		//役務消化除外
		if($data['r_times'] && !$data['payment_cash'] && !$data['payment_card'] && !$data['payment_transfer'] && !$data['payment_loan'] && !$data['payment_coupon'] && !$data['option_price'] && !$data['option_price'] && !$data['option_transfer'] && !$data['option_card'] ) continue; 	
		$loan_status = Get_Table_Col("contract","loan_status"," where del_flg=0 and id=".$data['contract_id']);
		if( $data['type']==9) $data['balance']=0;

		if($data['type']==1)$total_fixed_price += $data['fixed_price']; 										// コース金額,契約時のカウンセリング時だけ
		if($data['type']==1)$total_discount += $data['discount']; 												// 値引き合計,契約時のカウンセリング時だけ
		if($data['type']==1)$total_price += $data['fixed_price'] - $data['discount']; 							//請求金額（商品金額）
		// 残金支払合計
		if($data['type']<>1 && $data['type']<>6 && $data['type']<>10){
			if( $data['payment_cash']>0)$total_payment += $data['payment_cash'] ; 	
			if( $data['payment_card']>0)$total_payment += $data['payment_card'] ; 	
			if( $data['payment_transfer']>0)$total_payment += $data['payment_transfer'] ; 	
			if( $loan_status==1 && $data['payment_loan']>0)$total_payment += $data['payment_loan'] ; 
		}		
		$total_option_price += $data['option_price'] + $data['option_transfer'] + $data['option_card']; 		// オプション金額合計
		$total_sales += $data['payment_cash'] + $data['payment_card'] + $data['payment_transfer'] + $data['payment_loan'] + $data['payment_coupon'] + $data['option_price'] + $data['option_transfer'] + $data['option_card'];

		if($pre_no == $data['no']) $total_balance -= $pre_balance; 												//最新の売上だけを計上処理
		$total_balance += $data['balance']; 																	// 売掛金合計


		if($data['type']==1){
			if($course_type[$data['course_id']]) $cnt_monthly++; 												// 月額件数
			else $cnt_pack++; 																					// パック件数
		}

		$total_cash 	+= $data['payment_cash'] + $data['option_price']; 										// 現金売上合計
		$total_card 	+= $data['payment_card']  + $data['option_card']; 										// カード売上合計
		$total_transfer += $data['payment_transfer'] + $data['option_transfer'] ; 								// 振込売上合計
		$total_loan 	+= $data['payment_loan'] ; 																// ローン売上合計
		$total_coupon 	+= $data['payment_coupon'] ; 															// クーポン売上合計

		$pre_no = $data['no'];
		$pre_balance = $data['balance'];
		if($data['type']==1) $isexited_contract = true;

		//最新売掛金を格納
		$balance[$data['customer_id']] = $data['balance'];

		$i++;
	}
		$total_balance = array_sum($balance);
		$total_balance = $isexited_contract ? $total_balance : 0; 												// 契約データがなければ0
		$total_without_balance = $total_sales; 																	// 売掛含まない総合計

  }

  //予約件数、来店件数、契約件数、未契約数取得(status=2)----------------------------------------------------------------------

  $dSql = "SELECT id,status FROM reservation WHERE del_flg=0 AND type=1 AND hope_date>='2014-02-08' AND hope_date<='".$pre_date."'";
  if($key )$dSql .= " AND shop_id='".$key ."'";
  $rsv_sql = $GLOBALS['mysqldb']->query($dSql);

  $cnt_rsv	  	= 0 ; //予約件数
  $cnt_comein   	= 0 ; //来店件数
  $cnt_contract 	= 0 ; //契約件数
  $cnt_nocontract = 0 ; //未契約件数

  while ( $result = $rsv_sql->fetch_assoc() ) {
	if($result['status']>=0 && $result['status']<=10 ) $cnt_rsv++;
	if($result['status']>=2 && $result['status']<=10 ) $cnt_comein++;
	if($result['status']>=3 && $result['status']<=10 ) $cnt_contract++;
	if($result['status']==2) 						   $cnt_nocontract++;

  }

  //tableに格納----------------------------------------------------------------------------------
  $shop_id = $key ? $key : 0;
  $exited_data_id = Get_Table_Col("sales_report","id"," where shop_id=".$shop_id." and sales_month='' and sales_day='' ");
  if($exited_data_id ){
	$sql_sales = "UPDATE sales_report SET shop_id=".$key.",cnt_rsv=".$cnt_rsv.",cnt_comein=".$cnt_comein.",cnt_contract=".$cnt_contract.",cnt_nocontract=".$cnt_nocontract.",cnt_monthly=".$cnt_monthly.",cnt_pack=".$cnt_pack.",total_cash=".$total_cash.",total_card=".$total_card.",total_transfer=".$total_transfer.",total_loan=".$total_loan.",total_without_balance=".$total_without_balance.",total_balance=".$total_balance.",total_payment=".$total_payment.",reg_date='".date('Y-m-d H:i:s')."'";
	$sql_sales .= " WHERE id = '".addslashes($exited_data_id)."'";
  }else{
	$sql_sales  = "INSERT INTO sales_report (shop_id,cnt_rsv,cnt_comein,cnt_contract,cnt_nocontract,cnt_monthly,cnt_pack,total_cash,total_card,total_transfer,total_loan,total_without_balance,total_balance,total_payment,reg_date) VALUES (";
	$sql_sales .="'".$key."','".$cnt_rsv."','".$cnt_comein."','".$cnt_contract."','".$cnt_nocontract."',";
	$sql_sales .="'".$cnt_monthly."','".$cnt_pack."','".$total_cash."','".$total_card."','".$total_transfer."','".$total_loan."','".$total_without_balance."',";
	$sql_sales .="'".$total_balance."','".$total_payment."','".date('Y-m-d H:i:s')."')";
  }
  $GLOBALS['mysqldb']->query($sql_sales);
  //var_dump($sql_sales);
}
?>