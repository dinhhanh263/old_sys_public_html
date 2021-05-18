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

$table = "sales";
//パラメーターあればセッションに入れる
if (isset($_GET['new_regist_id'])) {
   $_SESSION['new_regist_id'] = $_GET['new_regist_id'];
//新規の場合はセッション破棄
} else if ($_SERVER['REQUEST_METHOD'] != "POST"){
   unset($_SESSION['new_regist_id']);
}

// 編集----------------------------------------------------------------------------

if( $_POST['action'] == "edit" ) {

	if( $_POST['id']){
		$sales = Get_Table_Row($table," WHERE type=51 and del_flg=0 and id = '".addslashes($_POST['id'])."'");
		$products = Get_Table_Array("product_stock","*"," WHERE del_flg=0 and sales_id = '".addslashes($_POST['id'])."'");
		$registered_products_no = Get_Table_Array("product_stock","product_no"," WHERE del_flg=0 and sales_id = '".addslashes($_POST['id'])."'");
	}
	if( $_POST['customer_id']) $customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($_POST['customer_id'])."'");
	if( $sales['customer_id']) $customert_date = Get_Table_Col("customer","id"," WHERE del_flg=0 and id = '".addslashes($sales['customer_id'])."'");
	if( $_POST['shop_id']) $shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($data['shop_id'])."'");

	// 遺伝子検査の購入履歴確認
	if ($_POST['id']) {
		$gene_product_past = Get_Table_Row("product_stock"," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."' and product_no = '".addslashes($gene_product_no)."' and sales_id != '".addslashes($_POST['id'])."'"); // 過去の物販レコード
		$gene_product_current = Get_Table_Row("product_stock"," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."' and product_no = '".addslashes($gene_product_no)."' and sales_id = '".addslashes($_POST['id'])."'"); // 今の物販レコード
	} else {
		$gene_product_past = Get_Table_Row("product_stock"," WHERE del_flg=0 and customer_id = '".addslashes($_POST['customer_id'])."' and product_no = '".addslashes($gene_product_no)."'");
	}

	$editable_date = date("Y-m-d",mktime(0, 0, 0, date("m") , date("d")-0, date("Y")));
	$editable_date2 = date("Y-m-d",mktime(0, 0, 0, date("m")-1 , date("d")-0, date("Y")));

	// if(!$_POST['customer_id'] || !$_POST['staff_id'] || !$_POST['product_count']){
	if(!$_POST['customer_id'] || !$_POST['staff_id']){
		$gMsg = "<span class='error_msg'>入力してください。</span>";
	} elseif (isset($_POST['product_no'][$gene_product_no]) && $_POST['product_count'][$gene_product_no] > 1) {
		$gMsg = "<font color='red' size='-1'>※遺伝子検査は2つ以上購入できません。</font>";
	} elseif (isset($_POST['product_no'][$gene_product_no]) && $_POST['product_count'][$gene_product_no] > 0 && $gene_product_past['id']) {
			$gMsg = "<font color='red' size='-1'>※過去に遺伝子検査を購入済みのため、遺伝子検査は購入できません。</font>";
	} elseif ((!$_POST['id'] || !isset($gene_product_current['id'])) && $_POST['product_count'][$gene_product_no] > 0 && ($customer['sugar_risk_id'] != 0 || $customer['fat_risk_id'] != 0 || $customer['protein_risk_id'] != 0)) {
			$gMsg = "<font color='red' size='-1'>※過去に遺伝子検査を実施済みのため、遺伝子検査は購入できません。</font>";
	// if(!$_POST['customer_id']){
	// 	$gMsg = "<span class='error_msg'>※会員番号を入力してください。</span>";
	// }elseif(!$_POST['rstaff_id']){
	// 	$gMsg = "<span class='error_msg'>※レジ担当者を入力してください。</span>";
	// }elseif(!$_POST['product_count']){
	// 	$gMsg = "<span class='error_msg'>※個数を入力してください。</span>";
	}else{
		// データ取得--------------------------------------------------------------------------------

		// POSTに格納------------------------------------------------------------------------------
		$_POST['reg_date'] = $_POST['edit_date'] = date("Y-m-d H:i:s");
		$_POST['reg_flg'] = 1;

		// 20170406 add ueda start 支払い方法選択と入金のpostを分離
		$_POST['option_price'] = 0;
		$_POST['option_card'] = 0;
		if($_POST['use_status'] === "cash"){
			$_POST['use_status'] = 1;
			$_POST['option_price'] = $_POST['total_price'];
		}else if($_POST['use_status'] === "card"){
			$_POST['use_status'] = 1;
			$_POST['option_card'] = $_POST['total_price'];
		}else if($_POST['use_status'] === "free"){
			$_POST['use_status'] = 2;
		}
		unset($_POST['total_price']);
		// 20170406 add ueda end

		// 売上tableに反映------------------------------------------------------------------------
		$sales_field  = array("id","type","customer_id","shop_id","staff_id","rstaff_id","option_name","option_price","option_card","pay_date","reg_date","edit_date","del_flg");
		$sales_field2  = array("id","type","customer_id","shop_id","staff_id","rstaff_id","option_name","option_price","option_card","pay_date","edit_date","del_flg");

		// 更新(過去の購入歴を見ている状態)
		if($sales['id']) {
			$_POST['id'] = Update_Data("sales",$sales_field2,$sales['id']);
		// 新規(submit押されていない状態)
		} else if(empty($_SESSION['new_regist_id'])) {
			$_POST['id'] = Input_New_Data($table, $sales_field);
		} else {
			header("Location: ./register.php?customer_id={$customer['id']}&hope_date={$_POST['hope_date']}");
			exit;
		}

		// 商品ごとにレコードを発行、登録
		$product_stock_field = array("id","sales_id","shop_id","product_no","product_count","customer_id","staff_id","rstaff_id","price","use_status","pay_date","reg_date","edit_date","status","del_flg");
		$product_stock_field2 = array("id","sales_id","shop_id","product_no","product_count","customer_id","staff_id","rstaff_id","price","use_status","pay_date","edit_date","status","del_flg");
		$product_stock = "product_stock";
		$product_no = $_POST['product_no'];
		$product_count = $_POST['product_count'];
		$product_price = $_POST['product_price'];
		$sales_id = $_POST['id']; //sales用IDをproduct_stock登録用に定義
		unset($_POST['id']);

		$_POST['sales_id'] = $sales_id; //売上ID
		//購入前・新規
		if(!$products){ //新規登録処理
			foreach ($product_no as $key) {
				$_POST['id'] = $_POST['product_stock_id'];// 新規物販ID
				$_POST['product_no'] = $product_no[$key];// 選択商品no
				$_POST['price'] = $product_price[$key];// 商品金額
				$_POST['product_count'] = $product_count[$key];// 商品個数
				if($_POST['product_count'] == 0) {
					continue; //新規時に個数が0ならば登録しない
				} else if(!$_POST['refund'] && empty($_SESSION['new_regist_id'])) {
					// 新規
					$_POST['id'] = Input_New_Data($product_stock, $product_stock_field);
				}
			}
		// 更新処理
		}else{
			// 購入・登録処理後
			foreach ($product_no as $key) {
				$_POST['product_no'] = $product_no[$key];// 選択商品no
				$_POST['price'] = $product_price[$key];// 商品金額
				$_POST['product_count'] = $product_count[$key];// 商品個数
				$result_no = array_search($_POST['product_no'], $registered_products_no); //登録商品noとPOSTの商品noの比較
				$key_no = $key - 1;
				if($result_no !== false){ //更新 = 選択商品noと登録済商品noが一致(=既存IDがある)したら
					$_POST['id'] = $products[$result_no]['id']; //既存IDをPOSTへ代入
					if($_POST['product_count'] <> 0){ //個数が0以外ならそのまま更新
						$_POST['del_flg'] = 0;
						$_POST['id'] = Update_Data($product_stock,$product_stock_field2,$products[$result_no]['id']);
					}else{ //個数が0ならdel_flgを登録する
						$_POST['del_flg'] = 1;
						$_POST['id'] = Update_Data($product_stock,$product_stock_field2,$products[$result_no]['id']);
					}
				}else if($_POST['product_count'] <> 0){ //新規 = 商品の既存登録がなく、かつ個数が0以外なら新規登録する
					$_POST['id'] = $_POST['product_stock_id'];// 新規物販ID
					$_POST['del_flg'] = 0;
					$_POST['id'] = Input_New_Data($product_stock,$product_stock_field);
				}else if($_POST['product_count'] == 0){ //新規 = 商品の既存登録がなく、かつ個数が0ならcontinue;
					continue;
				}else{
					echo "更新処理に失敗しました。売上一覧を確認し、システム対応へ連絡してください。";
					exit();
				}
			}
		}


		//salesにproduct_stockのIDを登録する
		$sales_field3  = array("stock_id","edit_date");
		$_POST['id'] = $sales_id; //$_POST['id']にsales用IDを再セット
		$test_array = Get_Table_Array("product_stock","id"," WHERE del_flg=0 and sales_id = '".addslashes($_POST['id'])."'");
		$_POST['stock_id'] = implode(",", $test_array);
		$_POST['id'] = Update_Data("sales",$sales_field3,$_POST['id']);

		//エラーなく登録or更新できた場合フラグを立てる
		$complete_flg = false;
		if ($_POST['id']) {
			$complete_flg = true;
			//リダイレクト時のパラメーター用
			$sales = Get_Table_Row($table," WHERE type=51 and del_flg=0 and id = '".addslashes($_POST['id'])."'");
		}
	}
}

// 詳細を取得----------------------------------------------------------------------------

if( $_POST['customer_id'] )  { //物販レジを開いた時（未登録・登録済どちらも）
	$customer = Get_Table_Row("customer"," WHERE del_flg=0 and id = '".addslashes($_POST['customer_id'])."'");
}

if($_POST['id']){ //物販レジ（登録済）を開いた時
	$product_stocks = Get_Table_Array("product_stock","*"," WHERE del_flg=0 and sales_id = '".addslashes($_POST['id'])."'");
	$sales = Get_Table_Row($table," WHERE type=51 and del_flg=0 and id = '".addslashes($_POST['id'])."'");
	$shop = Get_Table_Row("shop"," WHERE del_flg=0 and id = '".addslashes($sales['shop_id'])."'");
	// salesテーブルとprodcut_stockテーブルを結合（商品情報を取得）
	/* $dSql = "SELECT count(*) FROM ".$table. " s WHERE type=51 and del_flg = 0";
		$dRtn1 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
		$dAll_Cnt = $dRtn1->fetch_row();

		$dSql = "SELECT count(ps.id) FROM " . $table . " s,product_stock ps WHERE s.type=51 and s.del_flg = 0 AND ps.del_flg = 0".$dWhere;
		$dRtn2 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
		$dGet_Cnt = $dRtn2->fetch_row()[0]; */

	//社販ユーザーの場合
	if ($_POST['customer_id'] === $sales_employee_id) {
		//購入日に販売されていた商品(社販用:employee_flg)を取得
		$product_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM product WHERE start_date <= '".$sales['pay_date']."' AND (end_date >= '".$sales['pay_date']."' OR end_date is null) AND employee_flg = 1 AND del_flg = 0 ") or die('query error'.$GLOBALS['mysqldb']->error);
	//一般ユーザーの場合
	} else {
		//購入日に販売されていた商品(店舗用:shop_flg)を取得
		$product_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM product WHERE start_date <= '".$sales['pay_date']."' AND (end_date >= '".$sales['pay_date']."' OR end_date is null) AND shop_flg = 1 AND del_flg = 0 ") or die('query error'.$GLOBALS['mysqldb']->error);
	}
	$shop_list_where = " and open_date <='" . $sales['pay_date'] . "'and (close_date is null or close_date ='' or close_date >='" . $sales['pay_date'] . "')";

	$dSql  = "SELECT ps.*,s.id as sales_id ";
	$dSql .= "FROM " . $table . " s,product_stock ps WHERE s.id=ps.sales_id AND s.id = '".addslashes($_POST['id'])."' AND s.type=51 and  s.del_flg=0 AND ps.del_flg = 0".$dWhere;
	$dSql .= " ORDER BY ps.pay_date,ps.reg_date ";
	$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
	$i = 0;
	while ( $prduct_array = $dRtn3->fetch_array() ) {
		$product_result[$prduct_array['product_no']]['product_no'] = $prduct_array['product_no'];
		$product_result[$prduct_array['product_no']]['price'] = $prduct_array['price'];
		$product_result[$prduct_array['product_no']]['product_count'] = $prduct_array['product_count'];
		$i++;
	}
	//修正ボタン表示設定
	if($authority_level == 0){ //システム権限ならいつでも修正可能
		$retouch_flg = 1;
	}else if(!isset($_POST['refund']) && (date("Y-m-d") == $sales['pay_date'])){ //購入後・当日のみ全権限で修正可能
		$retouch_flg = 1;
	}else{
		$retouch_flg = 0;
	}
} else { //新規購入時
	//社販ユーザーの場合
	if ($_POST['customer_id'] === $sales_employee_id) {
		//予約日(hope_date)に販売中の商品(社販用:employee_flg)を取得
		$product_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM product WHERE status = 2 AND start_date <= '".$_POST['hope_date']."' AND (end_date >= '".$_POST['hope_date']."' OR end_date is null) AND employee_flg = 1 AND del_flg = 0 ") or die('query error'.$GLOBALS['mysqldb']->error);
	//一般ユーザーの場合
	} else {
		//予約日(hope_date)に販売中の商品(店舗用:shop_flg)を取得
		$product_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM product WHERE status = 2 AND start_date <= '".$_POST['hope_date']."' AND (end_date >= '".$_POST['hope_date']."' OR end_date is null) AND shop_flg = 1 AND del_flg = 0 ") or die('query error'.$GLOBALS['mysqldb']->error);
	}
	$shop_list_where = " and open_date <= '" . $_POST['hope_date'] . "' and (close_date is null or close_date ='' or close_date >='" . $_POST['hope_date'] . "')";
}

// 店舗リスト----------------------------------------------------------------------------
$shop_list = getDatalist("shop", "-", $shop_list_where);

$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

//tax
if($sales['id'] && $sales['reg_date']<"2014-04-01"){
    $tax = 0.05;
    $tax2 = 1.05;
} elseif ($sales['id'] && $sales['reg_date']<"2019-10-01") {
    $tax = 0.08;
    $tax2 = 1.08;
} else {
    $tax_data = Get_Table_Row("basic"," WHERE id = 1");
    $tax =$tax_data['value'];
    $tax2 = 1+$tax_data['value'];
}

//reduced_tax
$reduced_tax_data = Get_Table_Row("basic", " WHERE id = 9");
$reduced_tax = $reduced_tax_data['value'];
$reduced_tax2 = 1 + $reduced_tax_data['value'];

// $product_list[0] = "-";
$product_pretax[0] = "0";
$product_price[0] = "0";
// $product_name[0] = "-";

//商品リスト
while ( $result = $product_sql->fetch_assoc() ) {

	$product_list[$result['id']] = $result['name'];
	//社販ユーザーの場合
	if ($_POST['customer_id'] === $sales_employee_id) {
		//税抜
		$product_pretax[$result['id']] = $result['employee_price'];

		//税込
		if($result['fixed_employee_price'] > 0) {
			$product_price[$result['id']] = $result['fixed_employee_price'];
		} else if($result['reduced_tax_rate_flg'] == 1) {
			$product_price[$result['id']] = round(($result['employee_price'] * $reduced_tax2),0);
		} else  {
			$product_price[$result['id']] = round(($result['employee_price'] * $tax2), 0);
		}
	//一般ユーザーの場合
	} else {
		//税抜
		$product_pretax[$result['id']] = $result['base_price'];

		//税込
		if($result['fixed_base_price'] > 0) {
			$product_price[$result['id']] = $result['fixed_base_price'];
		} else if($result['reduced_tax_rate_flg'] == 1) {
			$product_price[$result['id']] = round(($result['base_price'] * $reduced_tax2),0);
		} else  {
			$product_price[$result['id']] = round(($result['base_price'] * $tax2), 0);
		}
		//税込,8%時代のボディラインジェルは金額固定
		if ($tax == 0.08 && $result['id']==15) {
			$product_price[$result['id']] = 4300;
		}
	}
	$product_name[$result['id']] = $result['name'];
}

$shop_address = str_replace("　", " ", $shop['address']);
list($shop_address1,$shop_address2) = explode(" ", $shop_address);

// pdf用パラメータ登録商品情報
if($product_stocks){
	for($i=0; $i<=(count($product_stocks)-1); $i++){
		$pdf_product_stock.= "&product_name".$i."=".$product_name[$product_stocks[$i]['product_no']]."&fixed_price".$i."=".$product_stocks[$i]['price']."&price".$i."=".($product_stocks[$i]['price']* $product_stocks[$i]['product_count'])."&product_count".$i."=×".$product_stocks[$i]['product_count'];
		$total_price+=intval($product_stocks[$i]['price']* $product_stocks[$i]['product_count']);
	}
	$pdf_product_stock.="&products=".$i;
		// pdf用パラメータ全体
	$pdf_param ="?shop_name=".$shop['name']."&shop_zip=".$shop['zip']."&shop_pref=".$gPref[$shop['pref']]."&shop_address1=".$shop_address1."&shop_address2=".$shop_address2."&shop_tel=".$shop['tel']."&name=".($customer['name'] ?$customer['name'] : $customer['name_kana'] )."&price=".$total_price."&tax=".$tax."&tax2=".$tax2."&discount=".""."&payment=".($sales['option_price']+$sales['option_card'])."&option_name=".""."&option_price=".""."&balance=".""."&pay_date=".$sales['reg_date'].$pdf_product_stock;
}

?>
