<?php include_once('../library/service/pay_monthly.php');?>
<?php include_once('../include/header_menu.html');?>
</form>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
    <!--  start page-heading -->
    <div id="page-heading">
        <h1>月額銀行引落一括処理</h1>
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
                <input name="pay_type" type="hidden" value="3">
				<?php echo($gMsg); ?>
				<tr>
                  <td>※タイトル:</td>
                  <td><input  class="inp-form" name="title" type="text" value="<?php echo $_POST['title'];?>" /></td>
                </tr>
    		    <tr>
                  <td>※振替日:</td>
    		      <td><input class="inp-form" name="option_date" type="text" id="day" value="<?php echo $_POST['option_date'] ? $_POST['option_date'] : date("Y-m-d");?>" placeholder="<?php echo date('Y-m-d');?>" readonly /></td>
				</tr>
    		    <tr>
    		      <td>※代行会社</td>
    		      <td style="height:29px;">
                    <?php echo InputRadioTag("bank_flg",$gBankNG ,$_POST['bank_flg'] );?>

                  </td>
                    
				</tr>
                <tr>
                  <td>※何年支払代金:</td>
                  <!--  年が選ばれていない場合は今年の年のプルダウンをセットする -->
                  <?php $option_year = ($_POST['option_year'] == "") ? date("Y") : $_POST['option_year'];?>
                  <td><select name="option_year" style="text-align:right; height:25px;" class="inp-form" ><?php Reset_Select_Val( $gOptionYear , $option_year);?></select>年</td>
                </tr>
                <tr>
                  <td>※何月分支払代金:</td>
                  <td><input  style="text-align:right;" class="inp-form" name="option_month" type="text" value="<?php echo $_POST['option_month'] ? $_POST['option_month'] : $_POST['option_month'] ;?>" />月分</td>
                </tr>
    		    <tr>
    		      <td>※CSVファイル</td>
    		      <td width="70%" style="height:29px;"><input type="file" name="import_file" /></td>
				</tr>
    		    <tr>
    		      <td colspan="2" width="30%" style="height:29px;text-align:center;"><input type="submit" value=" 月額処理 " />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" 戻る " onClick="history.back();" /></td>
				</tr>
            	</form>
  		    </table>
            <p><font color="red" size="-1">*　CSV形式： 会員番号, 名前（全角カナ）,金額,振込結果</font>
                &nbsp;(ダウンロード：<a href="./aplus_format.xlsx">IPSフォーマット</a>、<a href="./JCB_format.xlsx">JCBフォーマット</a>)</p>
            <br><font color="red" size="-1">* 振替成功と振替失敗のデータが同時一括処理可能</font>
            <table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
               
<?php
if ( $dRtn3->num_rows >= 1 ) {
    $i = 1;
    while ( $data = $dRtn3->fetch_assoc() ) {
        echo '<tr'. ( $i%2==0 ? ' class="alternate-row"' : '' ) . ( !$data['success_flg'] ? ' style="color:red"' : '' ).'>';
        echo    '<td>'.$data['no'].'</td>';
        echo    '<td>'.$data['name'].'</td>';
        echo    '<td align="right">'.number_format($data['payment']).'</td>';
        echo    '<td>'.($data['customer_id'] ? "会員あり" : "会員なし").'</td>';
        echo    '<td>'.($data['contract_id'] ? "月額あり" : "月額なし").'</td>';
        echo    '<td>'.($data['existed_flg']==1 ? "処理重複" : ($data['existed_flg']==2 ? "処理可" : "")).'</td>';
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