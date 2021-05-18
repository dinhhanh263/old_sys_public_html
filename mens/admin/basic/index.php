<?php include_once('../library/basic/index.php');?>
<?php include_once('../include/header_menu.html');?>
</form>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">

  <!--  start page-heading -->
  <div id="page-heading">
    <h1>基本情報管理</h1>
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
            <table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
              <tr>
                <th class="table-header-repeat"><a href="">項目名</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">値</a></th>
                <th class="table-header-repeat line-left minwidth-1" colspan="2"><a href="">オプション</a></th>
              </tr>
<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 0;
	while ( $data = $dRtn3->fetch_assoc() ) {
	$i++;
?>
			<tr <?php echo($i%2==0 ? 'class="alternate-row"' : '')?> >
			  <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="frm<?php echo $i;?>">
				<!--<td><input style="height:23px;" type="text" name="name" value="<?php echo( View_Cook( $data['name'] ) ); ?>" /></td>	-->
        <td><?php echo( View_Cook( $data['name'] ) ); ?></td>
				<td><input  class="inp-form" type="text" name="value" value="<?php echo( View_Cook( $data['value'] ) ); ?>" /></td>
			    <td><a href="javascript:document.forms['frm<?php echo $i;?>'].submit();" onclick="return confirm('変更しますか？')" title="変更" class="icon-1 info-tooltip"></a></td>
				<input name="action" type="hidden" value="edit">
				<input name="id" type="hidden" value="<?php echo($data['id']); ?>">
			  </form>
			</tr>
<?php
	} //while
}
?>
			</table>
		<!--  end product-table................................... --> 
      </div>
      <!--  end content-table  -->

      <div class="clear"></div>
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