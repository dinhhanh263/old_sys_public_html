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
  include_once( "../../lib/classes/encryption.php" );

// $answer_table = 'q_answer';

// $_POST['reg_date']=$_POST['reg_date'] ? $_POST['reg_date'] : ($_POST['reg_date2'] ? $_POST['reg_date2'] : date("2014-02-07"));
// $_POST['reg_date2']=$_POST['reg_date2'] ? $_POST['reg_date2'] : date("Y-m-d");

// $_POST['reg_date2']=$_POST['reg_date2'] ? $_POST['reg_date2'] : ($_POST['reg_date'] ? $_POST['reg_date'] : date("Y-m-d"));

// $pre_date = date("Y-m-d", strtotime($_POST['reg_date2']." -1day"));
// $next_date = date("Y-m-d", strtotime($_POST['reg_date2']." +1day"));

//shop list
// $shop_list = getDatalist("shop");
// $shop_lists = getDatalistArray3("shop","area", $gShops_priority);

// 表示ページ設定------------------------------------------------------------------------

// $dStart = 0;
// $dLine_Max = $_POST['line_max']= $_POST['line_max'] ? $_POST['line_max'] : 20;
// if( is_numeric( $_POST['start'] ) && $_POST['start'] >= 0 && $_POST['start'] < 99999 ){
//   $dStart = $_POST['start'];
// }

//アンケートテーブル情報
$p_table = 'q_pattern';
$q_table = 'q_detail';
$today = date('Y/m/d');

//アンケート情報の取得
$q_pattern =  Get_Table_Row($p_table," WHERE del_flg=0 and status = 2 and id=".$_GET['id']);
if(empty ($q_pattern)){
  $html = '<div>アンケート情報が見つかりませんでした。</div>';
  return;
}else{
  /* 設問取得 */
  $group_max = Get_Result_Sql_Col('SELECT max(group_id) FROM '.$q_table.' WHERE del_flg=0 and q_id = '.$q_pattern['id']);//設問グループの数
  $q_group = array();
  for($i=1; $i<=$group_max; $i++){/* 設問グループごとに配列を作成 */
    $q_group[] =  Get_Table_Array($q_table,'*',' WHERE del_flg=0 and q_id='.$q_pattern['id'].' and group_id='.$i.' ORDER BY parent_flg DESC,id ASC' );
  }

  /* 質問タイプ */
  $q_type_list = array(1 =>'radio', 2=>'checkbox', 3=>'text', 4=>'textarea');

  /* postする質問の一覧 */
  $post_q = array();

  /* 設問作成 */
  function make_block($value,$count,$q_type_list){
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
    $html .= make_block($value,$g_count,$q_type_list);
    $g_count++;
  }
}

 ?>