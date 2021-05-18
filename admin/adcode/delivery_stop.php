<?php include_once('../library/adcode/delivery_stop.php')?>
<?php include_once('../include/header_menu.html')?>

			<div id="main_list">
    		  <table width="1000" cellpadding="0" cellspacing="0">
              <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="" onSubmit="return conf('<?php echo $agent['name']?>');">
                <input type="hidden" name="mode" value="" />
    		    <tr id="title_bar">
    		      <td colspan="2">配信停止依頼　詳細</td>
  		        </tr>
				<!--メニュー start-->
				<tr class="bgb t-left" id="aaa-3">
					<td class="mbl10 m-font" colspan="2" width="10%">　　▼代理店名：　<?php echo $agent['name']; ?></td>
				</tr>
<?php if( $_POST['mode'] == "stop" && $_POST[id]) {
		
		print '<tr class="bgb t-left" id="aaa-3">';
		print '<td class="mbl10 m-font" width="20%">送信者</td>';
		print '<td width="80%"><input name="from_mail" type="text" value="' . $sender . '" size="140"></td>';
		print '</tr>';
		print '<tr class="bgb t-left" id="aaa-3">';
		print '<td class="mbl10 m-font" width="20%">宛先</td>';
		print '<td><input name="to_mail" type="text" value="' . $agent['mail'] . '" size="140"></td>';
		print '</tr>';
		print '<tr class="bgb t-left" id="aaa-3">';
		print '<td class="mbl10 m-font" width="20%">CC</td>';
		print '<td><input name="cc" type="text" value="" size="140"></td>';
		print '</tr>';
		print '<tr class="bgb t-left" id="aaa-3">';
		print '<td class="mbl10 m-font" width="20%">BCC</td>';
		print '<td><input name="bcc" type="text" value="' . $sender . '" size="140"></td>';
		print '</tr>';
		print '<tr class="bgb t-left" id="aaa-3">';
		print '<td class="mbl10 m-font" width="20%">件名</td>';
		print '<td><input name="subject" type="text" value="【配信停止依頼】" size="140"></td>';
		print '</tr>';
		print '<tr class="bgb t-left" id="aaa-3">';
		print '<td colspan="2"><textarea name="body" rows="15" cols="107">'.$body.'</textarea></td>';
		print '</tr>';

?>	
	<tr class="bgb t-left" id="aaa-3">
		<td colspan="2"  width="10%">
		<input type="submit" value="　送信　">　　
		<input type="button" value="　戻る　" onClick="history.back();">
		<input name="action" type="hidden" id="action"  value="send">
		<input name="id" type="hidden" id="id" value="<?php echo $_POST['id'] ?>">
		</td>
	</tr>
<?php }else{
	$msg = $send_flg ? "送信が完了しました。" : "コードが選択されませんでした。";
	$onclick = $send_flg ? "top(this.form);" : "history.back();";
	print '<tr align="center"><td colspan="2"><br><font color="red">'.$msg.'</font><br>';
	print '<br><input type="button" value="　　戻　る　　" onClick="' . $onclick . '"></td></tr>';
}?>

</form>
</table>
		</div><!-- end main_list -->

<?php include_once('../include/footer.html') ?>
