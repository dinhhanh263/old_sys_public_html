<?php include_once("../library/reservation/karte_c.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SYSTEM</title>
<link rel="stylesheet" href="../css/screen.css" type="text/css" media="screen" title="default" />

<!-- ブラウザの「戻る」ボタンを禁止-->
<!-- <script type="text/javascript">
function redirect(url)
{
    var Backlen=history.length;
    history.go(-Backlen);
    window.location.replace(url);
}
</script> -->

<script type="text/javascript" src="../js/main.js"></script>
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="css/pro_dropline_ie.css" />
<![endif]-->

<!--  jquery core -->
<script src="../js/jquery/jquery-1.4.1.min.js" type="text/javascript"></script>

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
<!-- <script src="../js/jquery/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('.styledselect_form_1').selectbox({ inputClass: "styledselect_form_1" });
  $('.styledselect_form_2').selectbox({ inputClass: "styledselect_form_2" });
});
</script> -->

<!--  styled select box script version 3 -->
<!-- <script src="../js/jquery/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('.styledselect_pages').selectbox({ inputClass: "styledselect_pages" });
});
</script> -->

<!--  styled file upload script -->
<!-- <script src="../js/jquery/jquery.filestyle.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
  $(function() {
      $("input.file_1").filestyle({
          image: "../images/forms/choose-file.gif",
          imageheight : 29,
          imagewidth : 78,
          width : 300
      });
  });
</script> -->

<!-- Custom jquery scripts -->
<script src="../js/jquery/custom_jquery.js" type="text/javascript"></script>

<!-- Tooltips -->
<!-- <script src="../js/jquery/jquery.tooltip.js" type="text/javascript"></script>
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
</script> -->


<!--  date picker script -->
<!-- <link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.all.css" />
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
</script> -->
<style type="text/css">
  span.ui-datepicker-year {
    margin-right:1em;
  }
</style>
<!--全角英数字、ハイフン->半角-->
<!-- <script type="text/javascript">
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
</script> -->

<!-- MUST BE THE LAST SCRIPT IN <HEAD></HEAD></HEAD> png fix -->
<script src="../js/jquery/jquery.pngFix.pack.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$(document).pngFix( );
});
</script>
<!-- <style>
#id-form th{
	line-height:28px;
	min-width:60px;
	padding:0 0 10px 10px;
	text-align: left;
	width: 80px;
}
</style> -->

<!-- ブラウザの「戻る」ボタンを禁止-->
<script type="text/javascript">
history.forward();
</script>

</head>
<body onunload="">
<!-- Start: page-top-outer -->
<div id="page-top-outer" style="height:50px">

