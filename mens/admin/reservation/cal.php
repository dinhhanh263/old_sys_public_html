<?php include_once("../library/reservation/cal.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SYSTEM</title>
<link rel="stylesheet" href="../css/screen.css" type="text/css" media="screen" title="default" />

<script src="../js/jquery/jquery-1.8.2.min.js" type="text/javascript"></script>
<script type="text/javascript">

// function detail(course_id, fixed_price, price, per_price, usered_price, payment, charge, payment_cash){
function detail(course_id, fixed_price, discount, price, r_times, per_price, usered_price, payment, charge, payment_cash,balance){

	// ご明細（右側）
	// コース名
	var course_names_str = '<?php echo $course_names;?>';
	var course_names = course_names_str.split(',');        //文字列をカンマで分解し、配列化
	var course_name =  course_names[course_id];
	$('#course_name').html(course_name); 				  //コース名（明細）

	// コース名 金額
	var course_price = String( fixed_price ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#course_price').html("￥" + course_price);         //コース金額（明細）

	// 値引き
	var discount = String( discount ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#discount').html("￥" + discount);         //値引き

	// 商品金額（税込）
	var price0 = String( price ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#price').html("￥" + price0);         //商品金額（税込）

	//支払済金額

		var payed_price = price - balance;
		var payed_price0 = String( payed_price ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
		$('#payed_price').html("￥" + payed_price0);         //支払済金額


	// 消化回数
	$('#r_times').html(r_times);	//消化回数

	// 消化単価
	var per_price = String( per_price ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#per_price').html("￥" + per_price);         //コース金額（明細）

	// 消化金額
	var usered_price = String( usered_price ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#usered_price').html("￥" + usered_price);   //消化金額

	// 残金
	var payment = String( -(payment) ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#payment').html("￥" + payment);   //残金

	// 手数料
	var charge = String( charge ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#charge').html("￥" + charge);   //手数料

	// 返金額（手数料含）
	var payment_cash = String( -(payment_cash) ).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' ); // 1,234
	$('#payment_cash').html("￥" + payment_cash);   //返金額（手数料含）
}


function keisan1(){
	// コース金額
	var course_id = document.form1.course_id.selectedIndex ;
	var course_prices_str = '<?php echo $course_prices;?>';
	var course_prices = course_prices_str.split(',');        //文字列をカンマで分解し、配列化
	var paid = '<?php echo $payed_price;?>';
	var fixed_price = course_prices[course_id];
	document.form1.fixed_price.value = fixed_price; // コース金額（税込）

	// 商品金額
	var discount = document.form1.discount.value; // 値引き
	var price = fixed_price - discount;
	document.form1.price.value = price; // 商品金額

	// 売掛金
	var balance =document.form1.balance.value ; // 売掛金

	// 役務回数
	var course_times_str = '<?php echo $course_times;?>';
	var course_times = course_times_str.split(',');        //文字列をカンマで分解し、配列化
	var course_time = course_times[course_id]; //役務回数

	// 消化単価
	var per_price = Math.round(price / course_time);
	document.form1.per_price.value = per_price;

	// 消化回数
	var r_times = document.form1.r_times.value;

	// 消化金額
	var usered_price = r_times * per_price;
	document.form1.usered_price.value = usered_price;

	// 残金(支払金額 - 消化金額)
	var payment = price - balance - usered_price;
	document.form1.payment.value = -(payment);

	// 手数料(コースの金額 - 値引き - 支払った金額)
	var charge = Math.round((fixed_price - discount - usered_price) * 0.1);

	if(charge > 20000) {
		charge = 20000;
	}
	document.form1.charge.value = charge;
	
	// 返金額(残金 - 手数料)
	payment_cash = payment - charge;
	document.form1.payment_cash.value = -(payment_cash);

	// ご明細（右側）
	detail(course_id, fixed_price, discount, price, r_times, per_price, usered_price, payment, charge, payment_cash,balance);
}

function keisan2(){

	var course_id = document.form1.course_id.selectedIndex ; //コースID
	var paid = '<?php echo $payed_price;?>'; //支払った金額
	var fixed_price = document.form1.fixed_price.value;	 //コース金額(税込):

	// 商品金額
	var discount = document.form1.discount.value; //値引き
	var price = fixed_price - discount;
	document.form1.price.value = price; //商品金額

	// 売掛金
	var balance =document.form1.balance.value ; // 売掛金

	// 役務回数
	var course_times_str = '<?php echo $course_times;?>';
	var course_times = course_times_str.split(',');        //文字列をカンマで分解し、配列化
	var course_time = course_times[course_id]; //役務回数

	// 消化単価
	var per_price = Math.round(price / course_time);
	document.form1.per_price.value = per_price;

	// 消化回数
	var r_times = document.form1.r_times.value;

	// 消化金額
	var usered_price = r_times * per_price;
	document.form1.usered_price.value = usered_price;

	// 残金(支払金額 - 消化金額)
	var payment = price - balance - usered_price;
	document.form1.payment.value = -(payment);

	// 手数料(コースの金額 - 値引き - 支払った金額)
	var charge = Math.round((fixed_price - discount - usered_price) * 0.1);

	if(charge > 20000) {
		charge = 20000;
	}
	document.form1.charge.value = charge;
	
	// 返金額(残金 - 手数料)
	payment_cash = payment - charge;
	document.form1.payment_cash.value = -(payment_cash);

	// ご明細（右側）
	detail(course_id, fixed_price, discount, price, r_times, per_price, usered_price, payment, charge, payment_cash,balance);
}

function keisan3(){

	var course_id = document.form1.course_id.selectedIndex ; //コースID
	var paid = '<?php echo $payed_price;?>'; //支払った金額
	var fixed_price = document.form1.fixed_price.value;	 //コース金額(税込):

	// 商品金額
	var discount = document.form1.discount.value; //値引き
	var price = fixed_price - discount;
	document.form1.price.value = price; //商品金額

	// 売掛金
	var balance =document.form1.balance.value ; // 売掛金

	// 役務回数
	var course_times_str = '<?php echo $course_times;?>';
	var course_times = course_times_str.split(',');        //文字列をカンマで分解し、配列化
	var course_time = course_times[course_id]; //役務回数

	// 消化単価
	var per_price = Math.round(price / course_time);
	document.form1.per_price.value = per_price;

	// 消化回数
	var r_times = document.form1.r_times.value;

	// 消化金額
	var usered_price = r_times * per_price;
	document.form1.usered_price.value = usered_price;

	// 残金(支払金額 - 消化金額)
	var payment = price - balance - usered_price;
	document.form1.payment.value = -(payment);

	// 手数料(コースの金額 - 値引き - 支払った金額)
	var charge = Math.round((fixed_price - discount - usered_price) * 0.1);

	if(charge > 20000) {
		charge = 20000;
	}
	document.form1.charge.value = charge;
	
	// 返金額(残金 - 手数料)
	payment_cash = payment - charge;
	document.form1.payment_cash.value = -(payment_cash);

	// ご明細（右側）
	detail(course_id, fixed_price, discount, price, r_times, per_price, usered_price, payment, charge, payment_cash,balance);
}

function keisan4(){

	var course_id = document.form1.course_id.selectedIndex ; //コースID
	var paid = '<?php echo $payed_price;?>'; //支払った金額

	// 商品金額
	var discount = document.form1.discount.value; //値引き
	var price = document.form1.price.value; //商品金額

	// コース金額（税込）
	var fixed_price = parseInt(price) + parseInt(discount);
	document.form1.fixed_price.value = fixed_price; // コース金額（税込）

	// 売掛金
	var balance =document.form1.balance.value ; // 売掛金

	// 役務回数
	var course_times_str = '<?php echo $course_times;?>';
	var course_times = course_times_str.split(',');        //文字列をカンマで分解し、配列化
	var course_time = course_times[course_id]; //役務回数

	// 消化単価
	var per_price = Math.round(price / course_time);
	document.form1.per_price.value = per_price;

	// 消化回数
	var r_times = document.form1.r_times.value;

	// 消化金額
	var usered_price = r_times * per_price;
	document.form1.usered_price.value = usered_price;

	// 残金(支払金額 - 消化金額)
	var payment = price - balance - usered_price;
	document.form1.payment.value = -(payment);

	// 手数料(コースの金額 - 値引き - 支払った金額)
	var charge = Math.round((fixed_price - discount - usered_price) * 0.1);

	if(charge > 20000) {
		charge = 20000;
	}
	document.form1.charge.value = charge;
	
	// 返金額(残金 - 手数料)
	payment_cash = payment - charge;
	document.form1.payment_cash.value = -(payment_cash);

	// ご明細（右側）
	detail(course_id, fixed_price, discount, price, r_times, per_price, usered_price, payment, charge, payment_cash,balance);
}

function keisan5(){

	var course_id = document.form1.course_id.selectedIndex ; //コースID
	var paid = '<?php echo $payed_price;?>'; //支払った金額

	// // 商品金額
	var discount = document.form1.discount.value; //値引き
	var price = document.form1.price.value; //商品金額

	// // コース金額（税込）
	var fixed_price = parseInt(price) + parseInt(discount); // コース金額（税込）

	// 売掛金
	var balance =document.form1.balance.value ; // 売掛金

	// 役務回数
	var course_times_str = '<?php echo $course_times;?>';
	var course_times = course_times_str.split(',');        //文字列をカンマで分解し、配列化
	var course_time = course_times[course_id]; //役務回数

	// 消化単価
	var per_price = document.form1.per_price.value; // 消化単価

	// 消化回数
	var r_times = document.form1.r_times.value;

	// 消化金額
	var usered_price = r_times * per_price;
	document.form1.usered_price.value = usered_price;

	// 残金(支払金額 - 消化金額)
	var payment = price - balance - usered_price;
	document.form1.payment.value = -(payment);

	// 手数料(コースの金額 - 値引き - 支払った金額)
	var charge = Math.round((fixed_price - discount - usered_price) * 0.1);

	if(charge > 20000) {
		charge = 20000;
	}
	document.form1.charge.value = charge;
	
	// 返金額(残金 - 手数料)
	payment_cash = payment - charge;
	document.form1.payment_cash.value = -(payment_cash);

	// ご明細（右側）
	detail(course_id, fixed_price, discount, price, r_times, per_price, usered_price, payment, charge, payment_cash,balance);
}

function keisan6(){

	var course_id = document.form1.course_id.selectedIndex ; //コースID
	var paid = '<?php echo $payed_price;?>'; //支払った金額

	// 商品金額
	var discount = document.form1.discount.value; //値引き
	var price = document.form1.price.value; //商品金額

	// コース金額（税込）
	var fixed_price = parseInt(price) + parseInt(discount); // コース金額（税込）

	// 売掛金
	var balance =document.form1.balance.value ; // 売掛金

	// 役務回数
	var course_times_str = '<?php echo $course_times;?>';
	var course_times = course_times_str.split(',');        //文字列をカンマで分解し、配列化
	var course_time = course_times[course_id]; //役務回数

	// // 消化単価
	var per_price = document.form1.per_price.value;

	// 消化回数
	var r_times = document.form1.r_times.value;

	// 消化金額
	var usered_price = r_times * per_price;
	document.form1.usered_price.value = usered_price;

	// 残金(支払金額 - 消化金額)
	var payment = price - balance - usered_price;
	document.form1.payment.value = -(payment);

	// 手数料(コースの金額 - 値引き - 支払った金額)
	var charge = Math.round((fixed_price - discount - usered_price) * 0.1);

	if(charge > 20000) {
		charge = 20000;
	}
	document.form1.charge.value = charge;
	
	// 返金額(残金 - 手数料)
	payment_cash = payment - charge;
	document.form1.payment_cash.value = -(payment_cash);

	// ご明細（右側）
	detail(course_id, fixed_price, discount, price, r_times, per_price, usered_price, payment, charge, payment_cash,balance);
}

function keisan7(){

	var course_id = document.form1.course_id.selectedIndex ; //コースID
	var paid = '<?php echo $payed_price;?>'; //支払った金額

	// 商品金額
	var discount = document.form1.discount.value; //値引き
	var price = document.form1.price.value; //商品金額

	// コース金額（税込）
	var fixed_price = parseInt(price) + parseInt(discount); // コース金額（税込）

	// 売掛金
	var balance =document.form1.balance.value ; // 売掛金

	// 役務回数
	var course_times_str = '<?php echo $course_times;?>';
	var course_times = course_times_str.split(',');        //文字列をカンマで分解し、配列化
	var course_time = course_times[course_id]; //役務回数

	// // 消化単価
	var per_price = document.form1.per_price.value;

	// 消化回数
	var r_times = document.form1.r_times.value;

	// 消化金額
	var usered_price = r_times * per_price;
	document.form1.usered_price.value = usered_price;

	// 残金(支払金額 - 消化金額)
	var payment = price - balance - usered_price;
	document.form1.payment.value = -(payment);

	// 手数料(コースの金額 - 値引き - 支払った金額)
	var charge = Math.round((fixed_price - discount - usered_price) * 0.1);

	if(charge > 20000) {
		charge = 20000;
	}
	document.form1.charge.value = charge;
	
	// 返金額(残金 - 手数料)
	payment_cash = payment - charge;
	document.form1.payment_cash.value = -(payment_cash);

	// ご明細（右側）
	detail(course_id, fixed_price, discount, price, r_times, per_price, usered_price, payment, charge, payment_cash,balance);
}

function keisan8(){

	var course_id = document.form1.course_id.selectedIndex ; //コースID
	var paid = '<?php echo $payed_price;?>'; //支払った金額
	var fixed_price = document.form1.fixed_price.value;	 //コース金額(税込):

	// 商品金額
	var discount = document.form1.discount.value; //値引き
	var price = fixed_price - discount;
	document.form1.price.value = price; //商品金額

	// 売掛金
	var balance = document.form1.balance.value; //売掛金

	// 役務回数
	var course_times_str = '<?php echo $course_times;?>';
	var course_times = course_times_str.split(',');        //文字列をカンマで分解し、配列化
	var course_time = course_times[course_id]; //役務回数

	// 消化単価
	var per_price = Math.round(price / course_time);
	document.form1.per_price.value = per_price;

	// 消化回数
	var r_times = document.form1.r_times.value;

	// 消化金額
	var usered_price = r_times * per_price;
	document.form1.usered_price.value = usered_price;

	// 残金(支払金額 - 消化金額)
	var payment = price - balance - usered_price;
	document.form1.payment.value = -(payment);

	// 手数料(コースの金額 - 値引き - 支払った金額)
	var charge = Math.round((fixed_price - discount - usered_price) * 0.1);

	if(charge > 20000) {
		charge = 20000;
	}
	document.form1.charge.value = charge;
	
	// 返金額(残金 - 手数料)
	payment_cash = payment - charge;
	document.form1.payment_cash.value = -(payment_cash);

	// ご明細（右側）
	detail(course_id, fixed_price, discount, price, r_times, per_price, usered_price, payment, charge, payment_cash,balance,balance);
}


function keisan333(){
	if(document.form1.keep.value){
		var return_price = document.form1.payment_cash.value;
		document.form1.change.value = Number(document.form1.keep.value) - Number(return_price); // お釣りを表示
	}
}


</script>

<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>レジ電卓（中途解約）<?php echo TA_Cook($gMsg) ;?></h1></div>
		<div id="content-table">
					<!--  start content-table-inner -->
					<div id="content-table-inner">
						<table border="0" width="100%" cellpadding="0" cellspacing="0" id="register-table">
							<tr>
								<!-- start id-form -->
								<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
									<td>
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
											<tr>
												<th valign="top">区分:</th>
												<td><select name="type" class="styledselect_form_3" disabled ><?php Reset_Select_Key( $gResType3 , 5);?></select></td>
											</tr>
											<tr>
												<th valign="top">コース:</th>
												<td><select name="course_id" class="styledselect_form_3" onChange="keisan1()"><option>-</option><?php Reset_Select_Key_Group( $course_list , $data['course_id'],$gCourseGroup);?></select></td>
											</tr>
											<tr>
												<th valign="top">コース金額(税込):</th>
												<td><input type="text" name="fixed_price" value="<?php echo TA_Cook($data['fixed_price']) ;?>" class="inp-form" onChange="keisan2()" /></td>
											</tr>
											<tr>
												<th valign="top">値引き:</th>
												<td><input type="text" name="discount" value="<?php echo TA_Cook($data['discount']) ;?>" id="fm2" class="inp-form" onChange="keisan3()" /></td>
											</tr>
											<tr>
												<th valign="top">商品金額(税込):</th>
												<td><input type="text" name="price" value="<?php echo TA_Cook($data['price']) ;?>" class="inp-form" onChange="keisan4()" /></td>
											</tr>
											<tr>
												<th valign="top">売掛金:</th>
												<td><input type="text" name="balance" value="<?php echo TA_Cook($data['balance']) ;?>" class="inp-form" onChange="keisan8()"  /></td>
											</tr>
											<tr>
												<th valign="top">消化回数:</th>
												<td><input type="text" name="r_times" value="<?php echo TA_Cook($data['r_times']) ;?>" class="inp-form" onChange="keisan5()" /></td>
											</tr>
											<tr>
												<th valign="top">消化単価:</th>
												<td><input type="text" name="per_price" value="<?php echo $per_price ;?>" class="inp-form" onChange="keisan6()" /></td>
											</tr>
											<tr>
												<th valign="top">消化金額:</th>
												<td><input type="text" name="usered_price" value="<?php echo $usered_price ;?>" class="inp-form" disabled/></td>
											</tr>
											<tr>
												<td>========================</td><td>=============================</td>
											</tr>
											<tr>
												<th valign="top">残金:</th>
												<td><input type="text" name="payment" value="<?php echo $sales['id'] ? $sales['payment'] : (0-$remained_price) ;?>" class="inp-form" disabled/></td>
											</tr>
											<tr>
												<th valign="top">手数料:</th>
												<td><input type="text" name="charge" value="<?php echo $charge ;?>" class="inp-form" disabled/></td>
											</tr>
											<tr>
												<th valign="top">返金額(手数料含):</th>
												<td><input type="tel" name="payment_cash" value="<?php echo $sales['id'] ? ($sales['payment']+$charge) : (0-$return_price);?>" id="fm3" class="inp-form" /></td>
											</tr>
										</table>
									</td>
									<td>
										<div class="ditail_middle">
											<table id="expenditures">
															<tr><td colspan="2"><h1>ご明細</h1></td></tr>
															<tr><td class="regTitle">コース名</td><td  class="regTitle">金額</td></tr>
															<tr>
																<td id="course_name" class="reg"><?php echo $course_list[$data['course_id']];?></td>
																<td id="course_price" class="regPrice">￥<?php echo number_format($data['fixed_price']);?></td>
															</tr>
															<tr>
																<td class="reg">値引き</td>
																<td id="discount" class="regPrice">▲￥<?php echo number_format($data['discount']);?></td>
															</tr>
															<tr>
																<td class="regTotal">商品金額(税込)</td>
																<td id="price" class="regTotalPrice">￥<?php echo number_format($data['price'] );?></td>
															</tr>
															<tr>
																<td class="reg">支払済金額</td>
																<td id="payed_price" class="regPrice">￥<?php echo number_format($payed_price);?></td>
															</tr>
															<tr>
																<td class="reg">消化回数</td>
																<td id="r_times" class="regPrice"><?php echo $data['r_times'];?></td>
															</tr>
															<tr>
																<td class="reg">消化単価</td>
																<td id="per_price" class="regPrice">￥<?php echo number_format($per_price);?></td>
															</tr>
															<tr>
																<td class="reg">消化金額</td>
																<td id="usered_price" class="regPrice">￥<?php echo number_format($usered_price);?></td>
															</tr>
															<tr>
																<td class="regTotal">残金</td>
																<td id="payment" class="regTotalPrice">￥<?php echo $sales['id'] ? number_format($sales['payment']) : number_format(0-$remained_price);?></td>
															</tr>
															<tr>
																<td class="reg">手数料</td>
																<td id="charge" class="regPrice">￥<?php echo number_format($charge);?></td>
															</tr>
															<tr>
																<td class="regTotal">返金額(手数料含)</td>
																<td id="payment_cash" class="regTotalPrice">￥<?php echo $sales['id'] ? number_format($sales['payment']+$charge) : number_format(0-$return_price);?></td>

															</tr>
															<tr><td></td></tr>
															<tr>
																<td class="reg">お預かり</td>
																<td class="regPrice">￥<input type="tel" name="keep" value="" id="fm" style="width:70px;text-align:right;padding-right:5px;" onChange="keisan333()"/></td>
															</tr>
															<tr>
																<td class="reg">お釣り</td>
																<td class="regPrice">￥<input type="tel" name="change" style="width:70px;text-align:right;padding-right:5px;" disabled="disabled"/></td>
															</tr>
														</table>
														<?php if($complete_flg){?>
															<div align="right"><a href="./">レジ一覧へ<a></div>
														<?php } ?>
													</div>
									</td>


								</form><!-- end id-form  -->
							</tr>
						</table>
					</div><!--  end content-table-inner  -->
		</div>
	<!--  end content-table -->
	</div>
	<!--  end content -->
</div>
<!--  end content-outer -->
<?php include_once("../include/footer.html");?>