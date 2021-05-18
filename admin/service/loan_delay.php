<?php include_once('../library/service/loan_delay.php');?>
<?php include_once('../include/header_menu.html');?>
</form>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
    <!--  start page-heading -->
    <div id="page-heading">
        <h1>ローン延滞者一括処理</h1>
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
                  <td>※タイトル:</td>
                  <td><input  class="inp-form" name="title" type="text" value="<?php echo $_POST['title'];?>" /></td>
                </tr>
    		    <tr>
                  <td>ローン会社:</td>
                  <td><select name="loan_delay_flg" class="styledselect_form_1"><?php Reset_Select_Key( $gLoanNG , $_POST['loan_delay_flg']);?></select></td>
                </tr>
    		    <tr>
    		      <td>※CSVファイル</td>
    		      <td width="70%" style="height:29px;"><input type="file" name="import_file" /></td>
				</tr>
    		    <tr>
    		      <td colspan="2" width="30%" style="height:29px;text-align:center;"><input type="submit" value=" 一括処理 " />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" 戻る " onClick="history.back();" /></td>
				</tr>
            	</form>
  		    </table>
            <p><font color="red" size="-1">*　CSV形式： 会員番号,顧客名（漢字）</font></p>
            <table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
               
<?php
if ( $dRtn3->num_rows >= 1 ) {
    $i = 1;
    while ( $data = $dRtn3->fetch_assoc() ) {
        echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) . ( !$data['success_flg'] ? ' style="color:red"' : '' ).'>';
        echo    '<td>'.$data['customer_id'].'</td>';
        echo    '<td>'.$data['name'].'</td>';
        echo    '<td>'.$data['pay_date'].'</td>';
        echo    '<td align="right">'.number_format($data['payment']).'</td>';
        echo    '<td>'.($data['customer_id'] ? "会員あり" : "会員なし").'</td>';
        echo    '<td>'.($data['contract_id'] ? "ローンあり" : "ローンなし").'</td>';
        echo    '<td>'.($data['success_flg'] ? "処理成功" : "処理失敗").'</td>';
        echo '</tr>';
        $i++;
    }
}
?>
                
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