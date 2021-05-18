<?php include_once("../library/main/index.php");?>
<?php include_once("../include/header_menu.html");?>
 <link rel="stylesheet" href="../css/colorbox.css" />

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox()
})
</script>
<script language="JavaScript">
<!--
function whereTo(url,room,time){
	var isConfirmed=confirm(room + 'に' + time + 'から新規予約をしますか？')
	if(isConfirmed){
		window.open(url)
	}
}
//-->
</script>

 <script src="../js/jquery.jPrintArea.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.link').click(function(){ $.jPrintArea('#mainform',"") });
    $('.link2').click(function(){ $.jPrintArea('#mainform','memo3') });
});
</script>
</form>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">

	<!--  start page-heading -->
	<div id="page-heading">
		<form name="search" method="post" action="">
		<h1>予約情報
		  <span style="margin-left:20px;">
		  	<a href="./?shop_id=<?php echo $_POST['shop_id'];?>&hope_date=<?php echo $pre_date?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
			<input style="width:76px;height:25px;" name="hope_date" type="text" id="day" value="<?php echo $_POST['hope_date'];?>" readonly  />
			<a href="./?shop_id=<?php echo $_POST['shop_id'];?>&hope_date=<?php echo $next_date?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
			<select name="shop_id" style="height:27px;" ><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'] );?></select>
			<input type="submit" value=" 表示 "  style="height:25px;" />

			<span style="float:right;margin-right:25px;padding-bottom:5px">
				<?php if($authority_level<22){?>
				<input type="button" value=" 担当別予約表 "  style="height:25px;"  onclick="window.open('./staff.php?shop_id=<?php echo$_POST['shop_id'];?>&hope_date=<?php echo $_POST['hope_date'];?>');"/>
				<input type="button" value=" シフト表(月) "  style="height:25px;"  onclick="window.open('./shift.php?shop_id=<?php echo$_POST['shop_id'];?>&shift_month=<?php echo substr($_POST['hope_date'],0,7);?>');"/>
				<?php }?>
				<input type="button" value=" 印刷 "  style="height:25px;" class="link"/>
				<input type="button" value=" 印刷（簡易版） "  style="height:25px;" class="link2"/>
			</span>

		  </span>
		</h1>
		<?php if($authority_level<22){?>
		<div style="padding-top:15px;padding-bottom:-10px"><iframe src="shift_d2.php?shop_id=<?php echo $_POST['shop_id']?>&hope_date=<?php echo $_POST['hope_date']?>" height="44" width="98%"></iframe></div>
		<?php }?>
		</form>
	</div>
	<!-- end page-heading -->

	<div id="content-table">
		<!--  start content-table-inner -->
		<div id="content-table-inner">

			<!--  start table-content  -->
			<div id="table-content">
				<!--  start product-table -->
				<form id="mainform" action="">
					<div id="main-title"><h1 style="color:<?php echo $shop_color;?>;"><?php echo getYobi($_POST['hope_date'],1);?><?php echo $shop_list[$_POST['shop_id']];?><!-- <font size="-1">(<a rel="facebox" href="shift_d.php?shop_id=<?php echo $_POST['shop_id']?>&hope_date=<?php echo $_POST['hope_date']?>">シフト表</a>)</font>--></h1>
					<?php if($_POST['hope_date'] == date("Y-m-d")){?>
						<span class="rsv-font">当日予約：前日21時から当日21時までブルー文字</span>
					<?php } ?>
					</div>

				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table" class="main-table">
					<tr id="room">
						<th class="table-header-repeat line-left minwidth-1 w5"><a href="">ルーム名</a> </th>
						<th class="table-header-repeat line-left w8" colspan="2"><a href="">12</a></th>
						<th class="table-header-repeat line-left w8" colspan="2"><a href="">13</a></th>
						<th class="table-header-repeat line-left w8" colspan="2"><a href="">14</a></th>
						<th class="table-header-repeat line-left w8" colspan="2"><a href="">15</a></th>
						<th class="table-header-repeat line-left w8" colspan="2"><a href="">16</a></th>
						<th class="table-header-repeat line-left w8" colspan="2"><a href="">17</a></th>
						<th class="table-header-repeat line-left w8" colspan="2"><a href="">18</a></th>
						<th class="table-header-repeat line-left w8" colspan="2"><a href="">19</a></th>
						<th class="table-header-repeat line-left w8" colspan="2"><a href="">20</a></th>
					</tr>
