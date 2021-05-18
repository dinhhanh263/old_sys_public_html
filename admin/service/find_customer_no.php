<?php include_once('../library/service/find_customer_no.php');?>
<?php include_once('../include/header_menu.html');?>
</form>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
    <!--  start page-heading -->
    <div id="page-heading">
        <h1>PP自動カード課金登録者の会員番号取得</h1>
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
                <tr>
                  <td>※CSVファイル</td>
                  <td width="70%" style="height:29px;"><input type="file" name="import_file" /></td>
                </tr>
                <tr>
                  <td colspan="2" width="30%" style="height:29px;text-align:center;"><input type="submit" value=" 一括取得 " />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" 戻る " onClick="history.back();" /></td>
                </tr>
                </form>
            </table>
            <p><font color="red" size="-1">*　CSV形式： 自動課金番号,カード名義, 名前（全角カナ）,下４桁</font>
                &nbsp;(ダウンロード：<a href="./find_customer_no_format.xlsm">自動カード課金登録者に会員番号取得フォーマット</a>)</p>
            <br>
            <table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
    
                <?php if ( $data )  echo $data;?>
                
            </table>
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