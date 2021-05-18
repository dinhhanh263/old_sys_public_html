<?php include_once("../library/allowance/index.php");?>
<?php include_once("../include/header_menu.html");?>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.ympicker.js"></script>
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.all.css" />
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.datepicker.css" />

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox() 
})
</script>

<style type="text/css">
#hoge {
    width:100%;
    position:absolute;
    top:160px;
    left:0;
}
#hogeInner {
    text-align: right;
    margin:0 0;
    padding: 0 23px;
}
</style>
<script type="text/javascript">
function csv_export () {

      document.search.action = "csv_export.php";
	  document.search.submit();

	  return true;

}
</script>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			スタッフ手当
			<span style="margin-left:20px;">
				<input style="width:50px;height:21px;" name="ym" type="text"  class="ympicker" value="<?php echo $_POST['ym'];?>" readonly  />
				<select name="shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] );?></select>

				<select name="staff_id"  style="height:25px;"><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $_POST['staff_id'] ? $_POST['staff_id'] : $data['staff_id'],getDatalist5("shop",$_POST['shop_id']));?></select>
				<input type="hidden" name="mode" value="display" />
				<input type="submit" value=" 表示 "  style="height:25px;" />
			</span>
			<?php if($authority_level<=1){?>
			<span style="float:right; margin-right:25px;">
				<input type='button' value='　CSV　' onclick='csv_export();' style="height:25px;" />
				<input type='hidden' name="csv_pw" value="" />
			</span>
			<span style="float:right; margin-right:25px;">
				<a rel="facebox" href="goal.php?ym=<?php echo $_POST['ym'];?>"　onclick="return confirm('目標達成設定をしましか？')" class="side"　>目標達成設定</a>
			</span>
			<?php  }?>
		</h1>
		</form>
	</div>
	<!-- end page-heading -->
	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<th rowspan="3" class="sized"><img src="../images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>
		<th rowspan="3" class="sized"><img src="../images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
			<!--  start table-content  -->
			<div id="table-content">
				<!--  start product-table ..................................................................................... -->

				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
					※　<?php echo $ym2?>月度結果　　<?php echo $_POST['ym'] ?>月支給分
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">ｽﾀｯﾌ名</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">出勤形態</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1" title="(実)前前月基本給"><a href=""><font size="-2">前月基本給</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1" title="(実)前月基本給"><a href=""><font size="-2">当月基本給</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">前月役職名</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">前月役職手当</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">基本給+役職手当</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">ｶｳﾝｾﾘﾝｸﾞ手当</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">施術手当</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">研修手当</font></a>/th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">出張手当</font></a>/th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">ｽﾀｯﾌ紹介</font></a></th>

					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">社長賞</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">目標達成手当</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">社販分</font></a></th>
					<th class="table-header-repeat line-left minwidth-1" title="役職手当以外の諸手当合計"><a href=""><font size="-2">手当合計</font></a></th>
					<th class="table-header-repeat line-left minwidth-1" title="基本給＋役職手当＋手当合計"><a href=""><font size="-2">総計</font></a></th>
					<th class="table-header-repeat line-left minwidth-1" colspan="2"><a href=""><font size="-2">オプション</font></a></th>

				</tr>
