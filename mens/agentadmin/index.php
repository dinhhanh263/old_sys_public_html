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
require_once LIB_DIR . 'db.php';
session_start();

if($_POST['mode']=='login'){
 if( $pAgent = Get_Table_Row("agent"," WHERE del_flg=0 and id = '".addslashes($_POST['login_id'])."' and password='".addslashes($_POST['login_pw'])."'")){
    $_SESSION['auth_flg'] =true;
    $_SESSION['agent_id'] = $pAgent['id']; 
 }else $err_msg="ユーザ名やパスワードが正しくありません！";
}

elseif($_GET['logout']) $_SESSION['auth_flg'] =false;

if($_SESSION['auth_flg']){
  //テーブル設定
  $table = "accesslog";
	$page_id = 3;

$_POST = $_REQUEST;
// 期間指定---------------------------------------------------------------------------

$_POST['access_date']=$_POST['access_date'] ? $_POST['access_date'] : date("Y-m-d");
$_POST['access_date2']=$_POST['access_date2'] ? $_POST['access_date2'] : ($_POST['access_date'] ? $_POST['access_date'] : date("Y-m-d"));

$pre_date = date("Y-m-d", strtotime($_POST['access_date']." -1day"));
$next_date = date("Y-m-d", strtotime($_POST['access_date']." +1day"));

// データの取得---------------------------------------------------------------------------
$dWhere = "where id!=0 ";
if($_POST['type'] != "" ){
	$dWhere .= " and type = '".$GLOBALS['mysqldb']->real_escape_string($_POST['type'])."'";
}

//元代理店有無判別
	$pAgent = Get_Table_Row("agent"," WHERE id = '".addslashes($_SESSION['agent_id'])."'");
	if($pAgent['pid']) $aAgent = Get_Table_Row("agent"," WHERE id = '".addslashes($pAgent['pid'])."'");
	else $aAgent = Get_Table_Row("agent"," WHERE id = '".addslashes($_SESSION['agent_id'])."'");
	
	//媒体情報取得
	$dWhere .= " and agent_id = '".$GLOBALS['mysqldb']->real_escape_string($aAgent['id'])."'";
	$adcode = $GLOBALS['mysqldb']->query( "SELECT * FROM adcode ".$dWhere." order by name" );
  

  $data = array();
  $total = array();
  $i=0;
  while ( $list = $adcode->fetch_assoc() ) {
	$data[$i]['release_date'] = $list['release_date'];
	$data[$i]['adcode'] = $list['adcode'];
	$data[$i]['type'] = $list['type'];
	$data[$i]['name'] = $list['name'];
	
	//端末別TOPページクリック数取得
	$data[$i]['total_top'] = 0;
	$rtn = $GLOBALS['mysqldb']->query("SELECT page_id,mo_agent,sum(count) as cnt FROM ".$table." WHERE page_id<=3 and adcode='".$list['id']."' and access_date >= '".$_POST['access_date']."' AND access_date <= '".$_POST['access_date2']."' GROUP BY adcode,page_id,mo_agent ORDER BY adcode,page_id,mo_agent ");
	if($rtn->num_rows >= 1){
		while($line = $rtn->fetch_assoc()){
			//端末別TOPページクリック数取得
			if($line['page_id']==1){
				$data[$i][$line['mo_agent']] = $line['cnt'];
				$data[$i]['total_top'] += $line['cnt'];
				$total[$line['mo_agent']] += $line['cnt'];
				$total['total_top'] += $line['cnt'];
			}
			//申込件数合計数取得
			if($line['page_id']==3){
				$data[$i]['reg_all'] += $line['cnt'];
				$total['reg_all'] += $line['cnt'];
			}
		}
	}


	//今月CV
	$rtn = $GLOBALS['mysqldb']->query("SELECT page_id,mo_agent,sum(count) as cnt FROM ".$table." WHERE page_id=3 and adcode='".$list['id']."' and access_date >= '".date("Y-m-01")."' AND access_date <= '".date("Y-m-t")."' GROUP BY adcode,page_id,mo_agent ORDER BY adcode,page_id,mo_agent ");
	if($rtn->num_rows >= 1){
		while($line = $rtn->fetch_assoc()){
			//申込件数合計数取得
			$data[$i]['reg_month'] += $line['cnt'];
			$total['reg_month'] += $line['cnt'];

		}
	}
	
	//本日と昨日をまとめ,2->1
	$yesterday = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
	// base: adcode -> id
	$rtn = $GLOBALS['mysqldb']->query("SELECT access_date,sum(count) as cnt FROM ".$table." WHERE  page_id=3 and access_date>='".$yesterday."' and adcode='".$list['id']."' GROUP BY adcode,access_date ORDER BY adcode,access_date ");
	if($rtn->num_rows >= 1){

		while($line = $rtn->fetch_assoc()){
			if($line['access_date']==date("Y-m-d")){//本日空メール申込件数取得
				$data[$i]['reg_today'] = $line['cnt'];
				$total['reg_today'] += $line['cnt'];
			}
			if($line['access_date']==$yesterday){//昨日空メール申込件数取得
				$data[$i]['reg_yesterday'] = $line['cnt'];
				$total['reg_yesterday'] += $line['cnt'];
			}

		}
	}
	$i++;
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO</title>
<link rel="stylesheet" href="./css/screen.css" type="text/css" media="screen" title="default" />
<script type="text/javascript" src="./js/main.js"></script>
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="css/pro_dropline_ie.css" />
<![endif]-->

<!--  jquery core -->
<script src="./js/jquery/jquery-1.8.2.min.js" type="text/javascript"></script>

<script type="text/javascript" src="./js/auto.jquerykana.js"></script>
<!--  checkbox styling script -->
<script src="./js/jquery/ui.core.js" type="text/javascript"></script>
<script src="./js/jquery/ui.checkbox.js" type="text/javascript"></script>checkBox
<script src="./js/jquery/jquery.bind.js" type="text/javascript"></script>
<!--<script type="text/javascript">
$(function(){
  $('input').checkBox();
  $('#toggle-all').click(function(){
  $('#toggle-all').toggleClass('toggle-checked');
  $('#mainform input[type=checkbox]').checkBox('toggle');
  return false;
  });
});
</script>  
-->
<![if !IE 7]>

<!--  styled select box script version 1 -->
<script src="./js/jquery/jquery.selectbox-0.5.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('.styledselect').selectbox({ inputClass: "selectbox_styled" });
});
</script>
 

