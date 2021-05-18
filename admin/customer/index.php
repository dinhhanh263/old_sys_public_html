<?php include_once("../library/customer/index.php");?>
<?php include_once("../include/header_menu.html");?>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox()
})
</script>

<script type="text/javascript">
function csv_export () {
    var name=prompt("パスワードを入力して下さい。", "");
    var path = "<?php echo $home_url;?>" + "admin/img/" + name + ".gif";
	var img = new Image();
    img.src = path;
    img.onload = function() {
      document.search.action = "csv_export.php";
	  document.search.submit();
	  document.search.csv_pw.value = name;
	  return true;
  	};
}

function change_rejected_flg(customer_id) {
		let url = '/admin/customer/change_rejected_flg.php';

	        $.ajax({
                url:url,
                type:'POST',
                data:{
					'rejected_flg':$(`#rejected_flg_${customer_id}`).val(),
					'customer_id':customer_id
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
					if(data==customer_id) {
						alert("登録に成功しました。");
					} else {
						alert("登録に失敗しました。時間をおいてもう一度お試しください");
					}
                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                    alert("登録に失敗しました。時間をおいてもう一度お試しください");
                })
                // Ajaxリクエストが成功・失敗どちらでも発動
                .always( (data) => {

			});
		}

function change_agree_status(customer_id) {
		let url = '/admin/customer/change_agree_status.php';

	        $.ajax({
                url:url,
                type:'POST',
                data:{
					'agree_status':$(`#agree_status_${customer_id}`).val(),
					'customer_id':customer_id
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
					if(data==customer_id) {
						alert("登録に成功しました。");
					} else {
						alert("登録に失敗しました。時間をおいてもう一度お試しください");
					}
                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                    alert("登録に失敗しました。時間をおいてもう一度お試しください");
                })
                // Ajaxリクエストが成功・失敗どちらでも発動
                .always( (data) => {
			});
		}

function change_mail_status(customer_id) {
    let url = '/admin/customer/change_mail_status.php';

    $.ajax({
        url:url,
        type:'POST',
        data:{
            'mail_status':$(`#mail_status_${customer_id}`).val(),
            'customer_id':customer_id
        }
    })
        // Ajaxリクエストが成功した時発動
        .done( (data) => {
            if(data==customer_id) {
                alert("登録に成功しました。");
            } else {
                alert("登録に失敗しました。時間をおいてもう一度お試しください");
            }

        })
        // Ajaxリクエストが失敗した時発動
        .fail( (data) => {
            alert("登録に失敗しました。時間をおいてもう一度お試しください");
        })
        // Ajaxリクエストが成功・失敗どちらでも発動
        .always( (data) => {

        });
}

$(function(){
	$('.styledselect_form_3').change(
		function() {
			if ($(this).val() != "") {
				location.href = $(this).val();
			}
		}
	);
});
</script>

<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="customer-content">
	<!--  start page-heading -->
	<div id="page-heading">

		<h1>
			顧客一覧
			<span style="margin-left:20px;">
				<a href="./?reg_date2=<?php echo $pre_date?>&search_shop_id=<?php echo $_POST['search_shop_id'];?>&ctype=<?php echo $_POST['ctype'];?>&rebook_flg=<?php echo $_POST['rebook_flg'];?>&adcode=<?php echo $_POST['adcode'];?>&line_max=<?php echo $_POST['line_max'];?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
				<input style="width:70px;height:21px;" name="reg_date" type="text" id="day" value="<?php echo $_POST['reg_date'];?>" readonly />~<input style="width:70px;height:21px;" name="reg_date2" type="text" id="day2" value="<?php echo $_POST['reg_date2'];?>" readonly  />
				<a href="./?reg_date2=<?php echo $next_date?>&search_shop_id=<?php echo $_POST['search_shop_id'];?>&ctype=<?php echo $_POST['ctype'];?>&rebook_flg=<?php echo $_POST['rebook_flg'];?>&adcode=<?php echo $_POST['adcode'];?>&line_max=<?php echo $_POST['line_max'];?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>
				<select name="search_shop_id" style="height:25px;" ><option>-</option><?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['search_shop_id'] ? $_POST['search_shop_id'] : "", $gArea_Group, "area_group" );?></select>
				<select name="ctype" style="height:25px;" ><option value="">会員タイプ</option><?php Reset_Select_Key( $gCustomerType , $_POST['ctype'] );?></select>
				<select name="rebook_flg" style="height:25px;" ><?php Reset_Select_Key( $gRebook_type , $_POST['rebook_flg'] );?></select>
				<select name="route" style="height:25px;" ><option value="">全経由</option><?php Reset_Select_Key( $gRoute , $_POST['route'] );?></select>
			<?php if($authority_level<=1){?>
				<select name="adcode" style="height:25px;width:100px;" ><?php Reset_Select_Key( $adcode_list , $_POST['adcode'] );?></select>
			<?php  }?>
				<input style="width:25px;height:20px;" name="line_max" type="text" value="<?php echo $_POST['line_max'];?>" /><span style="font-size:15px;">件/頁</span>
				<input type="submit" value=" 表示 "  style="height:25px;" onClick="form.action='index.php';return true" />
			</span>
			<?php if($authority_level<=1){?>
			<span style="float:right; margin-right:25px;">
				<input type='button' value='　CSV　' onclick='csv_export();' style="height:25px;" />
				<input type='hidden' name="csv_pw" value="" />
			</span>
			<?php  }?>
		</h1>
		</form>
	</div>

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
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
			<!--  start table-content  -->
			<div id="table-content">
				<!-- end page-heading -->
	<table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      	<!--<tr><td>総予約件数：<?php echo ($dGet_Cnt+$dGet_Cnt6[0]);?>件,&nbsp;&nbsp;媒体経由の件数：<?php echo $dGet_Cnt4;?>件,&nbsp;電話経由の件数：<?php echo $dGet_Cnt5;?>件,&nbsp;再申込の件数：<?php echo $dGet_Cnt6[0];?>件,&nbsp;<?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?></td></tr>-->
      	<tr><td><?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?></td></tr>
      </table>
      <!--  end paging................ -->
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
          <div class="sc-table">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
          <tbody>
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">経由</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">会員番号</font></a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">名前</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">生年月日</font></a></th>
				<?php if($authority_level<=6 || ($authority['id']==106 || $authority['id']==1449) && $_POST['ctype']<2){?>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">電話番号</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">メールアドレス</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">媒体</font></a></th>
				<?php } ?>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">友達紹介元</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">銀行口座</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">個人支払</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">マイページ</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">接触不可</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">親権者同意書</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">メールステータス</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">売上詳細</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">消化詳細</font></a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">登録日時</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">新規予約</a></th>
					<!--<th class="table-header-<?php echo ($authority_level<=1) ? "options" : "repeat" ?> line-left"><a href="">オプション</a></th>-->
					<th class="table-header-<?php echo ($authority_level<=1) ? "repeat" : "repeat" ?> line-left"><a href=""><font size="-2">オプション</font></a></th>
				</tr>
<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 1;
	while ( $data = $dRtn3->fetch_assoc() ) {
		$rsv = Get_Table_Row("reservation"," WHERE del_flg=0 and customer_id = '".addslashes($data['id'])."' order by hope_date desc,id desc limit 1");//最新予約
		switch ($rsv['today_status']) {
			case 1:
				$con_status ="green";
				break;
			case 2:
				$con_status ="purple";
				break;
			case 3:
				$con_status ="red";
				break;
			case 4:
				$con_status ="orange";
				break;
			default:
				$con_status ="black";
				break;

		}


		echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) .' style="color:'.$con_status.'">';
		echo 	'<td style="width:35px;">'.$gRoute[$data['route']].'</td>';
		echo 	'<td><a href="/admin/customer/edit.php?customer_id='.$data['id'].'" title="顧客詳細">'.$data['no'].'</td>';
		// echo 	'<td title="'.$data['name'].'">'.($data['name_kana'] ? $data['name_kana'] : $data['name']).'</td>';
		echo 	'<td title="'.$data['name'].'"><a href="/admin/contract/index.php?customer_id='.$data['id'].'" title="契約情報">'.($data['name_kana'] ? $data['name_kana'] : ($data['name'] ? $data['name'] : '無名')).'</a></td>';
		echo 	'<td>'.($data['birthday']=="0000-00-00" ? "" : $data['birthday']).'</td>';
	if($authority_level<=6 || ($authority['id']==106 || $authority['id']==1449) && $_POST['ctype']<2){
		echo 	'<td>'.str_replace('-' , '' , $data['mobile'] ? $data['mobile'] : $data['tel']).'</td>';
		echo 	'<td>'.$data['mail'].'</td>';
		echo 	'<td>'.$adcode_list[$data['adcode']].'</td>';
	}
		// echo 	'<td><a href="../main/?id='.$rsv['id'].'&shop_id='.$rsv['shop_id'].'&hope_date='.$rsv['hope_date'].'">'.$rsv['hope_date']." ".$gTime2[$rsv['hope_time']].'</a></td>';
		echo 	'<td>' . $data['introducer_name'] . '</td>';
		echo    '<td><a href="../customer/bank_detail.php?customer_id='.$data['id'].'" target="_blank">詳細</a></td>';
		echo    '<td><a href="../customer/account_info.php?customer_id='.$data['id'].'">詳細</a></td>';
		echo    '<td style="width:80px;" title="契約リンク">';
		echo    '<a href="javascript:void(0);" onclick="window.open(\'/admin/pdf/pdf_mypass.php?customer_id='. $data['id'] .'\', \'_blank\', \'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes\');" >情報出力</a> /<br>';
        echo    '<a href="../service/id_pass_issued.php?customer_id='.$data['id'].'">アイパス発行済処理</a>';
        if($data['pw_sent_flg']) echo "(済)</td>";
		echo 	'<td style="width:40px;"><select name="rejected_flg" class="styledselect_form_5" id="rejected_flg_'.$data['id'].'" onchange=change_rejected_flg('.$data['id'].')>';
		Reset_Select_Key( $gRejectNotification , $data['rejected_flg']);
		echo    '</select></td>';
		echo 	'<td style="width:40px;"><select name="agree_status" class="styledselect_form_5" id="agree_status_'.$data['id'].'" onchange=change_agree_status('.$data['id'].')>';
		Reset_Select_Key( $gAgreeStatus , $data['agree_status']);
		echo    '</select></td>';
		echo 	'<td style="width:40px;"><select name="mail_status" class="styledselect_form_7" id="mail_status_'.$data['id'].'" onchange=change_mail_status('.$data['id'].')>';
		Reset_Select_Key( $mail_status , $data['mail_status']);
		echo    '</select></td>';
		echo    '<td><a href="../account/?customer_id='.$data['id'].'">詳細</a></td>';
		echo    '<td><a href="../account/remain.php?customer_id='.$data['id'].'">詳細</a></td>';
		echo    '<td>' . $data['reg_date'] . '</td>';
		// 契約に紐付かない新規予約
		echo '<td style="width:40px;">';
			echo '<select class="styledselect_form_3">';
				echo '<option value=""> 予約タイプを選択 </option>';
				Print_Select_List_New_Reserve($data);
			echo '</select>';
		echo '</td>';
		echo 	'<td style="width:65px;">';
		echo 	'<a rel="facebox" href="/admin/reservation/mini.php?customer_id='.$data['id'].'" title="予約履歴" class="icon-history info-tooltip"></a>';
	if($authority_level<=1)	echo 		'<a href="index.php?action=delete&id='.$data['id'].'&shop_id='.$_POST['shop_id'].'&keyword='.$_POST['keyword'].'" onclick="return confirm(\'仮削除しますか？\')" title="仮削除" class="icon-delete info-tooltip"></a>';
		// echo 		'<a href="index.php?action=send&id='.$data['id'].'" onclick="return confirm(\'マイページ情報を送信しますか？\')" title="マイページ情報送信" class="icon-3 info-tooltip"></a>';
		echo 	'</td>';
		echo '</tr>';
		$i++;
	}
}
?>
</tbody>
</table></div>
				<!--  end product-table................................... -->
				</form>
			</div>
			<!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      	<tr>
				<td style="background-color:green">予約時telOK</td><td>&nbsp;</td>
				<td style="background-color:purple">予約時telﾙｽ</td><td>&nbsp;</td>
				<td style="background-color:red">予約時telNG</td><td>&nbsp;</td>
				<td style="background-color:orange">お客様切電</td><td>&nbsp;</td>
				<td><?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?></td>
			</tr>
      </table>
      <!--  end paging................ -->
      <div class="clear"></div>
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
<?php include_once("../include/footer.html");?>
