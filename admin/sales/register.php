<?php include_once("../library/sales/register.php");?>
<?php include_once("../include/header_menu.html");?>
</form>
<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading">
			<?php if(!isset($_POST['refund'])){ ?>
				<h1>商品販売レジ</h1>
				<p><a href="../help/register_manual/index.php" class="under_line" target="_blank">使い方ヘルプ</a></p>
			<?php }else{ ?>
				<h1>交換・返品処理</h1>
			<?php } ?>
			<!-- <p><?php echo $sales["pay_date"];?></p> -->
		</div>
		<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
			<tr>
				<th rowspan="3" class="sized"><img src="../images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
				<th class="topleft"></th>
				<td id="tbl-border-top"></td>
				<th class="topright"></th>
				<th rowspan="3" class="sized"><img src="../images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
			</tr>
			<tr>
				<td id="tbl-border-left"></td>
				<td>
					<!--  start content-table-inner -->
					<div id="content-table-inner">
						<?php echo $gMsg ? $gMsg : ''; ?>
						<!-- start id-form -->
						<!-- <form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="check_submit()"> -->
						<form action="" method="post" name="form1" id="form1" enctype="multipart/form-data">
							<input type="hidden" name="action" value="edit" />
							<input type="hidden" name="mode" value="<?php echo $_POST["mode"];?>" />
							<input type="hidden" name="type" value="51" />
							<input type="hidden" name="option_name" value="51" />
							<input type="hidden" name="id" value="<?php echo $_POST["id"] ? $_POST["id"] : $sales["id"];?>" /><!-- sales_id -->
							<input type="hidden" name="pay_date" value="<?php echo $sales["pay_date"] ? $sales["pay_date"] : date("Y-m-d");?>" />
							<input type="hidden" name="edit_date" value="<?php echo $sales["edit_date"];?>" />
							<input type="hidden" name="reg_date" value="<?php echo $sales["reg_date"];?>" />
							<input type="hidden" name="customer_id" value="<?php echo $customer['id'] ? $customer['id'] : $_POST["customer_id"]; ?>" />
							<input type="hidden" name="status" value="1" />
							<dl class="w350 half">
								<dt class="regster_title">会員番号</dt>
								<dd>
									<input id="customer_no" class="registration-form w11" type="text" name="customer_no" value="<?php echo $customer['no'] ? $customer['no'] : $_POST["customer_no"]; ?>" <?php echo $customer['no']||$_POST["customer_no"] ? 'readonly' : ''; ?>/>
								</dd>
								<dt class="regster_title">お客様名</dt>
								<dd class="regster_cont"><?php echo $customer['name_kana'] ? $customer['name_kana'] : ($customer['name'] ? $customer['name'] : "")?></dd>
								<dt class="regster_title">会員タイプ</dt>
								<dd class="regster_cont"><?php echo TA_Cook($gCustomerType[$customer['ctype']])?></dd>
								<dt class="regster_title">店舗名</dt>
								<dd><select id="shop_id" name="shop_id" class="styledselect_form_3"><?php Reset_Select_Key($shop_list , $sales['shop_id'] ? $sales['shop_id'] : $authority_shop['id']);?></select></dd>
								<dt class="regster_title">物販担当</dt>
								<dd>
									<select name="staff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray("staff","shop_id") , ($sales['staff_id'] && !isset($_POST['refund'])) ? $sales['staff_id'] : $authority_staff['id'] ,getDatalist5("shop", $sales['shop_id'] ? $sales['shop_id'] : $authority_shop['id']));?></select>
								</dd>
								<dt class="regster_title">レジ担当</dt>
								<dd>
									<select name="rstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray("staff","shop_id") , ($sales['rstaff_id'] && !isset($_POST['refund'])) ? $sales['rstaff_id'] : $authority_staff['id'] ,getDatalist5("shop", $sales['shop_id'] ? $sales['shop_id'] : $authority_shop['id']));?></select>
								</dd>
								<div class="payment_area">
									<dt class="regster_h_title">商品名・個数入力</dt>
									<dd class="regster_cont label_area" id="product_no">
										<ul>
											<!-- 購入前 -->
											<?php foreach ($product_list as $key => $value) { ?>
												<li>
													<span class="product_name"><?php echo $product_list[$key]; ?></span>
													<span class="product_price">￥<?php echo number_format($product_price[$key]); ?>（税込）</span>
													×
													<input type="number" min="0" name="product_count[<?php echo $key; ?>]" id="product_count<?php echo $key; ?>" class="registration-form w3" value="<?php echo $product_result[$key]['product_count']; ?>">
													<input type="hidden" name="product_no[<?php echo $key; ?>]" value="<?php echo $key; ?>">
													<input type="hidden" name="product_price[<?php echo $key; ?>]" id="product_price[<?php echo $key; ?>]" value="<?php echo $product_price[$key]; ?>">
												</li>
											<?php } ?>
										</ul>
									</dd>
								</div>
							</dl>

							<?php if(isset($_POST['refund'])){ ?><!-- 交換・返品処理 -->
								<dl class="w350 half">
									<dt class="regster_title2">商品選択</dt>
									<dd class="regster_cont label_area " id="product_no">
										<?php echo InputHiddenTagKey("product_no",$product_list,$data['product_no'],"","","product_name")?>
									</dd>
									<?php if($_POST['refund'] == 1){ ?> <!-- 返金ありの場合 -->
										<hr class="line2 w20">
										<dt class="regster_title2">返金額</dt>
										<dd class="regster_cont right_area">
											<ul>
												<li>
													<span class="regster_title">現金</span>
													<input type="text" id="cash2" class="registration-form w7" name="option_transfer" value="<?php  ?>">
												</li>
												<?php if($authority_level<=6){?>
												<li>
													<span class="regster_title">カード</span>
													<input type="text" id="card2" class="registration-form w7">
												</li>
												<?php } ?>
											</ul>
										</dd>
									<?php } ?>
									<hr class="line2 w20">
									<dt class="regster_title2">交換・返品理由</dt>
									<dd class="right_area">
										<textarea id="memo" class="form-textarea2" name="memo"></textarea>
									</dd>
									<dd class="btn-area">
										<input type="submit" value="submit" class="submit" id="submit">
										<input type="reset" value="reset" class="reset">
									</dd>
								</dl>
							<?php } ?><!-- 交換・返品処理ここまで -->

							<dl class="w350 half <?php echo isset($_POST['refund']) ? 'history_area':'' ; ?>"><!-- 購入時金額表示・購入履歴 -->
								<?php /* if($_POST['refund']){ */ ?><!-- 交換・返品処理画面 -->
								<?php /* }else{ */ ?><!-- 購入前・領収書画面 -->
									<dt class="regster_title2">合計金額</dt>
									<dd class="regster_cont total_price">￥<span id="count_buy"><?php echo number_format($sales['option_price'] + $sales['option_card']); ?></span></dd>
								<?php /* } */ ?>
								<?php if($_POST['id'] || $sales["id"]){ ?><!-- 購入後 -->
									<div class="history_area w20">
										<dt class="regster_h_title">購入履歴</dt>
										<hr class="line2 w20">
										<dd class="regster_cont label_area w100">
										<?php
											if($product_result){
												$i = 0;
												while ($i < count($product_result)) {
													$result =  array_slice($product_result,$i,1);
													echo '<li>';
													echo '<span class="product_name_h">'.$product_list[$result[0]['product_no']].'</span>';
													echo '<span class="product_price_h">';
													echo '￥'.number_format($result[0]['price']).'（税込）×';
													echo '<span class="product_count_h">'.$result[0]['product_count'].'</span>';
													echo '</span>';
													echo '</li>';
													$i++;
												}
											}
										 ?>
											<?php $product = Get_Table_Array_Multi("product_stock","id,product_no,price,product_count"," WHERE del_flg=0 and sales_id = '".addslashes(($_POST["id"] ? $_POST["id"] : $sales["id"]))."'"); //購入した商品名・金額・数を取得 ?>
											</ul>
										</dd>
									</div>
								<?php } ?>
							</dl>

							<!-- PDFの領収書代わりテスト制作
											<li class="button prodct_btn">
												<span class="product_name"><?php echo $value; ?></span>
												<span class="product_price">￥<?php echo number_format($product_price[$key]); ?>（税込）</span>
												<input type="number" min="0" name="product_count[<?php echo $key; ?>]" id="product_count<?php echo $key; ?>" class="registration-form w3">
												<input type="hidden" name="product_no[<?php echo $key; ?>]" value="<?php echo $key; ?>">
												<input type="hidden" name="product_price[<?php echo $key; ?>]" id="product_price[<?php echo $key; ?>]" value="<?php echo $product_price[$key]; ?>">
											</li>

							<dl class="w350 half">
								<ul id="reslut">
								</ul>
								<script type="text/javascript">
									$(".prodct_btn").on("click",function(){
										var $this,product_name,product_count,count_no,no_name,product_price,reslut_cont,li,span,reslut;
										$this = $(this);
										product_count = $this.children('[name^="product_count"]').val(); /* 個数の取得 */
										product_count++;
										$this.children('[name^="product_count"]').val(product_count);

										count_no = $this.children('[name^="product_count"]').attr('name'); /* 個数入力欄のname取得 */
										no_name = $this.children('[name^="product_no"]').attr('name'); /* 商品IDの取得 */
										if(document.getElementById(count_no)){
											document.getElementById(count_no).innerHTML = product_count;
										}else{
											product_name = $this.children('.product_name').text(); /* 商品名の取得 */
											product_price = $this.children('.product_price').text(); /* 表示金額の取得 */
											reslut_cont = (product_name + product_price + '×' + product_count);
											li = document.createElement('li'); /* 表示エリア作成 */
											span = document.createElement('span'); /* 個数エリア作成 */
											li.id = no_name;
											span.id= count_no;
											reslut = document.getElementById('reslut');
											li.innerHTML = reslut_cont;
											reslut.appendChild(li);
										}
									})
								</script>
							</dl> -->
							<dl class="w350 half <?php echo isset($_POST['refund']) ? 'history_area':'' ; ?>"><!-- 購入時支払方法 -->
								<div class="payment_area <?php echo isset($_POST['refund']) ? 'history_area':'' ; ?>">
									<dt class="regster_h_title">支払方法</dt>
									<hr class="line2 w20">
										<span>※全額現金、または全額カード支払のみ</span>
										<dd class="regster_cont label_area payment_methods">
											<div class="payment_methods">
												<button type="button" id="cash" class="button payment_btn option_price <?php echo ($sales['option_price'] <> 0 && $sales['option_card'] == 0) ? "selected" : ""; ?>">全額現金</button>
												<button type="button" id="card" class="button payment_btn option_card <?php echo ($sales['option_price'] == 0 && $sales['option_card'] <> 0) ? "selected" : ""; ?>">全額カード</button>
												<button type="button" id="free" class="button payment_btn free <?php echo ($sales && $sales['option_price'] == 0 && $sales['option_card'] == 0) ? "selected" : ""; ?>">プレゼント（無料）</button>
											</div>
											<input type="hidden" id="use_status" name="use_status" value="<?php
												if($sales['option_price'] <> 0 && $sales['option_card'] == 0){echo 'cash';}
												else if($sales['option_price'] == 0 && $sales['option_card'] <> 0){echo 'card';}
												else if($sales['option_price'] == 0 && $sales['option_card'] == 0){echo 'free';}
											 ?>">
											<input type="hidden" id="total_price" name="total_price" value="<?php echo ($sales['option_price'] + $sales['option_card']); ?>"><!-- 用途 -->
										</dd>
									<!-- </div> -->
								</div>
								<div class="btn-area">
									<?php if(!$sales["id"] && !$_POST["id"]){?><!-- 購入前 -->
										<input type="submit" value="submit" class="submit" id="submit">
										<input type="reset" value="reset" class="reset">
									<?php }else if($retouch_flg > 0){?><!-- システム権限か、購入後・当日のみ -->
											<!-- 修正はこちら --><input type="submit" value="修正する" class="submit" id="submit">
									<?php } ?>
								</div>

							<?php if($sales['id'] && !isset($_POST['refund'])){?><!-- 購入後、返品・交換処理でない場合 -->
									<!--  start related-activities -->
									<div id="related-activities">
										<!--  start related-act-top -->
										<div id="related-act-top">
											<div class="title">出力</div>
										</div>
										<!-- end related-act-top -->
										<!--  start related-act-bottom -->
										<div id="related-act-bottom">
											<!--  start related-act-inner -->
											<div id="related-act-inner">
												<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
												<div class="right">
													<h5><a href="javascript:void(0);" onclick="window.open('../pdf/pdf_register.php?sales_id=<?php echo $sales['id'];?>', 'mywindow4', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" class="side" title="領収書発行へ">領収書</a></h5>
												</div>
												<div class="clear"></div>
												<!-- 交換、返品処理制作中
												<div class="lines-dotted-short"></div>
												<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
												<div class="right">
													<h5>交換・返品処理</h5>
													<ul class="registration-cnt">
														<li><a class="button" href="./register.php?id=<?php echo $sales['id']; ?>&customer_id=<?php echo $sales['customer_id']; ?>&refund=0" class="side" title="交換・返品処理">返金なし</a></li>
														<li><a class="button" href="./register.php?id=<?php echo $sales['id']; ?>&customer_id=<?php echo $sales['customer_id']; ?>&refund=1" class="side" title="交換・返品処理">返金あり</a></li>
													</ul>
												</div>
												<div class="clear"></div> -->
											</div><!-- end related-act-inner -->
											<div class="clear"></div>
										</div><!-- end related-act-bottom -->
									</div>
									<!-- end related-activities -->
								<!-- </div> -->
							<?php } ?>
						</dl>
							<div class="regster_title">登録日時：<?php echo TA_Cook($sales['reg_date']) ;?></div>
							<div class="regster_title">販売No：<?php echo TA_Cook($sales['id']) ;?></div><!-- sales_id -->
						</form>
						<!-- end id-form  -->
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
	</div>
	<!--  end content -->
