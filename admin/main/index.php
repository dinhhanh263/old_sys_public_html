<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
?>
<?php include_once("../library/main/index.php"); ?>
<?php include_once("../include/header_menu.html"); ?>
<meta http-equiv="refresh" content="30" >
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
		<form name="search" method="get" action="">
		<h1>予約情報
		  <span style="margin-left:20px;">
		  	<a href="./?shop_id=<?php echo $_POST['shop_id'];?>&hope_date=<?php echo $pre_date?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
			<input style="width:76px;height:25px;" name="hope_date" type="text" id="day" value="<?php echo $_POST['hope_date'];?>" readonly  />
			<a href="./?shop_id=<?php echo $_POST['shop_id'];?>&hope_date=<?php echo $next_date?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
			<select id="shop_id" name="shop_id" style="height:27px;" ><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'], $gArea_Group, "area_group"); ?></select>
			<input type="submit" value=" 表示 "  style="height:25px;" />
			<span style="margin-left:15px;font-size:4mm">機械：<?php echo $shop['machines'] ?>台</span>
			<span style="float:right;margin-right:25px;padding-bottom:5px">
				<a href="./vacant_room.php" target="_blank">
					<input type="button" value="エリア別予約状況" class="button">
				</a>
				<?php if($authority_level<=22){?>
				<input type="button" value=" 担当別予約表 "  style="height:25px;"  onclick="window.open('./staff.php?shop_id=<?php echo$_POST['shop_id'];?>&hope_date=<?php echo $_POST['hope_date'];?>');"/>
				<input type="button" value=" シフト表(月) "  style="height:25px;"  onclick="window.open('./shift.php?shop_id=<?php echo$_POST['shop_id'];?>&shift_month=<?php echo substr($_POST['hope_date'],0,7);?>');"/>
				<?php }?>
				<?php if($authority_level<22){?>
				<input type="button" value=" 印刷 "  style="height:25px;" class="link"/>
				<input type="button" value=" 印刷（簡易版） "  style="height:25px;" class="link2"/>
				<!--<a rel="facebox" href="./total.php?mode=display&shop_id=<?php echo$_POST['shop_id'];?>&pay_date=<?php echo $_POST['hope_date'];?>">
					<input type="button" value="売上" class="button">
				</a>-->
				<?php }?>
			</span>
		  </span>
		</h1>
		<?php if($authority_level<22){?>
		<div style="padding-top:15px;padding-bottom:-10px"><iframe src="shift_d2.php?shop_id=<?php echo $_POST['shop_id']?>&hope_date=<?php echo $_POST['hope_date']?>" height="44" width="98%"></iframe></div>
		<?php }?>
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
				<form id="mainform" action="">
					<div align="center"><h1 style="color:<?php echo $shop_color;?>;"><?php echo getYobi($_POST['hope_date'],1);?><?php echo $shop_list[$_POST['shop_id']];?><!-- <font size="-1">(<a rel="facebox" href="shift_d.php?shop_id=<?php echo $_POST['shop_id']?>&hope_date=<?php echo $_POST['hope_date']?>">シフト表</a>)</font>--></h1>
					<?php if($_POST['hope_date'] == date("Y-m-d")){?>
						<span style="color:blue;font-weight:bold;">当日予約：前日21時から当日21時までブルー文字</span>
					<?php } ?>
					</div>

				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table" style="table-layout: fixed;">
				<tr id="room">
					<th class="table-header-repeat line-left minwidth-1"><a href="">ﾙｰﾑ名</a> </th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">11</a>	</th>
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
<?php if ( $room_list ) {
	$i = 1;
	$cnt = array();

	$param = '?route='.(($authority['id']==106 || $authority['id']==1449) ? 2 : ($authority_level>=7 ? 6 : 0)).'&shop_id='.$_POST['shop_id'].'&hope_date='.$_POST['hope_date'].'&hope_time=';
	foreach($room_list as $key => $room_name){
		$url = substr($key, 0,1)==3 ? "customer/search.php" : "reservation/edit.php";


		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td width="5.5%">'.$room_name.'</td>';
		if(is_array($data[$key])){
		  $last_position = 1;
		  foreach($data[$key] as $sub_key => $sub_val){
		  	// カウンセリング、追加契約、ショット
		  	if($sub_val['type']==1 || $sub_val['type']==32 || $sub_val['type']==33)$cnt[$sub_val['status0']] +=1;

		  	if($_POST['keyword']){
		  		$search_color = ( strstr($sub_val['name'], $_POST['keyword']) || strstr($sub_val['name_kana'], $_POST['keyword'])) ? ' style="color:red;font-weight:bold;"' : '';
		  	}elseif($_POST['id']){
		  		$search_color = ( $sub_val['id'] ==$_POST['id']) ? ' style="color:red;font-weight:bold;"' : '';

		  	// カウンセリング来店
		  	}else{
		  		$search_color = ( $sub_val['type']==1 && $sub_val['status0']==1 ) ? ' style="color:red;font-weight:bold;"' : '';
		  	}

		  	// 前日21時から当日21時までの予約がブルー文字
		  	if($_POST['hope_date'] == date("Y-m-d") && $sub_val['reg_date']>= date("Y-m-d 21:00:00", strtotime("-1 day")) && $sub_val['reg_date']<=date("Y-m-d 21:00:00")){
		  		$search_color = ' style="color:blue;font-weight:bold;"' ;
		  	}

		  	// 残金有り
		  	if($sub_val['balance']) $search_color = ' style="color:darkred;font-weight:bold;"' ;


		  	// 前、中スペース
		  	$space = $sub_val['hope_time']- $last_position;

			if($space){
				if($space>1){
					for ($j=$last_position; $j < ($sub_val['hope_time']-1); $j++) {
						if( array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']]) || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date']) || isset($shop['close_date']) && strtotime($shop['close_date']) < strtotime($_POST['hope_date'])){
							echo '<td style="background-color:black" width="4%" >☓</td>';
						}elseif(substr($key,0,1)==4){
							echo '<td style="background-color:#a9a9a9" width="4%" ></td>';
						}else{
							$onClick0 ='onClick="whereTo(\'../'.$url.$param.$j.'&type='.(substr($key,0,1)<=2 ? 1 : 2).'&room_id='.$key.'\',\''.$room_name.'\',\''.$gTime2[$j].'\');"';
							echo '<td title="新規" width="4%" '.$onClick0.'\'"></td>';
						}

					}
				}
				if(substr($key,0,1)==4){
							echo '<td style="background-color:#a9a9a9" width="4%" ></td>';
				}else{
					if( array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']]) || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date']) || isset($shop['close_date']) && strtotime($shop['close_date']) < strtotime($_POST['hope_date'])){
							echo '<td style="background-color:black" width="4%" >☓</td>';
						}else{
							echo '<td width="4%"></td>';
						}

				}
			}

			$start_time = $_POST['hope_date']." ".$gTime2[$sub_val['hope_time']].":00";
			$end_time0 	= $sub_val['hope_time'] + $sub_val['length'];
			$end_time 	= $_POST['hope_date']." ".$gTime4[$end_time0].":00";

			if($sub_val['ctype']==101) $backcolor="#657078"; //テストユーザー
			// ワンライフの一時対応　20171116
			elseif($sub_val['onelife_flg']) $backcolor="#00FF00";
			// 当キャンでトリートメントのまま
			elseif($sub_val['rsv_status']==14 && $sub_val['type']==2) $backcolor="red";
			//遅刻10分未満
            elseif($sub_val['status'] != '(来店)' && $sub_val['delay_time_status']==1)$backcolor="cyan";
			//遅刻10分以上
            elseif($sub_val['status'] != '(来店)' && $sub_val['delay_time_status']==2)$backcolor="lime";
			// 来店時間帯に来店状況がなし
			elseif(strtotime('now')>strtotime($start_time) &&  ($sub_val['type']==1 || $sub_val['type']==2 || $sub_val['type']==32 || $sub_val['type']==33) && $sub_val['status0']==0 )$backcolor="red";
			// サクシード自動解約
			// elseif($sub_val['loan_delay_flg']==11 && $sub_val['type']==2) $backcolor="olivedrab";
			// ローン延滞者
			elseif($sub_val['loan_delay_flg'] && $sub_val['type']==2) $backcolor="brown";
			// 月額引落手続き未処理、乗り換えの場合施術の二回目以後
			elseif($sub_val['course_type'] && (!($sub_val['dis_type']==1 && (!$sub_val['r_times'] && !$sub_val['sales_id'] || $sub_val['r_times']==1 && $sub_val['sales_id'] ) ) && $sub_val['pay_type']<2 || $sub_val['payinfo_del_flg']) ) $backcolor="pink";
			// カード引落NG
			elseif(($sub_val['digicat_ng_flg'] || $sub_val['nextpay_end_ng_flg'] || $sub_val['nextpay_end_op_flg']) && $sub_val['type']==2) $backcolor="#995000";
			// 銀行引落NG
			elseif($sub_val['bank_ng_flg'] && $sub_val['type']==2) $backcolor="#99a000";
			// medium violet red.契約（通いホーダイ）
			elseif($sub_val['status0']==15) $backcolor="#c71585";
			// darkred.契約（18回）
			elseif($sub_val['status0']==8) $backcolor="#ff69b4";
			// maroon.契約（15回）
			elseif($sub_val['status0']==7) $backcolor="#cc66ff";
			// saddlebrown.契約（12回）
			elseif($sub_val['status0']==6) $backcolor="#cc99ff";
			// sienna.契約（10回）
			elseif($sub_val['status0']==5) $backcolor="#f08080";
			// chocolate.契約（6回）
			elseif($sub_val['status0']==4) $backcolor="#ffccff";
			// white.契約（3回）
			elseif($sub_val['status0']==13) $backcolor="#ffffff";
			// darkgoldenrod.月額
			elseif($sub_val['status0']==3 || $sub_val['status0']==9) $backcolor="#ccffcc";
			// darkgoldenrod.カスタマイズパック
			elseif($sub_val['status0']==10) $backcolor="#ffe4b5";
			// 未契約
			elseif($sub_val['status0']==2) $backcolor="#f5f5f5";
			// gray.来店なし
			elseif($sub_val['status0']==1) $backcolor="#a9a9a9";
			// .全身月額プラン
			elseif($sub_val['status0']==16)    $backcolor="#F3FFD8";
			// .全身1年プラン
			elseif($sub_val['status0']==17)    $backcolor="#FF82B2";
			// .全身2年プラン
			elseif($sub_val['status0']==18)    $backcolor="#FF5192";
			// .全身SPプラン
			elseif($sub_val['status0']==19)    $backcolor="#9933FF";
			// 平日とく得1年プラン
			elseif($sub_val['status0']==20)    $backcolor="#FFA07A";
			// 平日とく得2年プラン
			elseif($sub_val['status0']==21)    $backcolor="#FF9999";
			// 平日とく得SP年プラン
			elseif($sub_val['status0']==22)    $backcolor="#D0B0FF";
			// U-19応援プラン
			elseif($sub_val['status0']==23)    $backcolor="#CBFFD3";
			// 全身お試しプラン
			elseif($sub_val['status0']==24)    $backcolor="#f0e68c";
			// 全身10回プラン
			elseif($sub_val['status0']==25)    $backcolor="#ee82ee";
			// 全身15回プラン
			elseif($sub_val['status0']==26)    $backcolor="#ba55d3";
			// 全身無制限プラン
			elseif($sub_val['status0']==27)    $backcolor="#ff00ff";
			// 平日とく得10回プラン
			elseif($sub_val['status0']==28)    $backcolor="#f4a460";
			// 平日とく得15回プラン
			elseif($sub_val['status0']==29)    $backcolor="#daa520";
			// 平日とく得無制限プラン
			elseif($sub_val['status0']==30)    $backcolor="#cd853f";
			// エステ契約60分
			elseif($sub_val['status0']==101)    $backcolor="#FA8072";
			// エステ契約90分
			elseif($sub_val['status0']==102)    $backcolor="#FF4F50";
			// 整体契約
			elseif($sub_val['status0']==103)    $backcolor="#00AA00";
			// エステショット
			elseif($sub_val['status0']==104)    $backcolor="#fddea5";
			// 整体ショット
			elseif($sub_val['status0']==105)    $backcolor="#59b9c6";
			// 脱毛最終仕上げプラン
            elseif($sub_val['status0']==90)    $backcolor="#ffa500";
			// Gold.スペシャルAKS
			elseif($sub_val['special']==4)    $backcolor="#FFD700";
			// モデル系、VIP系
			elseif($sub_val['ctype']==3 || $sub_val['ctype']==2) $backcolor="orange";
			// エステVIP
			elseif($sub_val['ctype']==6) $backcolor="greenyellow";
			//TGA
			elseif(strstr($sub_val['hope_campaign'],"TGA(全身1回無料)")){$backcolor="#9fd";
				$search_color = ' style="color:#E9546B;font-weight:bold;"' ;
			}

			// 施術予約で来店有り役務消化無し
			elseif(strtotime('now')>strtotime($end_time) && $sub_val['r_times_alarm']) $backcolor="yellow";
			// 来店後、カウンセリング予約で来店のまま
			elseif(strtotime('now')>strtotime($end_time) && $sub_val['type']==1 && $sub_val['status0']==11) $backcolor="yellow";
			// 役職者対応が必要
			elseif($sub_val['sv_flg'] && $sub_val['type']==2) $backcolor="green";
			// 水色.施術,美白
			elseif($sub_val['course_group']==9) $backcolor="#007AAA";
			// エステ60分施術
			elseif($sub_val['type']==2 && $sub_val['course_treatment_type']==1 && $sub_val['course_length']==2) $backcolor="#FFAD90";
			// エステ90分施術
			elseif($sub_val['type']==2 && $sub_val['course_treatment_type']==1 && $sub_val['course_length']==3) $backcolor="#FF9966";
			// 整体60分施術
			elseif($sub_val['type']==2 && $sub_val['course_treatment_type']==2 && $sub_val['course_length']==2) $backcolor="#99FF99";
			// 整体90分施術
			elseif($sub_val['type']==2 && $sub_val['course_treatment_type']==2 && $sub_val['course_length']==3) $backcolor="#00CC66";
			// LightSkyBlue.施術(トリートメント・通いホーダイ)
			elseif($sub_val['type']==2 && ($sub_val['course_id']==77 || $sub_val['course_id']==1002) ) $backcolor="#1960E3";
			// 施術(トリートメント・平日とく得プラン)
			elseif($sub_val['type']==2 && ($sub_val['course_interval_date'] != null && $sub_val['course_sales_start_date'] >= '2020-01-16' && $sub_val['course_weekdays_plan_type']==0 && $sub_val['course_weekdays_plan_type'] != null) || $sub_val['course_id']==109) $backcolor="#99FFFF";
			// 濃い水色.施術(トリートメント・全身プラン)
			elseif($sub_val['type']==2 && ($sub_val['course_interval_date'] != null && $sub_val['course_sales_start_date'] >= '2019-11-06') || $sub_val['course_id']==106) $backcolor="#5D99FF";
			// LightSkyBlue.施術(トリートメント・通常コース)
			elseif($sub_val['type']==2) $backcolor="#87CEFA";
			// white.施術(月額制)
			elseif($sub_val['type']==1 && $sub_val['status']==0) $backcolor="white";
			// その他のルーム
			elseif(substr($key,0,1)==4) $backcolor="#a9a9a9";
			// white.カウンセリング
			else $backcolor="white";


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

			// 予約表のみ表示
			if($authority_level==52){
				$contents = $sub_val['name'] ? $sub_val['name'] : $sub_val['name_kana'];
				if($sub_val['rebook_flg']==1) $contents .= "(再申込1)";
				if($sub_val['rebook_flg']==2) $contents .= "(再申込2)";

			}elseif($authority['id']<>"5" ){

				$contents = $gTime2[$sub_val['hope_time']]."～".$gTime2[$sub_val['hope_time']+$sub_val['length']];
				$contents .= '<span class="member_num">'.$sub_val['no'].$sub_val['status'].$sub_val['hope_campaign'].'</span>'
				.$sub_val['name_kana'].'<span class="memo1">('.$sub_val['age'].')'.'</span>'
				.'<span class="memo2">'.$sub_val['student_id'].$sub_val['attorney_status'].'</span>'
				.'<span class="memo3">'.$sub_val['tel'].'</span>'
				.'<span class="memo3">'.$con_status.$sub_val['cstaff_id'].'</span>'
				.'<span class="memo2">'.$sub_val['memo2'].'</span>'
				.'<span class="memo3">'.$sub_val['memo_head_office'].'</span>';
				if($authority_level<=1 && $sub_val['adcode']){
					$contents .= "<br>(媒体：".$sub_val['adcode'].")<br>" ;
				}
				if($sub_val['type']==1){//カウンセリング時のみ表示
					$contents .= '<span class="memo2">'.$sub_val['agree_status'].'</span>';
					if($sub_val['introducer_customer_id']){//友達紹介
						$contents .= "<br>"."友達紹介。友達紹介適応してください。備考記入不要です。" ;
					}else if($sub_val['ad_memo']){//広告申込み文章
						$contents .= "<br>".$sub_val['ad_memo'] ;
					}
				}
				// ローン不備の方の出しわけ 20160906 shimada
				if($sub_val['loan_status_no']==5){
					$contents .= '※ローン不備※不備訂正と処理をお願いいたします。<br>';
				} else {
					$contents .= $sub_val['loan_status']."<br>";
				}
				if($sub_val['rebook_flg']) $contents .= "(再申込)<br>";
				// 回数制トリートメントの場合のみ消化進捗表示
				if($sub_val['course_id']<1000 && $sub_val['course_type']==0 && $sub_val['course_zero_flg']==0 && $sub_val['type']==2){
					$contents .= "<br>消化進捗：".'<font color ="red">'.$sub_val['r_times']."/".$sub_val['times'].'</font>';
				}else{
					$contents .= "";
				}

			// 広告権限で内容非表示
			}else $contents = $sub_val['no']. ($sub_val['adcode'] ? "<br>(".$sub_val['adcode'].")" : "");

			// 通いホーダイ(シェービング保証プラン)の施術時、クラスを追加する 20160603 shimada
			if($sub_val['course_id']==77 || $sub_val['course_id']==1002)$doubles = ' doubles';
			else $doubles ="";
			// 新月額の場合、クラスを追加する 20161027 ueda
			if($sub_val['course_new_flg'] == 1){
				$new_flg = ' new_monthly_border';
			}else{$new_flg = '';}
			// 全身月額の場合、クラスを追加する
			if($sub_val['course_new_flg'] ==1 && $sub_val['course_id'] ==92)$new_flg = ' new_monthly_border2';
			// U-19応援プランの場合、クラスを追加する
			if($sub_val['course_minor_plan_flg'] ==1 && $sub_val['course_new_flg'] ==1 && $sub_val['course_id'] ==102)$new_flg = ' under_nineteen_border';
			// 旧月額・新月額シェービング代都度払いの顧客にクラスを追加する 20170110
			if($sub_val['course_type']==1 && ($sub_val['course_id'] == 89 || $sub_val['course_new_flg'] == 0))$doubles = ' shaving_border';
			// else $doubles =""; 20170406 delete ueda
			// 最終消化予定の場合、クラスを追加する
			if($sub_val['course_id']<1000 && $sub_val['course_type']==0 && $sub_val['course_zero_flg']==0 && $sub_val['type']==2 && ($sub_val['r_times']+1 == $sub_val['times']))$doubles = ' r_times_doubles';
			// 返金保証回数終了コースの場合、クラスを追加する
			if($sub_val['course_id']>1000 && $sub_val['course_id']!=1003 && $sub_val['course_id']!=1018 && $sub_val['course_status']==2 && $sub_val['course_del_flg']==0)$doubles = ' times_end_doubles';
			// 消化回数が1回目の場合、クラスを追加する
			if($sub_val['course_id']<1000 && $sub_val['type']==2 && $sub_val['course_status']==2 && $sub_val['r_times']==0)$doubles = ' r_times_first';
			// 消化回数が2回目の場合、クラスを追加する
			if($sub_val['course_id']<1000 && $sub_val['type']==2 && $sub_val['course_status']==2 && $sub_val['r_times']==1)$doubles = ' r_times_second';
			// カムバックキャンペーン用の場合、クラスを追加する
			if($sub_val['type']==1 && in_array($sub_val['rsv_adcode'],$gCampaignAdcode) )$doubles = ' campaign_adcode';
			// 親権者同意書が未提出の場合、クラスを追加する
			if($sub_val['type']==2 && $sub_val['agree_status_color']!='')$doubles = ' agree_status';
			// 要注意箇所がある場合、クラスを追加する
			if($sub_val['caution'] == 1){
				$stripe = ' tattoo-stripe';
			}else{$stripe = '';}
			// 月額休会の履歴がある場合、クラスを追加する
			if($sub_val['monthly_pause_flg']){
				$text_line = ' monthly_pause_line';
			}else{$text_line = '';}

			if($authority_level==52){
				echo '<td title="'.$sub_val['mail'].'" colspan="'.$sub_val['length'].'" class="'.($sub_val['ctype']==101 ? '': 'planSample').$new_flg.$stripe.'" style="background-color:'.$backcolor.'"><a class="'.$doubles.$text_line.'" href="#" '.$search_color.'>'.$contents.'</a></td>';
			}else{
				echo '<td title="'.$sub_val['name'].'" colspan="'.$sub_val['length'].'" class="'.($sub_val['ctype']==101 ? '': 'planSample').$new_flg.$stripe.'" style="background-color:'.$backcolor.'"><a class="'.$doubles.$text_line.'" href="../reservation/edit.php?reservation_id='.$sub_val['id'].'" '.$search_color.'>'.$contents.'</a></td>';
			}
			$last_position = $sub_val['hope_time'] + $sub_val['length'];
		  }

		  // 後ろスペース
		  $space2 = 21-$last_position;
		  if($space2){
				if($space2>1){
					for ($k=$last_position; $k < 20; $k++) {
						if(array_key_exists($_POST['hope_date'],$gParty) && in_array($_POST['shop_id'],$gParty[$_POST['hope_date']]) && $k>=$gPartyTime){
							echo '<td style="background-color:black" width="4%" >☓</td>';
						}elseif( array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']]) || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date']) || isset($shop['close_date']) && strtotime($shop['close_date']) < strtotime($_POST['hope_date'])){
							echo '<td style="background-color:black" width="4%" >☓</td>';
						}elseif( ($_POST['hope_date'] >= $gEndingDate ) && in_array($_POST['shop_id'],$gEndingShop) && $k>=$gEndingTime){
							echo '<td style="background-color:black" width="4%" >☓</td>';
						}elseif(substr($key,0,1)==4){
							echo '<td style="background-color:#a9a9a9" width="4%" ></td>';
						}else{
							$onClick0 ='onClick="whereTo(\'../'.$url.$param.$k.'&type='.(substr($key,0,1)<=2 ? 1 : 2).'&room_id='.$key.'\',\''.$room_name.'\',\''.$gTime2[$k].'\');"';
							echo '<td title="新規" width="4%" '.$onClick0.'\'"></td>';
						}
					}
				}
				if(array_key_exists($_POST['hope_date'],$gParty) && in_array($_POST['shop_id'],$gParty[$_POST['hope_date']]) && $k>=$gPartyTime){
							echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif( array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']]) || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date']) || isset($shop['close_date']) && strtotime($shop['close_date']) < strtotime($_POST['hope_date'])){
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif( ($_POST['hope_date'] >= $gEndingDate ) && in_array($_POST['shop_id'],$gEndingShop) && $k>=$gEndingTime){
							echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif(substr($key,0,1)==4){
							echo '<td style="background-color:#a9a9a9" width="4%" ></td>';
				}else{
					// 最後の30分枠に新規対象外
					echo '<td width="4%"></td>';
				}

			}

		}else{
			// 全行空き
			for ($k=1; $k < 20; $k++) {
				if(array_key_exists($_POST['hope_date'],$gParty) && in_array($_POST['shop_id'],$gParty[$_POST['hope_date']]) && $k>=$gPartyTime){
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif( array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']]) || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date']) || isset($shop['close_date']) && strtotime($shop['close_date']) < strtotime($_POST['hope_date'])){
					echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif( ($_POST['hope_date'] >= $gEndingDate ) && in_array($_POST['shop_id'],$gEndingShop) && $k>=$gEndingTime){
							echo '<td style="background-color:black" width="4%" >☓</td>';
				}elseif(substr($key,0,1)==4){
							echo '<td style="background-color:#a9a9a9" width="4%" ></td>';
				}else{
					$onClick0 ='onClick="whereTo(\'../'.$url.$param.$k.'&type='.(substr($key,0,1)<=2 ? 1 : 2).'&room_id='.$key.'\',\''.$room_name.'\',\''.$gTime2[$k].'\');"';
					echo '<td title="新規" width="4%" '.$onClick0.'\'"></td>';
				}
			}
			if(array_key_exists($_POST['hope_date'],$gParty) && in_array($_POST['shop_id'],$gParty[$_POST['hope_date']]) && $k>=$gPartyTime){ //第一木曜日　19:00以後達成会
				echo '<td style="background-color:black" width="4%" >☓</td>';
			}elseif(array_key_exists($_POST['hope_date'],$gClosed) && in_array($_POST['shop_id'],$gClosed[$_POST['hope_date']])  || $shop['open_date'] &&  strtotime($shop['open_date'])>strtotime($_POST['hope_date']) || isset($shop['close_date']) && strtotime($shop['close_date']) < strtotime($_POST['hope_date'])){
					echo '<td style="background-color:black" width="4%" >☓</td>';
			}elseif( ($_POST['hope_date'] >= $gEndingDate ) && in_array($_POST['shop_id'],$gEndingShop) && $k>=$gEndingTime){
							echo '<td style="background-color:black" width="4%" >☓</td>';
			}elseif(substr($key,0,1)==4){
							echo '<td style="background-color:#a9a9a9" width="4%" ></td>';
			}else{
				// 最後の30分枠に新規対象外
				echo '<td width="4%"></td>';
			}
		}
		echo '</tr>';

		$i++;
	}
}
?>
				<tr id="room">
					<th class="table-header-repeat line-left minwidth-1"><a href="">ルーム名</a> </th>
					<th class="table-header-repeat line-left" colspan="2"><a href="">11</a>	</th>
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
				<!--  start paging..................................................... -->
			<table border="0" cellpadding="0" cellspacing="0" id="example-table">
			<tr>
				<td><div style="color:blue;font-weight:bold;">当日予約</div></td>
				<td><div style="color:darkred;font-weight:bold;">売掛有り</div></td>
				<td><div style="color:green;font-weight:bold;">予約時telOK</div></td>
				<td><div style="color:purple;font-weight:bold;">予約時telﾙｽ</div></td>
				<td><div style="color:red;font-weight:bold;">予約時telNG</div></td>
				<td><div style="color:orange;font-weight:bold;">お客様切電</div></td>
				<td><div style="background-color:pink">月額引落手続前</div></td>
				<td><div style="background-color:green">役職者対応</div></td>
				<td><div style="background-color:brown">ローン延滞者</div></td>
				<!-- 一時削除 -->
				<!-- <td><div style="background-color:olivedrab">サクシード<br>自動解約</div></td> -->
				<td><div style="background-color:#995000">カード引落NG</div></td>
				<td><div style="background-color:#99a000">銀行引落NG</div></td>
			</tr>
			<tr>
				<td><div style="background-color:white">カウンセリング</div></td>
				<td><div style="background-color:#87CEFA">施術</div></td>
				<td><div style="background-color:#5D99FF">施術(全身プラン)</div></td>
				<td><div style="background-color:#99FFFF">施術(平日とく得プラン)</div></td>
				<td><div style="background-color:#87CEFA"><span class="shaving_border">シェービング代別途請求</span></div></td>
				<td><div style="background-color:#007AAA">施術（美白）</div></td>
				<td><div style="background-color:#1960E3"><span class="doubles">施術(ｼｪｰﾋﾞﾝｸﾞ保証)</span></div></td>
				<td><div style="background-color:#87CEFA" class="new_monthly_border">新月額</div></td>
				<td><div style="background-color:#87CEFA" class="new_monthly_border2">全身月額プラン</div></td>
				<td><div style="background-color:#87CEFA" class="under_nineteen_border">U-19応援プラン</div></td>
			</tr>
			<tr>
				<td><div style="background-color:#FFAD90">エステ施術60分</div></td>
				<td><div style="background-color:#FF9966">エステ施術90分</div></td>
				<td><div style="background-color:#99FF99">整体施術60分</div></td>
				<td><div style="background-color:#00CC66">整体施術90分</div></td>
			</tr>
			<tr>
				<td><div style="background-color:#FFD700">スペシャル紹介</div></td>
				<td><div style="background-color:orange">モデル系</div></td>
				<td><div style="background-color:greenyellow">エステモデル系</div></td>
				<td><div style="background-color:#9fd;color:#E9546B;font-weight:bold;">TGA</div></td>
				<td><div class="tattoo-stripe">タトゥー</div></td>
				<td><div class="monthly_pause_line">月額休会</div></td>
				<td><div style="background-color:white"><span class="campaign_adcode">カムバックキャンペーン</span></div></td>
				<td><div style="background-color:white"><span class="agree_status">親権者同意書未提出</span></div></td>
			</tr>
			<tr>
				<td><div style="background-color:white">来店(<?php echo $cnt['11'];?>)</div></td>
				<td><div style="background-color:white">来店中(<?php echo $cnt['12'];?>)</div></td>
				<td><div style="background-color:red">来店指定なし</div></td>
				<td><div style="background-color:yellow">（カ）来店のまま、消化忘れ</div></td>
				<td><div style="background-color:red">当キャンでトリのまま</div></td>
                <td><div style="background-color:cyan">遅刻10分未満</div></td>
                <td><div style="background-color:lime">遅刻10分以上</div></td>
				<td><div style="background-color:white"><span class="r_times_first">消化1回目</span></div></td>
				<td><div style="background-color:white"><span class="r_times_second">消化2回目</span></div></td>
				<td><div style="background-color:white"><span class="r_times_doubles">最終消化予定予約</span></div></td>
				<td><div style="background-color:white"><span class="times_end_doubles">【損金】返金保証回数終了コース</span></div></td>
			</tr>
			<tr>
				<td><div style="background-color:#c71585">契約通いホーダイ(<?php echo $cnt['15'];?>)</div></td>
				<td><div style="background-color:#ff69b4">契約18回(<?php echo $cnt['8'];?>)</div></td>
				<td><div style="background-color:#cc66ff">契約15回(<?php echo $cnt['7'];?>)</div></td>
				<td><div style="background-color:#cc99ff">契約12回(<?php echo $cnt['6'];?>)</div></td>
				<td><div style="background-color:#f08080">契約10回(<?php echo $cnt['5'];?>)</div></td>
				<td><div style="background-color:#ffccff">契約6回(<?php echo $cnt['4'];?>)</div></td>
				<td><div style="background-color:#ffffff">契約3回(<?php echo $cnt['13'];?>)</div></td>
				<td><div style="background-color:#ccffcc">契約月額(<?php echo $cnt['3'];?>)</div></td>
				<td><div style="background-color:#ccffcc">カスタマイズ月額(<?php echo $cnt['9'];?>)</div></td>
				<td><div style="background-color:#ffe4b5">カスタマイズパック(<?php echo $cnt['10'];?>)</div></td>
				<td><div style="background-color:#f5f5f5">未契約(<?php echo $cnt['2'];?>)</div></td>
				<td><div style="background-color:#a9a9a9">来店なし(<?php echo $cnt['1'];?>)</div></td>
			</tr>
			<tr>
				<td><div style="background-color:#F3FFD8">全身月額プラン(<?php echo $cnt['16'];?>)</div></td>
				<td><div style="background-color:#FF82B2">全身1年プラン(<?php echo $cnt['17'];?>)</div></td>
				<td><div style="background-color:#FF5192">全身2年プラン(<?php echo $cnt['18'];?>)</div></td>
				<td><div style="background-color:#9933FF">全身SPプラン(<?php echo $cnt['19'];?>)</div></td>
				<td><div style="background-color:#FFA07A">平日とく得<br>1年プラン(<?php echo $cnt['20'];?>)</div></td>
				<td><div style="background-color:#FF9999">平日とく得<br>2年プラン(<?php echo $cnt['21'];?>)</div></td>
				<td><div style="background-color:#D0B0FF">平日とく得<br>SPプラン(<?php echo $cnt['22'];?>)</div></td>
				<td><div style="background-color:#CBFFD3">U-19応援プラン(<?php echo $cnt['23'];?>)</div></td>
			</tr>
			<tr>
				<td><div style="background-color:#f0e68c">全身お試しプラン(<?php echo $cnt['24'];?>)</div></td>
				<td><div style="background-color:#ee82ee">全身10回プラン(<?php echo $cnt['25'];?>)</div></td>
				<td><div style="background-color:#ba55d3">全身15回プラン(<?php echo $cnt['26'];?>)</div></td>
				<td><div style="background-color:#ff00ff">全身無制限プラン(<?php echo $cnt['27'];?>)</div></td>
				<td><div style="background-color:#f4a460">平日とく得<br>10回プラン(<?php echo $cnt['28'];?>)</div></td>
				<td><div style="background-color:#daa520">平日とく得<br>15回プラン(<?php echo $cnt['29'];?>)</div></td>
				<td><div style="background-color:#cd853f">平日とく得<br>無制限プラン(<?php echo $cnt['30'];?>)</div></td>
			</tr>
			<tr>
				<td><div style="background-color:#FA8072">エステ契約60分(<?php echo $cnt['101'];?>)</div></td>
				<td><div style="background-color:#FF4F50">エステ契約90分(<?php echo $cnt['102'];?>)</div></td>
				<td><div style="background-color:#00AA00">整体契約(<?php echo $cnt['103'];?>)</div></td>
				<td><div style="background-color:#fddea5">エステショット(<?php echo $cnt['104'];?>)</div></td>
				<td><div style="background-color:#59b9c6">整体ショット(<?php echo $cnt['105'];?>)</div></td>
                <td><div style="background-color:#ffa500">脱毛最終仕上げプラン(<?php echo $cnt['90'];?>)</div></td>
			</tr><div>
			</table>
			<!--  end paging................ -->
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