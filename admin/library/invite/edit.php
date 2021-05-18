<?php
session_start();

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

// 権限設定------------------------------------------------------------------------
if($authority_level >= 2 && $authority_level <= 7 || $authority_level == 22){ //店長権限かスタッフ（契約）権限
  $authority_view = 'cc_staff';
}else if($authority_level < 2){ // 本社権限以上
  $authority_view = 'vielis';
}else{
  return;
}

$table = "introducer";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  // introducerテーブル取得
  $sql = "SELECT * FROM " . $table . " WHERE del_flg = 0 AND id = " . $GLOBALS['mysqldb']->real_escape_string($_GET['id']) . ";";
  $num_rows = $GLOBALS['mysqldb']->query($sql)->num_rows;
  if ($num_rows == 0) {
    header( "Location: index.php" );
  }

  $excute = $GLOBALS['mysqldb']->query($sql) or die('query error' . $GLOBALS['mysqldb']->error);

  while ( $row = $excute->fetch_assoc()) {
    $data = $row;
    // 紹介元


    $data['join_moto_customer'] = Get_Table_Row("customer"," WHERE del_flg = 0 AND id = '" . $GLOBALS['mysqldb']->real_escape_string($row['customer_id']) . "'");
    $data['join_moto_contract'] = Get_Table_Row("contract"," WHERE del_flg = 0 AND customer_id = '" . $GLOBALS['mysqldb']->real_escape_string($row['customer_id']) . "' order by contract_date desc, id DESC");
    $data['join_moto_bank'] = Get_Table_Row("bank"," WHERE del_flg = 0 AND customer_id = '" . $GLOBALS['mysqldb']->real_escape_string($row['customer_id']) . "'");

    // 紹介先
    $data['join_saki_customer'] = Get_Table_Row("customer"," WHERE del_flg = 0 AND id = '" . $GLOBALS['mysqldb']->real_escape_string($row['introducer_customer_id']) . "'");
    $data['join_saki_contract'] = Get_Table_Row("contract"," WHERE del_flg = 0 AND customer_id = '" . $GLOBALS['mysqldb']->real_escape_string($row['introducer_customer_id']) . "'");
  }

  $rsv_html = "";
  if ( $data['join_saki_customer']['id'] != null ) {
    $rsvSql = "SELECT * FROM reservation WHERE del_flg = 0 AND customer_id = " . $GLOBALS['mysqldb']->real_escape_string($data['join_saki_customer']['id']) . " ORDER BY id DESC";
    $rsvExcute = $GLOBALS['mysqldb']->query($rsvSql) or die('query error'.$GLOBALS['mysqldb']->error);
    while ( $row = $rsvExcute->fetch_assoc() ) {
      $params = 'reservation_id=' . $row['id'] ;
      $rsv_html .= '<p>予約日時:&nbsp;&nbsp;<a href="/admin/reservation/edit.php?' . $params . '" target="_blank">' . $row['hope_date'].' ' . $gTime2[$result['hope_time']] . '</a></p>';
      $rsv_html .= '<p>区&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;分:&nbsp;&nbsp;' . $gResType4[$row['type']] . '</p>';
      if ($row['rsv_status']) {
        $rsv_html .= '<p>予約状況:&nbsp;&nbsp;' . $gRsvStatus[$row['rsv_status']] . '</p>';
      }
      $rsv_html .= '<p>来店状況:&nbsp;&nbsp;'.$gBookStatus[$row['status']].'</p>';
      $rsv_html .= '<div class="lines-dotted-short"></div>';
    }
  }
} else {
  header( "Location: index.php" );
}
// ラベル------------------------------------------------------------------------

// ステータスリスト
$status_list = array(
  0 => '契約中',
  1 => '契約終了',
  2 => 'クーリングオフ',
  3 => '途中解約',
  4 => 'プラン変更',
  5 => 'ローン取消',
  6 => '自動解約',
  7 => '契約待ち',
  8 => '返金保証回数終了',
);

