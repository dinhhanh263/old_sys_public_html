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
define_approot($_SERVER['SCRIPT_FILENAME'],2);
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth3.php" );
include_once( "../library/menu.php" );

$_POST['id'] = $_POST['id'] ? $_POST['id'] : $_GET['id'];
// 詳細を取得----------------------------------------------------------------------------------------
if(  $_POST['id'] != "" ){
	$data = Get_Table_Row("user"," WHERE id = '".addslashes($_POST['id'])."'");
	$adcode = Get_Table_Row("adcode"," WHERE id = '".addslashes($data['adcode'])."'");
}
//プレゼントリスト----------------------------------------------------------------------------------------
$present = $GLOBALS['mysqldb']->query( "SELECT * FROM present" );
$gPresent[0] = "-";
while ( $list = $present->fetch_assoc() ) {
	$gPresent[$list['id']] = $list['name'];
}
$gColumnList['present']['param'] = $gPresent;//再定義

$gItems  = array('inquiry'	  => array( "name" =>'お問い合わせ内容',				"exist_check" => false, "item_name" => true,	"enter_check" => true ,		"type" => "checkbox"),
				 'time'	  		  => array( "name" =>'当院へのご来院は初めてでしょうか？',	"exist_check" => true, "item_name" => true,		"enter_check" => true ),
				 'request01'	  => array( "name" =>'第一希望日',					"exist_check" => true, "item_name" => true,		"enter_check" => false),
				 'request02' 	  => array( "name" =>'第一希望日(日)', 				"exist_check" => true, "item_name" => false,	"enter_check" => false,		"additional" => "　"),
				 'request03'	  => array( "name" =>'第一希望日(時間)',				"exist_check" => true, "item_name" => false,	"enter_check" => true,		"additional" => "～"),
				 'request04' 	  => array( "name" =>'第二希望日',					"exist_check" => true, "item_name" => true,		"enter_check" => false),
				 'request05'	  => array( "name" =>'第二希望日(日)', 				"exist_check" => true, "item_name" => false,	"enter_check" => false,		"additional" => "　"),
				 'request06'	  => array( "name" =>'第二希望日(時間)',				"exist_check" => true, "item_name" => false,	"enter_check" => true,		"additional" => "～"),
				 'mail_address'	  => array( "name" =>'メールアドレス', 					"exist_check" => true, "item_name" => true,		"enter_check" => true),
				 'name' 		  => array( "name" =>'お名前(カタカナ)',				"exist_check" => true, "item_name" => true,		"enter_check" => true),
				 'tel01' 		  => array( "name" =>'電話番号', 					"exist_check" => true, "item_name" => true,		"enter_check" => false,		"additional" => "-"),
				 'tel02' 		  => array( "name" =>'電話番号2', 					"exist_check" => true, "item_name" => false,	"enter_check" => false,		"additional" => "-"),
				 'tel03' 		  => array( "name" =>'電話番号3',					"exist_check" => true, "item_name" => false,	"enter_check" => true),
				 'age' 			  => array( "name" =>'年齢', 						"exist_check" => true, "item_name" => true,		"enter_check" => true,		"additional" => "歳"),
				 'prefecture' 	  => array( "name" =>'お住まいの地域', 				"exist_check" => false, "item_name" => true,	"enter_check" => false),
				 'address' 	  	  => array( "name" =>'市区町村', 					"exist_check" => false, "item_name" => false,	"enter_check" => true),
				 'sex' 			  => array( "name" =>'性別',							"exist_check" => false, "item_name" => true,	"enter_check" => true),
				 'comment' 		  => array( "name" =>'備考（ご相談やお問い合わせ）',		"exist_check" => false, "item_name" => true,	"enter_check" => true),
				 'enquete'	 	  => array( "name" =>'新宿HCCを知ったきっかけ',			"exist_check" => false, "item_name" => true,	"enter_check" => false ,		"type" => "checkbox"),
				  'enquete_other'  => array( "name" =>'新宿HCCを知ったきっかけ（その他）',		"exist_check" => false, "item_name" => false,	"enter_check" => true)
			);
?>