</div>
<!--  end content-outer -->
<script type="text/javascript">
jQuery(function ($) {
    //登録完了時アラート
    let complete_flg = "<?php echo $complete_flg; ?>";
    if (complete_flg) {
        let sales_id = "<?php echo $sales['id']; ?>";
        let customer_id = "<?php echo $customer['id']; ?>";
        alert('登録が完了しました。');
        location.href = `./register.php?id=${sales_id}&customer_id=${customer_id}&new_regist_id=${sales_id}`;
    }
    //登録完了後のブラウザバックを禁止
    let sales_param = "<?php echo $_GET['id']; ?>";
    if (sales_param) {
        history.pushState(null, null, null);

        window.addEventListener("popstate", function() {
            history.pushState(null, null, null);
        });
    }
	var $product_count,$product_price,$option_prices,price,$payment_btn,$readonly;
		price = 0;
		// $product_count = $("#product_count"),/* 購入個数 */
		$product_count = $("[id^='product_count']"),/* 購入個数 */
		$product_price = $("[id^='product_price']"),/* 商品金額 */
		// $option_prices = $("#option_price, #option_card"),/* 入金（現金,カード） */ 20170406 delete ueda
		$payment_btn = $(".payment_btn"); /*支払方法選択ボタン*/
		$use_status = $("#use_status"); /*用途選択*/
		$total_price = $("#total_price"); /*支払い合計金額 */
		$readonly = $(".readonly input");
		$product_count.on("change",function(e){/* 購入個数の変更時 */
			reg_price_calculate();
			set_total_price(); // 20170406 add ueda
			// $payment_btn.removeClass('selected'); 20170406 delete ueda
		});
		/* 支払方法の変更 */
		<?php if(!$sales["id"] || $retouch_flg > 0){?>/* 購入前、または購入日当日かシステム権限 */
			$payment_btn.on("click",function(){
				reg_price_calculate(); // 20170406 add ueda
				set_use_status(this); // 20170406 add ueda
			})
		<?php } ?>
		$("#submit").on("click",function(e){/* submit動作 */
			var ok_flg;
			ok_flg = check_submit();
			if(ok_flg == 0){return false;};
		})
	 // 20170406 add ueda
	function set_use_status(target){
		var $this,$id;
		$this = $(target),
		$id = target.id;
		if($this.hasClass('selected')){/* 選択済ボタン */
			$payment_btn.removeClass('selected');
			$option_prices.val(0);
		}else{/* 未選択ボタン */
			$payment_btn.removeClass('selected'),
			$this.addClass('selected'),
			$use_status.val($id);
		}
	}
	 // 20170406 add ueda
	function set_total_price(){
		$total_price.val(price);
	}
	function reg_price_calculate(){/* 合計金額表示 */
		var i,count_buy,all_price;
		count_buy = document.getElementById("count_buy"),
		all_price = 0;
		for(i=($product_price.length-1); i>=0; i--){
			all_price += ($product_price[i].value * $product_count[i].value);
		}
		count_buy.innerHTML = comma(all_price);
		price = all_price;/* 合計金額を変数へセット */
	};
	function comma(numbers){ //数値1234→1,234
		numbers = String(numbers).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' );
		return numbers;
	};
		/*入力個数の制御*/
	function check_submit(){/* submit時チェック */
		var shop_id,customer_no,staff_id,product_count_value,text,ok_flg;
		ok_flg = 0;
		shop_id = document.getElementById("shop_id"),
		shop_id = shop_id[shop_id.selectedIndex].value,
		customer_no = document.getElementById("customer_no").value,
		staff_id = document.getElementsByName("staff_id")[0],
		staff_id = staff_id[staff_id.selectedIndex].value;
		product_count_value = 0;
		for(var i = ($product_count.length - 1); i >=0; i--){
			product_count_value += Number($product_count[i].value);
		};
		if(!shop_id || !customer_no || !staff_id || !product_count_value || product_count_value < 1 || !$payment_btn.hasClass('selected')){
			text = (!shop_id || shop_id==0 ? "\n・店舗名":"") + (!customer_no ? "\n・会員番号":"") + (!staff_id ? "\n・物販担当":"") + (product_count_value < 1 ? "\n・個数":"")+ (!$payment_btn.hasClass('selected') ? "\n・支払方法":"")/*  + (!memo ? "\n・交換・返品理由":"")*/;
			ok_flg = 0;
			alert("以下の項目が不足しています。\n" + text + "\n\nもう一度入力してください。");
			return ok_flg;
		}else{
			ok_flg = conf1("");
			if(ok_flg == false){
				ok_flg = 0;
				return ok_flg;
			}
		}
	};
});
</script>
<script type="text/javascript">
// new AutoKana('Name', 'NameKana', {katakana: true, toggle: false});
// 	new AutoKana('FirstName', 'FirstNameKana', {katakana: true, toggle: false});
// 	new AutoKana('LastName', 'LastNameKana', {katakana: true, toggle: false});
</script>
<?php include_once("../include/footer.html");?>