<?php include_once('../library/mail_magazine/template_detail.php');?>
<?php include_once('../include/header_menu.html');?>
<script type="text/javascript">
    function setClass() {
        document.getElementById('ckeditor').className = "ckeditor";
        var editor1 = CKEDITOR.replace( 'header');
		editor1.setData();
		CKFinder.setupCKEditor( editor1, '../ckfinder/' ) ;

		var editor2 = CKEDITOR.replace('body' );
		editor2.setData();
		CKFinder.setupCKEditor( editor2, '../ckfinder/' ) ;

		var editor3 = CKEDITOR.replace( 'footer' );
		editor3.setData();
		CKFinder.setupCKEditor( editor3, '../ckfinder/' ) ;

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
</form>
<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>テンプレート作成</h1></div>
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
									<!-- start id-form -->
									<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
										<input type="hidden" name="action" value="edit" />
										<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form">

<?php if ( $gMsg ) { ?>
				<tr>
					<td width="30">&nbsp;</td>
					<td width="1000"><?php echo($gMsg); ?></td>
				</tr>

<?php }elseif($gTemplateList){
	foreach($gTemplateList as $key => $val){
		print '<tr class="bgb t-left" id="aaa-3">';
		print '<td class="mbl10 m-font" width="20%">' . $val['name'] . '</td>';
		print '<td width="80%">';
		switch ( $val['type'] ){
			case 'none':
				print TA_Cook($data[$key]) . '<input name="' . $key . '" type="hidden" value="' . $data[$key] . '">';break;
			case 'text':
				print '<input name="' . $key . '" class="imeon"  value="' . ($data[$key]) . '" size="'.$val['size'].'" >';break;
			case 'select':
				print '<select name="' . $key . '">';
				Reset_Select_Key( $val['param'] ,  $data[$key] );
				print '</select>';
				break;
			case 'radio':
				print '<input type="radio" name="format" value="0" onclick="deleteClass()" '.($data[$key]==0 ? "checked" : "").' id="raj0"/>テキスト形式
						<input type="radio" name="format" value="1" onclick="setClass()"  '.($data[$key]==1 ? "checked" : "").'  id="raj1"/>HTML形式';
				break;
			case 'textarea':
				print '<textarea class="'.($data['format']==1 ? "ckeditor" : "").'" id="ckeditor" name="' . $key . '" rows="' . $val['rows'] . '" cols="' . $val['cols'] . '">' .  $data[$key]  . '</textarea>';break;
		}	
		print '</td>';
		print '</tr>';
	}
	
?>
	<tr>
												<th>&nbsp;</th>
												<td valign="top">
													<input type="submit" value="" class="form-submit" />
													<input type="reset" value="" class="form-reset" />
												</td>
											</tr>
<?php } ?>
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

<?php include_once("../include/footer.html");?>