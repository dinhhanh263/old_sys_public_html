<?php include_once('../library/partner/index.php');?>
<?php include_once('../include/header_menu.html');?>
<script type="text/javascript">
function deleteChk () {
    var name=prompt("削除パスワードを入力して下さい。", "");
    var password="password";
    if (name==password) { 
      return true;
  }else{
    alert("パスワードが正しくありませんでした。");
    return false;
  }
}
</script>
 </form>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">

  <!--  start page-heading -->
  <div id="page-heading">
    <h1>紹介企業設定</h1>
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
                <th class="table-header-repeat"><a href="">ID</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">企業コード</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">企業名</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">表示状態</a></th>
                <th class="table-header-repeat line-left minwidth-1" colspan="2"><a href="">オプション</a></th>
              </tr>
              <tr>
                  <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="frm">
                  <input name="action" type="hidden" value="new">
                   <td></td>
                   <td><input  class="inp-form"  type="text" name="code" /></td>
                   <td><input  class="inp-form"  type="text" name="name" /></td>
                   <td><select class="styledselect_form_1" name="status"><?php Reset_Select_Key( $gDisplay ,"" ) ?></select></td>
                   <td colspan="2"><a href="javascript:document.forms['frm'].submit();" onclick="return confirm('新規登録しますか？')" title="New" class="icon-1 info-tooltip"></a></td>
                  </form>
              </tr>
              <tr><td colspan="7"></td></tr>

<?php
if ( $dRtn3->num_rows >= 1 ) {
  $i = 1 ;
  while ( $data = $dRtn3->fetch_assoc() ) {
  $i++;
?>
            <tr <?php echo($i%2==0 ? ' class="alternate-row"' : '')?> >
              <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="frm<?php echo $i;?>">
                <input type="hidden" name="id" value="<?php echo($data['id']); ?>">
                <input name="action" type="hidden" value="update">
                <td><?php echo($data['id']); ?></td>
                <td><input type="text" name="code" value="<?php echo( View_Cook( $data['code'] ) ); ?>"  class="inp-form" /></td>
                <td><input type="text" name="name" value="<?php echo( View_Cook( $data['name'] ) ); ?>"  class="inp-form" /></td>
                <td><select class="styledselect_form_1" name="status" ><?php Reset_Select_Key( $gDisplay , View_Cook( $data['status'] )) ?></select></td>
                <td><a href="javascript:document.forms['frm<?php echo $i;?>'].submit();" onclick="return confirm('変更しますか？')" title="変更" class="icon-1 info-tooltip"></a></td>
              </form>
              <!--削除--> 
              <td><a href="index.php?action=delete&id=<?php echo $data['id'];?>" onclick="return confirm('削除しますか？')" title="削除" class="icon-2 info-tooltip"></a></td>
            </tr>
<?php }}?>
        
      </table>
    <!--  end product-table................................... --> 
      </div>
      <!--  end content-table  -->
      <!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      <tr>

      <td>
      <?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
      </td>
      </tr>
      </table>
      <!--  end paging................ -->
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