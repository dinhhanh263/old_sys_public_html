<?php include_once('../library/adcode/ldp.php');?>
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
    <h1>ランディングページ管理</h1>
  </div>
  <!-- end page-heading -->
  <div id="content-table">
    <!--  start content-table-inner ...................................................................... START -->
    <div id="content-table-inner">

      <!--  start table-content  -->
      <div id="table-content">
            <table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
              <tr>
                <th class="table-header-repeat"><a href="">ID</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">ページID</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">サイト名</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">LP名</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">リダイレクト</a></th>
                <th class="table-header-repeat line-left minwidth-1" colspan="2"><a href="">オプション</a></th>
              </tr>
              <tr>
                  <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="frm">
                   <input name="action" type="hidden" value="new">
                   <td>新規</td>
                   <td><input class="inp-form" type="text" name="name"/></td>
                   <td><select class="styledselect_form_1" name="site"><?php Reset_Select_Key( $gSites ,"" ) ?></select></td>
                   <td><input class="inp-form" type="text" name="ldp_name" /></td>
                   <td><select class="styledselect_form_1" name="reurl"><?php Reset_Select_Key( $gReurlStatus ,"" ) ?></select></td>
                   <td colspan="2"><a href="javascript:document.forms['frm'].submit();" onclick="return confirm('新規登録しますか？')" title="新規" class="icon-1 info-tooltip"></a></td>
                  </form>
              </tr>
<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 1 ;
	while ( $data = $dRtn3->fetch_assoc() ) {
	$i++;
?>
    		    <tr id="aaa-2" <?php echo($i%2==0 ? ' class="alternate-row"' : '')?> >
              <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="efrm<?php echo $i;?>">
    		      	<input type="hidden" name="id" value="<?php echo($data['id']); ?>">
    		      	<input name="action" type="hidden" value="update">
    		      	<td><?php echo($data['id']); ?></td>
    		      	<td><input class="inp-form" type="text" name="name" value="<?php echo( View_Cook( $data['name'] ) ); ?>" /></td>
                <td><select class="styledselect_form_1" name="site" ><?php Reset_Select_Key( $gSites , View_Cook( $data['site'] )) ?></select></td>
    		      	<td><input class="inp-form" type="text" name="ldp_name" value="<?php echo(  $data['ldp_name']  ); ?>" /></td>
                <td><input class="inp-form" type="text" name="reurl" value="<?php echo(  $gReurlStatus[$data['reurl']]  ); ?>" /></td>
    		      	<td><a href="javascript:document.forms['efrm<?php echo $i;?>'].submit();" onclick="return confirm('変更しますか？')" title="変更" class="icon-1 info-tooltip"></a></td>
    		      </form>
    		      <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="frm<?php echo $i;?>" >
    		      	<input type="hidden" name="action" value="delete">
					      <input type="hidden" name="id" value="<?php echo($data['id']); ?>">
    		      	<td><a href="javascript:document.forms['frm<?php echo $i;?>'].submit();" onclick="return deleteChk('削除しますか？')" title="削除" class="icon-2 info-tooltip"></a></td>
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
      <!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      <tr>
      <!--<td>
        <a href="" class="page-far-left"></a>
        <a href="" class="page-left"></a>
        <div id="page-info">Page <strong>1</strong> / 15</div>
        <a href="" class="page-right"></a>
        <a href="" class="page-far-right"></a>
      </td>-->
      <td>
        <?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
      <!--<select  class="styledselect_pages">
        <option value="">Number of rows</option>
        <option value="">1</option>
        <option value="">2</option>
        <option value="">3</option>
      </select>-->
      </td>
      </tr>
      </table>
      <!--  end paging................ -->
    </div>
    <!--  end content-table-inner ............................................END  -->
  </div>
</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>