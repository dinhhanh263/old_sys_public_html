<?php include_once("../library/account/product_stock.php");?>
<?php include_once("../include/header_menu.html");?>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox()
})
</script>

<style type="text/css">
#hoge {
    width:100%;
    position:absolute;
    top:160px;
    left:0;
}
#hogeInner {
    text-align: right;
    margin:0 0;
    padding: 0 23px;
}
</style>
<script type="text/javascript">
function csv_export (target) {
    var target_csv = target+".php";
    var name=prompt("パスワードを入力して下さい。", "");
    var path = "<?php echo $home_url;?>" + "admin/img/" + name + ".gif";
		var img = new Image();
    img.src = path;
    img.onload = function(){
	    if( img.height > 0){ // 読み込みに失敗すれば 0 になる。
		    document.search.action = target_csv;
			  document.search.submit();
		    document.search.action = "";
			  return false;
	  	}else{
	    	return false;
	  	}
    };
}
</script>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<?php
			$gProducStatus = array( 1 => "物販", 2=>"プレゼント");
		?>
		<h1>
			物販管理一覧
			<span style="margin-left:20px;"><span style="font-size:15px;">
			<?php if(!$_POST['customer_id']){?>
				<a href="./product_stock.php?mode=display&pay_date=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>&type=<?php echo $_POST['type']?>&course_id=<?php echo $_POST['course_id'];?>&option_name=<?php echo $_POST['option_name'];?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="pay_date" type="text" id="day" value="<?php echo $_POST['pay_date'];?>" readonly  />~<input style="width:70px;height:21px;" name="pay_date2" type="text" id="day2" value="<?php echo $_POST['pay_date2'];?>" readonly  />
				<a href="./product_stock.php?mode=display&pay_date=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>&type=<?php echo $_POST['type']?>&course_id=<?php echo $_POST['course_id'];?>&option_name=<?php echo $_POST['option_name'];?>&keyword=<?php echo $_POST['keyword'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
			<?php } ?>

				店舗名：<!-- <select name="shop_id" style="height:25px;width:150px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select> -->
				<select id="shop_id" name="shop_id" style="height:25px;width:150px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><option value="0">全店舗</option><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'], $gArea_Group, "area_group"); ?></select>
				<select name="use_status" style="height:25px;width:100px;" ><option value="">全区分</option><?php Reset_Select_Key( $gProducStatus , $_POST['use_status'] );?></select>
				<select name="product_no" style="height:25px;width:180px;" ><option value="">全商品</option><?php Reset_Select_Key( $product_list , $_POST['product_no']?$_POST['product_no']:"" );?></select><!-- 商品名検索 -->
				<!--<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" />件/頁</span>-->
				<input type="hidden" name="mode" value="display" />
				<input type="submit" value=" 表示 "  style="height:25px;" onclick="form.action='product_stock.php'" />
			</span>
			<?php if($authority_level<=1){?>
			<span style="float:right; margin-right:25px;">
				<input type='button' value='顧客売上CSV' onclick="csv_export('prduct_customer_csv');" style="height:25px;" />
				<!-- <input type='button' value='スタッフ売上CSV' onclick="csv_export('prduct_csv');" style="height:25px;" /> -->
			</span>
			<?php  }?>
		</h1>
		</form>
		<p><a href="../help/register_manual/index.php#3" class="under_line" target="_blank">使い方ヘルプ</a></p>
	</div>
	<!-- end page-heading -->
	<p>※濃い色の行は無料プレゼント</p>
	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
		<tr>
			<td>
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
			<!--  start table-content  -->
			<div id="table-content">
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">清算ID</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">管理No</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">店舗</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">日付</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">商品名</font></font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">個数</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">区分</font></a>	</th>

					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">会員番号</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">顧客氏名</font></a></th>
					<th class="table-header-repeat line-left minwidth-1" title="クーポン入金"><a href=""><font size="-2">レジ担当</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">物販担当</font></a></th>
          <?php if($authority_level<1){ ?>
					<th class="table-header-repeat line-left"><a href="">取消</a></th>
          <?php } ?>
				</tr>
