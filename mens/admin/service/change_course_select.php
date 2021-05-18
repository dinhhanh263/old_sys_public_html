
<?php include_once("../library/service/change_course_select.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SYSTEM</title>
<!--必須項目チェック-->
<script type='text/javascript' src='../js/jquery.js'></script>
<script language="javascript" type="text/javascript" src="../js/jquery.validate.js"></script>
<script type="text/javascript">
// $(document).ready(function(){
//     $("#form1").validate({
//       rules : {
//           loan_date: {
//             required: true,
//           },

//         },

//        messages: {
//          loan_date: {
//           required: "処理日を入力してください。"
//          },

//        },

//         errorPlacement: function(label, element) {
//           label.insertAfter(element);
//         },
//     });
// });
</script>
<style type="text/css">
label.error {
    color: red;
    margin-left: 5px;
    font-size: 12px;
}
</style>

<!--全角英数字、ハイフン->半角-->
<script type="text/javascript">
$(function() {
  $('#fm,#fm2,#fm3,#fm4,#fm5,#fm6,#fm7,#fm8').change(function(){
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

</head>
<body>

  <div class="clear"></div>
<!-- start content-outer -->
<div >
	<!-- start content -->
	<div id="content">

		<div id="content-table">
			<!--  start content-table-inner -->
			<div id="loan_mini">
				<form action="../service/confirm_loan.php" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
					<input type="hidden" name="action" value="edit" />
					<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
					<input type="hidden" name="reservation_id" value="<?php echo $_POST["reservation_id"];?>" />

					<input type="hidden" name="mode" value="<?php echo $_POST["mode"];?>" />
					<input type="hidden" name="start" value="<?php echo $_POST["start"];?>" />
					<input type="hidden" name="contract_date" value="<?php echo $_POST["contract_date"];?>" />
					<input type="hidden" name="contract_date2" value="<?php echo $_POST["contract_date2"];?>" />
					<input type="hidden" name="status" value="<?php echo $_POST["status"];?>" />
					<input type="hidden" name="line_max" value="<?php echo $_POST["line_max"];?>" />

					<input type="hidden" name="shop_id" value="<?php echo $_POST["shop_id"];?>" />
					<input type="hidden" name="hope_date" value="<?php echo $_POST["hope_date"];?>" />
					<input type="hidden" name="pid" value="<?php echo $_POST["pid"];?>" />

					<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
						<tr>
							<th valign="top">会員番号:</th>
							<td><?php echo $customer['no'];?></td>
						</tr>
						<tr>
							<th valign="top">名前:</th>
							<td><?php echo $customer['name'];?></td>
						</tr>

						<!-- <tr>
							<th valign="top">契約状況:</th>
							<td><?php echo $gContractStatus[$contract_p['status']];?></td>
						</tr> -->

						<tr>
							<th valign="top">契約コース:</th>
				            <td>
				               <ul class="t_contract">
									<?php foreach($all_contract as $key => $value): 
										$course = "";
										$course = Get_Table_Row("course"," WHERE del_flg=0 and id = '".addslashes($value['course_id'])."'");
									?>
				                    <li>
				                    <!-- 翌日適応の場合は表示しない -->
				                    <?php $today_flg="";
				                          if($value['if_cancel_date']==1){
				                          	$today_flg=true;
				                          }elseif($value['if_cancel_date']==0){
				                          	$today_flg=false; // 当日適応フラグ
				                          }
				                    ?>
				                    	契約番号:<?php echo $value['pid'];?>&nbsp;
				                    	<!-- 0.契約中、4.プラン変更、7.契約待ちのみリンク表示 -->
				                    	<?php if( ($value['status'] == 0 || $value['status'] == 4 || $value['status'] == 7) && $course['type']==0){ ?>

				                    		<!-- 返金保証回数終了後のコース -->
				                    		<?php if(1000 < $course['id'] && $course['zero_flg']==0){ ?>
				                    			<?php echo $course_list[$value['course_id']];?>
				                    			<?php echo "<font color='red'>※保証期間終了後のコースはプラン変更できません</font>";?>
				                    		<?php } else { ?>
				                    		<!-- 返金保証回数終了していないコース -->
				                    	<a href="../service/change.php?id=<?php echo $value['id'];?>&customer_id=<?php echo $customer['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>"　onclick="return confirm('<?php echo $course_list[$value['course_id']];?>処理をしますか？')" class="side"　><?php echo $course_list[$value['course_id']];?></a><?php if($sales[$value['id']])echo "(済)"?></a>
				                    		(<?php echo $gContractStatus[$value['status']];?>)
				                    		<!-- プラン変更時、メンズでは適応日を指定できないため、下記コメントアウトさせていただきます。2017/05/23 add by shimada -->
				                    		<!-- <?php if($value['status'] == 4)echo '&nbsp※プラン変更適応日：'.$value['cancel_date'];?> -->
					                    	<!-- <?php if($today_flg==true && $value['status'] == 4){echo '&nbsp※プラン変更適応日：'.$value['contract_date'];?>
					                    	<?php }elseif($today_flg==false && $value['status'] == 4){echo '&nbsp※プラン変更適応日：'.date('Y-m-d', strtotime($value['contract_date']." +1 day"));?>
					                    	<?php }?>-->
				                    		<?php } ?>

				                    	<!-- 契約中かプラン変更以外のコース(リンク表示しない) -->
				                    	<?php } else {
				                    		echo $course_list[$value['course_id']];?>
				                    		(<?php echo $gContractStatus[$value['status']];?>)
				                    	<?php } ?>                    	
				                    </li>
				                  	<?php endforeach; ?>
				               </ul>
				            </td>
						</tr>
						<!-- <tr>
							<th>&nbsp;</th>
							<td valign="top">
								<input type="submit" value="" class="form-submit" />
								<input type="reset" value="" class="form-reset" />
							</td>
						</tr> -->

					</table>
				</form>
			</div>
			<!--  end content-table-inner  -->
		</div>
	</div>
	<!--  end content -->
</div>
<!--  end content-outer -->


</body>
</html>