<!-- Start: page-top -->
<div id="page-top">

  <!-- start logo -->
  <div id="logo" style="margin:5px 0 0 15px">
  <!--<a href="../main/"><img src="../images/shared/logo.png" height="40" alt="" /></a>-->
   <a href="../main/?shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/shared/logo.png"  height="40" alt="" /></a>
  </div>
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
				<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');" class="w90 center-cnt">
				<input type="hidden" name="action" value="edit" />
				<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
				<input type="hidden" name="shop_id" value="<?php echo $customer["shop_id"];?>" />
				<input type="hidden" name="customer_id" value="<?php echo $_POST["customer_id"];?>" />
				<div style="text-align:right;">
					カウンセリング担当：<!--<select name="staff_id" ><?php Reset_Select_Key( $staff_list , $data['staff_id'] ? $data['staff_id'] : $customer['cstaff_id']);?></select>-->
					<select name="staff_id"  ><?php Reset_Select_Array_Group( getDatalistArray("staff","shop_id") , $data['staff_id'] ? $data['staff_id'] : $customer['cstaff_id'],getDatalist5("shop",$data['shop_id'] ? $data['shop_id'] : $customer['shop_id']));?></select>
				</div>
				<br />
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table" class="registration-cnt">
  				<tr id="room">
            <th class="table-header-repeat line-left" colspan="4" style="text-align:center;"><a href="">カウンセリングカルテ</a></th>
          </tr>
  				<tr>
  					<td>お客様氏名</td><td><input type="text" name="name" value="<?php echo $data['name'] ? $data['name'] : $customer['name'];?>"/>　様</td>
  					<td>記入日</td>
            <td>
              <div>
                <input type="text" name="input_date" value="<?php echo $data['input_date'] ? $data['input_date'] : date("Y-m-d");?>">
              </div>
              <?php echo $data['edit_date'] ? ('<div class="previous-item">（最終更新日：'.$data['edit_date'].'）</div>') : '' ?>
            </td>
  				</tr>
  				<!-- <tr><td colspan="21"></td></tr> -->

          <tr>
            <td colspan="4">
              <div class="registration-ttl100">
                脱毛しようと思ったきっかけ（ex.自己処理がめんどくさいから）
              </div>
              <div>
                <textarea name="inspired" class="registration-textarea registration-center-indent w90"><?php echo $data['inspired'];?></textarea>
              </div>
              <div class="registration-ttl100">
                理想・目標（ex.自己処理しなくて良くなるまでツルツルにしたい）
              </div>
              <div>
                <textarea name="ideal_form" class="registration-textarea registration-center-indent w90"><?php echo $data['ideal_form'];?></textarea>
              </div>
            </td>
          </tr>

  				<tr>
            <td colspan="4">
              <div class="registration-ttl100">お客様の特徴<!-- （ex.脱毛未経験で痛みを心配されていた、アルコールでかぶれた、他店で顔が荒れた　など・・・） -->
                <span class="previous-item">脱毛経験： <?php echo $experience.$ex_history.$ex_period; ?> （ありの場合はどこのサロンでどこのパーツを何回くらいやったか、また辞めた理由など）</span>
              </div>
              <div>
                <textarea name="feature" class="registration-textarea registration-center-indent w90"><?php echo $data['feature'];?></textarea>
              </div>
            </td>
          </tr>

          <tr>
            <td colspan="4">
              <div class="registration-ttl100">
                一番気になるパーツ
              </div>
              <div>
                <input name="part_bothers" class="registration-form registration-center-indent w20" value="<?php echo $data['part_bothers'];?>">
              </div>
            </td>
          </tr>

  				<tr>
            <td colspan="4">
              <div class="registration-ttl100">
                お客様の要望<!-- （ex.ワキは施術が他店で完了している、もみあげを気にされている、うなじにこだわりあり　など・・・） -->
              </div>
              <div>
                <ul class="regster_cont">
                  <li>
                    <span class="registration-ttl3">期間</span>
                    <input name="hope_period" class="registration-form registration-center-indent w20" value="<?php echo $data['hope_period'];?>"></li>
                  <li>
                    <span class="registration-ttl3">予算</span>
                    <input name="hope_budget" class="registration-form registration-center-indent w20" value="<?php echo $data['hope_budget'];?>"></li>
                  <li>
                    <span class="registration-ttl3">お悩み（予約の取りやすさ、痛み）</span>
                    <textarea name="worry" class="registration-textarea registration-center-indent w90"><?php echo $data['worry'];?></textarea>
                  </li>
                </ul>
              </div>
            </td>
          </tr>

  				<tr>
            <td colspan="4">
              <div class="old_information_ttl old_information_button">以前の情報（備考・クロージング内容）</div>
              <div class="old_information">
                <div class="registration-ttl100">
                  備考
                </div>
                <div>
                  <textarea name="memo" class="registration-textarea registration-center-indent w90"><?php echo $data['memo'];?></textarea>
                </div>
                <div class="registration-ttl100">
                  要望
                </div>
                <div>
                  <textarea name="need" class="registration-textarea registration-center-indent w90"><?php echo $data['need'];?></textarea>
                </div>
                <div class="registration-ttl100">
                  クロージング内容<!-- (ex.月額希望で来店、パックのお得さ説明し、本当は18回に興味があるが、金銭面の都合で6回スタート　など・・・ ) -->
                </div>
                <div>
                  <textarea name="closing" class="registration-textarea registration-center-indent w90"><?php echo $data['closing'];?></textarea>
                </div>
              </div>
            </td>
          </tr>

				</table>
				<div style="text-align:center;"><input type="submit" class="submit" value="　完了　" />　　　　　<input type="reset" class="reset" value="　リセット　" /></div>
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
<script type="text/javascript">
  $(document).ready(function() {
    $('.old_information_button').click(function(){
      $(this).next().stop().slideToggle(200);
    });
  });
</script>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>