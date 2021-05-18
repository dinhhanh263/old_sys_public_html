<?php include_once("../library/main/detail.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Salon Chain</title>
<link rel="stylesheet" href="../css/screen.css" type="text/css" media="screen" title="default" />
<script type="text/javascript" src="../js/main.js"></script>
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="css/pro_dropline_ie.css" />
<![endif]-->

<!--  jquery core -->
<script src="../js/jquery/jquery-1.4.1.min.js" type="text/javascript"></script>

<script type="text/javascript" src="../js/auto.jquerykana.js"></script>
<!--  checkbox styling script -->
<script src="../js/jquery/ui.core.js" type="text/javascript"></script>
<script src="../js/jquery/ui.checkbox.js" type="text/javascript"></script>checkBox
<script src="../js/jquery/jquery.bind.js" type="text/javascript"></script>
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
<script src="../js/jquery/jquery.selectbox-0.5.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('.styledselect').selectbox({ inputClass: "selectbox_styled" });
});
</script>
 

<![endif]>

<!--  styled select box script version 2 --> 
<script src="../js/jquery/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('.styledselect_form_1').selectbox({ inputClass: "styledselect_form_1" });
  $('.styledselect_form_2').selectbox({ inputClass: "styledselect_form_2" });
});
</script>

<!--  styled select box script version 3 --> 
<script src="../js/jquery/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('.styledselect_pages').selectbox({ inputClass: "styledselect_pages" });
});
</script>

<!--  styled file upload script --> 
<script src="../js/jquery/jquery.filestyle.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
  $(function() {
      $("input.file_1").filestyle({ 
          image: "../images/forms/choose-file.gif",
          imageheight : 29,
          imagewidth : 78,
          width : 300
      });
  });
</script>

<!-- Custom jquery scripts -->
<script src="../js/jquery/custom_jquery.js" type="text/javascript"></script>
 
<!-- Tooltips -->
<script src="../js/jquery/jquery.tooltip.js" type="text/javascript"></script>
<script src="../js/jquery/jquery.dimensions.js" type="text/javascript"></script>
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


<!--  date picker script -->
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.all.css" />
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.datepicker.css" />
<script type="text/javascript" src="../js/datepicker/ui.datepicker.js"></script>
<script type="text/javascript" src="../js/datepicker/ui.datepicker-ja.js"></script>
<script type="text/javascript"> 
  $(document).ready(function(){
    // 時間ピッカー
    $("input#day,#day1").datepicker(
      {duration: "",dateFormat: 'yy-mm-dd'}
    );

  });
</script>
<style type="text/css"> 
  span.ui-datepicker-year {
    margin-right:1em;
  }
</style>
<!--全角英数字、ハイフン->半角--> 
<script type="text/javascript"> 
$(function() {
  $('#fm').change(function(){
    var result  = $(this).val();
    for(var i = 0; i < result.length; i++){
        var char = result.charCodeAt(i);
        if(char >= 0xff10 && char <= 0xff19 ){
            //全角数値なら
            result = result.replace(result.charAt(i),String.fromCharCode(char-0xfee0));
        }
        if(char == 0xff0d || char == 0x30fc || char == 0x2015 || char == 0x2212){
            //全角ハイフンなら
            result = result.replace(result.charAt(i),String.fromCharCode(0x2d));
        }
    }
    $(this).val(result);
  });
});
</script>

<!-- MUST BE THE LAST SCRIPT IN <HEAD></HEAD></HEAD> png fix -->
<script src="../js/jquery/jquery.pngFix.pack.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$(document).pngFix( );
});
</script>
<style>
#id-form th{
	line-height:28px;
	min-width:60px;
	padding:0 0 10px 10px;
	text-align: left;
	width: 80px;
}
</style>
</head>
<body> 
<!-- Start: page-top-outer -->
<div id="page-top-outer" style="height:50px">    

<!-- Start: page-top -->
<div id="page-top">

  <!-- start logo -->
  <div id="logo" style="margin:5px 0 0 15px">
  <a href="../main/"><img src="../images/shared/logo.png" height="40" alt="" /></a>
  </div>
  <!-- end logo -->
 
</div>
<!-- End: page-top -->

</div>
<!-- End: page-top-outer -->
  

 

 <div class="clear"></div>
