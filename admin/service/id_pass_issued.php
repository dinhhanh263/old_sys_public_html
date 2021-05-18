<?php include_once("../library/service/id_pass_issued.php");?>
<?php include_once("../include/header_menu.html");?>

</form>
<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
        <div id="page-heading"><h1>アイパス発行済処理<?php echo $data['pw_sent_flg'] ? '（済）': "";?></h1></div>
        <?php echo $gMsg ?>
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
                                    <form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return  conf1('');">
                                        <input type="hidden" name="action" value="edit" />
                                        <input type="hidden" name="id" value="<?php echo $data["id"];?>" />

                                        <input type="hidden" name="mode" value="<?php echo $_POST["mode"];?>" />
                                        <input type="hidden" name="start" value="<?php echo $_POST["start"];?>" />
                                        <input type="hidden" name="contract_date" value="<?php echo $_POST["contract_date"];?>" />
                                        <input type="hidden" name="contract_date2" value="<?php echo $_POST["contract_date2"];?>" />
                                        <input type="hidden" name="status" value="<?php echo $_POST["status"];?>" />
                                        <input type="hidden" name="line_max" value="<?php echo $_POST["line_max"];?>" />
                                        <input type="hidden" name="shop_id" value="<?php echo $_POST["shop_id"];?>" />
                                        <input type="hidden" name="hope_date" value="<?php echo $_POST["hope_date"];?>" />
                                        <input type="hidden" name="reservation_id" value="<?php echo $_POST["reservation_id"];?>" />

                                        <table border="0" cellpadding="0" cellspacing="0"  id="id-form">

                                            <tr>
                                                <th valign="top">会員番号:</th>
                                                <td><?php echo $data['no'];?></td>

                                            </tr>
                                            <tr>
                                                <th valign="top">名前:</th>
                                                <td><?php echo $data['name'];?></td>
                                            </tr>

                                            <tr>
                                                <th valign="top">マイパス発行済:</th>
                                                <td><input type="checkbox" name="pw_sent_flg" value="1" <?php echo ($data['pw_sent_flg']) ? "checked" : "" ;?> class="form-checkbox" /></td>
                                            </tr>
                                            <tr>
                                                <th valign="top">発行日:</th>
<!--                                                --><?php //if ($data['pw_sent_date']<>"0000-00-00"): ?>
<!--                                                    <td><input type="input" name="pw_sent_date" value="--><?php //echo $data['pw_sent_date'] ;?><!--" placeholder="--><?php //echo date("Y-m-d");?><!--" /></td>-->
<!--                                                --><?php //else: ?>
<!--                                                    <td><input type="input" name="pw_sent_date" value="" placeholder="--><?php //echo date("Y-m-d");?><!--" /></td>-->
<!--                                                --><?php //endif; ?>
                                                <td><input type="input" name="pw_sent_date" value="<?php echo ($data['pw_sent_date']<>"0000-00-00" ? $data['pw_sent_date'] : "");?>" placeholder="<?php echo date("Y-m-d");?>" /></td>
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