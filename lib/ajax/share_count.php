<?php
  header("Content-type: text/plain; charset=UTF-8");

$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'db.php';
  include_once( "../lib/member.php" );
  require_once LIB_DIR . 'function.php';

  if ( isset($_POST['myCode']) && isset($_POST['eventName']) ) {

    share_count($_POST['myCode'], $_POST['eventName']);

  } else {
    header("location: https://kireimo.jp/");
  }

  function share_count($myCode, $eventName) {

    if ( isset($myCode) && isset($eventName) ) {

      $customerInfo = Get_Table_Row("customer"," WHERE del_flg=0 and no = '" .  $GLOBALS['mysqldb']->real_escape_string($myCode) . "'");
      
      if ( isset($customerInfo) && $customerInfo !== false ) {

        $shareType = 0;

        switch($eventName) {
          case 'shareToFacebook': $shareType = 1; break;
          case 'shareToTwitter' : $shareType = 2; break;
          case 'shareToLine'    : $shareType = 3; break;
          case 'shareToMail'    : $shareType = 4; break;
          case 'shareToCopy'    : $shareType = 5; break;
          default:
        }
        
        if ( preg_match("/^[1-5]$/", $shareType) ) {
          $shareCountInsertSql = 'INSERT INTO `share_count` (';
          $shareCountInsertSql.= ' `customer_id`, `share_type`, `reg_date`, `edit_date`) VALUES (';
          $shareCountInsertSql.= $customerInfo['id'] . ', ' . $shareType . ', now(), now()' ;
          $shareCountInsertSql.= ');';
          $rtn = $GLOBALS['mysqldb']->query($shareCountInsertSql) or die('query error'.$GLOBALS['mysqldb']->error);
          exit();

        } else {
          '有効ではないイベント名';
        }
      } else {
        '有効ではない会員番号';
      }
    }

    return false;

  }

exit;