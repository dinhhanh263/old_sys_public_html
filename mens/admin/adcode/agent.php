<?php include_once('../library/adcode/agent.php');?>
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
    <h1>代理店管理</h1>
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
                <th class="table-header-repeat line-left minwidth-1"><a href="">PASS</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">代理店名</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">メールアドレス</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">担当者</a></th>
                <th class="table-header-repeat line-left minwidth-1" colspan="2"><a href="">オプション</a></th>
              </tr>
				      <tr>
                  <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="frm">
                	<input name="action" type="hidden" value="new">
    		      	   <td><input class="inp-form" type="text" name="id"/></td>
    		      	   <td><input class="inp-form" type="text" name="password" /></td>
    		      	   <td><input class="inp-form" type="text" name="name" /></td>
    		      	   <td><input class="inp-form" type="text" name="mail" /></td>
    		      	   <td><input class="inp-form" type="text" name="tantou" /></td>
    		      	   <td colspan="2"><a href="javascript:document.forms['frm'].submit();" onclick="return confirm('新規登録しますか？')" title="新規" class="icon-1 info-tooltip"></a></td>
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
    		      	<td><input class="inp-form" type="text" name="password" value="<?php echo( View_Cook( $data['password'] ) ); ?>" /></td>
    		      	<td><input class="inp-form" type="text" name="name" value="<?php echo(  $data['name']  ); ?>" /></td>
    		      	<td><input class="inp-form" type="text" name="mail" value="<?php echo(  $data['mail']  ); ?>" /></td>
    		      	<td><input class="inp-form" type="text" name="tantou" value="<?php echo(  $data['tantou']  ); ?>"/></td>
    		      	<td><a href="javascript:document.forms['frm<?php echo $i;?>'].submit();" onclick="return confirm('変更しますか？')" title="変更" class="icon-1 info-tooltip"></a></td>
    		      </form>
    		      <!--削除-->
              <td><a href="agent.php?action=delete&id=<?php echo $data['id'];?>" onclick="return confirm('削除しますか？')" title="削除" class="icon-2 info-tooltip"></a></td>
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
    </div>
    <!--  end content-table-inner ............................................END  -->
  </div>
</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>