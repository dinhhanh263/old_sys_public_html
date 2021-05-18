<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once $DOC_ROOT . '/lib/function.php';
require_once $DOC_ROOT . '/lib/db.php';
require_once $DOC_ROOT . '/lib/auth.php';

$table = "staff";

//目標達成情報------------------------------------------------------------------------
$ymd = str_replace("/", "-", $_POST['ym'])."-01";
$ym2 = date("Y/m", strtotime( $ymd."-2 month"));
$ymd2 = str_replace("/", "-", $ym2)."-01";
$goal = $data = Get_Table_Row("goal"," WHERE del_flg=0 and ym2 = '".addslashes($ym2)."'");

// 検索条件の設定-------------------------------------------------------------------

if( $_POST['shop_id'] ) $dWhere .= " AND  s.shop_id='".($_POST['shop_id'] )."'";
if( $_POST['staff_id'] ) $dWhere .= " AND  s.id='".($_POST['staff_id'] )."'";

// データの取得----------------------------------------------------------------------
$dSql = "SELECT s.*,p.base_salary,p.allowance FROM " . $table . " as s,posi_salary as p WHERE s.del_flg = 0 and s.type=p.position and (s.end_day='0000-00-00' OR s.end_day>='".$ymd2."') and s.type not in(19,21) ".$dWhere." order by s.type,s.id";
$dRtn3 = $GLOBALS['mysqldb']->query( $dSql ) or die('query error'.$GLOBALS['mysqldb']->error);

// 旧月額コースID取得
$old_month_id = implodeArray("course","id"," WHERE del_flg=0 AND type=1 AND new_flg=0");

// カウンセリング手当、施術手当対象
$obj_c = $obj_t = array("8","9","10","11","12","13","15","17","18","22","23","24","30");

//csv export----------------------------------------------------------------------
$filename = "allowance.csv"; 
header("Content-type:text/csv"); 
header("Content-Disposition:attachment;filename=".$filename);

