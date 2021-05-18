<?php include_once("../library/service/conversion_demo.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
<!-- 月のDATEPICKER を読み込むためのJS -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.ympicker-3monthonly.js"></script><!-- 選択範囲限定の施術開始年月を表示させるためのJS -->
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.all.css" />
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.datepicker.css" />

<script type="text/javascript">
$(document).ready(function() {
	$('#limit_ym').ympicker({
		monthNames: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
		monthNamesShort: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
		dateFormat: 'yymm',
		yearSuffix: '年',
		minDate: '+0m',
		maxDate: '+2m'
	});
});
function keisan(){
	var course_id = document.form1.course_id.selectedIndex ;
	var course_prices_str = '<?php echo $course_prices;?>';
	var course_prices = course_prices_str.split(',');        											// 文字列をカンマで分解し、配列化
	document.form1.fixed_price.value = course_prices[course_id]; 										// コース金額（税込）
    var remained_price = '<?php echo $remained_price;?>';
    var discount = document.form1.discount.value;
    var payed_price = '<?php echo $payed_price;?>';

    //var price = course_prices[course_id] - remained_price - discount ;								// 請求金額（税込）=コース金額-消化済金額
    var price = course_prices[course_id] - discount - payed_price;										// 請求金額（税込）=コース金額-値引き-支払済金額
	document.form1.price.value = price;
	//var price = document.form1.price.value; 															// 請求金額（税込）

	// 月額：施術開始予定年月表示制御のメソッドを呼び出す 2016/10/18 shimada
	start_ym('#start_ym');

	var f_payment = document.form1.payment_cash.value ; 												// 初回入金(現金)
	var c_payment = document.form1.payment_card.value ; 												// 初回入金(カード)
	var t_payment = document.form1.payment_transfer.value ;												// 初回入金(振込)
	var l_payment = document.form1.payment_loan.value ; 												// 初回入金(ローン)

	var payment = Number(f_payment) + Number(c_payment) + Number(t_payment) + Number(l_payment);

	//明細--------------------------------------------------------------------------------
	var course_names_str = '<?php echo $course_names;?>';
	var course_names = course_names_str.split(',');        												// 文字列をカンマで分解し、配列化
	var course_name =  "新：" + course_names[course_id];
	$('#course_name').html(course_name); 				  												// コース名（明細）

	var course_price = String( course_prices[course_id] ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#course_price').html("￥" + course_price);         												// コース金額（明細）

	var price2 = String( price ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); 							// 1,234
	$('#price').html("￥" + price2);         															// 入金金額（明細）

	var payment2 = String( payment ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); 						// 1,234
	$('#payment').html("￥" + payment2);         														// 入金金額（明細）

	var balance = price - payment ;
	var balance2 = String( balance ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' );						// 1,234
	$('#balance').html("￥" + balance2);         														// 入金金額（明細）

}

function keisan3(){
	if(document.form1.keep.value>0){
		document.form1.change.value = document.form1.keep.value - document.form1.payment_cash.value - document.form1.payment_card.value - document.form1.payment_transfer.value - document.form1.payment_loan.value; // お釣りを表示

	}
}
</script>

<script type="text/javascript">
  imobile_adv_sid = "6121";
  imobile_adv_cq = "top=1";
  document.write(unescape("<script src="" + ((document.location.protocol == "http:") ? "http" : "https") + "://spcnv.i-mobile.co.jp/script/adv.js?20120316"" + " type="text/javascript"></script>"));
</script>

<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>プラン組替<?php echo ($gMsg) ;?></h1></div>
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
					<div id="content-table-inner">
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr valign="top">
								<!-- start id-form -->
								<form action="" method="post" name="form1" enctype="multipart/form-data" >
									<input type="hidden" name="action" value="pdf_export" />
									<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
									<input type="hidden" name="customer_id" value="<?php echo $customer["id"];?>" />
									<input type="hidden" name="shop_id" value="<?php echo $_REQUEST["shop_id"];?>" /><!--店舗移動対応-->
									<input type="hidden" name="type" value="<?php echo $data["type"];?>" />

									<td>
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<tr>
												<th valign="top">区分:</th>
												<td><select name="type" class="styledselect_form_3" disabled ><?php Reset_Select_Key( $gResType3 , 6);?></select></td>

												<td rowspan="20">
													<div style="float:left;padding:40px;">
														<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
															<tr><td colspan="2"><h1>プラン組替処理明細</h1></td></tr>
															<tr><td class="regTitle">コース名</td><td  class="regTitle">金額</td></tr>
															<tr>
																<td class="reg">旧：<?php echo $course_list[$data['course_id']];?></td>
																<td class="regPrice">￥<?php echo number_format($data['fixed_price']);?></td>
															</tr>
															<tr>
																<td class="reg">値引き</td>
																<td class="regPrice">▲￥<?php echo number_format($data['discount']);?></td>
															</tr>
															<tr>
																<td class="regTotal">商品金額（旧）</td>
																<td class="regTotalPrice">￥<?php echo number_format($data['price'] );?></td>
															</tr>
															<tr>
																<td class="reg">支払済金額</td>
																<td i class="regPrice">￥<?php echo number_format($payed_price);?></td>
															</tr>
															<tr>
																<td class="reg">消化回数</td>
																<td class="regPrice"><?php echo $data['r_times'];?></td>
															</tr>
															<tr>
																<td class="reg">消化単価</td>
																<td class="regPrice">￥<?php echo number_format($per_price);?></td>
															</tr>
															<tr>
																<td class="reg">消化金額</td>
																<td class="regPrice">￥<?php echo number_format($usered_price);?></td>
															</tr>
															<tr>
																<td class="regTotal">未消化金額</td>
																<td class="regTotalPrice">￥<?php echo number_format($remained_price);?></td>
															</tr>
															<tr>
																<td id="course_name" class="reg">新：<?php echo $course_list[$data['new_course_id']];?></td>
																<td id="course_price" class="regPrice">￥<?php echo number_format($new_contract['fixed_price']);?></td>
															</tr>
															<tr>
																<td class="regTotal">請求金額</td>
																<td id="price" class="regTotalPrice">￥<?php echo number_format($new_contract['price'] );?></td>
															</tr>
															<tr>
																<td class="regTotal">入金金額</td>
																<td id="payment" class="regTotalPrice">￥<?php echo number_format($new_contract['payment'] );?></td>
															</tr>
															<tr>
																<td class="reg">売掛金</td>
																<td id="balance" class="regPrice">￥<?php echo number_format($new_contract['balance'] );?></td>
															</tr>
															<tr><td></td></tr>
															<tr>
																<td class="reg">お預かり</td>
																<td class="regPrice">￥<input type="tel" name="keep" value="" id="fm" style="width:70px;text-align:right;padding-right:5px;" onChange="keisan3()"/></td>
															</tr>
															<tr>
																<td class="reg">お釣り</td>
																<td class="regPrice">￥<input type="tel" name="change" style="width:70px;text-align:right;padding-right:5px;" disabled="disabled"/></td>
															</tr>
														</table>
														<?php if($complete_flg){?>
															<div align="right"><a href="./">レジ一覧へ<a></div>
														<?php } ?>
														<p>※ 請求金額＝新コース金額（税込）-値引き（新）-支払済金額</p>
													</div>

												<td>

											</tr>
											<tr>
												<th valign="top">旧コース:</th>
												<td><select name="" class="styledselect_form_3" disabled><option>-</option><?php Reset_Select_Key_Group( $course_list , $data['course_id'],$gCourseGroup);?></select></td>
											</tr>

											<tr>
												<th valign="top">旧コース金額（税込）:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($data['fixed_price']) ;?>" class="inp-form"  disabled/></td>
											</tr>
											<tr>
												<th valign="top">値引き（旧）:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($data['discount']) ;?>" id="fm2" class="inp-form"  disabled/></td>
											</tr>
											<tr>
												<th valign="top">旧商品金額（税込）:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($data['price']) ;?>" class="inp-form" disabled /></td>
											</tr>
											<tr>
												<th valign="top">支払済金額:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($payed_price) ;?>" class="inp-form" disabled /></td>
											</tr>
											<tr>
												<th valign="top">売掛金:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($data['balance']) ;?>" class="inp-form" disabled /></td>
											</tr>

											<tr>
												<th valign="top">消化回数:</th>
												<td><input type="text" name="" value="<?php echo TA_Cook($data['r_times']) ;?>" class="inp-form" disabled /></td>
											</tr>
											<tr>
												<th valign="top">消化単価:</th>
												<td><input type="text" name="" value="<?php echo $per_price ;?>" class="inp-form" disabled /></td>
											</tr>
											<tr>
												<th valign="top">消化金額:</th>
												<td><input type="text" name="" value="<?php echo $usered_price ;?>" class="inp-form" disabled /></td>
											</tr>

											<tr>
												<th valign="top">未消化金額:</th>
												<td><input type="text" name="" value="<?php echo $remained_price ;?>" class="inp-form" disabled /></td>
											</tr>

											<tr>
												<th valign="top">新コース:</th>
												<td><select name="course_id" class="styledselect_form_3" onChange="keisan()"><option>-</option><?php Reset_Select_Key_Group( $course_list , $_REQUEST['course_id'],$gCourseGroup);?></select></td>
											</tr>
											<tr>
												<th valign="top">新コース金額（税込）:</th>
												<td><input type="text" name="fixed_price" value="<?php echo TA_Cook($_REQUEST['fixed_price']) ;?>" class="inp-form" readonly="readonly"/></td>
											</tr>
											<tr>
												<th valign="top">値引き（新）:</th>
												<td><input type="text" name="discount" value="<?php echo TA_Cook($_REQUEST['discount']) ;?>" id="fm2" class="inp-form"  onChange="keisan()"　/></td>
											</tr>
											<tr>
												<th valign="top">請求金額:</th>
												<td><input type="text" name="price" value="<?php echo TA_Cook($_REQUEST['price']) ;?>" class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">入金金額(現金):</th>
												<td><input id="f_payment" type="tel" name="payment_cash" value="<?php echo TA_Cook($sales['payment_cash']) ;?>" class="inp-form" onChange="keisan()" /></td>
											</tr>
											<tr>
												<th valign="top">入金金額(カード):</th>
												<td><input id="c_payment" type="tel" name="payment_card" value="<?php echo TA_Cook($sales['payment_card']) ;?>" class="inp-form" onChange="keisan()" /></td>
											</tr>
											<tr>
												<th valign="top">入金金額(振込):</th>
											<?php if($authority_level<=6){ ?>
												<td><input id="t_payment" type="tel" name="payment_transfer" value="<?php echo TA_Cook($sales['payment_transfer']) ;?>" class="inp-form" onChange="keisan()" /></td>
											<?php }else{ ?>
												<td><input type="tel" value="<?php echo TA_Cook($sales['payment_transfer']) ;?>" class="inp-form-disable" disabled /></td>
												<input id="t_payment" type="hidden" name="payment_transfer" value="<?php echo TA_Cook($sales['payment_transfer']) ;?>" />
											<?php } ?>
											</tr>
											<tr>
												<th valign="top">入金金額(ローン):</th>
												<td><input id="l_payment" type="tel" name="payment_loan" value="<?php echo TA_Cook($sales['payment_loan']) ;?>" class="inp-form" onChange="keisan()" /></td>
											</tr>
											<!-- 月額のみ表示 -->
											<tr id="start_ym" >
												<th valign="top">施術開始予定(年月):</th>
												<td>
													<input style="width:55px;height:21px;" id="limit_ym" name="start_ym" type="text" value="<?php echo $_REQUEST['start_ym']<>0 ? TA_Cook($_REQUEST['start_ym']): "" ;?>"readonly />
												</td>
											</tr>
											<tr>
												<th valign="top">ミドルカウンセリング担当:</th>

												<td><select name="staff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray("staff","shop_id") , $_REQUEST['staff_id'],getDatalist5("shop",$_REQUEST['shop_id']));?></select></td>
											</tr>
											<tr>
												<th valign="top">レジ担当:</th>

												<td><select name="rstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray("staff","shop_id") , $_REQUEST['rstaff_id'],getDatalist5("shop",$_REQUEST['shop_id']));?></select></td>
											</tr>
											<tr>
												<th valign="top">処理日:</th>
												<td><input type="tel" name="cancel_date" value="<?php echo date('Y-m-d') ;?>" class="inp-form"  /></td>
											</tr>
											<tr>
												<th>&nbsp;</th>
												<td valign="top">
												<br />
													<input type="submit" value="　プラン組替通知書出力　" />
												</td>
											</tr>
										</table>
									</td>
									<td>
										<!--  start related-activities -->
										<div id="related-activities">
											<!--  start related-act-top -->
											<div id="related-act-top">
												<!--<img src="../images/forms/header_related_act.gif" width="271" height="43" alt="" />-->
												<div class="title"><a href="../customer/edit.php?customer_id=<?php echo $customer['id']?>"  style="font-weight:bold;color:#94b52c;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94b52c'" title="顧客詳細へ"><?php echo $customer['name_kana'] ? $customer['name_kana'] : ($customer['name'] ? $customer['name'] : "無名")?></a></div>
											</div>
											<!-- end related-act-top -->

											<!--  start related-act-bottom -->
											<div id="related-act-bottom">
												<!--  start related-act-inner -->
												<div id="related-act-inner">
													<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>会員番号 : <?php echo $customer['no']?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>名前 : <?php echo $customer['name']?></h5></div>
													<div class="clear"></div>

												</div><!-- end related-act-inner -->
												<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->
									</td>
								</form><!-- end id-form  -->
							</tr>
							<tr><td><img src="../images/shared/blank.gif" width="695" height="1" alt="blank" /></td></tr>
						</table>
 						<div class="clear"></div>
					</div><!--  end content-table-inner  -->
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
	// 新月額のプルダウンに色を付ける
	function new_monthly(target_select){
		var $options,$options_num,$i;
		$options = $("[name='" + target_select + "']").find('option'),
		$options_num = $options.length;
		for($i =0; $i<$options_num; $i++){
			var $this = $options[$i];
			if($this.text.indexOf('新月額') !== -1){
				$this.classList.add('new_monthly');
			}
		}
	};
// 月額のトリートメント開始年月の表示制御 2016/10/18 add by shimada
	function start_ym(target_id){
		// コースタイプを取得する
		var course_id = document.form1.course_id.selectedIndex ;
		var course_types_str = '<?php echo $course_types;?>';
		var course_types = course_types_str.split(',');        //文字列をカンマで分解し、配列化
		var course_type = course_types[course_id]; // コースタイプ 0.パック、1.月額
		// 月額の時：トリートメント開始年月 ボックスを表示させる
		if (course_type==1){
			$(target_id).css("display","table-row");
		} else {
			$(target_id).css("display","none");
		}
	}
    new_monthly('course_id');
	start_ym('#start_ym');
</script>
<?php include_once("../include/footer.html");?>