<![endif]>

<!--  styled select box script version 2 --> 
<script src="./js/jquery/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('.styledselect_form_1').selectbox({ inputClass: "styledselect_form_1" });
  $('.styledselect_form_2').selectbox({ inputClass: "styledselect_form_2" });
});
</script>

<!--  styled select box script version 3 --> 
<script src="./js/jquery/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('.styledselect_pages').selectbox({ inputClass: "styledselect_pages" });
});
</script>

<!--  styled file upload script --> 
<script src="./js/jquery/jquery.filestyle.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
  $(function() {
      $("input.file_1").filestyle({ 
          image: "./images/forms/choose-file.gif",
          imageheight : 29,
          imagewidth : 78,
          width : 300
      });
  });
</script>

<!-- Custom jquery scripts -->
<script src="./js/jquery/custom_jquery.js" type="text/javascript"></script>
 
<!-- Tooltips -->
<script src="./js/jquery/jquery.tooltip.js" type="text/javascript"></script>
<script src="./js/jquery/jquery.dimensions.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
  $('a.info-tooltip ').tooltip({
    track: true,
    delay: 0,
    fixPNG: true, 
    showURL: false,
    showBody: " - ",
    top: -35,
    left: 5
  });
});
</script> 

<!-- MUST BE THE LAST SCRIPT IN <HEAD></HEAD></HEAD> png fix -->
<script src="./js/jquery/jquery.pngFix.pack.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$(document).pngFix( );
});
</script>



<!--  date picker script -->
<link rel="stylesheet" type="text/css" href="../admin/js/datepicker/themes/flick/ui.all.css" />
<link rel="stylesheet" type="text/css" href="../admin/js/datepicker/themes/flick/ui.datepicker.css" />

<script type="text/javascript" src="../admin/js/datepicker/ui.datepicker.js"></script>
<script type="text/javascript" src="../admin/js/datepicker/ui.datepicker-ja.js"></script>
<script type="text/javascript"> 
  $(document).ready(function(){
    // 時間ピッカー
    $("input#day,#day2").datepicker(
      {duration: "slow",dateFormat: 'yy-mm-dd'}
    );

  });
</script>
<style type="text/css"> 
  span.ui-datepicker-year {
    margin-right:1em;
  }
</style>


