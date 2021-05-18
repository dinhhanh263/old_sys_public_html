<?php include_once('../library/mail_magazine/scenario_import.php');?>
<?php include_once('../include/header_menu.html');?>
</form>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
    <!--  start page-heading -->
    <div id="page-heading">
        <h1>シナリオデータ導入</h1>
    </div>
    <!-- end page-heading -->
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
                <!--  start product-table ..................................................................................... -->
                <table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
              <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" name="form1" onSubmit="return check_form()">
				<input name="mode" type="hidden" value="csv_import">
				<?php echo($gMsg); ?>
				<tr id="title_bar">
    		      <td colspan="2">シナリオデータ導入</td>
  		      	</tr>
				<tr class="bgb t-left m-font mbl10" id="aaa-3">
    		      <td colspan="2" width="10%">▼データをインポートします。</td>
				</tr>
    		    <tr class="bgb2 t-left" id="aaa-3">
    		      <td class="m-font" width="30%">※データ名</td>
    		      <td width="70%"><input id="i13-0" class="mt5 bgb2" type="text" name="data_name" /></td>
				</tr>
    		    <tr class="bgb t-left" id="aaa-3">
    		      <td class="m-font" width="30%">タイプ</td>
    		      <td width="70%"><select id="i13-1" class="bgb" name="type"><?php Reset_Select_Key( $gType ,$_POST['type'] ) ?></select></td>
				</tr>
    		    <tr class="bgb2 t-left" id="aaa-3">
    		      <td class="m-font" width="30%">ジャンル</td>
    		      <td width="70%"><select id="i13-2" class="bgb" name="genre"><?php Reset_Select_Key( $gGenre ,$_POST['genre'] ) ?></select></td>
				</tr>
    		    <tr class="bgb t-left" id="aaa-3">
    		      <td class="m-font" width="30%">※CSVファイル</td>
    		      <td width="70%"><input id="i13-3" class="mt5 bgb" type="file" name="import_file" /></td>
				</tr>
    		    <tr class="bgb t-center" id="aaa-3">
    		      <td colspan="2" width="30%" align="center"><input type="submit" value="確認" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="戻る" onClick="history.back();" /></td>
				</tr>
            	</form>
  		    </table>
			<br />
			<!--<font color="red" size="-1">*　CSV形式： メール, 名前, 配信状態, 端末キャリア</font>-->
            <font color="red" size="-1">*　CSV形式： メール, 名前</font>
		</table>
                <!--  end product-table................................... --> 
                </form>
            </div>
            <!--  end content-table  -->

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