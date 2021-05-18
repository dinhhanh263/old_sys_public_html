<?php include_once('../library/mail_magazine/index.php');?>
<?php include_once('../include/header_menu.html');?>

<link rel="stylesheet" type="text/css" href="../js/clockpick/clockpick.1.2.5.css">
<script type="text/javascript" src="../js/clockpick/jquery.clockpick.1.2.6.js"></script>
<script type="text/javascript"> 
$(document).ready(function(){
		// 時間ピッカー
	$("input#clock").clockpick({
		"starthour":1,
		"endhour":23,
		"military":true,
		"showminites":true,
		"minutedivisions":12
	});
});
</script>
<style type="text/css"> 
input#clock{
	font-family:"Courier New", Courier, monospace;
	width:60em;
	text-align:center;
}
</style>

<script type="text/javascript">
    function setClass() {
    	document.getElementById('i10-4').className = "ckeditor";
        var editor1 = CKEDITOR.replace( 'header' );
		editor1.setData();
		CKFinder.setupCKEditor( editor1, '../ckfinder/' ) ;

        document.getElementById('i10-5').className = "ckeditor";
        var editor = CKEDITOR.replace( 'body' );
		editor.setData();
		CKFinder.setupCKEditor( editor, '../ckfinder/' ) ;

		document.getElementById('i10-6').className = "ckeditor";
        var editor2 = CKEDITOR.replace( 'footer' );
		editor2.setData();
		CKFinder.setupCKEditor( editor2, '../ckfinder/' ) ;
    }
    function deleteClass() {
        /*document.getElementById('i10-5').className = "";
        CKEDITOR.destroy();
		var editor = CKEDITOR.replace( 'body' );
		editor.setData();*/
		window.location.reload();
    }

