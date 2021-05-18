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
include_once( "../../lib/auth.php" );

$table = "sales";

// 店舗リスト------------------------------------------------------------------------
$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0 AND status=2 order by id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$shop_list[0] = "全店舗";
while ( $result = $shop_sql->fetch_assoc() ) {
	$shop_list[$result['id']] = $result['name'];
}

// staff list
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 AND status=2 ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
	$staff_list[$result['id']] = $result['name'];
}

// courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 order by name" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $course_sql->fetch_assoc() ) {
	$course_list[$result['id']] = $result['name'];
	$course_type[$result['id']] = $result['type'];
	$course_new_type[$result['id']] = $result['new_flg']; // 新月額フラグ
	$course_times[$result['id']] = $result['times'];
	$course_length[$result['id']] = $result['length'];
	$course_price[$result['id']] = $result['price'];
}

//------------------------------------------------------------------------------------
$_POST['pay_date']=$_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d");
$_POST['pay_date2']=$_POST['pay_date2'] ? $_POST['pay_date2'] : ($_POST['pay_date'] ? $_POST['pay_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['pay_date2']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['pay_date2']." +1day"));

// 検索条件の設定-------------------------------------------------------------------
$dWhere = "";
if( $_POST['keyword'] != "" ){
	$dWhere .= " AND ( ";
	$dWhere .= "  replace(c.name, '　', '') LIKE '%". str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.name_kana, '　', '') LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or c.no LIKE '%".str_replace("　","",mb_convert_kana($_POST['keyword'],"SKV", "UTF-8") )."%'";
	$dWhere .= " or replace(c.tel, '-', '') LIKE '%".addslashes( str_replace("-","",$_POST['keyword']) )."%'";
	$dWhere .= " or c.mail LIKE '%".addslashes( $_POST['keyword'] )."%'";
	$dWhere .= " ) ";
}

$dWhere .= " AND  s.pay_date>='".$_POST['pay_date']."'";
$dWhere .= " AND  s.pay_date<='".$_POST['pay_date2']."'";
$order = "pay_date";

if($_POST['shop_id']) $dWhere .= " AND  s.shop_id='".$_POST['shop_id'] ."'";

if( $_POST['type'] ) $dWhere .= " AND s.type = '".addslashes($_POST['type'])."'";

// 月額コースID取得
$month_id = implodeArray("course","id"," where del_flg=0 and type=1");

if( $_POST['course']==1 ) $dWhere .= " AND s.course_id in (".$month_id."  )";
if( $_POST['course']==2 ) $dWhere .= " AND s.course_id not in (".$month_id."  )";
if( $_POST['staff_id'] ) $dWhere .= " AND s.staff_id = '".addslashes($_POST['staff_id'])."'";
if( $_POST['ctype'] !=0 ) $dWhere .= " AND c.ctype = '".addslashes($_POST['ctype'])."'";

// データの取得----------------------------------------------------------------------
// 【条件】消化あり、会員タイプ：テスト以外 2016/12/08 add by shimada
$dSql = "SELECT s.*,c.no as no,c.ctype,c.name as name,c.name_kana as name_kana,c.introducer_type,r.length,r.part FROM " . $table . " s left join reservation r on s.reservation_id=r.id,customer c WHERE s.r_times>0 and s.customer_id=c.id AND c.del_flg=0 AND s.del_flg = 0".$dWhere." ORDER BY s.".$order;
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

// csv export----------------------------------------------------------------------
$filename = "remain_list.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("区分,店舗,来店日,経由,会員番号,会員タイプ,名前,名前カナ,購入コース,請求金額,所要時間(H),役務残(ﾊﾟｯｸ),消化回数\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {
		$data['price'] = $data['fixed_price'] - $data['discount'] ;
		$times = $course_times[$data['course_id']] ? $course_times[$data['course_id']] : 1;

		// 割引率の計算 2016/12/16 add by shimada
		if($data['introducer_type']==3){
			// スタッフ紹介
			$rate_intro = 0.2;
		} else if($data['introducer_type']==5){
			// 企業紹介
			$rate_intro = 0.1;
		}else{
			// 紹介なし
			$rate_intro = 0;
		}

		// 単価の種類を定義
		$price_once    = 0;                                                 // 消化単価(初期化)
		$per_price_dis = round($data['price']/$times);                      // 割引単価
		$per_price_adj = $data['price']-($times-1)*$per_price_dis;          // 調整単価
		$per_price     = round($data['fixed_price']*(1-$rate_intro)/$times);// 通常単価

		// コース別 加算値を設定 2016/12/16 add by shimada
		// 旧月額/パック ×1回毎、新月額 ×2回毎
		$course_plus = 1;
		if($course_new_type[$data['course_id']]){
			// 加算値
			$course_plus = 2;
		}

		// 消化単価を計算する 2016/12/16 add by shimada
		if($course_type[$data['course_id']]){
		// 月額処理
			// 割引期間内(割引最終回を含む)
			if( ($data['r_times']-1)*$course_plus < $times && $data['r_times']*$course_plus >= $times){
				// コース回数で偶数・奇数のときの計算
				if($times%2==1){// 奇数
					// 全身:2回分、半身:1回分の振り分け
					if($data['part']==0){
						// 調整単価+通常単価
						$price_once = $per_price_adj+$per_price;
					} else {
						// 調整単価
						$price_once = $per_price_adj;
					}
				} else { // 偶数
					// 全身:2回分、半身:1回分の振り分け
					if($data['part']==0){
						// 調整単価+割引単価
						$price_once = $per_price_adj+$per_price_dis;
					} else {
						// 割引単価(運用上想定なし)
						$price_once = $per_price_dis;
					}
				}
			}
			// 割引期間内+割引期間外(割引最終回は含まない)
			elseif($data['r_times']*$course_plus < $times){
				// 全身:2回分、半身:1回分の振り分け
				if($data['part']==0){
					// 割引単価*$course_plus
					$price_once = $per_price_dis *$course_plus;
				} else {
					// 割引単価
					$price_once = $per_price_dis;
				}
			}
			// 通常の消化
			 else {
			 	// 全身:2回分、半身:1回分の振り分け
			 	if($data['part']==0){
			 		$price_once = $per_price*$course_plus;
			 	} else {
			 		$price_once = $per_price;
			 		//ホットペッパー月額ケース(既存)
			 		if($data['course_id']==70){
			 			$price_once = $course_price['45']*1.08/$course_times['45']; //消費税1.08に固定
			 		}
			 	}
			}
		} else {
		// パック処理
			// 端数処理動作確認後、下記のelse内のコメントアウトを外す 2016/12/26 shimada
			// 消化回数==コース回数が一致したとき調整単価を消化単価とする
			// if($data['r_times']==$times){
			// 	// 調整単価
			// 	$price_once = $per_price_adj;
			// 	// 端数処理動作確認後、使用可能です。 2016/12/16 add by shimada
			// 	// 消化（された）金額
			// 	$price_used =  $per_price_dis * ($times-1)+ $per_price_adj;
			// 	// 役務残
			$price_remain = 0;
			// } else {
			// 割引単価
			$price_once = $per_price_dis;
			// 	// 端数処理動作確認後、使用可能です。 2016/12/16 add by shimada
			// 	// 消化（された）金額
			// 	$price_used =  $price_once * $data['r_times'] ;
			// 役務残(請求金額-消化済単価)
			$price_remain = $data['price'] - $price_used ;
			// }
		}

		// 端数処理動作確認後、下記処理不要。 2016/12/16 add by shimada
		// 消化（された）金額
		$price_used =  $price_once * $data['r_times'] ;

		/* if($course_type[$data['course_id']] && $data['r_times']%2 ){
			$length = 1;
		}else{
			$length = $course_length[$data['course_id']] * 0.5;
		} */

		// 端数処理動作確認後、下記処理不要。 2016/12/16 add by shimada
		// 役務残(請求金額-消化済単価-端数),月額除外
		$price_remain = $course_type[$data['course_id']] ?  0 : ( $data['price'] - $price_used) ;

		//$price_once = round($data['price'] / $course_times[$data['course_id']] , 0);

		$latest_date = $data['latest_date'] == "0000-00-00" ? "" : $data['latest_date'] ;  // 最終消化日

		if($data['type']==2) echo mb_convert_encoding($gResType3[$data['type']],"SJIS-win", "UTF-8")  . ",";
		else echo mb_convert_encoding(($data['rsv_status'] ? $gRsvStatus[$data['rsv_status']] : $gResType3[$data['type']]),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($shop_list[$data['shop_id']],"SJIS-win", "UTF-8")  . ",";
		echo $data['pay_date'] . ",";
		echo mb_convert_encoding($gRoute[$data['route']],"SJIS-win", "UTF-8")  . ",";
		echo $data['no']. ",";
		echo mb_convert_encoding($gCustomerType[$data['ctype']],"SJIS-win", "UTF-8")  . ",";// 会員タイプ 2016/12/08 add by shimada
		echo mb_convert_encoding($data['name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['name_kana'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($course_list[$data['course_id']],"SJIS-win", "UTF-8") . ",";
		echo $data['price']. ",";
		echo ($data['length']*0.5). ",";
		echo $price_remain. ",";
		echo ($course_type[$data['course_id']] ? 0 : $data['r_times']). ",";
		echo "\n";
	}

	// CSV Export Log
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