// コースリスト
$course_sql = $GLOBALS['mysqldb']->query( "select * from course WHERE del_flg = 0 AND status=2 ORDER BY id" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $course_sql->fetch_assoc() ) {
  $course_list[$result['id']] = $result['name'];
  $course_type[$result['id']] = $result['type'];
}

// 店舗リスト
$shop_sql = $GLOBALS['mysqldb']->query( "select * from shop WHERE del_flg = 0  AND status=2 order by id" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $shop_sql->fetch_assoc() ) {
	$shop_list[$result['id']] = $result['name'];
	$shop_code[$result['id']] = $result['code'];
}

// 返金額リスト
$refund_sql = $GLOBALS['mysqldb']->query( "SELECT * FROM refund WHERE del_flg = 0" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result3 = $refund_sql->fetch_assoc() ) {
	$refund_list[$result3['cource_id']] = $result3['money'];
}


// 編集
if( $_POST['action'] == "edit" ) {

  // エラーメッセージ格納用
  $_SESSION['error'] = array();

  // 入力チェック > 備考
  if ( mb_strlen($_POST['remarks']) > 1000 )
    $_SESSION['error']['remarks'] = '※備考は、1000文字以内で入力してください。';

  // 電話番号整形
  if ( !empty($_POST['tel']) ) {
    if ( sepalate_tel($_POST['tel']) == null || sepalate_tel($_POST['tel']) == 0 ) {
      $_SESSION['error']['tel'] = '正しい電話番号を入力してください。';
    } else {
      $_POST['tel'] = sepalate_tel($_POST['tel']);
    }
  }


  // 入力チェック
  if ( $_POST['mail'] && !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['mail']) ) {
    $_SESSION['error']['mail'] = '正しいメールアドレスを入力してください。';
  }

  // 入力チェック > 返金日
  if ( $_POST['refund_date'] == '0000-00-00' )
    $_POST['refund_date'] = NULL;
  if ( !empty($_POST['refund_date']) ) {
    list($y, $m, $d) = explode('-', $_POST['refund_date']);
    if ( !checkdate($m, $d, $y) && !preg_match("/^([1-9][0-9]{3})-([1-9]{1}|1[0-2]{1})-([1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/", $_POST['refund_date']) ) {
      $_SESSION['error']['refund_date'] = '正しい返金日を入力してください。';
    }
  }

  // 入力チェック > 申請受付連絡日
  if ( $_POST['refund_request'] == '0000-00-00' )
    $_POST['refund_request'] = NULL;
  if ( !empty($_POST['refund_request']) ) {
    list($y, $m, $d) = explode('-', $_POST['refund_request']);
    if ( !checkdate($m, $d, $y) && !preg_match("/^([1-9][0-9]{3})-([1-9]{1}|1[0-2]{1})-([1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/", $_POST['refund_date']) ) {
      $_SESSION['error']['refund_request'] = '正しい申請受付連絡日を入力してください。';
    }
  }

  // 入力チェック > 返金連絡日
  if ( $_POST['refund_contact'] == '0000-00-00' )
    $_POST['refund_contact'] = NULL;
  if ( !empty($_POST['refund_contact']) ) {
    list($y, $m, $d) = explode('-', $_POST['refund_contact']);
    if ( !checkdate($m, $d, $y) && !preg_match("/^([1-9][0-9]{3})-([1-9]{1}|1[0-2]{1})-([1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/", $_POST['refund_date']) ) {
      $_SESSION['error']['refund_contact'] = '正しい返金連絡日を入力してください。';
    }
  }

  // 口座情報の入力が１つでもあればバリデーションする
  $valid_flg = false;
  if (!empty($_POST['bank_name']))         $valid_flg = true ;
  if (!empty($_POST['bank_branch']))       $valid_flg = true ;
  if (!empty($_POST['bank_account_type'])) $valid_flg = true ;
  if (!empty($_POST['bank_account_no']))   $valid_flg = true ;
  if (!empty($_POST['bank_account_name'])) $valid_flg = true ;
  if ($_POST['bank_status'] !== null)      $valid_flg = true ;

  if ($valid_flg) {

    // 入力チェック > 銀行名
    if ( empty($_POST['bank_name']) ) {
      $_SESSION['error']['bank_name'] = '銀行名を入力してください。';
    } else if ( mb_strlen($_POST['bank_name']) > 64 ) {
      $_SESSION['error']['bank_name'] = '銀行名は64文字以下で入力してください。';
    }

    // 入力チェック > 支店名：
    if ( empty($_POST['bank_branch']) ) {
      $_SESSION['error']['bank_branch'] = '支店名を入力してください。';
    } else if ( mb_strlen($_POST['bank_branch']) > 64 ) {
      $_SESSION['error']['bank_branch'] = '支店名は64文字以下で入力してください。';
    }

    // 入力チェック > 口座種別
    if ( empty($_POST['bank_account_type']) ) {
      $_SESSION['error']['bank_account_type'] = '口座種別を選択してください。';
    } else if ( !preg_match("/^[1-3]$/", $_POST['bank_account_type']) ) {
      $_SESSION['error']['bank_account_type'] = '口座種別の値が不正です。';
    }

    // 入力チェック > 口座番号
    if ( empty($_POST['bank_account_no']) ) {
      $_SESSION['error']['bank_account_no'] = '口座番号を入力してください。';
    } else if ( !preg_match("/^[0-9]+$/", $_POST['bank_account_no']) ) {
      $_SESSION['error']['bank_account_no'] = '口座番号の値が不正です。';
    } else if ( strlen($_POST['bank_account_no']) != 7 ) {
      $_SESSION['error']['bank_account_no'] = '口座番号は7桁で入力してください。';
    }

    // 入力チェック > 口座名義
    if ( empty($_POST['bank_account_name']) ) {
      $_SESSION['error']['bank_account_name'] = '口座名義を入力してください。';
    } else if ( !preg_match("/^[ａ-ｚＡ-Ｚ０-９ァ-ヶー（）．－／　]+$/u", $_POST['bank_account_name']) ) {
      $_SESSION['error']['bank_account_name'] = '口座名義は全角カナ, 全角数字, 全角英字, 全角記号（（ ） ． ー ／ 全角スペース のみ）で入力してください。';
    } else if ( mb_strlen($_POST['bank_account_name']) > 64 ) {
      $_SESSION['error']['bank_account_name'] = '口座名義は64文字以下で入力してください。';
    }

    // 入力チェック > 口座状況
    if ( $_POST['bank_status'] == null || $_POST['bank_status'] == ""  ) {
      $_SESSION['error']['bank_status'] = '口座状況を選択してください。';
    } else if ( !preg_match("/^[0-2]$/", $_POST['bank_status']) ) {
      $_SESSION['error']['bank_status'] = '口座状況の値が不正です。';
    }

  }


  // 入力エラーチェック
  if ( empty($_SESSION['error']) ) {

    // トランザクション開始
    $GLOBALS['mysqldb']->query("SET AUTOCOMMIT = 0");
    $GLOBALS['mysqldb']->query("begin");

    // 紹介元顧客情報 更新
    $motoCustomerUpdateSql = 'UPDATE `customer` SET ';
    $motoCustomerUpdateSql.= '`tel` = "' . $GLOBALS['mysqldb']->real_escape_string($_POST['tel']) . '", ';
    $motoCustomerUpdateSql.= '`mail` = "' . $GLOBALS['mysqldb']->real_escape_string($_POST['mail']) . '" ';
    $motoCustomerUpdateSql.= 'WHERE `id` = ' . $GLOBALS['mysqldb']->real_escape_string($data['join_moto_customer']['id']) . ';';
    $motoCustomerExcute = $GLOBALS['mysqldb']->query($motoCustomerUpdateSql) or die ('query error' . $GLOBALS['mysqldb']->error);

    // 紹介者情報 更新
    $introducerUpdateSql = 'UPDATE `introducer` SET ';
    $introducerUpdateSql.= '`refund_request` = "' . $GLOBALS['mysqldb']->real_escape_string($_POST['refund_request']) . '", ';
    $introducerUpdateSql.= '`refund_date` = "' . $GLOBALS['mysqldb']->real_escape_string($_POST['refund_date']) . '", ';
    $introducerUpdateSql.= '`refund_contact` = "' . $GLOBALS['mysqldb']->real_escape_string($_POST['refund_contact']) . '" ';
    $introducerUpdateSql.= 'WHERE `id` = ' . $GLOBALS['mysqldb']->real_escape_string($data['id']) .  ';';
    $introducerExcute = $GLOBALS['mysqldb']->query($introducerUpdateSql) or die ('query error' . $GLOBALS['mysqldb']->error);

    // 紹介元口座情報情報 挿入OR更新
    if ($valid_flg) {
      if ($data['join_moto_bank'] == false) {
        $bankSql = 'INSERT INTO `bank` (`customer_id`, `bank_name`, `bank_branch`, `bank_account_type`, `bank_account_no`, `bank_account_name`, `reg_date`, `edit_date`, `del_flg`, `status`) VALUES (';
        $bankSql.= $GLOBALS['mysqldb']->real_escape_string($data['join_moto_customer']['id']) . ', ' ;
        $bankSql.= '"' . $GLOBALS['mysqldb']->real_escape_string($_POST['bank_name']) . '", ' ;
        $bankSql.= '"' . $GLOBALS['mysqldb']->real_escape_string($_POST['bank_branch']) . '", ' ;
        $bankSql.= $GLOBALS['mysqldb']->real_escape_string($_POST['bank_account_type']) . ', ' ;
        $bankSql.= '"' . $GLOBALS['mysqldb']->real_escape_string($_POST['bank_account_no']) . '", ' ;
        $bankSql.= '"' . $GLOBALS['mysqldb']->real_escape_string($_POST['bank_account_name']) . '", ' ;
        $bankSql.= 'now(),' ;
        $bankSql.= 'now(),' ;
        $bankSql.= '0,' ;
        $bankSql.= $GLOBALS['mysqldb']->real_escape_string($_POST['bank_status']) ;
        $bankSql.= ');';
      } else {
        $bankSql = 'UPDATE `bank` SET ';
        $bankSql.= '`bank_name` = "' . $GLOBALS['mysqldb']->real_escape_string($_POST['bank_name']) . '", ' ;
        $bankSql.= '`bank_branch` = "' . $GLOBALS['mysqldb']->real_escape_string($_POST['bank_branch']) . '", ' ;
        $bankSql.= '`bank_account_type` = "' . $GLOBALS['mysqldb']->real_escape_string($_POST['bank_account_type']) . '", ' ;
        $bankSql.= '`bank_account_no` = "' . $GLOBALS['mysqldb']->real_escape_string($_POST['bank_account_no']) . '", ' ;
        $bankSql.= '`bank_account_name` = "' . $GLOBALS['mysqldb']->real_escape_string($_POST['bank_account_name']) . '", ' ;
        $bankSql.= '`status` = "' . $GLOBALS['mysqldb']->real_escape_string($_POST['bank_status']) . '" ' ;
        $bankSql.= 'WHERE `id` = ' . $GLOBALS['mysqldb']->real_escape_string($data['join_moto_bank']['id']) . ';';
      }
      $bankExcute = $GLOBALS['mysqldb']->query($bankSql) or die ('query error' . $GLOBALS['mysqldb']->error);
    } else {
      $bankExcute = true;
    }

    if ( $motoCustomerExcute && $introducerExcute && $bankExcute ) {
      $GLOBALS['mysqldb']->query("commit");
      $_SESSION['success']['commit'] = 'データを更新しました。';
      header( "Location: index.php");
    } else {
      $GLOBALS['mysqldb']->query("rollback");
      $_SESSION['error']['rollback'] = 'データの更新に失敗しました。';
      header( "Location: index.php");
    }

  }

}