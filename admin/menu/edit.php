<?php include_once('../library/menu/edit.php');?>
<?php include_once('../include/header_menu.html');?>

			<div id="main_list">
    		  <table width="1000" cellpadding="0" cellspacing="0">
              <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="" enctype="multipart/form-data" onSubmit="return conf1('<?php echo $data['name']?>');">
    		    <tr id="title_bar">
    		      <td colspan="2">メニュー情報　編集</td>
  		        </tr>

<?php if ( $gMsg ) { ?>
				<tr>
					<td width="30">&nbsp;</td>
					<td width="1000"><?php echo($gMsg); ?></td>
				</tr>
<?php }else{?>


	<tr class="bgb t-left" id="aaa-3">
		<td class="mbl10 m-font" colspan="2" width="10%"> 　▼メニュー情報　詳細</td>
	</tr>
	<tr class="bgb t-left" id="aaa-3">
		<td class="mbl10 m-font" width="20%">メニュー名</td>
		<td width="80%"><input name="name" class="imeon"  value="<?php echo $data['name'] ?>" size="50" maxlength="100"></td>
	</tr>
	<tr class="bgb t-left" id="aaa-3">
		<td class="mbl10 m-font" width="20%">ファイル名</td>
		<td><input name="onclick" class="imeoff"  value="<?php echo $data['onclick'] ?>" size="50" maxlength="100"></td>
	</tr>　
	<tr class="bgb t-left" id="aaa-3">
		<td class="mbl10 m-font" width="20%">表示場所</td>
		<td>
			<?php echo InputRadioTag("page",$gMenuPage,$data['page'],"")?>
		</td>
	</tr>
	<tr class="bgb t-left" id="aaa-3">
		<td class="mbl10 m-font" width="20%">利用者</td>
		<td class="td_val"><select name="authority"><?php Reset_Select_Key( $gAuthority ,$data['authority'] ) ?></select></td>
	</tr>
	<tr class="bgb t-left" id="aaa-3">
		<td class="mbl10 m-font" width="20%">表示状態</td>
		<td>
			<input name="status" type="radio" value="1" <?php Set_Radio_Def(1,$data['status']); ?>>表示　　
			<input name="status" type="radio" value="0" <?php Set_Radio_Def(0,$data['status']); ?>>非表示
		</td>
	</tr>
	<tr class="bgb t-left" id="aaa-3">
		<td class="mbl10 m-font" width="20%">表示順</td>
		<td><input name="rank" class="imeoff"  value="<?php echo $data['rank'] ?>" size="50" maxlength="100"></td>
	</tr>　
	<tr class="bgb t-left" id="aaa-3">
		<td colspan="2"  width="10%" align="center">
			<input type="submit" value="　入力の確認　">　　
			<input type="button" value="　　戻　る　　" onClick="history.back();">
			<input name="action" type="hidden" id="action" value="input">
			<input name="id" type="hidden" id="id" value="<?php echo($data['id']); ?>"><br>
		</td>
	</tr>

</form>

<?php }?>
</table>
</div><!-- end main_list -->

<?php include_once('../include/footer.html') ?>
