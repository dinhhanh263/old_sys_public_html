<?php include_once("../library/reservation/karte.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SYSTEM</title>
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" href="../css/screen.css" type="text/css" media="screen" title="default" />

<!-- ブラウザの「戻る」ボタンを禁止-->
<script type="text/javascript">
function redirect(url)
{
    var Backlen=history.length;
    history.go(-Backlen);
    window.location.replace(url);
}
</script>

<script type="text/javascript" src="../js/main.js"></script>
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="css/pro_dropline_ie.css" />
<![endif]-->

<!--  jquery core -->
<!-- <script src="../js/jquery/jquery-1.4.1.min.js" type="text/javascript"></script> -->
<script src="../js/jquery/jquery-1.7.min.js" type="text/javascript"></script>

<script type="text/javascript" src="../js/auto.jquerykana.js"></script>
<!--  checkbox styling script -->
<script src="../js/jquery/ui.core.js" type="text/javascript"></script>
<script src="../js/jquery/ui.checkbox.js" type="text/javascript"></script>
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
/*#id-form th{
	line-height:28px;
	min-width:60px;
	padding:0 0 10px 10px;
	text-align: left;
	width: 80px;
}

label {
	color: #000;
}

.td_space label {
	padding: 0 100px 0 0;
}*/
</style>

<!-- ブラウザの「戻る」ボタンを禁止-->
<script type="text/javascript">
history.forward();
</script>

</head>
<body onunload="" id="karte">
<!-- Start: page-top-outer -->
<div id="page-top-outer" style="height:50px">

<!-- Start: page-top -->
<div id="page-top">

  <!-- start logo -->
  <div id="logo" style="margin:5px 0 0 15px">
  <!--<a href="../main/"><img src="../images/shared/logo.png" height="40" alt="" /></a>-->
   <a href="../main/?shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/shared/logo.png"  height="40" alt="" /></a>  </div>
  <!-- end logo -->

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


	</div>
	<!-- end page-heading -->

	<div id="content-table">
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
			<!--  start table-content  -->
			<div id="table-content">
				<!--  start product-table ..................................................................................... -->
				<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
				<input type="hidden" name="action" value="edit" />
				<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
				<input type="hidden" name="shop_id" value="<?php echo $reservation["shop_id"];?>" />
				<input type="hidden" name="customer_id" value="<?php echo $reservation["customer_id"];?>" />
				<input type="hidden" name="reservation_id" value="<?php echo $_POST["reservation_id"];?>" />
				<input type="hidden" name="r_times" value="<?php echo $reservation["r_times"];?>" />
				<input type="hidden" name="hope_date" value="<?php echo $reservation["hope_date"];?>" />
				<input type="hidden" name="hope_time" value="<?php echo $reservation["hope_time"];?>" />


				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table" class="karte">

				<?php if($data['karte_ver'] == "1"){ // V1 フォーマット ?>
				<input type="hidden" name="karte_ver" value="1" />
				<tr id="room"><th class="table-header-repeat line-left th_title" colspan="7">トリートメントカルテ</th></tr>
				<tr>
					<td>氏名</td>
    			<td>
						<?php echo $data["name"] ? $data["name"] : ($customer["name"] ? $customer["name"] : $customer["name_kana"]);?>　様
					</td>
					<td>施術メニュー</td>
          <td>
  					<!-- 今回施術したコース -->
            <?php if($reservation['multiple_contract_id']){
              foreach ($select_contract_array as $key => $value):
               echo "<span class='arrow_btn'>".$course_list[$value['course_id']]."</span>";
  	          endforeach; }else{ ?>
              1回コース当日
            <?php } ?>
          </td>
				</tr>
				<tr>
					<!-- <th colspan="7" style="text-align:center;"> -->
          <th colspan="4">
  					<?php if($pre_reservation ){?>
              <a class="arrow_btn" href="./karte.php?reservation_id=<?php echo $pre_reservation?>">
    						<img src="../images/table/paging_left.gif" title="前へ" />
              </a>
            <?php }?>
  					<!-- <?php echo $reservation['r_times'];?> 回目 -->来店日時： <?php echo str_replace("-","/",$reservation['hope_date']);?> <?php echo $gTime2[$reservation['hope_time']];?>
  					<?php if($next_reservation ){?><a class="arrow_btn" href="./karte.php?reservation_id=<?php echo $next_reservation?>"><?php }?>
  						<img src="../images/table/paging_right.gif" title="次へ" />
  					<?php if($next_reservation ){?></a><?php }?>
              <?php if($next_reservation ){?>
              <a class="arrow_btn" href="./karte.php?reservation_id=<?php echo $next_reservation?>">
                <img src="../images/table/paging_right.gif" title="次へ" />
              </a>
              <?php }?>
  					</th>
  				</tr>
  				<tr>
  					<td >当日の剃毛　( <?php echo InputRadioTag("shaving",$gShaving,$data['shaving']," &nbsp;")?> )</td>
  					<td>効果実感（ <?php echo InputRadioTag("effect",$gEffect,$data['effect']," &nbsp;")?> ）</td>
  					<td colspan="2">
  						<!--主担当担当：<select name="tstaff_id" ><?php Reset_Select_Key( $staff_list , $data['tstaff_id'] ? $data['tstaff_id'] : $reservation['tstaff_id']);?></select>
  						サブ担当1：<select name="tstaff_sub1_id" ><?php Reset_Select_Key( $staff_list , $data['tstaff_sub1_id'] ? $data['tstaff_sub1_id'] : $reservation['tstaff_sub1_id']);?></select>
  						サブ担当2：<select name="tstaff_sub2_id" ><?php Reset_Select_Key( $staff_list , $data['tstaff_sub2_id'] ? $data['tstaff_sub2_id'] : $reservation['tstaff_sub2_id']);?></select>-->

  						主担当：<select name="tstaff_id" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_id'] ? $data['tstaff_id'] : $reservation['tstaff_id'],getDatalist5("shop",$reservation['shop_id']));?></select>
  						サブ担当1：<select name="tstaff_sub1_id"><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_sub1_id'] ? $data['tstaff_sub1_id'] : $reservation['tstaff_sub1_id'],getDatalist5("shop",$reservation['shop_id']));?></select>
  						サブ担当2：<select name="tstaff_sub2_id"><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_sub2_id'] ? $data['tstaff_sub2_id'] : $reservation['tstaff_sub2_id'],getDatalist5("shop",$reservation['shop_id']));?></select>
  					</td>
  				</tr>
  				<tr>
  					<td>脚の　 　　<?php echo InputCheckboxTag2("foot",$gFoot,$data['foot']," &nbsp;")?>　</td>
  					<td>ｱﾚﾙｷﾞｰ：<input type="text" name="allergy" value="<?php echo $data["allergy"] ;?>" /></td>
  					<td colspan="2">トリートメント後の肌状態(　<?php echo InputCheckboxTag2("skin_status",$gSkinStatus,$data['skin_status']," &nbsp;")?>　）</td>
  				</tr>
  				<tr>
  					<td>手の指　　  <?php echo InputCheckboxTag2("finger",$gFinger,$data['finger']," &nbsp;")?></td>
  					<td>薬服用（ <?php echo InputRadioTag("drug1",$gDrug1,$data['drug1']," &nbsp;")?> ）（<?php echo InputRadioTag("drug2",$gDrug2,$data['drug2']," &nbsp;")?>)
            </td>
  					<td colspan="2">毛質(<?php echo InputRadioTag("hair_quality",$gHairQuality,$data['hair_quality']," &nbsp;")?> ）　毛量(<?php echo InputRadioTag("hair_amount",$gHairAmount,$data['hair_amount']," &nbsp;")?> ）</td>
  				</tr>
  				<tr>
  					<td>V (<input type="text" name="datumo_v" value="<?php echo $data["datumo_v"] ;?>" />)</td>
  					<td>皮膚（<?php echo InputCheckboxTag2("skin",$gSkin,$data['skin']," &nbsp;")?>)</td>
  					<!-- <td colspan="2" rowspan="3">繰り越し部位<br><textarea name="repeat_part" cols=70 rows=4><?php echo ($data["repeat_part"] ? $data["repeat_part"] : $pre_data["repeat_part"]) ;?></textarea></td> -->
  				</tr>
  				<tr>
  					<td>IO(<input type="text" name="datumo_io" value="<?php echo $data["datumo_io"] ;?>" />)</td>
  					<td>日焼け（ <?php echo InputRadioTag("sunburn",$gSunburn,$data['sunburn']," &nbsp;")?> ）</td>
  				</tr>
  				<tr>
  					<td>うなじ（<input type="text" name="neck" value="<?php echo $data["neck"] ;?>" />）</td>
  					<td>タトゥー（箇所<input type="text" name="tattoo" value="<?php echo $data["tattoo"] ;?>" />）</td>
  				</tr>
  				<tr>
  					<td colspan="2">備考<br><textarea name="memo" cols=70 rows=6><?php echo $data["memo"] ;?></textarea></td>
  					<td colspan="2" >特記事項<br><textarea name="report" cols=70 rows=6><?php echo $data["report"] ;?></textarea></td>
  				</tr>
				<?php } else { // v2 フォーマット ?>
				<input type="hidden" name="karte_ver" value="2"/>
				<tr id="room"><th class="table-header-repeat line-left th_title" colspan="4">トリートメントカルテ</th></tr>
				<tr>
					<td>氏名</td>
    			<td>
						<?php echo $data["name"] ? $data["name"] : ($customer["name"] ? $customer["name"] : $customer["name_kana"]);?>　様
					</td>
					<td>施術メニュー</td>
          <td>
			        <!-- 今回施術したコース -->
            <?php if($reservation['multiple_contract_id']){
              foreach ($select_contract_array as $key => $value):
               echo "<span class='arrow_btn'>".$course_list[$value['course_id']]."</span>";
              endforeach; }else{ ?>
              1回コース当日
            <?php } ?>
          </td>
				</tr>
				<tr>
          <th colspan="4">
					<?php if($pre_reservation ){?>
            <a class="arrow_btn" href="./karte.php?reservation_id=<?php echo $pre_reservation?>">
  						<img src="../images/table/paging_left.gif" title="前へ" />
            </a>
          <?php }?>
					<!-- <?php echo $reservation['r_times'];?> 回目　 -->来店日時： <?php echo str_replace("-","/",$reservation['hope_date']);?> <?php echo $gTime2[$reservation['hope_time']];?>
					<?php if($next_reservation ){?>
            <a class="arrow_btn" href="./karte.php?reservation_id=<?php echo $next_reservation?>">
						<img src="../images/table/paging_right.gif" title="次へ" />
            </a>
          <?php }?>
					</th>
				</tr>
				<tr>
          <td colspan="2">
						主担当：<select name="tstaff_id"><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_id'] ? $data['tstaff_id'] : $reservation['tstaff_id'],getDatalist5("shop",$reservation['shop_id']));?></select>
					</td>
          <td colspan="2">
						サブ担当1：<select name="tstaff_sub1_id"><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_sub1_id'] ? $data['tstaff_sub1_id'] : $reservation['tstaff_sub1_id'],getDatalist5("shop",$reservation['shop_id']));?></select>
						サブ担当2：<select name="tstaff_sub2_id"><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_sub2_id'] ? $data['tstaff_sub2_id'] : $reservation['tstaff_sub2_id'],getDatalist5("shop",$reservation['shop_id']));?></select>
					</td>
				</tr>
				<tr>
          <td>
						ｼｪｰﾋﾞﾝｸﾞの状態
					</td>
					<td colspan="3">
						<input style="width:100%;" type="text" name="shaving_stat" value="<?php echo $data["shaving_stat"]; ?>" />
					</td>
				</tr>
				<tr>
					<td>
						毛質
					</td>
					<td colspan="3" class="td_space">
						<?php echo InputRadioLabelTag("hair_quality",$gHairQuality,$data['hair_quality'],"")?>
					</td>
				</tr>
				<tr>
					<td>
						毛量
					</td>
					<td colspan="3" class="td_space">
						<?php echo InputRadioLabelTag("hair_amount",$gHairAmount,$data['hair_amount'],"")?>
					</td>
				</tr>
				<tr>
					<td>
						肌色
					</td>
					<td colspan="3" class="td_space">
						<?php echo InputRadioLabelTag("skin_color",$gSkinColor,$data['skin_color'],"")?>
					</td>
				</tr>
				<tr>
					<td>
						肌質
					</td>
					<td>
						<?php echo InputRadioLabelTag("skin_type",$gSkinType,$data['skin_type'],"")?>
					</td>
					<td>
						お顔
					</td>
          <td>
						<?php echo InputRadioLabelTag("face",$gSkinType,$data['face'],"")?>
					</td>
				</tr>
        <tr class="parts_karte">
          <td class="col_fst">部位</td>
          <td class="col_scnd">出力</td>
          <td class="col_srd">施術パーツ</td>
          <td>肌状態・形</td>
        </tr>
        <tr>
          <td>
            お顔
          </td>
          <td>
            <input style="width:60px;" type="number" name="face_j" value="<?php echo $data["face_j"]; ?>"/> J <!-- &nbsp;<input style="width:60px;" type="number" name="face_p" value="<?php echo $data["face_p"]; ?>"/> plus -->
          </td>
          <td>
            <?php echo InputCheckboxTagKeyFold("face_parts",$gSuggestedPart,$data['face_parts'],"",8,'',0,8)?>
            <span class="all_checked_btn">
              <label><input type="button" name="face_parts" id="face_p_all" class="all_checked" />全て</label>
            </span>
          </td>
          <td>
            <label><input type="checkbox" name="face_stat[]" value="全剃り" <?php echo (false !== mb_strpos($data['face_stat'],"全剃り",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 全剃り </label>
            <label><input type="checkbox" name="face_stat[]" value="整える" <?php echo (false !== mb_strpos($data['face_stat'],"整える",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 整える </label>（形：<input type="text" name="face_line" value="<?php echo $data["face_line"]; ?>"/>）<br>
            毛質：
            <label><input type="checkbox" name="face_stat[]" value="太い" <?php echo (false !== mb_strpos($data['face_stat'],"太い",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 太い </label>
            <label><input type="checkbox" name="face_stat[]" value="普通" <?php echo (false !== mb_strpos($data['face_stat'],"普通",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 普通 </label>
            <label><input type="checkbox" name="face_stat[]" value="細い" <?php echo (false !== mb_strpos($data['face_stat'],"細い",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 細い</label><br>
            毛量：
            <label><input type="checkbox" name="face_stat[]" value="多い" <?php echo (false !== mb_strpos($data['face_stat'],"多い",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 多い </label>
            <label><input type="checkbox" name="face_stat[]" value="普通" <?php echo (false !== mb_strpos($data['face_stat'],"普通",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 普通 </label>
            <label><input type="checkbox" name="face_stat[]" value="少ない" <?php echo (false !== mb_strpos($data['face_stat'],"少ない",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 少ない</label><br>
            その他：
            <input style="width:100%;" type="text" name="face_other" value="<?php echo $data["face_other"]; ?>"/>
          </td>
        </tr>
				<tr>
					<td>
						うなじ
					</td>
					<td>
						<input style="width:60px;" type="number" name="neck_j" value="<?php echo $data["neck_j"]; ?>"/> J <!-- &nbsp;<input style="width:60px;" type="number" name="neck_p" value="<?php echo $data["neck_p"]; ?>"/> plus -->
					</td>
					<td>
            <?php echo InputCheckboxTagKeyFold("neck_parts",$gSuggestedPart,$data['neck_parts'],"",1,'',8,1)?>
					</td>
					<td>
						<input style="width:100%;" type="text" name="neck_other" value="<?php echo $data["neck_other"]; ?>"/>
					</td>
				</tr>
        <tr>
        <tr>
          <td>
            お腹・胸
          </td>
          <td>
            <input style="width:60px;" type="number" name="stomach_j" value="<?php echo $data["stomach_j"]; ?>"/> J <!-- &nbsp;<input style="width:60px;" type="number" name="stomach_p" value="<?php echo $data["stomach_p"]; ?>"/> plus -->
          </td>
          <td>
            <?php echo InputCheckboxTagKeyFold("stomach_parts",$gSuggestedPart,$data['stomach_parts'],"",2,'',9,2)?>
            <span class="all_checked_btn">
              <label><input type="button" name="stomach_parts" class="all_checked" />全て</label>
            </span>
          </td>
          <td>
            <label><input type="checkbox" name="stomach_stat[]" value="ニキビ" <?php echo (false !== mb_strpos($data['stomach_stat'],"ニキビ",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> ニキビ  </label>
            <label><input type="checkbox" name="stomach_stat[]" value="ﾆｷﾋ跡" <?php echo (false !== mb_strpos($data['stomach_stat'],"ﾆｷﾋ跡",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> ニキビ跡  </label>
            <label><input type="checkbox" name="stomach_stat[]" value="乾燥" <?php echo (false !== mb_strpos($data['stomach_stat'],"乾燥",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 乾燥  </label>
            <label><input type="checkbox" name="stomach_stat[]" value="日焼け" <?php echo (false !== mb_strpos($data['stomach_stat'],"日焼け",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 日焼け  </label>
            <label><input type="checkbox" name="stomach_stat[]" value="色素沈着" <?php echo (false !== mb_strpos($data['stomach_stat'],"色素沈着",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 色素沈着 </label><br>
            <label><input type="checkbox" name="stomach_stat[]" value="アザ" <?php echo (false !== mb_strpos($data['stomach_stat'],"アザ",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> アザ </label>（部位：<input style="width:100px;" type="text" name="stomach_nevus" value="<?php echo $data["stomach_nevus"]; ?>"/>）
            <label><input type="checkbox" name="stomach_stat[]" value="傷" <?php echo (false !== mb_strpos($data['stomach_stat'],"傷",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 傷 </label>（部位：<input style="width:100px;" type="text" name="stomach_scar" value="<?php echo $data["stomach_scar"]; ?>"/>）<br>
            <label><input type="checkbox" name="stomach_stat[]" value="タトゥー" <?php echo (false !== mb_strpos($data['stomach_stat'],"タトゥー",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> タトゥー </label>（部位：<input style="width:100px;" type="text" name="stomach_tattoo" value="<?php echo $data["stomach_tattoo"]; ?>"/>）
          </td>
        <tr>
          <td>
            背中
          </td>
          <td>
            <input style="width:60px;" type="number" name="back_j" value="<?php echo $data["back_j"]; ?>"/> J <!-- &nbsp;<input style="width:60px;" type="number" name="back_p" value="<?php echo $data["back_p"]; ?>"/> plus -->
          </td>
          <td>
            <?php echo InputCheckboxTagKeyFold("back_parts",$gSuggestedPart,$data['back_parts'],"",2,'',11,2)?>
            <span class="all_checked_btn">
              <label><input type="button" name="back_parts" class="all_checked" />全て</label>
            </span>
          </td>
          <td>
            <label><input type="checkbox" name="back_stat[]" value="ニキビ" <?php echo (false !== mb_strpos($data['back_stat'],"ニキビ",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> ニキビ </label>
            <label><input type="checkbox" name="back_stat[]" value="ﾆｷﾋ跡" <?php echo (false !== mb_strpos($data['back_stat'],"ﾆｷﾋ跡",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> ニキビ跡 </label>
            <label><input type="checkbox" name="back_stat[]" value="乾燥" <?php echo (false !== mb_strpos($data['back_stat'],"乾燥",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 乾燥 </label>
            <label><input type="checkbox" name="back_stat[]" value="日焼け" <?php echo (false !== mb_strpos($data['back_stat'],"日焼け",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 日焼け </label>
            <label><input type="checkbox" name="back_stat[]" value="色素沈着" <?php echo (false !== mb_strpos($data['back_stat'],"色素沈着",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 色素沈着 &nbsp;</label><br>
            <label><input type="checkbox" name="back_stat[]" value="アザ" <?php echo (false !== mb_strpos($data['back_stat'],"アザ",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> アザ </label>（部位：<input style="width:100px;" type="text" name="back_nevus" value="<?php echo $data["back_nevus"]; ?>"/>）
            <label><input type="checkbox" name="back_stat[]" value="傷" <?php echo (false !== mb_strpos($data['back_stat'],"傷",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 傷 </label>（部位：<input style="width:100px;" type="text" name="back_scar" value="<?php echo $data["back_scar"]; ?>"/>）<br>
            <label><input type="checkbox" name="back_stat[]" value="タトゥー" <?php echo (false !== mb_strpos($data['back_stat'],"タトゥー",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> タトゥー </label>（部位：<input style="width:100px;" type="text" name="back_tattoo" value="<?php echo $data["back_tattoo"]; ?>"/>）
          </td>
        </tr>
        </tr>
          <td>
            腕
          </td>
          <td>
            <input style="width:60px;" type="number" name="arm_j" value="<?php echo $data["arm_j"]; ?>"/> J <!-- &nbsp;<input style="width:60px;" type="number" name="arm_p" value="<?php echo $data["arm_p"]; ?>"/> plus -->
          </td>
          <td>
            <?php echo InputCheckboxTagKeyFold("arm_parts",$gSuggestedPart,$data['arm_parts'],"",4,'',13,4)?>
            <span class="all_checked_btn">
              <label><input type="button" name="arm_parts" class="all_checked" />全て</label>
            </span>
          </td>
          <td>
            手の指：
            <label><input type="checkbox" name="arm_stat[]" value="第２関節" <?php echo (false !== mb_strpos($data['arm_stat'],"第２関節",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 第２関節 </label>
            <label><input type="checkbox" name="arm_stat[]" value="第１関節" <?php echo (false !== mb_strpos($data['arm_stat'],"第１関節",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 第１関節 </label>
            <label><input type="checkbox" name="arm_stat[]" value="ニキビ" <?php echo (false !== mb_strpos($data['arm_stat'],"ニキビ",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> ニキビ  </label>
            <label><input type="checkbox" name="arm_stat[]" value="ﾆｷﾋ跡" <?php echo (false !== mb_strpos($data['arm_stat'],"ﾆｷﾋ跡",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> ニキビ跡 </label>
            <label><input type="checkbox" name="arm_stat[]" value="乾燥" <?php echo (false !== mb_strpos($data['arm_stat'],"乾燥",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 乾燥 </label><br>
            <label><input type="checkbox" name="arm_stat[]" value="日焼け" <?php echo (false !== mb_strpos($data['arm_stat'],"日焼け",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 日焼け </label>
            <label><input type="checkbox" name="arm_stat[]" value="色素沈着" <?php echo (false !== mb_strpos($data['arm_stat'],"色素沈着",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 色素沈着 </label>
            <label><input type="checkbox" name="arm_stat[]" value="アザ" <?php echo (false !== mb_strpos($data['arm_stat'],"アザ",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> アザ  </label>（部位：<input style="width:100px;" type="text" name="arm_nevus" value="<?php echo $data["arm_nevus"]; ?>"/>） <br>
            <label><input type="checkbox" name="arm_stat[]" value="傷" <?php echo (false !== mb_strpos($data['arm_stat'],"傷",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 傷  </label>（部位：<input style="width:100px;" type="text" name="arm_scar" value="<?php echo $data["arm_scar"]; ?>"/>）
            <label><input type="checkbox" name="arm_stat[]" value="タトゥー" <?php echo (false !== mb_strpos($data['arm_stat'],"タトゥー",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> タトゥー  </label>（部位：<input style="width:100px;" type="text" name="arm_tattoo" value="<?php echo $data["arm_tattoo"]; ?>"/>）<br>
            脇：<label><input type="checkbox" name="arm_stat[]" value="全剃り" <?php echo (false !== mb_strpos($data['arm_stat'],"全剃り",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 全剃り </label>
            <label><input type="checkbox" name="arm_stat[]" value="形を整える" <?php echo (false !== mb_strpos($data['arm_stat'],"形を整える",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 形を整える</label> （幅：<input style="width:40px;" type="number" name="arm_v_width" value="<?php echo $data["arm_v_width"]; ?>"/>cm・高さ：<input style="width:40px;" type="number" name="arm_v_height" value="<?php echo $data["arm_v_height"]; ?>"/>cm）<br>
          </td>
        </tr>
				<tr>
					<td>
						ヒップ
					</td>
					<td>
						<input style="width:60px;" type="number" name="buttocks_j" value="<?php echo $data["buttocks_j"]; ?>"/> J <!-- &nbsp;<input style="width:60px;" type="number" name="buttocks_p" value="<?php echo $data["back_p"]; ?>"/> plus -->
					</td>
          <td>
            <?php echo InputCheckboxTagKeyFold("buttocks_parts",$gSuggestedPart,$data['buttocks_parts'],"",1,'',17,1)?>
          </td>
					<td>
						<label><input type="checkbox" name="buttocks_stat[]" value="ニキビ" <?php echo (false !== mb_strpos($data['buttocks_stat'],"ニキビ",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> ニキビ </label>
						<label><input type="checkbox" name="buttocks_stat[]" value="ﾆｷﾋ跡" <?php echo (false !== mb_strpos($data['buttocks_stat'],"ﾆｷﾋ跡",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> ニキビ跡 </label>
						<label><input type="checkbox" name="buttocks_stat[]" value="乾燥" <?php echo (false !== mb_strpos($data['buttocks_stat'],"乾燥",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 乾燥 </label>
						<label><input type="checkbox" name="buttocks_stat[]" value="日焼け" <?php echo (false !== mb_strpos($data['buttocks_stat'],"日焼け",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 日焼け </label>
						<label><input type="checkbox" name="buttocks_stat[]" value="色素沈着" <?php echo (false !== mb_strpos($data['buttocks_stat'],"色素沈着",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 色素沈着 </label><br>
						<label><input type="checkbox" name="buttocks_stat[]" value="アザ" <?php echo (false !== mb_strpos($data['buttocks_stat'],"アザ",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> アザ </label>（部位：<input style="width:100px;" type="text" name="buttocks_nevus" value="<?php echo $data["buttocks_nevus"]; ?>"/>）
						<label><input type="checkbox" name="buttocks_stat[]" value="傷" <?php echo (false !== mb_strpos($data['buttocks_stat'],"傷",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 傷 </label>（部位：<input style="width:100px;" type="text" name="buttocks_scar" value="<?php echo $data["buttocks_scar"]; ?>"/>）<br>
						<label><input type="checkbox" name="buttocks_stat[]" value="タトゥー" <?php echo (false !== mb_strpos($data['buttocks_stat'],"タトゥー",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> タトゥー </label>（部位：<input style="width:100px;" type="text" name="buttocks_tattoo" value="<?php echo $data["buttocks_tattoo"]; ?>"/>）
					</td>
				</tr>
        <tr>
          <td>
            脚
          </td>
          <td>
            <input style="width:60px;" type="number" name="foot_j" value="<?php echo $data["foot_j"]; ?>"/> J <!-- &nbsp;<input style="width:60px;" type="number" name="foot_p" value="<?php echo $data["foot_p"]; ?>"/> plus -->
          </td>
          <td>
            <?php echo InputCheckboxTagKeyFold("foot_parts",$gSuggestedPart,$data['foot_parts'],"",4,'',18,4)?>
            <span class="all_checked_btn">
              <label><input type="button" name="foot_parts" class="all_checked" />全て</label>
            </span>
          </td>
          <td>
            <label><input type="checkbox" name="foot_stat[]" value="甲指" <?php echo (false !== mb_strpos($data['foot_stat'],"甲指",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 甲指 </label>
            <label><input type="checkbox" name="foot_stat[]" value="指のみ" <?php echo (false !== mb_strpos($data['foot_stat'],"指のみ",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 指のみ  </label>（<input style="width:100px;" type="text" name="foot_toe" value="<?php echo $data["foot_toe"]; ?>"/>）<br>
            <label><input type="checkbox" name="foot_stat[]" value="ニキビ" <?php echo (false !== mb_strpos($data['foot_stat'],"ニキビ",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> ニキビ </label>
            <label><input type="checkbox" name="foot_stat[]" value="ﾆｷﾋﾞ跡" <?php echo (false !== mb_strpos($data['foot_stat'],"ﾆｷﾋﾞ跡",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> ニキビ跡 </label>
            <label><input type="checkbox" name="foot_stat[]" value="乾燥" <?php echo (false !== mb_strpos($data['foot_stat'],"乾燥",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 乾燥 </label>
            <label><input type="checkbox" name="foot_stat[]" value="日焼け" <?php echo (false !== mb_strpos($data['foot_stat'],"日焼け",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 日焼け </label>
            <label><input type="checkbox" name="foot_stat[]" value="色素沈着" <?php echo (false !== mb_strpos($data['foot_stat'],"色素沈着",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 色素沈着 </label><br>
            <label><input type="checkbox" name="foot_stat[]" value="アザ" <?php echo (false !== mb_strpos($data['foot_stat'],"アザ",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> アザ  </label>（部位：<input style="width:100px;" type="text" name="foot_nevus" value="<?php echo $data["foot_nevus"]; ?>"/>）
            <label><input type="checkbox" name="foot_stat[]" value="傷" <?php echo (false !== mb_strpos($data['foot_stat'],"傷",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 傷  </label>（部位：<input style="width:100px;" type="text" name="foot_scar" value="<?php echo $data["foot_scar"]; ?>"/>）<br>
            <label><input type="checkbox" name="foot_stat[]" value="タトゥー" <?php echo (false !== mb_strpos($data['foot_stat'],"タトゥー",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> タトゥー  </label>（部位：<input style="width:100px;" type="text" name="foot_tattoo" value="<?php echo $data["foot_tattoo"]; ?>"/>）
          </td>
        </tr>
        <tr>
          <td>
            ＶＩＯ
          </td>
          <td>
            <input style="width:60px;" type="number" name="vio_j" value="<?php echo $data["vio_j"]; ?>"/> J <!-- &nbsp;<input style="width:60px;" type="number" name="vio_p" value="<?php echo $data["vio_p"]; ?>"/> plus -->
          </td>
          <td>
            <?php echo InputCheckboxTagKeyFold("vio_parts",$gSuggestedPart,$data['vio_parts'],"",3,'',22,3)?>
            <span class="all_checked_btn">
              <label><input type="button" name="vio_parts" class="all_checked" />全て</label>
            </span>
          </td>
          <td>
            Ｖライン：
            <label><input type="checkbox" name="vio_v_stat[]" value="全剃り" <?php echo (false !== mb_strpos($data['vio_v_stat'],"全剃り",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 全剃り </label>
            <label><input type="checkbox" name="vio_v_stat[]" value="産毛を剃る" <?php echo (false !== mb_strpos($data['vio_v_stat'],"産毛を剃る",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 産毛を剃る </label>
            <label><input type="checkbox" name="vio_v_stat[]" value="形を整える" <?php echo (false !== mb_strpos($data['vio_v_stat'],"形を整える",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 形を整える</label> （幅：<input style="width:40px;" type="number" name="vio_v_width" value="<?php echo $data["vio_v_width"]; ?>"/>cm・高さ：<input style="width:40px;" type="number" name="vio_v_height" value="<?php echo $data["vio_v_height"]; ?>"/>cm）<br>
            Ｉライン：
            <label><input type="checkbox" name="vio_i_stat[]" value="全剃り" <?php echo (false !== mb_strpos($data['vio_i_stat'],"全剃り",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 全剃り </label>
            <label><input type="checkbox" name="vio_i_stat[]" value="玉・竿残す" <?php echo (false !== mb_strpos($data['vio_i_stat'],"玉・竿残す",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 玉・竿残す </label>
            <label><input type="checkbox" name="vio_i_stat[]" value="整える" <?php echo (false !== mb_strpos($data['vio_i_stat'],"整える",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 整える </label>（幅：<input style="width:40px;" type="number" name="vio_i_width" value="<?php echo $data["vio_i_width"]; ?>"/>cm残す）<br>
            Ｏライン：
            <label><input type="checkbox" name="vio_o_stat[]" value="全剃り" <?php echo (false !== mb_strpos($data['vio_o_stat'],"全剃り",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 全剃り </label>
            <label><input type="checkbox" name="vio_o_stat[]" value="残す" <?php echo (false !== mb_strpos($data['vio_o_stat'],"残す",0,"UTF-8")) ? 'checked="checked"' : ''; ?>> 残す </label>
          </td>
        </tr>
				<tr>
          <td colspan="4">
            ※注意事項<br>
            <textarea style="width:100%;" name="notice" cols=70 rows=6><?php echo $data["notice"] ;?></textarea>
          </td>
				</tr>
<!-- 				<tr>
          <td colspan="4">
            ※繰越部位 &nbsp;
						<?php echo InputRadioLabelTag("repeat_part_chk",$gRepeatPartChk,$data['repeat_part_chk']," &nbsp;")?><br>
            <textarea style="width:100%;" name="repeat_part_memo" cols=70 rows=6><?php echo $data["repeat_part_memo"] ? $data["repeat_part_memo"] : $pre_data["repeat_part_memo"];?></textarea>
          </td>
				</tr> -->
				<tr>
          <td colspan="4">
            ※コミュニケーション内容　<br><textarea style="width:100%;" name="communication" cols=70 rows=6><?php echo $data["communication"] ;?></textarea>
          </td>
				</tr>
				<?php } ?>
				</table>
				<div class="btn-area-bottom">
          <input type="submit" class="submit" value="　完了　" />
          <input type="reset" class="reset" value="　リセット　" />
        </div>
				<!--  end product-table................................... -->
				</form>
			</div>
			<!--  end content-table  -->
		</div>
		<!--  end content-table-inner ............................................END  -->
	</div>

</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->
<script type="text/javascript">
  function check_all(btn){ //全てにチェックを入れる
    var $this,target_name,$target;
    $this = $(btn), //「全て」ボタン
    target_name = $this.attr("name"); //「全て」ボタンname
    $target = $("input[name^="+ target_name +"]"); //「全て」ボタンとnameが同一のボタン（前方一致で同一）
    if(!$this.hasClass('checked')){
      $this.addClass('checked');
      $target.prop("checked", true); //全てにチェック
    }else{
      $this.removeClass('checked');
      $target.prop("checked", false); //全てのチェックをはずす
    }
  };
  $(".all_checked").on("click",function(){
    check_all(this);
  });
</script>
<?php include_once("../include/footer.html");?>