</head>
<?php if(!$_SESSION['auth_flg']){?>
<body id="login-bg"> 
<!-- Start: login-holder -->
<div id="login-holder">
	<!-- start logo -->
	<div id="logo-login">
		<a href=""><img src="images/shared/logo.png" height="40" alt="" /></a>
	</div>
	<!-- end logo -->
	<div class="clear"></div>
	<!--  start loginbox ................................................................................. -->
	<div id="loginbox">
	<!--  start login-inner -->
	<div id="login-inner">
		<div style="line-height:30px;"><?php echo $err_msg;?></div>
	  	<form action="" method="post">	  	
	  	<input type="hidden" name="mode" value="login" />
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>ユーザ名</th>
			<td><input type="text" name="login_id" value="<?php echo $_POST['login_id'];?>" class="login-inp" autocapitalize="off" /></td>
		</tr>
		<tr>
			<th>パスワード</th>
			<td><input type="password" name="login_pw" value="<?php echo $_POST['login_pw'];?>"  onfocus="this.value=''" class="login-inp"  /></td>
		</tr>
		<tr>
			<th></th>
			<td valign="top"><!--<input type="checkbox" class="checkbox-size" id="login-check" /><label for="login-check">Remember me</label>--></td>
		</tr>
		<tr>
			<th></th>
			<td><input type="submit" class="submit-login"  /></td>
		</tr>
		</table>
	  </form>
	</div>
 	<!--  end login-inner -->
	<div class="clear"></div>
	<a href="" class="forgot-pwd">Forgot Password?</a>
 </div>
 <!--  end loginbox -->
</div>
<!-- End: login-holder -->
</body>
 <?php }else{?>  
<body> 
<!-- Start: page-top-outer -->
<div id="page-top-outer">    
<!-- Start: page-top -->
<div id="page-top">
  <!-- start logo -->
  <div id="logo">
 <img src="./images/shared/logo.png"  height="40" alt="" />
  <!--<a href="./main/"><img src="./images/shared/logo.png" width="156" height="40" alt="" /></a>-->
  </div>
  <!-- end logo -->
  <!--  start top-search -->
  <div id="top-search">
      <a href="./?logout=1" id="logout"><img src="./images/shared/nav/nav_logout.gif" width="64" height="14" alt="" /></a>
  </div>
  <!--  end top-search -->
  <div class="clear"></div>
</div>
<!-- End: page-top -->
</div>
<!-- End: page-top-outer -->
   <div class="clear"></div>
 <!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">
		<form name="" method="post" action="">
		<h1><?php echo $pAgent['pid'] ? $pAgent['name'] : $aAgent['name']; ?>
			<span style="margin-left:20px;">
				
				<a href="./?access_date=<?php echo $pre_date?>"><img src="../admin/images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="access_date" type="text" id="day" value="<?php echo $_POST['access_date'];?>" readonly />~<input style="width:70px;height:21px;" name="access_date2" type="text" id="day2" value="<?php echo $_POST['access_date2'];?>" readonly  />
				<a href="./?access_date=<?php echo $next_date?>"><img src="../admin/images/table/paging_right.gif" title="翌日" /></a>
                <input type="submit" value=" 表示 "  style="height:25px;" />
               	
            </span>
		</h1>
		</form>
	</div>
	<!-- end page-heading -->
	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<th rowspan="3" class="sized"><img src="./images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>
		<th rowspan="3" class="sized"><img src="./images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
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
				<tr>
					<th class="table-header-repeat"><a href="./?sort=adcode&seq= DESC<?php echo $param; ?>">広告コード</a></th>
					<th class="table-header-repeat line-left"><a href="./?sort=name&seq= DESC<?php echo $param; ?>">媒体名</a></th>
					<th class="table-header-repeat line-left"><a href="">android</a></th>
					<th class="table-header-repeat line-left"><a href="">iphone</a></th>
					<th class="table-header-repeat line-left"><a href="">ipad</a></th>
					<th class="table-header-repeat line-left"><a href="">pc</a></th>
					<th class="table-header-repeat line-left"><a href="">&nbsp;Click<br>&nbsp;(total)</a></th>
					<!--<th class="table-header-repeat line-left"><a href="">&nbsp;CV<br>&nbsp;(本日)</a></th>-->
					<!--<th class="table-header-repeat line-left"><a href="">&nbsp;CV<br>&nbsp;(昨日)</a></th>-->
					<th class="table-header-repeat line-left"><a href="">&nbsp;CV<br>&nbsp;(total)</a></th>
					<th class="table-header-repeat line-left"><a href="">CVR</a></th>
					<th class="table-header-repeat line-left"><a href="">&nbsp;CV<br>&nbsp;(今月)</a></th>
				</tr>
<?php
if ( $data ) {
  $i = 1;
  foreach ( $data as $key=>$val ) {
  	if(!$val['total_top'] && !$val['reg_all']) continue;
	if($val['rel_reg_all']){
		$percent =  round($val['rel_reg_all']/$val['reg_all']*100)."%" ;
		$percent = ($val['rel_reg_all']/$val['reg_all']<0.3) ? "<font color='red'>".$percent."</font>" : $percent;
	}
	
	//上限オーバー判別
	$d_Cnt = 0;
	$cost_cnt = "";
	if($val['maximum']<>0 ){
		$dRtn = $GLOBALS['mysqldb']->query( "SELECT sum(count) FROM accesslog WHERE page_id=3 and adcode='".$val['adcode']."' AND access_date <= '".$_POST['access_date2']."'");
		$d_Cnt = $dRtn->fetch_row();
	}
	if($val['maximum']<>0 && $d_Cnt >= $val['maximum']){
		$reg_all = $val['maximum'] - ($d_Cnt - $val['reg_all']);
		$reg_all = $reg_all > 0 ? $reg_all : "";	
		$alarm_tab_begin = "<font color='red'><b>";	$alarm_tab_end = "</b></font>";
		$cost_cnt = $reg_all ? "(".$reg_all.")" : "";
	}else{
		$reg_all = $val['reg_all'];	$alarm_tab_begin = ""; $alarm_tab_end = "";
	}
	$cvr = ($val['total_top'] && $val['reg_all']) ? round(($val['reg_all']/$val['total_top']*100),2)."%" : "";

?>
					<tr <?php echo($val['del_flg'] ?  'bgcolor="#CCCCCC"' : ($i%2==0 ? '' : ' class="alternate-row"'))?>>
						<td><?php echo($val['adcode']); ?></td>
						<td><?php echo ($val['name']); ?></td>
						<td style="width:60px;text-align:right;padding-right:5px;"><?php if($val['3']) echo number_format($val['3'] + 0); ?></td>
						<td style="width:60px;text-align:right;padding-right:5px;"><?php if($val['1']) echo number_format($val['1'] + 0); ?></td>
						<td style="width:60px;text-align:right;padding-right:5px;"><?php if($val['2']) echo number_format($val['2'] + 0); ?></td>
						
						<td style="width:60px;text-align:right;padding-right:5px;"><?php if($val['0']) echo number_format($val['0'] + 0); ?></td>
						<td style="width:60px;text-align:right;padding-right:5px;"><?php if($val['total_top']) echo number_format($val['total_top'] + 0); ?></td>
						<!--<td style="width:60px;text-align:right;padding-right:5px;"><?php echo($val['reg_today']); ?></td>-->
						<!--<td style="width:60px;text-align:right;padding-right:5px;"><?php echo($val['reg_yesterday']); ?></td>-->
						<td style="width:60px;text-align:right;padding-right:5px;"><?php echo( $alarm_tab_begin . $val['reg_all'] .$cost_cnt. $alarm_tab_end ); ?></td>
						<td style="width:60px;text-align:right;padding-right:5px;"><?php echo ($cvr); ?></td>
						<td style="width:60px;text-align:right;padding-right:5px;"><?php echo($val['reg_month']); ?></td>
					</tr>
<?php
	$i++;
  }
}
?>
					<tr class="bgb" id="eee">
						<td colspan="2" id="ct">合計</td>
						<td style="text-align:right;padding-right:5px;"><?php echo number_format($total['3'] + 0); ?></td>
						<td style="text-align:right;padding-right:5px;"><?php echo number_format($total['1'] + 0); ?></td>
						<td style="text-align:right;padding-right:5px;"><?php echo number_format($total['2'] + 0); ?></td>
						<td style="text-align:right;padding-right:5px;"><?php echo number_format($total['0'] + 0); ?></td>
						<td style="text-align:right;padding-right:5px;"><?php echo number_format($total['total_top'] + 0); ?></td>
						<!--<td style="text-align:right;padding-right:5px;"><?php echo number_format($total['reg_today'] + 0); ?></td>-->
						<!--<td style="text-align:right;padding-right:5px;"><?php echo number_format($total['reg_yesterday'] + 0); ?></td>-->
						<td style="text-align:right;padding-right:5px;"><?php echo number_format($total['reg_all'] + 0); ?></td>

						<td style="text-align:right;padding-right:5px;"><?php echo $total['total_top'] ? round(($total['reg_all']/$total['total_top']*100),2)."%" : ""; ?></td><!--CVR-->
						<td style="text-align:right;padding-right:5px;"><?php echo number_format($total['reg_month'] + 0); ?></td>

					</tr>
                   
				</table>
				<!--  end product-table................................... --> 
			</div>
			<!--  end content-table  -->
			
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

<div class="clear">&nbsp;</div>
    
<!-- start footer -->         
<div id="footer">
	<!--  start footer-left -->
	<div id="footer-left">
	
	&copy; Copyright Vielis Ltd. <span id="spanYear"></span> <a href="">www.vielis.co.jp</a>. All rights reserved.</div>
	<!--  end footer-left -->
	<div class="clear">&nbsp;</div>
</div>
<!-- end footer -->
 
</body>
 <?php }?>   
</html>