<?php if ( $room_list ) {
	$i = 1;
	$cnt = array();
	$param = '?route='.(($authority['id']==106 || $authority['id']==1449) ? 2 : ($authority_level>=7 ? 6 : 0)).'&shop_id='.$_POST['shop_id'].'&hope_date='.$_POST['hope_date'].'&hope_time=';
	foreach($room_list as $key => $room_name){
		$url = substr($key, 0,1)==3 ? "customer/search.php" : "reservation/edit.php";

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$room_name.'</td>';

		if(is_array($data[$key])){
		  $last_position = 1;
		  foreach($data[$key] as $sub_key => $sub_val){
		  	// カウンセリングだけ
		  	if($sub_val['type']==1)$cnt[$sub_val['status0']] +=1;

		  	if($_POST['keyword']){
		  		$search_color = ( strstr($sub_val['name'], $_POST['keyword']) || strstr($sub_val['name_kana'], $_POST['keyword'])) ? ' class="tel-ng"' : '';
		  	}elseif($_POST['id']){
		  		$search_color = ( $sub_val['id'] ==$_POST['id']) ? ' class="tel-ng"' : '';
		  	// カウンセリング来店
		  	}else{
		  		$search_color = ( $sub_val['type']==1 && $sub_val['status0']==1 ) ? ' class="tel-ng"' : '';
		  	}
		  	// 前日21時から当日21時までの予約がブルー文字
		  	if($_POST['hope_date'] == date("Y-m-d") && $sub_val['reg_date']>= date("Y-m-d 21:00:00", strtotime("-1 day")) && $sub_val['reg_date']<=date("Y-m-d 21:00:00")){
		  		$search_color = ' class="rsv-font"' ;
		  	}

		  	// 残金有り(売掛あり)
		  	$search_color_class=""; // 売掛ありのスタイル(初期化)
		  	if($sub_val['balance']) {
		  		$search_color = ' class="rsv-unpaid"' ;
		  		$search_color_class=" rsv-unpaid"; // 連続したクラス名の末尾に追記するために追加 2017/04/27 add by shimada
		  	}

		  	// 前、中スペース
		  	$space = $sub_val['hope_time']- $last_position;

			if($space){
				if($space>1){
					for ($j=$last_position; $j < ($sub_val['hope_time']-1); $j++) {
						if( array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']]) || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date'])  ){
							echo '<td style="background-color:black" width="4%" >☓</td>';
						}elseif($key>40){
							echo '<td style="background-color:#a9a9a9" width="4%" ></td>';
						}else{
							$onClick0 ='onClick="whereTo(\'../'.$url.$param.$j.'&type='.(substr($key,0,1)<=2 ? 1 : 2).'&room_id='.$key.'\',\''.$room_name.'\',\''.$gTime2[$j].'\');"';
							echo '<td title="新規" '.$onClick0.'\'"></td>';
						}

					}
				}
				if($key>40){
							echo '<td style="background-color:#a9a9a9" width="4%" ></td>';
				}else{
					if( array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']]) || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date'])  ){
						echo '<td style="background-color:black" width="4%" >☓</td>';
					}else{
						echo '<td width="4%"></td>';
					}
				}
			}

			$start_time = $_POST['hope_date']." ".$gTime2[$sub_val['hope_time']].":00";
			$end_time0 	= $sub_val['hope_time'] + $sub_val['length'];
			$end_time 	= $_POST['hope_date']." ".$gTime4[$end_time0].":00";

			// テストユーザー
			if($sub_val['ctype']==5) $classname="test-user"; 
			// サクシード自動解約
			elseif($sub_val['loan_delay_flg']==11 && $sub_val['type']==2) $classname="succead-rate";
			// ローン延滞者
			elseif($sub_val['loan_delay_flg'] && $sub_val['type']==2) $classname="loan-rate"; 
			// 来店時間帯に来店状況がなし
			elseif(strtotime('now')>strtotime($start_time) &&  ($sub_val['type']==1 || $sub_val['type']==2 || $sub_val['type']==20) && $sub_val['status0']==0 )$classname="salon-not_coming";
			// カード引落NG
			elseif(($sub_val['digicat_ng_flg'] || $sub_val['nextpay_end_ng_flg'] || $sub_val['nextpay_end_op_flg']) && $sub_val['type']==2) $classname="card-ng"; 
			// 銀行引落NG
			elseif($sub_val['bank_ng_flg'] && $sub_val['type']==2) $classname="withdrawal-ng"; 
			// 全身
			elseif($sub_val['status0']==10) $classname="c-body"; 
			// 選べる5か所
			elseif($sub_val['status0']==9) $classname="c-slect5"; 
			// 選べる3か所
			elseif($sub_val['status0']==8) $classname="c-slect3"; 
			// VIO
			elseif($sub_val['status0']==7) $classname="c-vio"; 
			// 脚パック
			elseif($sub_val['status0']==6) $classname="c-legs"; 
			// お顔
			elseif($sub_val['status0']==5) $classname="c-face"; 
			// 1回コースのみ
			elseif($sub_val['status0']==4) $classname="c-onece";
			// 複数契約 
			elseif($sub_val['status0']==3) $classname="c-multiple"; 
			// モデル系
			elseif($sub_val['ctype']==3) $classname="rsv-model"; 
			// 未契約
			elseif($sub_val['status0']==2) $classname="c-not_contract"; 
			// white.来店なし
			elseif($sub_val['status0']==1) $classname="c-not_coming"; 
			// Gold.スペシャルAKS
			elseif($sub_val['special']==4)    $classname="rsv-special"; 
			// 施術予約で来店有り役務消化無し
			elseif(strtotime('now')>strtotime($end_time) && $sub_val['r_times_alarm']) $classname="salon-foget";
			// 来店後、カウンセリング予約で来店のまま
			elseif(strtotime('now')>strtotime($end_time) && $sub_val['type']==1 && $sub_val['status0']==11) $classname="salon-foget";
			// 月額引落手続き未処理、乗り換えの場合施術の二回目以後
			elseif($sub_val['course_type'] && !($sub_val['dis_type']==1 && (!$sub_val['r_times'] && !$sub_val['sales_id'] || $sub_val['r_times']==1 && $sub_val['sales_id'] ) ) && $sub_val['pay_type']<2) $classname="pink"; 
			// 役職者対応が必要
			elseif($sub_val['sv_flg'] && $sub_val['type']==2) $classname="vip-treatment"; 
			// LightSkyBlue.施術
			elseif($sub_val['type']==2) $classname="c-treatment";
			// 1回当日(施術)
			elseif($sub_val['type']==20) $classname="c-treatment_onece";
			// その他のルーム
			elseif($key>40) $classname="c-other"; 

			else 	$classname="c-counseling"; 

			$con_status = "";
			if($sub_val['3dmail_status']) $con_status = $sub_val['3dmail_status'];
			if($sub_val['premail_status']) $con_status .= $sub_val['premail_status'];
			if($sub_val['preday_status']) $con_status .= $sub_val['preday_status'];
			if($sub_val['today_status']) {
				$con_status .= "<font color=";
				switch ($sub_val['today_status']) {
					case '予約時telOK':
						$con_status .="green";
						break;
					case '予約時telﾙｽ':
						$con_status .="purple";
						break;
					case '予約時telNG':
						$con_status .="red";
						break;
					case 'お客様切電':
						$con_status .="orange";
						break;
				}
				$con_status .= ">(".$sub_val['today_status'].")</font>";
			}

			if(!$sub_val['3dmail_status'] && !$sub_val['premail_status'] && !$sub_val['preday_status'] && !$sub_val['today_status']) $con_status = $sub_val['con_status'];

			//予約表のみ表示
			if($authority_level==52){
				$contents = $sub_val['name'] ? $sub_val['name'] : $sub_val['name_kana'];
				if($sub_val['rebook_flg']==1) $contents .= "(再申込1)";
				if($sub_val['rebook_flg']==2) $contents .= "(再申込2)";

			}elseif($authority['id']<>"5" ){

				// 契約情報を表示する
				$contents = $gTime2[$sub_val['hope_time']]."～".$gTime2[$sub_val['hope_time']+$sub_val['length']]."<br>";
				$contents .= '<span class="member_num">'.$sub_val['no'].$sub_val['status'].$sub_val['hope_campaign'].'</span>'.$sub_val['name_kana'].'<span class="memo1">('.$sub_val['age'].')'.'</span>';

				// 複数契約IDの時、コース名・消化回数を取得する
				$course_name = ""; // コース名
				if($sub_val['multiple_contract_id']){ // 一つ・複数の契約
					$sub_val['multiple_contract_id'] = explode(',', $sub_val['multiple_contract_id']);
					foreach ($sub_val['multiple_contract_id'] as $key1 => $value) {
						$multiple_course = Get_Table_Row("contract"," WHERE id = '".$value."'");
						$course_name .= '<span class="memo_contract">'.$course_list[$multiple_course['course_id']].'&nbsp;'.$multiple_course['r_times'].'回目</span>';
						// 契約部位がある場合はカンマ区切りで表示する
						if($multiple_course['contract_part']){
							if(strpos($multiple_course['contract_part'],',') !== false){
								$multiple_course['contract_part'] = explode(',', $multiple_course['contract_part']);
								// 契約部位IDから契約部位名にループしながら変換する
								$parts_name = "";  // 部位名
								foreach ($multiple_course['contract_part'] as $key11 => $part) {
								$parts_name .=$gContractParts[$part].',';
							}
							$course_name .= '<span class="memo_parts">'.$parts_name.'</span>';
							} elseif($multiple_course['contract_part']) {
								$course_name  .= '<span class="memo_parts">'.$gContractParts[$multiple_course['contract_part']].'</span>';
							}
							// 1部位の時、重複で部位が表示されてしまうためコメントアウトする 2017/07/11 modify by shimada
							// $course_name  .= '<br>'. $gContractParts[$multiple_course['contract_part']];
						}
					}
				} else { // コース契約がない
					$course_name="";
				}
				$contents .= '<span class="memo2">'.$course_name.'</span>';

				$contents .= '<span class="memo2">'.$sub_val['agree_status'].$sub_val['student_id'].$sub_val['attorney_status'].'</span>'
				.'<span class="memo3">'.$sub_val['tel'].'</span>'
				.'<span class="memo2">'.$con_status.$sub_val['cstaff_id'].'</span>'
				.'<span class="memo2">'.$sub_val['memo2'].'</span>'
				.'<span class="memo3">'.$sub_val['memo3'].'</span>';
				if($authority_level<=1 && $sub_val['adcode']){
					$contents .= "<br>(媒体：".$sub_val['adcode'].")" ;
				}
				if($sub_val['ad_memo']){
					$contents .= "<br>".$sub_val['ad_memo'] ;
				}

				if($sub_val['loan_status']) $contents .= $sub_val['loan_status'];
				if($sub_val['rebook_flg']) $contents .= "(再申込)";
			// 広告権限で内容非表示
			}else $contents = $sub_val['no']. ($sub_val['adcode'] ? "<br>(".$sub_val['adcode'].")" : "");

			if($authority_level==52){
				echo '<td title="'.$sub_val['mail'].'" colspan="'.$sub_val['length'].'" class="planSample '.$classname.$search_color_class.'"><a href="#" '.$search_color.'>'.$contents.'</a></td>';
				// $search_color_class追加 2017/04/27 add by shimada
			}else{
				echo '<td title="'.$sub_val['name'].'" colspan="'.$sub_val['length'].'" class="planSample '.$classname.$search_color_class.'"><a href="../reservation/edit.php?id='.$sub_val['id'].'&shop_id='.$_POST['shop_id'].'&hope_date='.$_POST['hope_date'].'" '.$search_color.'>'.$contents.'</a></td>';
				// $search_color_class追加 2017/04/27 add by shimada
			}
			$last_position = $sub_val['hope_time'] + $sub_val['length'];
		  }

		  // 後ろスペース
		  $space2 = 19-$last_position;
		  if($space2){
				if($space2>1){
					for ($k=$last_position; $k < 18; $k++) {
						if(array_key_exists($_POST['hope_date'],$gParty) && in_array($_POST['shop_id'],$gParty[$_POST['hope_date']]) && $k>=$gPartyTime){ 
							echo '<td style="background-color:black" width="4%" >☓</td>';
						}elseif( array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']]) || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date'])  ){
							echo '<td style="background-color:black" >☓</td>';
						}elseif( ($_POST['hope_date'] >= $gEndingDate ) && in_array($_POST['shop_id'],$gEndingShop) && $k>=$gEndingTime){ 
							echo '<td style="background-color:black" width="4%" >☓</td>';
						}elseif($key>40){
							echo '<td style="background-color:#a9a9a9" width="4%" ></td>';
						}else{
							$onClick0 ='onClick="whereTo(\'../'.$url.$param.$k.'&type='.(substr($key,0,1)<=2 ? 1 : 2).'&room_id='.$key.'\',\''.$room_name.'\',\''.$gTime2[$k].'\');"';
							echo '<td title="新規" '.$onClick0.'\'"></td>';
						}
					}
				}
				if(array_key_exists($_POST['hope_date'],$gParty) && in_array($_POST['shop_id'],$gParty[$_POST['hope_date']]) && $k>=$gPartyTime){ 
							echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif( array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']]) || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date'])  ){
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif( ($_POST['hope_date'] >= $gEndingDate ) && in_array($_POST['shop_id'],$gEndingShop) && $k>=$gEndingTime){ 
							echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif($key>40){
							echo '<td style="background-color:#a9a9a9" width="4%" ></td>';
				}else{
					echo '<td></td>'; // 最後の30分枠に新規対象外
				}

			}

		}else{
			// 全行空き
			for ($k=1; $k < 18; $k++) {
				if(array_key_exists($_POST['hope_date'],$gParty) && in_array($_POST['shop_id'],$gParty[$_POST['hope_date']]) && $k>=$gPartyTime){ 
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif( array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']]) || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date'])  ){
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif( ($_POST['hope_date'] >= $gEndingDate ) && in_array($_POST['shop_id'],$gEndingShop) && $k>=$gEndingTime){ 
							echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif($key>40){
							echo '<td style="background-color:#a9a9a9" width="4%" ></td>';
				}else{
					$onClick0 ='onClick="whereTo(\'../'.$url.$param.$k.'&type='.(substr($key,0,1)<=2 ? 1 : 2).'&room_id='.$key.'\',\''.$room_name.'\',\''.$gTime2[$k].'\');"';
					echo '<td title="新規" '.$onClick0.'\'"></td>';
				}
			}
			if(array_key_exists($_POST['hope_date'],$gParty) && in_array($_POST['shop_id'],$gParty[$_POST['hope_date']]) && $k>=$gPartyTime){ // 第一木曜日　19:00以後達成会
				echo '<td style="background-color:black" width="4%" >☓</td>';
			}elseif(array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']])  || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date']) ){
					echo '<td style="background-color:black" width="4%" >☓</td>';
			}elseif( ($_POST['hope_date'] >= $gEndingDate ) && in_array($_POST['shop_id'],$gEndingShop) && $k>=$gEndingTime){ 
							echo '<td style="background-color:black" width="4%" >☓</td>';
			}elseif($key>40){
							echo '<td style="background-color:#a9a9a9" width="4%" ></td>';
			}else{
				echo '<td></td>'; // 最後の30分枠に新規対象外
			}
		}
		echo '</tr>';
		$i++;
	}
}
?>
					<tr>
						<th class="table-header-repeat line-left"><a href="">ルーム名</a></th>
						<th class="table-header-repeat line-left" colspan="2"><a href="">12</a></th>
						<th class="table-header-repeat line-left" colspan="2"><a href="">13</a></th>
						<th class="table-header-repeat line-left" colspan="2"><a href="">14</a></th>
						<th class="table-header-repeat line-left" colspan="2"><a href="">15</a></th>
						<th class="table-header-repeat line-left" colspan="2"><a href="">16</a></th>
						<th class="table-header-repeat line-left" colspan="2"><a href="">17</a></th>
						<th class="table-header-repeat line-left" colspan="2"><a href="">18</a></th>
						<th class="table-header-repeat line-left" colspan="2"><a href="">19</a></th>
						<th class="table-header-repeat line-left" colspan="2"><a href="">20</a></th>
					</tr>
				</table>
				<!--  end product-table................................... -->
				</form>
			</div>
			<!--  end content-table  -->
				<!--  start paging -->
			<table border="0" cellpadding="0" cellspacing="0" id="paging-table">
				<tr>
					<td class="c-body">全身(<?php echo $cnt['10'];?>)</td><td>&nbsp;</td>
					<td class="c-slect5">選べる5か所(<?php echo $cnt['9'];?>)</td><td>&nbsp;</td>
					<td class="c-slect3">選べる3か所(<?php echo $cnt['8'];?>)</td><td>&nbsp;</td>
					<td class="c-vio">VIO(<?php echo $cnt['7'];?>)</td><td>&nbsp;</td>
					<td class="c-legs">脚パック(<?php echo $cnt['6'];?>)</td><td>&nbsp;</td>
					<td class="c-face">お顔(<?php echo $cnt['5'];?>)</td><td>&nbsp;</td>
					<td class="c-onece">1回(<?php echo $cnt['4'];?>)</td><td>&nbsp;</td>
					<td class="c-multiple">複数契約(<?php echo $cnt['3'];?>)</td><td>&nbsp;</td>
	<!-- 				<td style="background-color:#ffe4b5">カスタマイズパック(<?php //echo $cnt['10'];?>)</td><td>&nbsp;</td> -->
					<td class="c-not_contract">未契約(<?php echo $cnt['2'];?>)</td><td>&nbsp;</td>
					<td class="c-not_coming">来店なし(<?php echo $cnt['1'];?>)</td><td>&nbsp;</td>
					<td class="c-coming">来店(<?php echo $cnt['11'];?>)</td><td>&nbsp;</td>
	<!-- 				<td style="background-color:white">来店中(<?php //echo $cnt['12'];?>)</td><td>&nbsp;</td> -->
					<td class="c-counseling">カウンセリング</td><td>&nbsp;</td>
					<td class="c-treatment">施術</td><td>&nbsp;</td>
					<td class="c-treatment_onece">施術(1回当日)</td><td>&nbsp;</td>
	<!-- 				<td style="background-color:#007AAA">施術（美白）</td><td>&nbsp;</td> -->
					<td class="rsv-special">スペシャル紹介</td><td>&nbsp;</td>
					<td class="rsv-model">モデル系</td><td>&nbsp;</td>
					<td class="rsv-font">当日予約</td>
					<td class="rsv-unpaid">売掛有り</td>
					<td class="tel-ok">予約時telOK</td>
					<td class="tel-out">予約時telﾙｽ</td>
					<td class="tel-ng">予約時telNG</td>
					<td class="tel-cut">お客様切電</td>
					<td class="salon-not_coming">来店指定なし</td><td>&nbsp;</td>
					<td class="salon-foget">（カ）来店のまま、消化忘れ</td><td>&nbsp;</td>
	<!-- 				<td style="background-color:pink">月額引落手続前</td><td>&nbsp;</td> -->
					<td class="vip-treatment">役職者対応</td><td>&nbsp;</td>
					<td class="loan-rate">ローン延滞者</td><td>&nbsp;</td>
					<td class="succead-rate">サクシード自動解約</td><td>&nbsp;</td>
	<!-- 				<td style="background-color:#995000">カード引落NG</td><td>&nbsp;</td>
					<td style="background-color:#99a000">銀行引落NG</td><td>&nbsp;</td> -->
				</tr>
			</table>
			<!--  end paging................ -->
		</div>
		<!--  end content-table-inner ............................................END  -->
		</td>
<!-- 		<td id="tbl-border-right"></td> -->
	</tr>
<!-- 	<tr>
		<th class="sized bottomleft"></th>
		<td id="tbl-border-bottom">&nbsp;</td>
		<th class="sized bottomright"></th>
	</tr> -->
	</table>
	<div class="clear">&nbsp;</div>

</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>