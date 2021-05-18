<?php
if(empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
}else $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once $DOC_ROOT . '/config/config.php';
require_once $DOC_ROOT . '/lib/function.php';
require_once $DOC_ROOT . '/lib/db.php';
require_once $DOC_ROOT . '/lib/auth.php';

$table = "product_stock";

//検索期間設定------------------------------------------------------------------------------------
$_POST['pay_date']=$_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d");
$_POST['pay_date2']=$_POST['pay_date2'] ? $_POST['pay_date2'] : ($_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d"));

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist("shop");

// 商品リスト---------------------------------------------------------------------------------
$product_sql = $GLOBALS['mysqldb']->query( "select * from product WHERE status = 2 AND del_flg = 0" ) or die('query error'.$GLOBALS['mysqldb']->error);
while (  $result = $product_sql->fetch_assoc() ) {
  $product_list[$result['id']] = $result['name'];
  //税込
  $product_price[] = round(($result['base_price'] * $tax2),0);
  $product_name[] = $result['name'];
}

// 検索条件設定---------------------------------------------------------------------------------
//売上店舗
$dWhere = '';
$dGroup = '';
if($_POST['shop_id'] > 0){
  $dWhere .= " AND  ps.shop_id = '".$_POST['shop_id']."' ";
}
// 区分
if($_POST['use_status'] > 0){
  $dWhere .= " AND  ps.use_status = '".$_POST['use_status']."' ";
}
// 商品
if($_POST['product_no'] > 0){
  $dWhere .= " AND ps.product_no = '".$_POST['product_no']."' ";
}
//期間
$dWhere .= " AND ps.pay_date BETWEEN '".$_POST['pay_date']."' AND '".$_POST['pay_date2']."' ";

// データの取得----------------------------------------------------------------------
$dSql = "SELECT ps.shop_id as sales_shop,c.no, p.name as product_name, ps.product_count, (ps.price*ps.product_count) as sum_price , ps.price , ps.use_status, ps.pay_date, st.name , st.shop_id ";
$dSql .= "FROM " . $table . " as ps, sales as s, staff as st, product as p, customer as c ";
$dSql .= "WHERE ps.del_flg = 0 AND s.del_flg = 0 AND st.del_flg = 0 AND p.del_flg = 0 AND c.del_flg = 0 AND st.status = 2 AND st.id=ps.staff_id AND ps.sales_id = s.id  AND ps.product_no = p.id AND ps.customer_id=c.id ".$dWhere;
$dSql .= "ORDER BY ps.pay_date,ps.reg_date ";

$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

// //csv export----------------------------------------------------------------------

$filename = "product_sales.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
  echo mb_convert_encoding("販売用途,支払日,店舗名,会員ID,商品,定価,販売個数,販売金額,スタッフ,所属店舗\n","SJIS-win", "UTF-8");
  while ( $data = $dRtn3->fetch_assoc() ) {
      echo $data['use_status'] . ",";
      echo $data['pay_date'] . ",";
      echo mb_convert_encoding($shop_list[$data['sales_shop']],"SJIS-win", "UTF-8")  . ",";
      echo $data['no'] . ",";
      echo mb_convert_encoding($data['product_name'],"SJIS-win", "UTF-8")  . ",";
      echo $data['price'] . ",";
      echo $data['product_count'] . ",";
      echo $data['sum_price'] . ",";
      echo mb_convert_encoding($data['name'],"SJIS-win", "UTF-8")  . ",";
      echo mb_convert_encoding($shop_list[$data['shop_id']],"SJIS-win", "UTF-8")  . ",";
      echo "\n";
    }
  }
  //CSV Export Log
  setCSVExportLog($_POST['csv_pw'],$filename);

?>


