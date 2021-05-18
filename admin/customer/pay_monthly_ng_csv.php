<?php 
include_once("../library/customer/pay_monthly_ng.php"); 

//csv export----------------------------------------------------------------------

$filename = "payment_ng_list.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $list ) {
	echo mb_convert_encoding("NGパターン,会員番号,顧客カナ,電話番号,契約ステータス,購入コース,入金金額,支払日,何年,何月分,予約日,契約日\n","SJIS-win", "UTF-8");
	foreach($list as $key => $val){
		if($val['customer_id']){
		$hope_date = Get_Table_Col("reservation","hope_date"," WHERE del_flg=0 AND customer_id=".$val['customer_id']." AND type=2 AND hope_date>='".date('Y-m-d')."' ORDER BY hope_date LIMIT 1");
		}else{
			$hope_date = "";
		}

		echo mb_convert_encoding($val['ng'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($val['no'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($val['name_kana'] ? $val['name_kana'] : ($val['name'] ? $val['name'] : '無名'),"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($val['tel'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($val['status'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($val['course_name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($val['pay_amount'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($val['option_date'],"SJIS-win", "UTF-8")  . ",";
		$option_year = "";
        $option_month = "";
        if($val['option_year']) {
            $option_year = $val['option_year'].'年';
        }
        echo mb_convert_encoding($option_year,"SJIS-win", "UTF-8")  . ",";
        if($val['option_month']) {
            $option_month = $val['option_month'].'月分';
        }
		echo mb_convert_encoding($option_month,"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($hope_date,"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding($val['contract_date'],"SJIS-win", "UTF-8")  . ",";
		echo "\n";
	}
	//CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
