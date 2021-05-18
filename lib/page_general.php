<?php
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

session_start();

//アクセス解析
if($page_id)IncrementAccessLog(date('Ymd'), $page_id, $mo_agent, $_SESSION['AD_CODE']); 
//var_dump($page_id);

////$prospect = Get_Table_Row("prospect"," WHERE status=1 order by id DESC limit 1");
//$voice_list = Get_Result_Sql_Array("select * from voice WHERE status=1 order by reg_date DESC,id DESC ");
?>
