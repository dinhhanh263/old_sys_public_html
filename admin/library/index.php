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

session_start();
session_unset();
session_destroy();
if($_POST['mode']=='login' && $_POST['login_id'] && $_POST['login_pw']){
  if( Get_Table_Row("authority"," WHERE del_flg=0 and login_id = '".addslashes($_POST['login_id'])."' and password='".addslashes($_POST['login_pw'])."'")){
    session_start();
    $_SESSION['user_id'] = $_POST['login_id'];
    $_SESSION['pw'] = $_POST['login_pw'];
    
    if( $_SESSION['login_id']=="ad") header("Location: ./adcode/");       
    else header("Location: ./main/");
    exit();
  }
  $err_msg="ユーザ名やパスワードが正しくありません！";
}
/*
$user_list = $GLOBALS['mysqldb']->query( "SELECT * FROM authority where del_flg=0 and authority< ".LOWEST_AUTH_LEVEL ) or die('query error'.$GLOBALS['mysqldb']->error) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $list = $user_list->fetch_assoc() ) {
  $users[$list['login_id']] = $list['password'];
}

if($_POST['mode']=='login'){
  $login_id= $_POST['login_id'] ;
  $login_pw= $_POST['login_pw'] ;
    foreach($users as $id=>$pw){
        if($id==$login_id && $pw==$login_pw){
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['pw'] = $pw;
            
            header("Location: ./main/");
            exit();
        }
        
    }
    $err_msg="ユーザ名やパスワードが正しくありません！";
}
*/
?>