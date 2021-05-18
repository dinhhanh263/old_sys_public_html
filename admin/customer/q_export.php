<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once $DOC_ROOT . '/lib/function.php';
require_once $DOC_ROOT . '/lib/db.php';
require_once $DOC_ROOT . '/lib/auth.php';

$table = "q_answer";

//店舗リスト------------------------------------------------------------------------
$shop_list = getDatalist("shop");

//スタッフリスト------------------------------------------------------------------------
$staff_sql = $GLOBALS['mysqldb']->query( "select * from staff WHERE del_flg = 0 ".$where_shop." ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
$staff_list[0] = "-";
while ( $result = $staff_sql->fetch_assoc() ) {
  $staff_list[$result['id']] = $result['name'];
}

//検索期間設定------------------------------------------------------------------------------------
$_POST['reg_date']=$_POST['reg_date'] ? $_POST['reg_date'] : ($_POST['reg_date2'] ? $_POST['reg_date2'] : date("2014-02-07"));
$_POST['reg_date2']=$_POST['reg_date2'] ? $_POST['reg_date2'] : date("Y-m-d");

$_POST['reg_date2']=$_POST['reg_date2'] ? $_POST['reg_date2'] : ($_POST['reg_date'] ? $_POST['reg_date'] : date("Y-m-d"));

//検索条件の設定------------------------------------------------------------------------------------
$dWhere .= " AND  reg_date>='".$_POST['reg_date']." 00:00:00'";
$dWhere .= " AND  reg_date<='".$_POST['reg_date2']." 23:59:59'";
if($_POST['search_shop_id'] != 0){
  $dWhere2 = " shop_id='".($_POST['search_shop_id'])."' AND ";
}

//アンケートテーブル情報
$p_table = 'q_pattern';
$q_table = 'q_detail';
$today = date('Y/m/d');

//アンケート情報の取得
$q_pattern =  Get_Table_Row($p_table," WHERE del_flg=0 and status = 2 and id=".$_POST['id']);
$q_name = $q_pattern['name'];
/* アンケート情報から検索条件を追加 */
$dWhere .= " AND  q_id ='".$q_pattern['id']."'";
/* 設問取得 */
$group_count = Get_Result_Sql_Col('SELECT count(distinct group_id) FROM '.$q_table.' WHERE del_flg=0 and q_id = '.$q_pattern['id']);//設問グループの数
$q_group = array();
for($i=1; $i<=$group_count[0]; $i++){/* 設問グループごとに配列を作成 */
  $q_group[] =  Get_Table_Array($q_table,'*',' WHERE del_flg=0 and q_id='.$q_pattern['id'].' and group_id='.$i.' ORDER BY parent_flg DESC,id ASC' );
}
/* postする質問の一覧 */
$post_q = array();
$post_q_name = array();
/* 質問タイプ */
$q_type_list = array(1 =>'radio', 2=>'checkbox', 3=>'text', 4=>'textarea', 5=>'select');
/* 設問作成 */
function make_pull_query($value,$count){
  foreach ($value as $item) {
    if($item['parent_flg'] == 1){/* 親項目かどうかの判定 */
      $item_no = 1;//radio,text,textarea選択肢nameに利用する番号
      $item_count = $item['items_max'];/*回答項目数取得*/
    }else{/* 子項目だった場合 */
    	$post_q_name[] = $item['items_name'];
      /* 設問作成開始 */
        $post_q[] = 'q'.$count.'_'.$item_no;/* postする質問の一覧に追加 */
        $item_no++;
      /* 設問作成終了 */
	  }
	}
	  $result1 .= join (',',$post_q);
	  $result2 .= join (',',$post_q_name);
  return array($result1, $result2, $item_count);
}
/* グループごとに設問を作成 */
$result_all = array();
$g_count = 1;/* 今何番目のグループを処理しているか */
foreach ($q_group as $value) {/* グループごとに回答項目を取得 */
  $result_all[] = make_pull_query($value,$g_count);
  $g_count++;
}
$col_count = 0;
foreach ($result_all as $value) {/* 取得した回答項目を種別に並べ替え */
	$col_names[] = $value[0];//DB物理名
	$col_kana[] = $value[1];//質問項目名
	$col_count = $col_count + $value[2];//質問の総数
}
$col_names = explode(',', join(',',$col_names));
$col_kana = join (',',$col_kana);

// データの取得----------------------------------------------------------------------
$dSql = "SELECT * FROM " . $table . " WHERE del_flg = 0".$dWhere." ORDER BY reg_date DESC ";
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql );
// //csv export----------------------------------------------------------------------
$filename = "q_anser.csv";
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
  echo mb_convert_encoding($q_name,"SJIS-win", "UTF-8")."\n"; // アンケート名取得
	echo mb_convert_encoding("店舗,予約日,予約時間,カウンセリング担当者,施術主担当,施術サブ担当1,施術サブ担当2,CC受付担当,".$col_kana.",登録日,登録時間\n","SJIS-win", "UTF-8");

	while ( $data = $dRtn3->fetch_assoc() ) {
		$rsv = Get_Result_Sql_Row('SELECT shop_id,cstaff_id,tstaff_id,tstaff_sub1_id,tstaff_sub2_id,ccstaff_id,hope_date,hope_time,course_id FROM reservation WHERE '.$dWhere2.' del_flg=0 AND id = '.$data['reservation_id']);
    if($rsv !== ''){
      list($reg_date,$reg_time) = explode(" ",  $data['reg_date']);
  		echo mb_convert_encoding($shop_list[$rsv['shop_id']],"SJIS-win", "UTF-8")  . ",";
  		echo $rsv['hope_date'] . ",";
  		echo $gTime2[$rsv['hope_time']]. ",";
      echo mb_convert_encoding($staff_list[$rsv['cstaff_id']], "SJIS-win", "UTF-8"). ",";
      echo mb_convert_encoding($staff_list[$rsv['tstaff_id']], "SJIS-win", "UTF-8"). ",";
  		echo mb_convert_encoding($staff_list[$rsv['tstaff_sub1_id']], "SJIS-win", "UTF-8"). ",";
  		echo mb_convert_encoding($staff_list[$rsv['tstaff_sub2_id']], "SJIS-win", "UTF-8"). ",";
      echo mb_convert_encoding($staff_list[$rsv['ccstaff_id']], "SJIS-win", "UTF-8"). ",";
  		for($i=0; $i<=$col_count; $i++){
        $text = str_replace(',','、',$data[$col_names[$i]]);
        /* if(substr($text, -1) !== '"'){
          $text .= '"';
        } */
  			 echo mb_convert_encoding($text,"SJIS-win", "UTF-8")  . ",";
  		}
  		echo $reg_date . ",";
  		echo $reg_time . ",";
  		echo "\n";
    }
	}
	//CSV Export Log
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>