<?php
if ( $dRtn3 ) {
	$i = 1;
	$cnt_product = 0;
	while ( $data = $dRtn3->fetch_assoc() ) {
		echo '<tr class="'. ( $i%2==0 ? 'alternate-row' : '' ).( ($data['use_status'] == 2 || $data['use_status'] == 4) ? ' closed' : '' ) .'">';
		echo 	'<td><a href="../sales/register.php?id='.$data['sales_id'].'&customer_id='.$data['customer_id'].'" class="under_line" target="_blank">'.$data['sales_id'].'</a></td>'; //売上ID（清算ID）
		echo 	'<td>'.$data['id'].'</td>'; //物販ID（管理No）
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>'; //店舗
		echo 	'<td>'.$data['pay_date'].'</td>'; //日付
		echo 	'<td>'.$product_list[$data['product_no']]; //商品名
		echo 	'<td class="priceFormat">'.number_format($data['product_count']).'</td>'; 							//個数
		echo 	'<td>'.$gProducStatus[$data['use_status']].'</td>'; //区分
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td><a href="../sales/register.php?id='.$data['sales_id'].'&customer_id='.$data['customer_id'].'" class="under_line" target="_blank">'.($data['name'] ? $data['name'] : $data['name_kana']).'</a></td>';
		// echo 	'<td class="priceFormat">'.number_format($data['price']).'</td>'; 							//商品販売金額
		echo 	'<td class="priceFormat">'.$staff_list[$data['rstaff_id']].'</td>'; 							//レジ担当
		echo 	'<td class="priceFormat">'.$staff_list[$data['staff_id']].'</td>'; 								//物販担当
		if($authority_level<1){
      echo    '<td><a href="product_stock.php?action=delete&sales_id='.$data['sales_id'].'&id='.$data['id'].'" onclick="return confirm(\'物販レジを取消しますか？\')" title="物販取消" class="icon-2 info-tooltip"></a></td>';
    };								//取消
		echo '</tr>';
		$cnt_product += number_format($data['product_count']); //出庫数
		if($data['use_status']==2)$cnt51_noprice += number_format($data['product_count']); //無料出庫数

		$i++;
	}

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>合計</td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo 	'<td></td>';
		echo 	'<td></td>';

		echo 	'<td></td>';
		echo 	'<td class="priceFormat">'.number_format($total_option_price).'</td>'; 	//商品販売金額
		echo 	'<td class="priceFormat"></td>';		//レジ担当の下
		echo 	'<td class="priceFormat"></td>';		//物販担当の下
		echo '</tr>';

		echo '<tr><td colspan="10"></td><td>内訳</td>';

		echo '<tr class="line"></tr><tr><td colspan="10" class="priceFormat">出庫数:</td><td class="priceFormat">'.number_format($cnt_product).' 個</td></tr>';
		echo '<tr><td colspan="10" class="priceFormat">無料出庫数:</td><td class="priceFormat">('.number_format($cnt51_noprice).' 個)</td></tr>';
}
?>

				</table>
				</form>
				<div id="hoge">
			    <div id="hogeInner">
		        <?php
							$param  = '?cnt_monthly='.$cnt_monthly.'&cnt_pack='.$cnt_pack;
							$param .= '&total_cash='.$total_cash.'&total_card='.$total_card.'&total_transfer='.$total_transfer.'&total_loan='.$total_loan.'&total_balance='.$total_balance;
							$param .= '&total_incloude='.($total_balance + $total_without_balance).'&total_uncloude='.$total_without_balance;
		        ?>
			    </div>
				</div>
			</div>
			<!--  end content-table  -->
			<!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
	      <tr>
		      <td>
		      <?php //Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
		      </td>
	      </tr>
      </table>
      <!--  end paging................ -->
    </div>
    <!--  end content-table-inner ............................................ -->
			</td>
	  </tr>
  </table>
</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>