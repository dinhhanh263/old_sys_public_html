<?php include_once("../library/customer/sheet.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>KIREIMO SYSTEM</title>
	<link rel="shortcut icon" href="../images/favicon.ico" />
	<link rel="stylesheet" href="../css/screen.css" type="text/css" media="screen" title="default" />
	<link href="../css/part.css" rel="stylesheet" type="text/css" />

	<!-- ブラウザの「戻る」ボタンを禁止-->
	<script type="text/javascript">
		function redirect(url)
		{
			var Backlen=history.length;
			history.go(-Backlen);
			window.location.replace(url);
		}
	</script>

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

<script type="text/javascript">
//ブラウザの「戻る」ボタンを禁止
history.forward();

// 日付を2桁(1桁の場合前に0)にする関数
function dateNum(num) {
	if ((num > 0) && (num < 10))  {
		num = "0" + num;
	}
	return num;
}

// 生年月日から年齢を算出する関数
function dateToAge(birthday, input_date) {
	var date = new Date(birthday);
	if(birthday == (date.getFullYear() + "/" + (date.getMonth() + 1) + "/" + date.getDate())){
		var rec;
		var birth_y = document.form1.birthday_y.value;
		var birth_m = document.form1.birthday_m.value;
		var birth_d = document.form1.birthday_d.value;
		var today = new Date();
		var att;
		var date_att = new Date(input_date); // 問診票記入日
		var sheet_flg = <?php echo $data['id'] ? 1 : 0 ?>;
		if ( parseInt(birth_m, 10) * 100 + parseInt(birth_d, 10) > (today.getMonth() + 1) * 100 + today.getDate() ) {
			rec = today.getFullYear() - parseInt(birth_y, 10) - 1;
		} else {
			rec = today.getFullYear() - parseInt(birth_y, 10);
		}
		if ( parseInt(birth_m, 10) * 100 + parseInt(birth_d, 10) > (date_att.getMonth() + 1) * 100 + date_att.getDate() ) {
			att = date_att.getFullYear() - parseInt(birth_y, 10) - 1; // 問診票記入時の年齢
		} else {
			att = date_att.getFullYear() - parseInt(birth_y, 10);
		}

		if(rec > 0 && att > 0 && sheet_flg) {
			$("#age").text("　/　記入時"+att+"歳　/　現在"+rec+"歳");
			if(att < 20){
				$("#hogosha").css("display","block");
			}else{
				$("#hogosha").css("display","none");
			}
		} else if(att > 0) {
			$("#age").text("　/　"+att+"歳");
			if(att < 20){
				$("#hogosha").css("display","block");
			}else{
				$("#hogosha").css("display","none");
			}
		} else {
			$("#age").text("");
		}
	}else{
		$("#age").text("");
	}
}

$(function(){
	// 読み込み時
	input_list();
	job_sub_on(document.form1.job);
	dateToAge(($("[name='birthday_y']").val() + "/" + parseInt($("[name='birthday_m']").val(), 10) + "/" + parseInt($("[name='birthday_d']").val(), 10)), $("[name='input_date']").val());

	//　初回読み込み時の年齢(数値のみ取り出してageにセットする)
	var age_text  = document.getElementById("age").innerHTML;
	age = age_text.match(/[0-9]+\.?[0-9]*/g);
	// 初回読み込み時、年齢が入っていたら保護者ボックスを制御する
	if(age > 0) {
		if(age < 20){
			$("#hogosha").css("display","block");
		}
	}

	// 生年月日に変更があった時、年齢を算出
	$("[name='birthday_y'], [name='birthday_m'] ,[name='birthday_d'], [name='input_date']").change(function(){
		dateToAge(($("[name='birthday_y']").val() + "/" + parseInt($("[name='birthday_m']").val(), 10) + "/" + parseInt($("[name='birthday_d']").val(), 10)), $("[name='input_date']").val());
	});
});
/* checkebox,radio btn */
function input_list(){
	var input,same_name,next;
	input = document.getElementsByTagName("input");
	for(var i=0; i<input.length; i++){
		checked(input[i]);
		input[i].onclick = function(){
			if(this.type == "checkbox"){
				input_checkbox(this);
				focus_t(this);
			}else if(this.type == "radio"){
				input_radio(this);
				focus_t(this);
			};
		};
	};
	function checked(e){
		if(e.checked == true){
			e.parentNode.classList.add("checked")
		}
	};
	function input_checkbox(e){
		e.parentNode.classList.toggle("checked");
	};
	function input_radio(e){
		same_name = e.name;
		same_name = document.getElementsByName(same_name);
		e.parentNode.classList.add("checked");
		for(i=0; i<same_name.length; i++){
			if(same_name[i].checked == false && same_name[i].parentNode.classList.contains("checked")){
				same_name[i].parentNode.classList.remove("checked");
			};
		};
	};
	function focus_t(e){
		next = e.parentNode.nextElementSibling;
		if(e.checked == true && next.type == "text"){
			next.focus();
		}
	}
};
/* job select */
function job_sub_on(obj){
	var job_num,job_value,parent,next_select,next_span;
	job_num = obj.selectedIndex,
	job_value = obj[job_num].value,
	parent = obj.parentNode;
	next_select = document.createElement("select"),
	next_select.name = "job_sub",
	next_select.className = "<?php echo $errmsg['job_sub'] ? 'error_input': "" ?>",
	next_span = document.createElement("span"),
	next_span.className = "d_block indent06",
	next_span.appendChild(next_select);
	for_input_box = document.createElement("span") ,
	for_input_box.className = "d_block indent06";
	if(job_value == "1"){
		next_del(obj);
		parent.appendChild(next_span);
		next_select.innerHTML = '<?php Reset_Select_Key2($gJobSub01, $data['job_sub']); ?>';
		next_select.focus();
	}else if(job_value == "3"){
		next_del(obj);
		parent.appendChild(next_span);
		next_select.innerHTML = '<?php Reset_Select_Key2($gJobSub02, $data['job_sub']); ?>';
		next_select.focus();
	}else if(job_value == "8"){
		next_del(obj);
		parent.appendChild(for_input_box);
		for_input_box.innerHTML = '<input type="text" name="job_other" class ="<?php echo $errmsg['job_other'] ? 'error_input': '' ?>" value="<?php echo h($data["job_other"]);?>"" />';
		for_input_box.focus();
	}else{
		next_del(obj);
	};
	function next_del(obj){
		if(obj.nextSibling != null){
			parent.removeChild(obj.nextSibling);
		};
	};
};
/* step nav */
function step_nav(){
	var allH4,winH4,nowY,step1,step2,step3;
	allH4 = document.documentElement.clientHeight,
	allH4 = Math.floor(allH4/4),
	winH4 = document.getElementById("content").clientHeight
	winH4 = (Math.floor(winH4/4) - 100),
	nowY = document.documentElement.scrollTop || document.body.scrollTop,
	stepAll = document.getElementById("step"),
	step1 = document.getElementById("step1"),
	step2 = document.getElementById("step2"),
	step3 = document.getElementById("step3"),
	step4 = document.getElementById("step4");
	step1.style.cssText += ";height:" + (allH4 - 20) + "px;";
	step2.style.cssText += ";height:" + (allH4 - 20) + "px;";
	step3.style.cssText += ";height:" + (allH4 - 20) + "px;";
	step4.style.cssText += ";height:" + (allH4 - 20) + "px;";
	if(nowY < 50){
		stepAll.style.cssText += ";top:50px;";
	}else{
		stepAll.style.cssText += ";top:0;";
	};
	if(nowY < (winH4 - 500)){
		step1.classList.add("now");
		step2.classList.remove("now");
		step3.classList.remove("now");
		step4.classList.remove("now");
	}else if((winH4 - 500) <= nowY && nowY < (winH4*2)){
		step2.classList.add("now");
		step1.classList.remove("now");
		step3.classList.remove("now");
		step4.classList.remove("now");
	}else if((winH4*2) <=nowY && nowY < (winH4*3)){
		step3.classList.add("now");
		step1.classList.remove("now");
		step2.classList.remove("now");
		step4.classList.remove("now");
	}else if((winH4*3) <=nowY && nowY < (winH4*4)){
		step4.classList.add("now");
		step1.classList.remove("now");
		step2.classList.remove("now");
		step3.classList.remove("now");
	};
};
window.onscroll = function(){
	step_nav();
}
</script>

<!--郵便番号から住所の自動入力-->
<script type="text/javascript" src="https://ajaxzip3.github.io/ajaxzip3.js"></script>
<!-- 住所から郵便番号&郵便番号から住所の自動入力-->
<!-- <script type="text/javascript" src="http://jsonp-hosting.googlecode.com/svn/trunk/jsonpzip/lib/jsonpzip.js" charset="UTF-8"></script>-->

</head>
<body onunload="" id="sheet">
	<!-- Start: page-top-outer -->
	<div id="page-top-outer" style="height:50px">

		<!-- Start: page-top -->
		<div id="page-top">

			<!-- start logo -->
			<div id="logo" style="margin:5px 0 0 15px">
				<?php if($authority_level<=6){?>
				<a href="../main/"><img src="../images/shared/logo.png" height="40" alt="" /></a>
				<?php }else{?>
				<img src="../images/shared/logo.png" height="40" alt="" />
				<?php } ?>
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
						<?php if($gMsg) {echo $gMsg;}else{?>

						<!--  start sheet-table ..................................................................................... -->
						<div id="step">
							<span id="step1"></span>
							<span id="step2"></span>
							<span id="step3"></span>
							<span id="step4"></span>
						</div>
						<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');"  autocomplete="off">
							<input type="hidden" name="action" value="edit" />
							<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
							<input type="hidden" name="shop_id" value="<?php echo $customer["shop_id"] ? $customer["shop_id"] : $data["shop_id"];?>" />
							<input type="hidden" name="customer_id" value="<?php echo $_POST["customer_id"];?>" />
							<input type="hidden" name="staff_id" value="<?php echo $data["staff_id"];?>" />
							<div class="reset_btn">
								<span class="d_inline td_ques required">ご記入日</span>
								<input type="text" class="w10 <?php echo $errmsg["input_date"] ? "error_input": "" ?>" placeholder="例）<?php echo date('Y-m-d'); ?>" name="input_date" value="<?php echo h($data["input_date"]);?>" />
								<?php echo $errmsg["input_date"] ? "<span class=\"error d_block\">*".$errmsg["input_date"]."</span>" : "" ?>
								<!-- <span class="d_inline indent01">
									担当者：<select name="staff_id"><?php Reset_Select_Array_Group( getDatalistArray("staff","shop_id") , ($customer['cstaff_id'] ? $customer['cstaff_id'] : $data['staff_id']),getDatalist3("shop", $customer["shop_id"] ? $customer["shop_id"] : $data["shop_id"]));?></select>
								</span> -->
							</div>

							<p id="room" class="table-header-repeat line-left th" style="text-align:center;"><a href="" class="title">KIREIMO　問診票</a></p>
							<div class="sheet-table">
								<p class="required">この項目は必須項目です。</p>
								<p class="margin30">
									<span>
										<span class="td_ques required">お名前</span>
										<input type="text" class="<?php echo $errmsg["name"] ? "error_input": "" ?>" placeholder="例）山田　花子" name="name" value="<?php echo $customer["name"] ? h($customer["name"]) : h($data["name"]);?>"/>
									</span>
									<span class="d_inline indent01">
										<span class="td_ques required">フリガナ</span>
										<input type="text" class="<?php echo $errmsg["name_kana"] ? "error_input": "" ?>" placeholder="例）ヤマダ　ハナコ" name="name_kana" value="<?php echo $customer["name_kana"] ? h($customer["name_kana"]) : h($data["name_kana"]);?>"/>
									</span>
									<span>
										<?php echo $errmsg["name"] ? "<span class=\"error d_block\">*".$errmsg["name"]."</span>" : "" ?>
										<?php echo $errmsg["name_kana"] ? "<span class=\"error d_block\">*".$errmsg["name_kana"]."</span>" : "" ?>
									</span>
								</p>
								<p class="margin30">
									<span>
										<span class="d_inline td_ques required">生年月日</span>
										<input class="w5 <?php echo $err_birthday ? "error_input": "" ?>" type="text" placeholder="例）1994" name="birthday_y" value="<?php echo $birthday_y ? h($birthday_y) : '';?>"/>年
										<select name="birthday_m" class="<?php echo $err_birthday ? "error_input": "" ?>"><?php Reset_Select_Key($gBirthday_m ,$birthday_m); ?>	</select>月
										<select name="birthday_d" class="<?php echo $err_birthday ? "error_input": "" ?>"><?php Reset_Select_Key($gBirthday_d ,$birthday_d); ?>	</select>日
										<?php $data["age"] = Birth_To_Age($customer['birthday'],$customer['age']);?>
										<span id="age"><?php echo $err_birthday ? "" : "　/　".$data["age"]."歳"; ?></span>
									</span>
									<span class="d_inline indent01">
										<span class="td_ques">身長</span>
										<input class="w5 <?php echo $errmsg["height"] ? "error_input": "" ?>" type="number" name="height" value="<?php echo $customer["height"] ? h($customer["height"]) : h($data["height"]);?>"/>cm
									</span>
									<span class="d_inline indent01">
										<span class="td_ques">体重</span>
										<input class="w5 <?php echo $errmsg["weight"] ? "error_input": "" ?>" type="number" name="weight" value="<?php echo $customer["weight"] ? h($customer["weight"]) : h($data["weight"]);?>"/>kg
									</span>
								</p>
									<?php echo $errmsg["birthday_y"] ? "<span class=\"error d_block\">*".$errmsg["birthday_y"]."</span>" : "" ?>
									<?php echo $errmsg["birthday_m"] ? "<span class=\"error d_block\">*".$errmsg["birthday_m"]."</span>" : "" ?>
									<?php echo $errmsg["birthday_d"] ? "<span class=\"error d_block\">*".$errmsg["birthday_d"]."</span>" : "" ?>
									<?php echo $errmsg["birthday"] ? "<span class=\"error d_block\">*".$errmsg["birthday"]."</span>" : "" ?>
									<?php echo $errmsg["height"] ? "<span class=\"error d_block\">*".$errmsg["height"]."</span>" : "" ?>
									<?php echo $errmsg["weight"] ? "<span class=\"error d_block\">*".$errmsg["weight"]."</span>" : "" ?>
								<p class="margin10">
									<span class="d_inline td_ques required">ご職業</span>
									<select name="job" onchange="job_sub_on(this)" class=" <?php echo $errmsg["job"] ? "error_input": "" ?>">
										<?php Reset_Select_Key2($gJobMain, $data['job']); ?>
									</select>
									<?php echo $errmsg["job"] ? "<span class=\"error d_block\">*".$errmsg["job"]."</span>" : "" ?>
								</p>
								<?php echo $errmsg["job_sub"] ? "<span class=\"error d_block\">*".$errmsg["job_sub"]."</span>" : "" ?>
								<?php echo $errmsg["job_other"] ? "<span class=\"error d_block\">*".$errmsg["job_other"]."</span>" : "" ?>
								<p class="margin10">
									<span>
										<span class="d_inline td_ques required">電話</span>
										<input class="w10 <?php echo $errmsg["tel"] ? "error_input": "" ?>" type="tel" placeholder="例）090-1234-5678" name="tel" value="<?php echo $customer["tel"] ? h($customer["tel"]) : h($data["tel"]);?>"/>
									</span>
									<span class="d_inline indent01">
										<span class="td_ques required">メール</span>
										<input class="w17 <?php echo $errmsg["mail"] ? "error_input": "" ?>" type="email" placeholder="例）datsumou@kireimo.jp" name="mail" value="<?php echo $customer["mail"] ? h($customer["mail"]) : h($data["mail"]);?>"/>
									</span>
									<?php echo $errmsg["tel"] ? "<span class=\"error d_block\">*".$errmsg["tel"]."</span>" : "" ?>
									<?php echo $errmsg["mail"] ? "<span class=\"error d_block\">*".$errmsg["mail"]."</span>" : "" ?>
								</p>

								<p class="margin30">
									<span class="d_block td_ques required">住所　<a href="https://www.post.japanpost.jp/zipcode/index.html" target="_blank">（郵便番号がわからない方はこちら）</a></span>
									<span class="d_block indent01">
										〒<input class="w5 <?php echo $errmsg["zip"]||$errmsg["zip1"] ? "error_input": "" ?>" type="tel" name="zip1" value="<?php echo $customer["zip1"] ? h($customer["zip1"]) : h($data["zip1"]);?>" onKeyUp="AjaxZip3.zip2addr('zip1','zip2','pref','address');"> - <input  class="w5 <?php echo $errmsg["zip"]||$errmsg["zip2"] ? "error_input": "" ?>" type="tel" name="zip2" value="<?php echo $customer["zip2"] ? h($customer["zip2"]) : h($data["zip2"]);?>" onKeyUp="AjaxZip3.zip2addr('zip1','zip2','pref','address');">
										都道府県：<select name="pref" class=" <?php echo $errmsg["pref"] ? "error_input": "" ?>"><?php Reset_Select_Key($gPref, $customer['pref'] ? $customer['pref'] : $data['pref']); ?></select>
										<?php echo $errmsg["zip"] ? "<span class=\"error d_block\">*".$errmsg["zip"]."</span>" : "" ?>
										<?php echo $errmsg["zip1"] ? "<span class=\"error d_block\">*".$errmsg["zip1"]."</span>" : "" ?>
										<?php echo $errmsg["zip2"] ? "<span class=\"error d_block\">*".$errmsg["zip2"]."</span>" : "" ?>
										<?php echo $errmsg["pref"] ? "<span class=\"error d_block\">*".$errmsg["pref"]."</span>" : "" ?>
									</span>
									<span class="d_block indent01 margin10 sheet_note">※部屋番号等は略さず、正しい住所のご入力をお願い致します。</span>
									<span class="d_block indent01">
										住所：<input type="text" placeholder="例）新宿区西新宿1-19-8新東京ビルディング5F" name="address" value="<?php echo $customer["address"] ? h($customer["address"]) : h($data["address"]);?>" class="w30 <?php echo $errmsg["address"] ? "error_input": "address" ?>" autocomplete="off" size="60"/>
									</span>
									<?php echo $errmsg["address"] ? "<span class=\"error d_block indent01\">*".$errmsg["address"]."</span>" : "" ?>
									<!--下記入力できない文字の種類リンク-->
									<!--<?php echo $errmsg["address"] ? "<span class=\"error d_block indent01\">*".$errmsg["address"]."</span><span class=\" indent01\"><img src=\"../images/customer/sheet.png \" style=\"margin-top:3px;\"></span>" : "" ?>-->
								</p>
								<p id="hogosha" class="margin10">
									<span class="d_block margin10 td_ques required">保護者情報（未成年の方）</span>
									<span class="d_block margin10 indent01">
										<?php $checked = ($data['parent_address_check'] === '1') ? '上記住所と同じ' : '';
										echo InputCheckboxTag2("parent_address_check",$gParentAddressFlg,$checked,"")
										?>
										<span>上記住所と同じ方は、保護者名と電話番号だけ入力してください。</span>
										<span class="d_block indent01">
											〒<input class="w5 <?php echo $errmsg["parent_zip"]||$errmsg["parent_zip1"] ? "error_input": "" ?>" type="tel" name="parent_zip1" value="<?php echo $customer["parent_zip1"] ? h($customer["parent_zip1"]) : h($data["parent_zip1"]);?>" onKeyUp="AjaxZip3.zip2addr('parent_zip1','parent_zip2','parent_pref','parent_address');"> - <input class="w5 <?php echo $errmsg["parent_zip"]||$errmsg["parent_zip2"] ? "error_input": "" ?>" type="tel" name="parent_zip2" value="<?php echo $customer["parent_zip2"] ? h($customer["parent_zip2"]) : h($data["parent_zip2"]);?>" onKeyUp="AjaxZip3.zip2addr('parent_zip1','parent_zip2','parent_pref','parent_address');">
											都道府県：<select class=" <?php echo $errmsg["parent_pref"] ? "error_input": "" ?>" name="parent_pref"><?php Reset_Select_Key($gPref ,$data['parent_pref']); ?>	</select>
											<?php echo $errmsg["parent_zip"] ? "<span class=\"error d_block\">*".$errmsg["parent_zip"]."</span>" : "" ?>
											<?php echo $errmsg["parent_zip1"] ? "<span class=\"error d_block\">*".$errmsg["parent_zip1"]."</span>" : "" ?>
											<?php echo $errmsg["parent_zip2"] ? "<span class=\"error d_block\">*".$errmsg["parent_zip2"]."</span>" : "" ?>
											<?php echo $errmsg["parent_pref"] ? "<span class=\"error d_block\">*".$errmsg["parent_pref"]."</span>" : "" ?>
										</span>
										<span class="d_block indent01">
											住所：<input type="text" placeholder="例）新宿区西新宿1-19-8新東京ビルディング5F" name="parent_address" value="<?php echo $customer["parent_address"] ? h($customer["parent_address"]) : h($data["parent_address"]);?>" class="w30 <?php echo $errmsg["parent_address"] ? "error_input": "" ?>" autocomplete="off" />
											<span class="d_block">
												<?php echo $errmsg["parent_address"] ? "<span class=\"error d_block\">*".$errmsg["parent_address"]."</span>" : "" ?>
												保護者名<input class="10 <?php echo $errmsg["parent_name"] ? "error_input": "" ?>" type="text" placeholder="例）ヤマダ　ハナコ" name="parent_name" value="<?php echo $customer["parent_name"] ? h($customer["parent_name"]) : h($data["parent_name"]);?>"/>
												<?php echo $errmsg["parent_name"] ? "<span class=\"error\">*".$errmsg["parent_name"]."</span>" : "" ?>
											</span>
											<span class="d_block">
												電話番号<input class="w10 <?php echo $errmsg["parent_tel"] ? "error_input": "" ?>" type="tel" placeholder="例）090-1234-5678" name="parent_tel" value="<?php echo $customer["parent_tel"] ? h($customer["parent_tel"]) : h($data["parent_tel"]);?>"/>
												<?php echo $errmsg["parent_tel"] ? "<span class=\"error\">*".$errmsg["parent_tel"]."</span>" : "" ?>
											</span>
										</span>
									</span>
								</p>
								<!-- <p class="margin10">
									<span class="d_block td_ques ">勤務先</span>
									<span class="d_block indent01">
										〒<input class="w5" type="number" name="work_zip1" value="<?php echo $customer["work_zip1"] ? $customer["work_zip1"] : $data["work_zip1"];?>" onKeyUp="AjaxZip3.zip2addr('work_zip1','work_zip2','work_pref','work_address');"> - <input class="w5" type="number" name="work_zip2" value="<?php echo $customer["work_zip2"] ? $customer["work_zip2"] : $data["work_zip2"];?>" onKeyUp="AjaxZip3.zip2addr('work_zip1','work_zip2','work_pref','work_address');">
										都道府県：<select name="work_pref"><?php Reset_Select_Key($gPref ,$data['work_pref']); ?>	</select>
									</span>
									<span class="d_block indent01">
										住所：<input type="text" placeholder="例）新宿区西新宿1-19-8新東京ビルディング5F" name="work_address" value="<?php echo $customer["work_address"] ? $customer["work_address"] : $data["work_address"];?>" class="w30" autocomplete="off" />
										<?php echo $errmsg["work_pref"] ? "<span class=\"error d_block\">*".$errmsg["work_pref"]."</span>" : "" ?>
									</span>
									<span class="d_block indent01">
										電話番号<input type="tel" class="w10" placeholder="例）090-1234-5678" name="work_tel" value="<?php echo $customer["work_tel"] ? $customer["work_tel"] : $data["work_tel"];?>"/>
										<?php echo $errmsg["work_tel"] ? "<span class=\"error d_block\">*".$errmsg["work_tel"]."</span>" : "" ?>
										<span class="d_inline indent01">
											年収<input class="w5" type="text" placeholder="例）300" name="work_annual_income" value="<?php echo $customer["work_annual_income"] ? $customer["work_annual_income"] : $data["work_annual_income"];?>"/>万円
										</span>
										<?php echo $errmsg["work_annual_income"] ? "<span class=\"error d_block\">*".$errmsg["work_annual_income"]."</span>" : "" ?>
									</span>
								</p> -->
							</div>

							<dl class="sheet-table">
								<!-- <dt class="td_ques required <?php echo $errmsg["skin_type"] ? "error_input" : "" ?>">１．肌質について</dt>

								<dd class="ques_sel"><?php echo InputRadioTag("skin_type",$gSkinType,$data['skin_type'],"")?>
									<?php echo $errmsg["skin_type"] ? "<span class=\"error d_block\">*".$errmsg["skin_type"]."</span>" : "" ?>
								</dd>


								<dt class="td_ques required <?php echo $errmsg["self"] ? "error_input" : "" ?>">２．自己処理方法について（複数回答可）</dt>
								<dd class="ques_sel"><?php echo InputCheckboxTag5("self",$gSelf,$data['self'],"",5)?>
									(<input type="text" name="self_other" value="<?php echo $data['self_other'];?>"/>)
									<?php echo $errmsg["self"] ? "<span class=\"error d_block\">*".$errmsg["self"]."</span>" : "" ?>
								</dd> -->
								<dt class="td_ques required <?php echo $errmsg["experience"] ? "error_input" : "" ?>">１．脱毛のご経験（複数回答可）</dt>
								<dd class="ques_sel">
									<?php echo InputCheckboxRandom("experience",$gExperience,$data['experience'],"",false,'',0,4);?>
									<label <?php echo strstr( $data['experience'], $gExperience[5] ) ? 'class="checked"' : '' ; ?>><input type="checkbox" name="experience[4]" value="その他" <?php echo strstr( $data['experience'], $gExperience[5] ) ? ' checked="checked"' : ''; ?>> その他</label>
									(<input type="text" name="self_other" value="<?php echo h($data['self_other']);?>"/>)
									<span class="d_block">
										(いつ頃：<input type="text" name="ex_history" value="<?php echo h($data['ex_history']);?>" />
										期間：<input type="text" name="ex_period" value="<?php echo h($data['ex_period']);?>"/>)
									</span>
									<?php echo $errmsg["experience"] ? "<span class=\"error d_block\">*".$errmsg["experience"]."</span>" : "" ?>
								</dd>


								<dt class="td_ques">ありの場合（複数回答可）</dt>


								<dd class="ques_sel">
									<?php echo InputCheckboxRandom("experience_facility",$gExperienceFacility,$data['experience_facility'],"",false,'',0,9)?>
									<label <?php echo strstr( $data['experience_facility'], $gExperienceFacility[10] ) ? 'class="checked"' : '' ; ?>><input type="checkbox" name="experience_facility[9]" value="その他" <?php echo strstr( $data['experience_facility'], $gExperienceFacility[10] ) ? ' checked="checked"' : ''; ?>> その他</label>
									(<input type="text" name="experience_other" value="<?php echo h($data['experience_other']);?>"/>)
								</dd>

								<dt class="td_ques">２．脱毛サロンを選ぶポイントは何ですか？（複数回答可）</dt>

								<dd class="ques_sel">
									<?php echo InputCheckboxRandom("point",$gPoint,$data['point'],"",false,'',0,9)?>
									<label <?php echo strstr( $data['point'], $gPoint[10] ) ? 'class="checked"' : '' ; ?>><input type="checkbox" name="point[9]" value="その他" <?php echo strstr( $data['point'], $gPoint[10] ) ? ' checked="checked"' : ''; ?>> その他</label>
								<!-- <?php echo InputCheckboxTag2("point",$gPoint,$data['point'],"")?> -->
									(<input type="text" name="point_other" value="<?php echo h($data['point_other']);?>"/>)
								</dd>


								<dt class="td_ques required <?php echo $errmsg["cm"] ? "error_input" : "" ?>">３．過去の病歴はありますか？</dt>

								<dd class="ques_sel">
									<?php echo InputRadioTag("cm",$gMediStatus,$data['cm'],"")?>
									(病名<input class="w17" type="text" name="cm_name" value="<?php echo h($data['cm_name']);?>" />)
									<?php echo $errmsg["cm"] ? "<span class=\"error d_block\">*".$errmsg["cm"]."</span>" : "" ?>
								</dd>

								<dt class="td_ques required <?php echo $errmsg["oc"] ? "error_input" : "" ?>">４．現在治療中及び定期検査のご予定はありますか？</dt>

								<dd class="ques_sel">
									<?php echo InputRadioTag("oc",$gMediStatus,$data['oc'],"")?>
									(内容<input class="w30" type="text" name="oc_name" value="<?php echo h($data['oc_name']);?>" />)
									<?php echo $errmsg["oc"] ? "<span class=\"error d_block\">*".$errmsg["oc"]."</span>" : "" ?>
								</dd>

								<dt class="td_ques required <?php echo $errmsg["drug"] ? "error_input" : "" ?>">５．現在お薬の服用や、軟膏・湿布等の塗布はありますか？※市販薬含む</dt>

								<dd class="ques_sel">
									<?php echo InputRadioTag("drug",$gMediStatus,$data['drug'],"")?>
									(お薬名<input class="w17" type="text" name="drug_name" value="<?php echo h($data['drug_name']);?>" />)
									<?php echo $errmsg["drug"] ? "<span class=\"error d_block\">*".$errmsg["drug"]."</span>" : "" ?>
								</dd>

								<!-- <dt class="td_ques required <?php echo $errmsg["allergie"] ? "error_input" : "" ?>">７．アレルギーをお持ちですか？</dt>

								<dd class="ques_sel">
									<?php echo InputRadioTag("allergie",$gMediStatus,$data['allergie'],"")?>
									（ｱﾚﾙｷﾞｰ名<input class="w17" type="text" name="allergie_name" value="<?php echo $data['allergie_name'];?>" />)
									<?php echo $errmsg["allergie"] ? "<span class=\"error d_block\">*".$errmsg["allergie"]."</span>" : "" ?>
								</dd>-->

								<dt class="td_ques required <?php echo $errmsg["pregnancy"] ? "error_input" : "" ?>">６．現在妊娠中または、可能性がありますか？</dt>

								<dd class="ques_sel">
									<?php echo InputRadioTag("pregnancy",$gMediStatus,$data['pregnancy'],"")?>
									<?php echo $errmsg["pregnancy"] ? "<span class=\"error d_block\">*".$errmsg["pregnancy"]."</span>" : "" ?>
								</dd>

								<dt class="td_ques required <?php echo $errmsg["keloid"] ? "error_input" : "" ?>">７．ケロイド体質、または白斑と診断を受けたことがある。または、自覚がありますか？</dt>

								<dd class="ques_sel">
									<?php echo InputRadioTag("keloid",$gMediStatus,$data['keloid'],"")?>
									(<?php echo InputCheckboxTag2("keloid_check",$gKeloid,$data['keloid_check'],"")?>)
									<?php echo $errmsg["keloid"] ? "<span class=\"error d_block\">*".$errmsg["keloid"]."</span>" : "" ?>
								</dd>

								<!-- <dt class="td_ques required <?php echo $errmsg["kabure"] ? "error_input" : "" ?>">１０．化粧品によるカブレを起こしたことがありますか？</dt>
								<dd class="ques_sel">
									<?php echo InputRadioTag("kabure",$gMediStatus,$data['kabure'],"")?>
									（化粧品名：<input class="w17" type="text" name="cosme_name" value="<?php echo $data['cosme_name'];?>" />)
									<?php echo $errmsg["kabure"] ? "<span class=\"error d_block\">*".$errmsg["kabure"]."</span>" : "" ?>
								</dd>-->

								<dt class="td_ques required <?php echo $errmsg["alien"] ? "error_input" : "" ?>">８．脱毛希望箇所に異物は入っていますか？（医療用ボルト・シリコンなど）</dt>

								<dd class="ques_sel">
									<?php echo InputRadioTag("alien",$gMediStatus,$data['alien'],"")?>
									(場所：<input type="text" name="alien_palce" value="<?php echo h($data['alien_palce']);?>" />)
									<?php echo $errmsg["alien"] ? "<span class=\"error d_block\">*".$errmsg["alien"]."</span>" : "" ?>
								</dd>

								<!-- <dt class="td_ques required <?php echo $errmsg["menstruation"] ? "error_input" : "" ?>">１２．月経周期について</dt>

								<dd class="ques_sel">
									<?php echo InputRadioTag("menstruation",$gMenstruation,$data['menstruation'],"")?>
									<span class="d_block">
										周期（約：<input class="w5" type="text" name="m_period" value="<?php echo $data['m_period'];?>" />日周期)
									</span>
									<?php echo $errmsg["menstruation"] ? "<span class=\"error d_block\">*".$errmsg["menstruation"]."</span>" : "" ?>
								</dd> -->

								<dt class="td_ques required <?php echo $errmsg["tattoo"] ? "error_input" : "" ?>">９．タトゥーは入っていますか？</dt>

								<dd class="ques_sel">
									<?php echo InputRadioTag("tattoo",$gMediStatus,$data['tattoo'],"")?>
									(場所：<input class="w5" type="text" name="tattoo_place" value="<?php echo h($data['tattoo_place']);?>" />
									大きさ：<input class="w5" type="text" name="tattoo_size" value="<?php echo h($data['tattoo_size']);?>" />㎝ぐらい)
									<?php echo $errmsg["tattoo"] ? "<span class=\"error d_block\">*".$errmsg["tattoo"]."</span>" : "" ?>
								</dd>

								<!-- <dt class="td_ques required <?php echo $errmsg["sunburn"] ? "error_input" : "" ?>">１４．日焼けはされていますか？もしくは、日焼けのご予定はありますか？</dt>

								<dd class="ques_sel">
									<?php echo InputRadioTag("sunburn",$gMediStatus,$data['sunburn'],"")?>
									（部位：<input type="text" name="s_place" value="<?php echo $data['s_place'];?>" />
									いつ頃：<input type="text" name="s_history" value="<?php echo $data['s_history'];?>" />)
									<?php echo $errmsg["sunburn"] ? "<span class=\"error d_block\">*".$errmsg["sunburn"]."</span>" : "" ?>
								</dd> -->

								<!-- <dt class="td_ques required <?php echo $errmsg["infection"] ? "error_input" : "" ?>">１５．感染症である、または感染症の疑いがありますか？</dt>

								<dd class="ques_sel">
									<?php echo InputRadioTag("infection",$gMediStatus,$data['infection'],"")?>
									<?php echo $errmsg["infection"] ? "<span class=\"error d_block\">*".$errmsg["infection"]."</span>" : "" ?>
								</dd> -->


								<!-- <dt class="td_ques">１６．KIREIMOを知ったきっかけは何ですか？（複数回答可）</dt>
								<dd class="ques_sel">
									<span class="d_block">
										<?php echo InputCheckboxTag6("knowledge1",$gKnowledge,$data['knowledge'],"",3,'',0,4)?>
										（<input type="text" name="knowledge_magazine" value="<?php echo $data['knowledge_magazine'];?>" />で見た)
									</span>
									<span class="d_block">
										<?php echo InputCheckboxTag6("knowledge2",$gKnowledge,$data['knowledge'],"",'','',4,1)?>
										(<input type="text" name="knowledge_freepaper" value="<?php echo $data['knowledge_freepaper'];?>" />で見た)
									</span>
									<span class="d_block">
										<?php echo InputCheckboxTag6("knowledge3",$gKnowledge,$data['knowledge'],"",4,'',5,6)?>
										(イベント名：<input type="text" name="knowledge_event" value="<?php echo $data['knowledge_event'];?>" />)
									</span>
									<span class="d_block">
										<?php echo InputCheckboxTag6("knowledge4",$gKnowledge,$data['knowledge'],"",2,'',11,3)?>
										(<input type="text" name="knowledge_news" value="<?php echo $data['knowledge_news'];?>" />で見た)
										<?php echo InputCheckboxTag6("knowledge5",$gKnowledge,$data['knowledge'],"",'','',14,1)?>
										(<input type="text" name="knowledge_blog" value="<?php echo $data['knowledge_blog'];?>" />で見た)
									</span>
									<?php echo InputCheckboxTag6("knowledge6",$gKnowledge,$data['knowledge'],"",'','',15,2)?>
									(<input type="text" name="knowledge_other" value="<?php echo $data['knowledge_other'];?>"/>)
								</dd> -->

								<dt class="td_ques">１０．KIREIMOへお越し頂いたきっかけを教えてください。（複数回答可）</dt>
								<dd class="ques_sel">
									<span class="d_block">
										<?php echo InputCheckboxRandom("seeing",$gSeeing,$data['seeing'],"",'','',0,13)?>
										<!-- <span class="d_block">
											<?php echo InputCheckboxTag6("seeing1",$gSeeing,$data['seeing'],"",'','',0,1)?>
											（紹介者：<input type="text" name="seeing_intro" value="<?php echo $data['seeing_intro'];?>" />)
										</span>
										<span class="d_block">
											<?php echo InputCheckboxTag6("seeing2",$gSeeing,$data['seeing'],"",'','',1,1)?>
											(<input type="text" name="seeing_blog" value="<?php echo $data['seeing_blog'];?>" />を見て)
										</span>
										<span class="d_block">
											<?php echo InputCheckboxTag6("seeing3",$gSeeing,$data['seeing'],"",'','',2,1)?>
											(<input type="text" name="seeing_magazine" value="<?php echo $data['seeing_magazine'];?>" />を見て)
										</span>
										<span class="d_block">
											<?php echo InputCheckboxTag6("seeing4",$gSeeing,$data['seeing'],"",1,'',3,2)?>
											(<input type="text" name="seeing_freepaper" value="<?php echo $data['seeing_freepaper'];?>" />を見て)
										</span> -->
									</span>
									<span class="d_block">
										<label <?php echo strstr( $data['seeing'], $gSeeing[14] ) ? 'class="checked"' : '' ; ?>><input type="checkbox" name="seeing[14]" value="その他" <?php echo strstr( $data['seeing'], $gSeeing[14] ) ? ' checked="checked"' : ''; ?>> その他</label>
										(<input type="text" name="seeing_other" value="<?php echo h($data['seeing_other']);?>"/>)
									</span>
								</dd>

								<dt class="td_ques">１１．無料カウンセリングを受けようと思った理由を教えてください。（複数回答可）</dt>
								<dd class="ques_sel">
									<span class="d_block">
										<?php echo InputCheckboxRandom("beginning",$gBeginning,$data['beginning'],"",'','',0,8)?>
									</span>
									<!-- <span class="d_block">
										<?php echo InputCheckboxTag6("beginning1",$gBeginning,$data['beginning'],"",'','',0,2)?>
									</span>
									<span class="d_block">
										(<?php echo InputCheckboxTag2("beginning_place",$gBeginningPlace,$data['beginning_place'],"")?>)
									</span> -->
									<!-- <?php echo InputCheckboxTag6("beginning2",$gBeginning,$data['beginning'],"",'','',2,11)?> -->
									<label <?php echo strstr( $data['beginning'], $gBeginning[9] ) ? 'class="checked"' : '' ; ?>><input type="checkbox" name="beginning[8]" value="その他" <?php echo strstr( $data['beginning'], $gBeginning[9] ) ? ' checked="checked"' : ''; ?>> その他</label>
									(<input type="text" name="beginning_other" value="<?php echo h($data['beginning_other']);?>"/>)
								</dd>

								<!-- <dt class="td_ques">２０．KIREIMO以外に脱毛を検討した、カウンセリングに行った、またはその予定があるお店はありますか？（複数回答可）</dt>
								<dd class="ques_sel"><?php echo InputCheckboxTag2("externalshop",$gExternalshop,$data['externalshop'],"")?>
									(<input type="text" name="externalshop_other" value="<?php echo $data['externalshop_other'];?>"/>)
								</dd>

								<dt class="td_ques">２１．普段使用しているスキンケア用品を教えてください。（複数回答可）</dt>
								<dd class="ques_sel"><?php echo InputCheckboxTag2("skincare",$gSkincare,$data['skincare'],"")?>
									（<input type="text" name="skincare_other" value="<?php echo $data['skincare_other'];?>" />）
								</dd>

								<dt class="td_ques">２２．臭い対策に使用しているケア用品があれば教えてください。（複数回答可）</dt>
								<dd class="ques_sel"><?php echo InputCheckboxTag2("care",$gCare,$data['care'],"")?></dd>

								<dt class="td_ques">２３．使い分けている石鹸の種類があれば教えてください。（複数回答可）</dt>
								<dd class="ques_sel"><?php echo InputCheckboxTag2("soapbar",$gSoapbar,$data['soapbar'],"")?></dd>

								<dt class="td_ques">２４．スキンケアにかける月の平均金額はいくらぐらいですか？</dt>

								<dd class="ques_sel"><?php echo InputRadioTag("money",$gMoney,$data['money'],"")?></dd>

								<dt class="td_ques">２５．いま使用しているスキンケア用品は、どのような効果に魅力を感じ購入しましたか？（複数回答可）</dt>
								<dd class="ques_sel"><?php echo InputCheckboxTag2("buy",$gBuy,$data['buy'],"")?></dd> -->


								<dt class="td_ques">備考</dt>
								<dd class="ques_sel"><textarea class="w40" name="memo" rows=5 ><?php echo h($data["memo"]);?></textarea></dd>

							</dl>
							<div class="sheet-table">
								<span class="d_block submit_btn">
									<input type="submit" value="完了" onclick="return confirm('問診表記入を完成しますか？')"/>
								</span>
                  <!--
                  <span class="d_block reset_btn">
                    <input type="reset" value="リセット" />
                  </span>
              -->
          </div>
          <!--  end product-table................................... -->
      </form>
      <?php } ?>
  </div>
  <!--  end content-table  -->

</div>
<!--  end content-table-inner ............................................END  -->
</div>
<div class="clear">&nbsp;</div>

</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>