<?php 
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/mens/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth.php" );

$table = "customer";


// 詳細を取得------------------------------------------------------------------------

if( $_POST['id'] != "" )  {
	$data = Get_Table_Row($table," WHERE id = '".addslashes($_POST['id'])."'");
	$shop = Get_Table_Row("shop"," WHERE id = '".addslashes($data['shop_id'])."'");

	//予約リスト
	$contract_p = Get_Table_Array("contract_P","*"," WHERE del_flg=0 and customer_id = '".addslashes($_POST['id'])."' order by contract_date desc, id DESC");
	// パック契約
	$contract = Get_Table_Array("contract","*"," WHERE del_flg=0 and customer_id = '".addslashes($_POST['id'])."' order by contract_date desc, id DESC");
	// 単発契約
	$one_contract = Get_Table_Array("contract","*"," WHERE del_flg=0 and times=1 and customer_id = '".addslashes($_POST['id'])."' order by contract_date desc, id DESC");
	// 契約状況
	$all_contract = Get_Table_Array("contract","*"," WHERE del_flg=0 and customer_id = '".addslashes($_POST['id'])."' order by contract_date desc, id DESC");

	//予約リスト(単発以外)
	$sql = $GLOBALS['mysqldb']->query( "select * from reservation WHERE del_flg = 0 and customer_id = '".addslashes($_POST['id'])."' order by id DESC" );
	//$sql = $GLOBALS['mysqldb']->query( "select * from reservation WHERE del_flg = 0 and customer_id = '".addslashes($_POST['id'])."' order by hope_date DESC, hope_time DESC" );
	if($sql){
		$i = 1;
		//$rsv_html = '<p>予約履歴:</p>';
		while ( $result = $sql->fetch_assoc() ) {
			// 今回予約したコース
			$csql = $GLOBALS['mysqldb']->query( "select * from contract WHERE del_flg = 0 and id in (".addslashes($result['multiple_contract_id']).") order by id DESC" );

				$rsv_html .= '<span class="history_box">';
				$rsv_html .= '<dt>予約日時:</dt><dd><a href="../reservation/edit.php?id='.$result['id'].'&shop_id='.$result['shop_id'].'&hope_date='.$result['hope_date'].'"  target="_blank">'.$result['hope_date'].' '.$gTime2[$result['hope_time']].'</a></dd>';
				$rsv_html .= '<dt>区分:<dd>'.$gResType4[$result['type']].'</dd></dt>';
				if($result['rsv_status'])$rsv_html .= '<dt>予約状況:</dt><dd>'.$gRsvStatus[$result['rsv_status']].'</dd>';
				$rsv_html .= '<dt>来店状況:</dt><dd>'.$gBookStatus[$result['status']].'</dd>';

				// 選べるコース情報
				if($csql){
					$rsv_html .= '<dt>箇所:</dt>';
					$rsv_html .= '<dd><ul class="t_contract">';
					while ( $multiple_result = $csql->fetch_assoc() ) {

						// コース情報
						$course = Get_Table_Row("course", " WHERE del_flg=0 and status=2 and id ='".$multiple_result['course_id']."'");
						$rsv_html .= '<li>'.$course['name'].'</li>';
						// 選べる部位
						if($multiple_result['contract_part']<>""){
							$multiple_result['contract_part'] = explode(',',$multiple_result['contract_part']);
							$rsv_html .= '<span class="t_parts">(';
							foreach ($multiple_result['contract_part'] as $key => $part){
								$rsv_html .= $gContractParts[$part];
								if ($part <> end($multiple_result['contract_part'])) { $rsv_html .= ',';}
							}
							$rsv_html .= ')</span>';
						}
					}
					$rsv_html .= '</ul></dd>';
				}
			$rsv_html .= '</span>';
		}
	}
	//予約リスト(単発)
	$sql = $GLOBALS['mysqldb']->query( "select * from reservation WHERE del_flg = 0 and type=20 and customer_id = '".addslashes($_POST['id'])."' order by id DESC" );
	//$sql = $GLOBALS['mysqldb']->query( "select * from reservation WHERE del_flg = 0 and type=2 and customer_id = '".addslashes($_POST['id'])."' order by id DESC" );
	if($sql){
		$i = 1;
		//$rsv_html = '<p>予約履歴:</p>';
		while ( $result = $sql->fetch_assoc() ) {
			// 今回予約したコース
			$csql = $GLOBALS['mysqldb']->query( "select * from contract WHERE del_flg = 0 and id in (".addslashes($result['multiple_contract_id']).") order by id DESC" );
			//$csql = $GLOBALS['mysqldb']->query( "select * from contract WHERE del_flg = 0 and times = 1 and id in (".addslashes($result['multiple_contract_id']).") order by id DESC" ); // 1回コース(course.type=2廃止されたため)

				$one_rsv_html .= '<span class="history_box">';
				$one_rsv_html .= '<dt>予約日時:</dt><dd><a href="../reservation/edit.php?id='.$result['id'].'&shop_id='.$result['shop_id'].'&hope_date='.$result['hope_date'].'"  target="_blank">'.$result['hope_date'].' '.$gTime2[$result['hope_time']].'</a></dd>';
				$one_rsv_html .= '<dt>区分:<dd>'.$gResType4[$result['type']].'</dd></dt>';
				if($result['rsv_status'])$rsv_html .= '<dt>予約状況:</dt><dd>'.$gRsvStatus[$result['rsv_status']].'</dd>';
				$one_rsv_html .= '<dt>来店状況:</dt><dd>'.$gBookStatus[$result['status']].'</dd>';

				// 選べるコース情報
				if($csql){
					$one_rsv_html .= '<dt>箇所:</dt>';
					$one_rsv_html .= '<dd><ul class="t_contract">';
					while ( $multiple_result = $csql->fetch_assoc() ) {

						// コース情報
						$course = Get_Table_Row("course", " WHERE del_flg=0 and status=2 and id ='".$multiple_result['course_id']."'");
						$one_rsv_html .= '<li>'.$course['name'].'</li>';
						// 選べる部位
						if($multiple_result['contract_part']<>""){
							$multiple_result['contract_part'] = explode(',',$multiple_result['contract_part']);
							$one_rsv_html .= '<span class="t_parts">(';
							foreach ($multiple_result['contract_part'] as $key => $part){
								$one_rsv_html .= $gContractParts[$part];
								if ($part <> end($multiple_result['contract_part'])) { $one_rsv_html .= ',';}
							}
							$one_rsv_html .= ')</span>';
						}
					}
					$one_rsv_html .= '</ul></dd>';
				}
			$one_rsv_html .= '</span>';
		}
	}
}

// 店舗リスト------------------------------------------------------------------------

$shop_list = getDatalist_shop();
// $mensdb = changedb();


// courseリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 ORDER BY id" );
while ( $result2 = $course_sql->fetch_assoc() ) {
	$course_list[$result2['id']] = $result2['name'];
	$course_type[$result2['id']] = $result2['type'];
}

?>
