
<?php include_once("../library/allowance/staff_info.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SYSTEM</title>
<!--必須項目チェック-->
<script type='text/javascript' src='../js/jquery.js'></script>
<script language="javascript" type="text/javascript" src="../js/jquery.validate.js"></script>
<script type="text/javascript"> 
$(document).ready(function(){
    $("#form1").validate({
      rules : {
          loan_date: {
            required: true,
          },

        },

       messages: {
         loan_date: {
          required: "処理日を入力してください。"
         },

       },

        errorPlacement: function(label, element) {
          label.insertAfter(element);
        },
    });
});
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
					<div id="content-table-inner" style="padding:0;">
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr valign="top">
								<td>
									<form action="staff_info.php" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
										<input type="hidden" name="action" value="edit" />
										<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
										<input type="hidden" name="ym" value="<?php echo $_POST["ym"];?>" />

										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">

											<tr>
												<th valign="top">年月:</th>
												<td><?php echo $_POST['ym'];?></td>
											</tr>
											<tr>
												<th valign="top">名前:</th>
												<td><?php echo $staff['name'];?></td>
											</tr>
											<tr>
												<th valign="top">出勤形態：</th>
												<td><?php echo $shop_id ==1001 ? "本社(土日祝休)"  : "店舗";?></td>
											</tr>
											<tr>
												<th valign="top">基本給:</th>
												<td><input type="text" name="base_salary" value="<?php echo ($data['base_salary'] ? $data['base_salary'] : $posi_salary['base_salary']);?>" /></td>
											</tr>
											<tr>
												<th valign="top">役職名:</th>
												<td><select name="type" style="height:25px;" ><?php Reset_Select_Key( $gStaffType , $data['type'] ? $data['type'] : $staff['type']);?></select></td>
											</tr>
										
											<tr>
												<th valign="top">役職手当:</th>
												<td><input type="text" name="allowance" value="<?php echo $data['allowance'] ? $data['allowance'] : $posi_salary['allowance'];?>" /></td>
											</tr>
											<tr>
												<th valign="top">所属:</th>
												<td><select name="shop_id" style="height:25px;" ><?php Reset_Select_Key( $shop_list , $data['shop_id'] ? $data['shop_id'] : $staff['shop_id']);?></select></td>
											</tr>
											<tr>
												<th>&nbsp;</th>
												<td valign="top">
													<input type="submit" value="" class="form-submit" />
													<input type="reset" value="" class="form-reset" />
												</td>
				
											</tr>

											
										</table>
									</form>
								</td>
							</tr>
							<tr><td><img src="../images/shared/blank.gif" width="695" height="1" alt="blank" /></td></tr>
						</table>
 						<div class="clear"></div>
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
		<div class="clear">&nbsp;</div>
	</div>
	<!--  end content -->
	<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer -->

 
</body>
</html>