</script>
<!--CKeditor-->
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../ckfinder/ckfinder.js"></script>
<script type="text/javascript">
function changRadio(){
	document.getElementById("ontime").checked = true;
}
</script>
</form>
<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">       
        <div id="page-heading"><h1>一斉配信</h1></div>
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
								<td>
              <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="form1" >
                <input type="hidden" name="mode" value="" />
                <table border="0" cellpadding="0" cellspacing="0"  id="id-form">
			<?php if( $_POST['action'] != "send" ) {?>
				<tr><th colspan="2">▼送信条件設定</td></tr>
				<tr>
					<th valign="top">テンプレート名</th>
					<td width="80%">
						<select style="height:25px;" name="tid" size="1" onChange="location.href = this.options[this.selectedIndex].value">
							<option value="./?tid=-">なし</option>
							<?php $tmplist = $GLOBALS['mysqldb']->query( "SELECT * FROM mail_template " );
									while ( $val = $tmplist->fetch_assoc() ) {
										if($val['id'] == $tid)	$ifselected = "selected";
										print "<option value='./?tid={$val['id']}' $ifselected>".$val['name']."</option>\n" ;
										$ifselected = "";
									}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th valign="top">シナリオ名</th>
					<td>
						<select style="height:25px;" name="scenario_id">
							<!--<option value="">本サイトユーザー</option>-->
							<?php $scenario_list = $GLOBALS['mysqldb']->query( "SELECT * FROM mail_scenario_info" );
									while ( $val = $scenario_list->fetch_assoc() ) {
										if($val['id'] == $_REQUEST['scenario_id'])	$ifselected = "selected";
										print"<option value='{$val['id']}' $ifselected>".$val['name']."</option>\n";
										$ifselected = "";
									}
							?>
						</select>
					</td>
				</tr>

				<!--<tr>
					<th valign="top">会員状態</th>
					<td><?php echo InputCheckboxTag2("status",$gStatus,"2")?></td>
				</tr>
				<tr>
					<th valign="top">キャリア選択</th>
					<td><?php echo InputCheckboxTag2("mo_agent",$moStatus,($_REQUEST['mo_agent'] ? $_REQUEST['mo_agent'] : "0123456789" ));?></td>
				</tr>
				<tr>
					<th valign="top">メール形式</th>
					<td><input type="radio" name="format" value="0" onclick="deleteClass()" <?php echo ($template['format']==0 ? "checked" : "");?> id="raj0"/>テキスト形式
						<input type="radio" name="format" value="1" onclick="setClass()" <?php echo ($template['format']==1 ? "checked" : "");?>  id="raj1"/>HTML形式</td>

				</tr>-->
				<tr>
					<th valign="top">送信者</th>
					<!--<td><input style="width:170px;height:25px;" type="text" name="sender" value="<?php echo MAIL_SENDER_EMAIL ?>" /></td>-->
					<td><input style="width:170px;height:25px;" type="text" name="sender" value="info@vielis.co.jp" /></td>
				</tr>
				<tr>
					<th valign="top">送信予定日</th>
					<td>
						<input type="radio" name="send_now" value="1" checked />即時送信<br />
						<input type="radio" name="send_now" value="0" id="ontime"/>
						送信予定日付 ： <input style="width:70px;;height:25px;" type="text" name="plan_date" id="day" value="<?php echo date('Y-m-d')?>" />
						時刻 ： <input style="width:40px;height:25px;" name="plan_time" type="text" id="clock" value=""  onClick="changRadio()" /> (例:2011-01-01 10:00)
					</td>
				</tr>
				<tr>
					<th valign="top">テスト送信</th>
					<td><input style="width:535px;height:25px;" name="test_mail" type="text"  placeholder="info@kiremo.jp" />&nbsp;<input type="button" style=";height:28px;" class="imeoff" value="テスト送信" onclick="javascript:test_send();" /></td>
				</tr>
				<tr>
					<th valign="top">件名</th>
					<td><input style="width:600px;height:25px;" name="subject" type="text" value="<?php echo $template['title'] ?>" /></td>
				</tr>
				<tr>
					<th valign="top">ヘッダー</th>
					<td>
						<textarea  style="width:600px;" class="<?php echo ($template['format']==1 ? 'ckeditor' : '');?>" id="i10-4" name="header" ><?php echo $template['header'] ? $template['header'] : "%%name%% 様 \r\n\r\n" ?></textarea>
						
					</td>
				</tr>
				<tr>
					<th valign="top">本文</th>
					<td>
						<textarea style="width:600px;height:350px;" class="<?php echo ($template['format']==1 ? 'ckeditor' : '');?>" name="body"><?php echo $template['body'] ?></textarea>
					</td>
				</tr>
				<tr>
					<th valign="top">フッター</th>
					<td>
						<!--<textarea style="width:600px;height:350px;" class="<?php echo ($template['format']==1 ? 'ckeditor' : '');?>" name="footer"><?php echo $template['footer'] ? $template['footer'] : ($_REQUEST['format'] ? str_replace("\n","<br>",MAIL_FOOTER) : MAIL_FOOTER) ; ?></textarea>-->
						<textarea style="width:600px;height:350px;" class="<?php echo ($template['format']==1 ? 'ckeditor' : '');?>" name="footer"><?php echo $template['footer']  ; ?></textarea>
					</td>
				</tr>
				<tr>
	 				 <th valign="top"></th>
	 				 <td valign="top" align="center">
						<br>
						<input type="button" value="　戻る　" onclick="history.back();">
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" value="　送信　" onclick="javascript:send();">　　
						<input name="action" type="hidden"   value="send">
					</td>
				</tr>
				<tr>
					<th valign="top"></th>
					<td><font size=-2><br />＊ 置換文字(%%name%% : 名前）</font></td>
				</tr>

<?php }else{
	$msg = $msg ? $msg : "受信者がありませんでした。";
	$onclick =  "history.back();";
	print '<tr align="center"><td colspan="2"><br><font color="red">'.$msg.'</font><br>';
	print '<br><input type="button" value="　　戻　る　　" onClick="' . $onclick . '"></td></tr>';


}?>
</table>
									</form>
									<!-- end id-form  -->
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

<?php include_once('../include/footer.html') ?>

