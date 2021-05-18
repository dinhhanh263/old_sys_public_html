<?php
mb_internal_encoding('UTF-8');
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'db.php';
require_once LIB_DIR . 'function.php';
include_once( "../lib/classes/encryption.php" );

if( $_POST['action'] <> "edit" ) include_once( "../lib/auth.php" );
if($authority_level>6){
  session_start();
  session_destroy();
}

$answer_table = 'q_answer';
/* 投稿時処理 start */
if($_POST['action'] === 'edit' ) {
  foreach ($_POST as $key => $value) {
    if(is_array($value)){
      $_POST[$key] = join(',',$value);
    }else if($key !== 'q_id' && $key !== 'reservation_id'){
      $_POST[$key] = mb_convert_kana($value,'R');
      $_POST[$key] = str_replace(',','、',$_POST[$key]);
      // 回答に改行が含まれていた場合、CSV出力時にセルが分割されないよう文字置換する
      $_POST[$key] = '"' . str_replace('"', '""', $_POST[$key]);
      // 改行を文字置換したセルを囲む"を追加する
      $_POST[$key] .= '"';
    }
  }
  $post_array = array('q_id','reservation_id','reg_date','edit_date');
  $post_q_array = explode(',',$_POST['post_q_array']);/* post_q_arrayを「,」区切り文字列にしてから配列化 */
  $post_array = array_merge($post_array , $post_q_array);
  $answer_table = 'q_answer';
  $_POST['reg_date'] = date("Y-m-d H:i:s");
  $_POST['edit_date'] = date("Y-m-d H:i:s");
  $data_ID =  Input_NEW_Data($answer_table,$post_array);
  header('Location: ./thanks.html');
}else if(isset($_POST['action']) && $_POST['action'] == ""){
  $html .= '<dt>投稿エラーが発生しました</dt>';
  $html .= '<dd>恐れ入りますが最初からやり直してください。</dd>';
}
/* 投稿時処理 end */


/* アクセス時エラーチェック start */
  function id_check($data,$data_name){
    if($data !== ''){
      return;
    }else{
      $result = $data_name.'が見つかりませんでした。';
      $result .= 'キレイモカスタマーセンターまでお問合せください。';
      return $result;
    }
  }

  $error_msg = '';
  /* urlから予約ID取得 */
  if (isset($_GET['code']) && $_GET['code'] !== '') {
    $reservation_id = isset($_GET['code'])? $encryption->decode($_GET['code']) : '';
  }else{
    $error_msg .= 'URLをご確認ください。アンケート情報が取得できませんでした。';
    return;
  }
  /* 予約IDチェック */
    $data = Get_Result_Sql_Row('SELECT id as reservation_id,customer_id,hope_date,shop_id FROM reservation WHERE del_flg=0 and id = "'.$reservation_id.'"');
    $error_msg .= id_check($data['reservation_id'],'ご予約用アンケート');
  /* 顧客IDチェック */
    $data2 = Get_Result_Sql_Row('SELECT id FROM customer WHERE del_flg=0 and id = "'.addslashes($data['customer_id']).'"');
    $error_msg .= id_check($data2['id'],'お客様用アンケート');
  /* 回答有無確認 */
    $anser_check = Get_Table_Col($answer_table,'reservation_id',' WHERE del_flg=0 AND reservation_id = '.$data['reservation_id']);
    if($anser_check !== ""){
      $error_msg .= 'こちらのアンケートは回答済となっております。';
    }
  /* 正常形メッセージ作成 */
    $view_date = str_replace('-','.',$data['hope_date']);
    $for_msg = 'ご来店日 '.$view_date;
/* アクセス時エラーチェック end */

/* 出勤スタッフ情報 */
function staff($data,$flg){
  $table = "shift";
  $shift_month = substr($data['hope_date'],0,7);
  $current_day = date("j",strtotime($data['hope_date'])); //月:先頭にゼロをつけない。
  $shop_id = $data['shop_id'];
  $result_array = array();
  /* 検索条件の設定*/
  $dWhere =" WHERE del_flg=0 AND shop_id='".$shop_id."'";
  if($flg == 'today'){
    if($current_day) $dWhere .= " AND day".$current_day." NOT IN(0,4,5,6,7,11,12,37,38)";//休み除外.基本休、希望休、欠勤、有休、忌引、夏季休暇;
    $dWhere .= " AND day".$current_day;
  }
  if($shift_month) $dWhere .= " AND shift_month='".$shift_month."'";
  /* シフトデータの取得 */
  $dSql = "SELECT * FROM " . $table . $dWhere." ORDER BY day".$current_day.",staff_id ";
  $dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);
  /* スタッフリスト */
  $staff_sql = $GLOBALS['mysqldb']->query( "select id,name from staff WHERE del_flg = 0 ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
  $staff_list[0] = "-";
  while ( $result = $staff_sql->fetch_assoc() ) {
    $staff_list[$result['id']] = $result['name'];
  }
  if ( $dRtn3->num_rows >= 1 ) {
    while ( $data = $dRtn3->fetch_assoc() ) {
      // $result_array[] = array('value' => $data['staff_id'], 'name' => $staff_list[$data['staff_id']]);
      // $result_array['name'] .= $staff_list[$data['staff_id']];
      $return_html .= '<option value="'.$staff_list[$data['staff_id']].'">'.$staff_list[$data['staff_id']].'</option>';
    }
    return $return_html;
  }
}

//アンケートテーブル情報
$p_table = 'q_pattern';
$q_table = 'q_detail';
$today = date('Y/m/d');

