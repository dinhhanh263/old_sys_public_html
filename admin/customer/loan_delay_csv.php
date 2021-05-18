<?php 
include_once("../library/customer/loan_delay.php"); 

//csv export----------------------------------------------------------------------

$filename = "loan_delay.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3 ) {
	echo mb_convert_encoding("区分,ローン会社,会員番号,顧客名,電話番号,購入コース,請求金額,入金金額（税込）,売掛金,契約日,登録日時\n","SJIS-win", "UTF-8");
	while ( $data = mysql_fetch_assoc($dRtn3) ) {
		echo mb_convert_encoding($gContractStatus[$data['status']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($gLoanNG[$data['loan_delay_flg']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['no'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['name_kana'] ? $data['name_kana'] : ($data['name'] ? $data['name'] : '無名'),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['tel'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($course_list[$data['course_id']],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['price'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['payment'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['balance'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['contract_date'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($data['reg_date'],"SJIS-win", "UTF-8")  . ",";
		echo "\n";
	}
	//CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
