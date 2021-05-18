<?php include_once("../library/reservation/index.php");?>
<?php include_once("../include/header_menu.html");?>
<script type="text/javascript">
function csv_export () {
    var name=prompt("パスワードを入力して下さい。", "");
    var path = "<?php echo $home_url;?>" + "admin/img/" + name + ".gif";
	var img = new Image();
    img.src = path;
    if( img.height > 0){ // 読み込みに失敗すれば 0 になる。
      document.search.action = "csv_export.php";
	  document.search.submit();
	  return fales;
  	}else{
    	return false;
  	}
}
</script>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			予約/来店情報一覧
			<span style="margin-left:20px;">
				<a href="./?hope_date=<?php echo $pre_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="hope_date" type="text" id="day" value="<?php echo $_POST['hope_date'];?>" readonly  />~<input style="width:70px;height:21px;" name="hope_date2" type="text" id="day2" value="<?php echo $_POST['hope_date2'];?>" readonly  />
				<a href="./?hope_date=<?php echo $next_date?>&shop_id=<?php echo $_POST['shop_id'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<!--<select name="shop_id" style="height:25px;" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : $_POST['shop_id'] );?></select>-->
				<select name="shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']);?></select>
				<select name="ctype" style="height:25px;" ><option value="">会員タイプ</option><?php Reset_Select_Key( $gCustomerType , $_POST['ctype'] );?></select>
				<select name="type" style="height:25px;" ><option value="">区分</option><?php Reset_Select_Key( $gResType4 , $_POST['type'] );?></select>
				<!-- <select name="hp_flg" style="height:25px;" ><option value="">-</option><option value="1">月額(HP)</option></select> -->
				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type="submit" value=" 表示 "  style="height:25px;" onClick="form.action='index.php';return true" />
			</span>
			<?php if($authority_level<=1){?>
			<span style="float:right; margin-right:25px;">
				<input type='button' value='　CSV　' onclick='csv_export();' style="height:25px;" />
			</span>
			<?php  }?>
		</h1>
    <p>消化（来店）回数は現在表示されません。</p>
		</form>
	</div>
	<!-- end page-heading -->
	<div id="content-table">
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
			<!--  start table-content  -->
			<div id="table-content">
				<!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      	<tr><td><?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?></td></tr>
      </table>
      <!--  end paging................ -->
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href="">区分</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">店舗</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">経由</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">会員番号</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">名前</a></th>
				<?php if($authority_level<=6 || $authority['id']==106){?>
					<th class="table-header-repeat line-left minwidth-1"><a href="">電話番号</a></th>
				<?php } ?>
					<th class="table-header-repeat line-left minwidth-1"><a href="">コース</a></th>
          <!-- 消化回数一時コメントアウト 20160323 -->
					<!-- <th class="table-header-repeat line-left minwidth-1"><a href="">消化(来店)回数</a></th> -->
					<th class="table-header-repeat line-left minwidth-1"><a href="">売掛金</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">来店日時</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">オプション</a></th>
				</tr>
<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		/*if($data['contract_id']) $contract = Get_Table_Row("contract"," WHERE del_flg=0 and id = '".addslashes($data['contract_id'])."'");
		else $contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($data['customer_id'])."' order by id desc");*/

		$contract = Get_Table_Row("contract"," WHERE del_flg=0 and customer_id = '".addslashes($data['customer_id'])."' order by id desc");

		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .'>';
		echo 	'<td>'.$gResType4[$data['type']].'</td>';
		echo 	'<td>'.$shop_list[$data['shop_id']].'</td>';
		echo 	'<td>'.$gRoute[$data['route']].'</td>';
		echo 	'<td>'.$data['no'].'</td>';
		echo 	'<td title="'.$data['name'].'">'.$data['name_kana'].'</td>';
		if($authority_level<=6 || $authority['id']==106){
		echo 	'<td>'.( $data['ctype']<2 ? $data['tel'] : "").'</td>';
		}

		// 1つのコース/複数コース表示出しわけ 2017/04/26 add by shimada
		if($data['multiple_contract_id']){
			if(is_numeric($data['multiple_contract_id'])){
				// 契約IDからコース名を取得する
				$course_id = Get_Table_Col("contract"," course_id"," WHERE del_flg=0 and id=".$data['multiple_contract_id']);
				echo 	'<td><span class="c_couse_oen">'.$course_list[$course_id].'</span></td>';
    } else {
				// 複数コースIDがあるときは分解した配列を作り、各コース名を表示する
				$multiple_course = explode(',', $data['multiple_contract_id']);
      echo  '<td><ul class="c_couse_list">';
      foreach ($multiple_course as $key => $value) {
					// 契約IDからコース名を取得する
					$course_id = Get_Table_Col("contract"," course_id"," WHERE del_flg=0 and id=".$value);
					echo 	'<li>'.$course_list[$course_id].'</li>';
      }
      echo  '</ul></td>';
    }
		} else {
			 echo 	'<td>-</td>';
		}


    /* 消化回数一時コメントアウト 20160323 */
		// echo 	'<td class="priceFormat">'.$contract['r_times'].'</td>';
		echo 	'<td class="priceFormat">'.number_format($contract['balance']).'</td>';
		echo 	'<td>'.$data['hope_date'].' '.$gTime2[$data['hope_time']].'</td>';
		echo 	'<td style="width:140px;">';
		echo 		'<a href="edit.php?id='.$data['id'].'&shop_id='.$data['shop_id'].'&hope_date='.$data['hope_date'].'" title="予約詳細" class="icon-1 info-tooltip"></a>';
		echo 		'<a href="index.php?action=cancel&id='.$data['id'].'&shop_id='.$data['shop_id'].'" onclick="return confirm(\'キャンセルしますか？\')" title="キャンセル処理" class="icon-2 info-tooltip"></a>';
		//echo 		'<a href="index.php?action=send&id='.$data['id'].'" onclick="return confirm(\'マイページ情報を送信しますか？\')" title="Send" class="icon-3 info-tooltip"></a>';
		echo 		'<a href="edit.php?mode=new_rsv&type=2&customer_id='.$data['customer_id'].'&shop_id='.$data['shop_id'].'" onclick="return confirm(\'次の予約をしますか？\')" title="次回予約新規" class="icon-5 info-tooltip"></a>';
		//echo 		'&nbsp;<a href="../account/reg_detail.php?id='.$data['id'].'" onclick="return confirm(\'レジ精算に移動しますか？\')" title="レジ精算" class="icon-4 info-tooltip"></a>';
	if($data['type']==1){
		echo 	'<a href="../account/reg_detail.php?id='.$data['id'].'&shop_id='.$data['shop_id'].'" title="レジ精算へ" class="icon-1 info-tooltip"></a>';
	}else{
		echo 	'<a href="../service/detail.php?id='.$data['id'].'&shop_id='.$data['shop_id'].'" title="レジ精算へ" class="icon-1 info-tooltip"></a>';
	}
		echo 	'</td>';
		echo '</tr>';
		$i++;
	}
}
?>
				
				</table>
				<!--  end product-table................................... --> 
				</form>
			</div>
			<!--  end content-table  -->
			<!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      	<tr><td><?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?></td></tr>
      </table>
      <!--  end paging................ -->
      <div class="clear"></div>
    </div>
    <!--  end content-table-inner ............................................END  -->
  </div>
</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>