if ( $dRtn3->num_rows >= 1 ) {
	echo mb_convert_encoding("社員番号,ｽﾀｯﾌ名,出勤形態,前月基本給,当月基本給,役職名,役職手当,基本給+役職手当,ｶｳﾝｾﾘﾝｸﾞ手当,施術手当,研修手当,出張手当,ｽﾀｯﾌ紹介,社長賞,目標達成手当,社販分,手当合計,総計\n","SJIS-win", "UTF-8");
	$i = 0;
	$cnt = array();
	while ( $data = $dRtn3->fetch_assoc() ) {
		$counseling_allowance = 0;
		$treatment_allowance = 0;
		
		$allowance = Get_Table_Row("allowance"," WHERE del_flg=0 and staff_id=".$data['id']." and ym = '".addslashes($_POST['ym'])."'");
		$shop_id = $allowance['shop_id'] ? $allowance['shop_id'] : $data['shop_id'];

		$type = $allowance['type'] ? $allowance['type'] :$data['type'] ;

		//ｶｳﾝｾﾘﾝｸﾞ手当
		if($allowance['coun_allowance']){
			$counseling_allowance = $allowance['coun_allowance'];
		//}elseif(in_array($type, $obj_c) && $shop_id<999 ){
		}elseif(in_array($type, $obj_c) ){	
			$counseling_sql =  "select c2.times,count(c1.id) as cnt from contract as c1,course as c2 where c1.del_flg=0 and c1.status=0 and c1.balance=0 and c1.course_id=c2.id and c2.type=0 and c1.staff_id=".$data['id']." and substring(c1.pay_complete_date,1,7)='".str_replace("/","-",$ym2)."' group by c1.course_id";
			$counseling_query = $GLOBALS['mysqldb']->query( $counseling_sql ) or die('query error'.$GLOBALS['mysqldb']->error);
			while ( $result = $counseling_query->fetch_assoc() ) {
				switch ($result['times']){
					case 6:
						$counseling_allowance += $result['cnt'] * 500;
						break;
					case 10:
					case 12:
					case 15:
						$counseling_allowance += $result['cnt'] * 1000;
						break;
					case 18:
						$counseling_allowance += $result['cnt'] * 2000;
						break;
				}
			}
		}
		
		//施術手当
		if($allowance['trea_allowance']){
			$treatment_allowance = $allowance['trea_allowance'];
		}elseif(in_array($type, $obj_t)){

			//　VIPなども考慮して契約が関係なく、90分以上の施術があれば100円施術手当を付与（旧月額除外）,60分→50円
			// 90分以上
			 $treatment_sql1 =  "SELECT count(id) AS cnt FROM reservation WHERE del_flg=0 AND type=2 AND status=11 AND length>=3 AND course_id NOT IN(".$old_month_id.") AND tstaff_id=".$data['id']." AND SUBSTRING(hope_date,1,7)='".str_replace("/","-",$ym2)."' ";


			$treatment_query1 = $GLOBALS['mysqldb']->query( $treatment_sql1 ) or die('query error'.$GLOBALS['mysqldb']->error);
			while ( $result = $treatment_query1->fetch_assoc() ) {
				$treatment_allowance += $result['cnt'] * 100;
			}

			// 60分
			$treatment_sql2 =  "SELECT count(id) AS cnt FROM reservation WHERE del_flg=0 AND type=2 AND status=11 AND ( length=2 OR length>=3 AND course_id IN(".$old_month_id.") )AND tstaff_id=".$data['id']." AND SUBSTRING(hope_date,1,7)='".str_replace("/","-",$ym2)."' ";
			$treatment_query2 = $GLOBALS['mysqldb']->query( $treatment_sql2 ) or die('query error'.$GLOBALS['mysqldb']->error);
			while ( $result = $treatment_query2->fetch_assoc() ) {
				$treatment_allowance += $result['cnt'] * 50;
			}
		}

		//目標達成手当
		if($allowance['achi_allowance']){
			$achi_allowance = $allowance['achi_allowance'];
		}else{
			$achi_allowance = 0;
			$achi_allowance_c = 0;
			$achi_allowance_t = 0;
			$achi_sales_all = 0;
			$churn_all = 0;
			$contract_all = 0;

			$achi_sales=0;
			$achi_churn=0;
			$achi_contract=0;


			$goal_counseling = explode(",",$goal['shop_id']); //売上達成店舗
			$goal_treatment = explode(",",$goal['shop_id2']); //施術達成店舗

			$goal_churn3 = explode(",",$goal['shop_id3']); //3%解約率達成店舗
			$goal_churn5 = explode(",",$goal['shop_id4']); //5%解約率達成店舗
			$goal_churn7 = explode(",",$goal['shop_id5']); //7%解約率達成店舗
			$goal_contract80 = explode(",",$goal['shop_id6']); //80%成約率達成店舗
			$goal_contract75 = explode(",",$goal['shop_id7']); //80%成約率達成店舗



			switch ($data['type']){
				case 2:
				case 3:
					//全店舗売上達成率
					if($goal['sales_all']) $achi_sales_all = 50000;

					//全店舗解約率3%
					if($goal['churn_all']==1) $churn_all = 30000;
					//全店舗解約率5%
					elseif($goal['churn_all']==2) $churn_all = 20000;
					//全店舗解約率7%
					elseif($goal['churn_all']==3) $churn_all = 10000;

					//全店舗成約率80%
					if($goal['contract_all']==1) $contract_all = 30000;
					//全店舗成約率75%
					elseif($goal['contract_all']==2) $contract_all = 20000;

					$achi_allowance_c = $achi_sales_all+$churn_all+$contract_all;

					//歩合MAX:100,000
					if($achi_allowance_c > 100000) $achi_allowance_c = 100000;

					break;
				case 5:
					//全店舗売上達成率
					if($goal['sales_all']) $achi_sales_all = 50000;

					//全店舗解約率3%
					if($goal['churn_all']==1) $churn_all = 20000;
					//全店舗解約率5%
					elseif($goal['churn_all']==2) $churn_all = 10000;
					//全店舗解約率7%
					elseif($goal['churn_all']==3) $churn_all = 5000;

					//全店舗成約率80%
					if($goal['contract_all']==1) $contract_all = 30000;
					//全店舗成約率75%
					elseif($goal['contract_all']==2) $contract_all = 20000;

					$achi_allowance_c = $achi_sales_all+$churn_all+$contract_all;

					//歩合MAX:80,000
					if($achi_allowance_c > 80000) $achi_allowance_c = 80000;

					break;
				case 7:
					//担当店舗売上達成
					if(in_array($shop_id, $goal_counseling) && !empty($goal_counseling)) $achi_sales = 20000;
					//担当店舗施術達成
					if(in_array($shop_id, $goal_treatment) && !empty($goal_treatment)) $achi_allowance_t=20000;

					//担当店舗3%解約率達成
					if(in_array($shop_id, $goal_churn3) && !empty($goal_churn3)) $achi_churn=10000;
					//担当店舗5%解約率達成
					elseif(in_array($shop_id, $goal_churn5) && !empty($goal_churn5)) $achi_churn=5000;

					//担当店舗80%成約率達成
					if(in_array($shop_id, $goal_contract80) && !empty($goal_contract80)) $achi_contract=10000;
					//担当店舗75%成約率達成
					elseif(in_array($shop_id, $goal_contract75) && !empty($goal_contract75)) $achi_contract=5000;

					$achi_allowance_c = $achi_sales+$achi_allowance_t+$achi_churn+$achi_contract;


					break;

				
				case 8:
				case 9:
				case 10:
				case 11:
				case 12:
				case 13:
				case 15:
				case 17:
				case 18:
					if(in_array($shop_id, $goal_counseling)) $achi_allowance_c=5000;
					if(in_array($shop_id, $goal_treatment))  $achi_allowance_t=5000;
					break;

			}
			$achi_allowance = $achi_allowance_c + $achi_allowance_t;
		}

		//手当合計
		$sub_total = $counseling_allowance + $treatment_allowance + $allowance['train_allowance'] + $allowance['trav_allowance'] + $allowance['intro_allowance'] + $allowance['president_award'] + $achi_allowance + $allowance['sales'] ;

		//総計	
		$total = $data['base_salary'] + $data['allowance'] + $sub_total;

		echo $data['code'];
		echo mb_convert_encoding( $data['name'],"SJIS-win", "UTF-8")  . ",";
		echo mb_convert_encoding( ($data['shop_id']==1001 ? "本社(土日祝休)"  : ""),"SJIS-win", "UTF-8")  . ",";
		echo $data['base_salary'] . ",";
		echo $data['base_salary'] . ",";
		echo mb_convert_encoding($gStaffType[$type],"SJIS-win", "UTF-8")  . ",";
		echo ($data['allowance'] ? $data['allowance'] : "") . ",";
		echo ($data['base_salary']+$data['allowance']) . ",";
		echo $counseling_allowance . ",";
		echo $treatment_allowance . ",";
		echo $allowance['train_allowance'] . ",";
		echo $allowance['trav_allowance'] . ",";
		echo $allowance['intro_allowance'] . ",";
		echo $allowance['president_award'] . ",";
		echo $achi_allowance . ",";
		echo $allowance['sales'] . ",";
		echo $sub_total . ",";
		echo $total . ",";

		echo "\n";
	}

	//CSV Export Log 
	setCSVExportLog($_POST['csv_pw'],$filename);
}
?>
