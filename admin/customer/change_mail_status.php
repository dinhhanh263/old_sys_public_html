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

$post_mail_status = $_POST['mail_status'];
header('Content-type: text/plain; charset= UTF-8');
if (isset($post_mail_status) && is_numeric($post_mail_status) && ($post_mail_status >= 0 || $post_mail_status <= 7) && $_POST['customer_id']) {
	$customer_id = h($_POST['customer_id']);
	$_POST['edit_date'] = date('Y-m-d H:i:s');

	$customer_field = array("mail_status","edit_date");
	$customer_id = Update_Data("customer", $customer_field, $customer_id);
	echo $customer_id;
} else {
	header("Location: /admin/main/");
}