<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content" style="max-width:800px;min-width:200px;padding:20px 0 20px 0;">
		<div id="page-heading"><h1><a href="../reservation/edit.php?id=<?php echo $_REQUEST['id'];?>" target="_blank">予約詳細</a></h1>（予約詳細画面へ）</div>
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
					<!--  start content-table-inner -->
					<div id="content-table-inner" style="padding:0;">
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr valign="top">
								<td>
									<!-- start id-form -->
									<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
										<input type="hidden" name="action" value="edit" />
										<input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>" />
										<?php echo $gMsg;?>
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<tr>
												<th valign="top">区分:</th>
												<td><select name="type" style="height:33px;"><?php Reset_Select_Key( $gResType , $data['type']);?></select></td>
												<td>
													<div class="bubble-left"></div>
													<div class="bubble-inner">区分変更、キャンセル処理</div>
													<div class="bubble-right"></div>
												</td>
											</tr>
											<tr>
												<th valign="top">会員番号:</th>
												<td><?php echo $customer['no'];?></td>
											</tr>
											
											<tr>
												<th valign="top">名前:</th>
												<td><a href="../customer/edit.php?id=<?php echo $customer['id'];?>" target="_blank"><?php echo $customer['name'];?></a></td>
												<td>
													<div class="bubble-left"></div>
													<div class="bubble-inner">顧客詳細画面へのリンク</div>
													<div class="bubble-right"></div>
												</td>
											</tr>
											<tr>
												<th valign="top">電話番号:</th>
												<td><?php echo $customer['mobile'] ? $customer['mobile'] : $customer['tel'] ;?></td>
											</tr> 
											<tr>
												<th valign="top">消化回数:</th>
												<td><?php echo $data['count'];?></td>
											</tr> 
											<tr>
												<th valign="top">売掛:</th>
												<td><?php echo $data['balance'];?></td>
											</tr>
											<tr>
												<th valign="top">店舗:</th>
												<td><select name="shop_id" style="height:33px;"><?php Reset_Select_Key( $shop_list , $data['shop_id']);?></select></td>
											</tr>
											<tr>
												<th valign="top">ルーム:</th>
												<td><select name="room_id" style="height:33px;"><?php Reset_Select_Key( $room_list , $data['room_id']);?></select></td>
											</tr>  
											<tr>
												<th valign="top">コース:</th>
												<td><select name="course_id" style="height:33px;"><?php Reset_Select_Key( $course_list , $data['course_id']);?></select></td>
											</tr> 
											<tr>
												<th valign="top">日時:</th>
												<td class="noheight">
													<table border="0" cellpadding="0" cellspacing="0">
														<tr  valign="top">
															<td><input style="width:70px;height:25px;" name="hope_date" type="text" id="day" value="<?php echo $data['hope_date'];?>" readonly /></td>
														</tr>
													</table>
												</td>
												<td></td>
											</tr>
											<tr>
												<th valign="top">開始時間:</th>
												<td><select size="1" name="hope_time" style="height:33px;"><?php Reset_Select_Key( $gTime  , $data['hope_time']);?></select></td>
											</tr> 
											<tr>
												<th valign="top">人数:</th>
												<td><select name="persons" ><?php Reset_Select_Key( $gPersons , $data['persons']);?></select></td>
											</tr>
											<tr>
												<th valign="top">所要時間:</th>
												<td><select name="length" style="height:33px;"><?php Reset_Select_Key( $gLength , $data['length']);?></select></td>
											</tr>
											<tr>
												<th valign="top">予約表記載:</th>
												<td><input type="text" name="memo2" value="<?php echo TA_Cook($data['memo2']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">備考:</th>
												<td><input type="text" name="memo" value="<?php echo TA_Cook($data['memo']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th>&nbsp;</th>
												<td valign="top">
													<input type="submit" value="" class="form-submit" />
													<input type="reset" value="" class="form-reset" />
												</td>
											</tr>
										</table>
									</form>
									<!-- end id-form  -->
								</td>
							</tr>
							<tr><td><img src="../images/shared/blank.gif" width="695" height="1" alt="blank" /></td></tr>
						</table>
 						<div class="clear"></div>
					</div>
					<!--  end content-table-inner  -->
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
<!--  end content-outer -->
<script type="text/javascript">
	new AutoKana('Name', 'NameKana', {katakana: true, toggle: false});
</script>

 
</body>
</html>