//アンケート情報の取得
$q_id = $_GET['q_id']; // URLからアンケートid取得
$q_pattern =  Get_Table_Row($p_table," WHERE del_flg=0 and status = 2 and id=".$q_id." and start_date <= '".$today."' and (end_date >= '".$today."' or end_date = '0000-00-00')");
if(empty ($q_pattern)){
  $error_msg = '現在、実施中のアンケートはございません。';
  return;
}else{
  /* 設問取得 */
  $group_max = Get_Result_Sql_Col('SELECT count(distinct group_id) FROM '.$q_table.' WHERE del_flg=0 and q_id = '.$q_pattern['id']);//設問グループの数
  $group_max = $group_max[0];
  $q_group = array();
  for($i=1; $i<=$group_max; $i++){/* 設問グループごとに配列を作成 */
    $q_group[] =  Get_Table_Array($q_table,'*',' WHERE del_flg=0 and q_id='.$q_pattern['id'].' and group_id='.$i.' ORDER BY parent_flg DESC,id ASC' );
  }

  /* 質問タイプ */
  $q_type_list = array(1 =>'radio', 2=>'checkbox', 3=>'text', 4=>'textarea');

  /* postする質問の一覧 */
  $post_q = array();
  /* 設問作成 */
  function make_block($value,$count,$data,$q_type_list){
    foreach ($value as $item) {
      if($item['parent_flg'] == 1){/* 親項目かどうかの判定 */
        $item_no = 1;//radio,text,textarea選択肢nameに利用する番号
        $item_type = $item['input_id'];/* 設問の選択肢タイプ取得 */
        $title = '<dt class="dt_ques">'.$item['items_name'].'</dt>';
        $q_box = '<dd class="ques_sel">';
      }else{/* 子項目だった場合 */
        if($item['required_flg']==1){/* 必須項目かどうかの設定 */
          $required_result = ' required';
        }else{
          $required_result = '';
        }
        $q_column .= '<div class="one_item'.$required_result.'">';
        $q_column .= '<span class="q_title">'.$item['items_name'].'</span>';/* 子項目名 */

        $question_array = Get_Table_Row('q_input',' WHERE del_flg=0 and id='.$item_type);/* 設問の選択肢取得 */
        $question_array = array_filter($question_array);
        $question_type = array_splice($question_array,0,4);/* 質問項目以外を削除 */
        $question_type = $question_type['type'];
        /* 設問作成開始 */
        if($question_type === "1"){ /*radio*/
          $post_q[] = 'q'.$count.'_'.$item_no;/* postする質問の一覧に追加 */
          for($i=1; $i <= count($question_array); $i++){
            $q_column .= '<label class="'.$q_type_list[$question_type].'"><input type="'.$q_type_list[$question_type].'" name="q'.$count.'_'.$item_no.'" value="'.$question_array["choices".$i].'">'.$question_array["choices".$i].'</label>';
          }
          $item_no++;
        }else if($question_type === "2"){ /*checkbox*/
          $post_q[] = 'q'.$count.'_'.$item_no;/* postする質問の一覧に追加 */
          $for_count = count($question_array);
          for($i=1; $i <= $for_count; $i++){
            $q_column .= '<label class="'.$q_type_list[$question_type].'"><input type="'.$q_type_list[$question_type].'" name="q'.$count.'_'.$item_no.'[]" value="'.$question_array["choices".$i].'">'.$question_array["choices".$i].'</label>';
          }
          $item_no++;
        }else if($question_type === "3"){ /*text*/
          $post_q[] = 'q'.$count.'_'.$item_no;/* postする質問の一覧に追加 */
          $q_column .= '<label class="'.$q_type_list[$question_type].'"><input type="'.$q_type_list[$question_type].'" name="q'.$count.'_'.$item_no.'" value=""></label>';
          $item_no++;
        }else if($question_type === "4"){ /*textarea*/
          $post_q[] = 'q'.$count.'_'.$item_no;/* postする質問の一覧に追加 */
          $q_column .= '<label class="'.$q_type_list[$question_type].'"><'.$q_type_list[$question_type].' name="q'.$count.'_'.$item_no.'"></'.$q_type_list[$question_type].'></label>';
          $item_no++;
        }else if($question_type === "5"){ /*select*/
          $post_q[] = 'q'.$count.'_'.$item_no;/* postする質問の一覧に追加 */
          $for_count = count($question_array);
          $q_column .= '<select class="staff_list" name="q'.$count.'_'.$item_no.'"><option value="-">-</option>';
          for($i=1; $i <= $for_count; $i++){
            $q_column .= '<option class="'.$q_type_list[$question_type].'" value="'.$question_array["choices".$i].'">'.$question_array["choices".$i].'</option>';
          }
          $q_column .= '</select>';
          $item_no++;
        }else if($question_type === "100"){/* スタッフ名リスト */
          $post_q[] = 'q'.$count.'_'.$item_no;/* postする質問の一覧に追加 */
          $q_column .= '<select class="staff_list" name="q'.$count.'_'.$item_no.'"><option value="-">-</option>';
          $q_column .= staff($data,$question_array["choices1"]);
          $q_column .= '</select>';
          $item_no++;
        }else{
          $q_column .= '質問の読み込みエラーです。';
        }
        /* 設問作成終了 */
        $q_column .= '</div>';
      }
    }
    $result = $title;
    $result .= $q_box;
    $result .= $q_column;
    $result .= '</dd>';
    $result .= '<input type="hidden" name="post_q_array[]" value="'.join (',',$post_q).'">';
    return $result;
  }

  /* グループごとに設問を作成 */
  $g_count = 1;/* 今何番目のグループを処理しているか */
  foreach ($q_group as $value) {
    $html .= make_block($value,$g_count,$data,$q_type_list);
    $g_count++;
  }
}

 ?>