<?php
if ( $dRtn3 ) {
	$i = 0;
	$cnt = array();
	while ( $data = $dRtn3->fetch_assoc() ) {
		$counseling_allowance = 0;
		$treatment_allowance = 0;
		
		$allowance = Get_Table_Row("allowance"," WHERE del_flg=0 AND staff_id=".$data['id']." AND ym = '".addslashes($ym2)."'");

		$base_salary = $allowance['base_salary'] ? $allowance['base_salary'] : $data['base_salary'];
		$base_allowance = $allowance['type'] ? $posi_salary_list[$allowance['type']] : $data['allowance'];

		$shop_id = $allowance['shop_id'] ? $allowance['shop_id'] : $data['shop_id'];

		$type = $allowance['type'] ? $allowance['type'] :$data['type'] ;

		// ｶｳﾝｾﾘﾝｸﾞ手当
		if($allowance['coun_allowance']){
			$counseling_allowance = $allowance['coun_allowance'];
		//}elseif(in_array($type, $obj_c) && $shop_id<999 ){
		}elseif(in_array($type, $obj_c)){	
			$counseling_sql =  "SELECT u.times,count(t.id) AS cnt FROM contract AS t,course AS u WHERE t.del_flg=0 AND t.status=0 AND t.balance=0 AND t.course_id=u.id AND u.type=0 AND t.staff_id=".$data['id']." AND substring(t.pay_complete_date,1,7)='".str_replace("/","-",$ym2)."' group by t.course_id";
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
		
		// 施術手当
		if($allowance['trea_allowance']){
			$treatment_allowance = $allowance['trea_allowance'];
		}elseif(in_array($type, $obj_t) ){	
					
			//　VIPなども考慮して契約が関係なく、120分以上の施術があれば100円施術手当を付与,1時間以上2時間未満の施術→50円
			// 120分以上
			$treatment_sql1 =  "SELECT count(id) AS cnt FROM reservation WHERE del_flg=0 AND type=2 AND status=11 AND length>=4 AND tstaff_id=".$data['id']." AND SUBSTRING(hope_date,1,7)='".str_replace("/","-",$ym2)."' ";

			$treatment_query1 = $GLOBALS['mysqldb']->query( $treatment_sql1 ) or die('query error'.$GLOBALS['mysqldb']->error);
			while ( $result = $treatment_query1->fetch_assoc() ) {
				$treatment_allowance += $result['cnt'] * 100;
			}

			// 1時間以上2時間未満
			$treatment_sql2 =  "SELECT count(id) AS cnt FROM reservation WHERE del_flg=0 AND type=2 AND status=11 AND length>=2 AND length<4 AND tstaff_id=".$data['id']." AND SUBSTRING(hope_date,1,7)='".str_replace("/","-",$ym2)."' ";
			$treatment_query2 = $GLOBALS['mysqldb']->query( $treatment_sql2 ) or die('query error'.$GLOBALS['mysqldb']->error);
			while ( $result = $treatment_query2->fetch_assoc() ) {
				$treatment_allowance += $result['cnt'] * 50;
			}
		}

		// 目標達成手当
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


			$goal_counseling = explode(",",$goal['shop_id']); 	// 売上達成店舗
			$goal_treatment = explode(",",$goal['shop_id2']); 	// 施術達成店舗

			$goal_churn3 = explode(",",$goal['shop_id3']); 		// 3%解約率達成店舗
			$goal_churn5 = explode(",",$goal['shop_id4']); 		// 5%解約率達成店舗
			$goal_churn7 = explode(",",$goal['shop_id5']); 		// 7%解約率達成店舗
			$goal_contract80 = explode(",",$goal['shop_id6']); // 80%成約率達成店舗
			$goal_contract75 = explode(",",$goal['shop_id7']); // 80%成約率達成店舗



			switch ($data['type']){
				case 2:
				case 3:
					// 全店舗売上達成率
					if($goal['sales_all']) $achi_sales_all = 50000;

					// 全店舗解約率3%
					if($goal['churn_all']==1) $churn_all = 30000;
					// 全店舗解約率5%
					elseif($goal['churn_all']==2) $churn_all = 20000;
					// 全店舗解約率7%
					elseif($goal['churn_all']==3) $churn_all = 10000;

					// 全店舗成約率80%
					if($goal['contract_all']==1) $contract_all = 30000;
					// 全店舗成約率75%
					elseif($goal['contract_all']==2) $contract_all = 20000;

					$achi_allowance_c = $achi_sales_all+$churn_all+$contract_all;

					// 歩合MAX:100,000
					if($achi_allowance_c > 100000) $achi_allowance_c = 100000;

					break;
				case 5:
					// 全店舗売上達成率
					if($goal['sales_all']) $achi_sales_all = 40000;

					// 全店舗解約率3%
					if($goal['churn_all']==1) $churn_all = 20000;
					// 全店舗解約率5%
					elseif($goal['churn_all']==2) $churn_all = 10000;
					// 全店舗解約率7%
					elseif($goal['churn_all']==3) $churn_all = 5000;

					// 全店舗成約率80%
					if($goal['contract_all']==1) $contract_all = 30000;
					// 全店舗成約率75%
					elseif($goal['contract_all']==2) $contract_all = 20000;

					$achi_allowance_c = $achi_sales_all+$churn_all+$contract_all;

					// 歩合MAX:80,000
					if($achi_allowance_c > 80000) $achi_allowance_c = 80000;

					break;
				case 7:
				case 9:
					// 担当店舗売上達成
					if(in_array($shop_id, $goal_counseling) && !empty($goal_counseling)) $achi_sales = 20000;
					// 担当店舗施術達成
					if(in_array($shop_id, $goal_treatment) && !empty($goal_treatment)) $achi_allowance_t=20000;

					// 担当店舗3%解約率達成
					if(in_array($shop_id, $goal_churn3) && !empty($goal_churn3)) $achi_churn=10000;
					// 担当店舗5%解約率達成
					elseif(in_array($shop_id, $goal_churn5) && !empty($goal_churn5)) $achi_churn=5000;

					// 担当店舗80%成約率達成
					if(in_array($shop_id, $goal_contract80) && !empty($goal_contract80)) $achi_contract=10000;
					// 担当店舗75%成約率達成
					elseif(in_array($shop_id, $goal_contract75) && !empty($goal_contract75)) $achi_contract=5000;

					$achi_allowance_c = $achi_sales+$achi_allowance_t+$achi_churn+$achi_contract;


					break;

				
				case 11:
				case 15:
				case 17:
				case 18:
					if(in_array($shop_id, $goal_counseling)) $achi_allowance_c=5000;
					if(in_array($shop_id, $goal_treatment))  $achi_allowance_t=5000;
					break;

			}
			$achi_allowance = $achi_allowance_c + $achi_allowance_t;
		}

		// 手当合計
		$sub_total = $counseling_allowance + $treatment_allowance + $allowance['train_allowance'] + $allowance['trav_allowance'] + $allowance['intro_allowance'] + $allowance['president_award'] + $achi_allowance + $allowance['sales'] ;

		// 総計	
		$total = $base_salary + $base_allowance + $sub_total;

		echo '<tr'. ( $i%2<>0 ? ' class="alternate-row"' : '' ) .'>';
		echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post" name="frm'. $i.'">';
		echo 	'<td><a  rel="facebox" href="staff_info.php?staff_id='.$data['id'].'&ym='.$ym2.'">'.$data['name'].'</a></td>';
		echo 	'<td><font size="-2">'.($data['shop_id']==1001 ? "本社(土日祝休)"  : "").'</font></td>';
		if($data['shop_id']==1001) echo '<input name="work_location" type="hidden" value="1">';
		echo 	'<td class="priceFormat">'.number_format($base_salary).'</td>';
		echo 	'<td class="priceFormat">'.number_format($data['base_salary']).'</td>';
		echo 	'<td class="priceFormat">'.$gStaffType[$type] .'</td>';
		echo 	'<td class="priceFormat">'.number_format($base_allowance).'</td>';
		echo 	'<td class="priceFormat">'.number_format($base_salary+$base_allowance).'</td>'; 									
		echo 	'<td><input  class="inp-form2" type="text" name="coun_allowance" value="'. $counseling_allowance .'" /></td>';
		echo 	'<td><input  class="inp-form2" type="text" name="trea_allowance" value="'. $treatment_allowance .'" /></td>';
		echo 	'<td><input  class="inp-form2" type="text" name="train_allowance" value="'.  $allowance['train_allowance']  .'" /></td>';		
		echo 	'<td><input  class="inp-form2" type="text" name="trav_allowance" value="'.  $allowance['trav_allowance']  .'" /></td>';
		echo 	'<td><input  class="inp-form2" type="text" name="intro_allowance" value="'.  $allowance['intro_allowance']  .'" /></td>';
		echo 	'<td><input  class="inp-form2" type="text" name="president_award" value="'.  $allowance['president_award']  .'" /></td>';	
		echo 	'<td><input  class="inp-form2" type="text" name="achi_allowance" value="'.  $achi_allowance  .'" /></td>';		
		echo 	'<td><input  class="inp-form2" type="text" name="sales" value="'.  $allowance['sales']  .'" /></td>';	
		echo 	'<td class="priceFormat">'.number_format($sub_total).'</td>';
		echo 	'<td class="priceFormat">'.number_format($total).'</td>';
		echo 	' <td><a href="javascript:document.forms[\'frm'. $i.'\'].submit();" onclick="return confirm(\'変更しますか？\')" title="変更" class="icon-1 info-tooltip"></a></td>';
		echo 	'<input name="action" type="hidden" value="edit">
				 <input name="id" type="hidden" value="'.$allowance['id'].'">
				 <input name="shop_id" type="hidden" value="'.$shop_id.'">
				 <input name="staff_id" type="hidden" value="'.$data['id'].'">
				 <input name="ym" type="hidden" value="'.$_POST['ym'].'">

				 <input name="base_salary" type="hidden" value="'.$base_salary.'">
				 <input name="type" type="hidden" value="'.$type.'">
				 <input name="allowance" type="hidden" value="'.$allowance.'">

			  </form>';
		echo '</tr>';

		$i++;
	}
		krsort($cnt);
}
?>
				
				</table>
				<!--  end product-table................................... --> 
				<!--※ アドバイザーは自動手当計算の対象外です。-->
	
			</div>
			<!--  end content-table  -->

      <div class="clear"></div>
    </div>
    <!--  end content-table-inner ............................................END  -->
    </td>
    <td id="tbl-border-right"></td>
  </tr>
  <tr>
    <th class="sized bottomleft"></th>
    <td id="tbl-border-bottom">&nbsp;</td>
    <th class="sized bottomright"></th>
  </tr>
  </table>
  <div class="clear">&nbsp;</div>
</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>