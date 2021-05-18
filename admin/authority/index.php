<?php include_once('../library/authority/index.php');?>
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
    <h1>アカウント管理</h1>
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
                <!--<th class="table-header-repeat"><a href="">従業員ID</a></th>-->
                 <th class="table-header-repeat"><a href="">従業員名</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">ユーザ名</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">パスワード</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">権限</a></th>
                <th class="table-header-repeat line-left minwidth-1"><a href="">稼働</a></th>
                <th class="table-header-repeat line-left minwidth-1" colspan="2"><a href="">オプション</a></th>
              </tr>
              <tr>
                  <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="frm">
                   <input name="action" type="hidden" value="edit">
                   <td></td><td></td>
                   <td><input  class="inp-form" type="text" name="login_id"/></td>
                   <td><input  class="inp-form" type="text" name="password"/></td>
                   <td><select style="height:23px;" name="authority"><?php Reset_Select_Key( $gAuthority ,"" ) ?></select></td>
                   <td><select style="height:23px;" name="del_flg"><?php Reset_Select_Key( $gShowStatus ,"" ) ?></select></td>
                   <td colspan="2"><a href="javascript:document.forms['frm'].submit();" onclick="return confirm('新規登録しますか？')" title="新規" class="icon-1 info-tooltip"></a></td>
                  </form>
              </tr>
              <tr><td colspan="8"></td></tr>
<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 1 ;
	while ( $data = $dRtn3->fetch_assoc() ) {
    $staff_name = $data['staff_id'] ? Get_Table_Col("staff","name"," WHERE id= ".$data['staff_id']) : "";
	  $i++;
?>
    		    <tr <?php echo($i%2==0 ? ' class="alternate-row"' : '')?> >
				  <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="frm<?php echo $i;?>">
            <td><?php echo View_Cook($data['id']);?></td>
            <!--<td><?php echo View_Cook($data['staff_id'] ? $data['staff_id'] : "");?></td>-->
            <td><?php echo $staff_name; ?></td>
            <!--<td><?php echo View_Cook($data['staff_id'] ? $data['name'] : "");?></td>-->
					<td><input name="login_id" type="text"  class="inp-form" value="<?php echo( View_Cook( $data['login_id'] ) ); ?>" ></td>
					<td><input name="password" type="text"  class="inp-form" value="<?php echo( View_Cook( $data['password'] ) ); ?>" ></td>
					<td>
            <?php 
              if($data['authority']==50){ 
                echo "広告";
              }
              elseif($data['authority']==51){ 
                echo "会計";
              }
              else{?>
                <select name="authority"><?php Reset_Select_Key( $gAuthority , View_Cook( $data['authority'] )) ?></select>
            <?php }?>
          </td>
					<td><select name="del_flg"><?php Reset_Select_Key( $gShowStatus , View_Cook( $data['del_flg'] )) ?></select></td>
					<!--詳細編集-->
					<td><a href="javascript:document.forms['frm<?php echo $i;?>'].submit();" onclick="return confirm('変更しますか？')" title="変更" class="icon-1 info-tooltip"></a></td>
					<input name="action" type="hidden" value="edit">
					<input name="id" type="hidden" value="<?php echo($data['id']); ?>">
          <input name="staff_id" type="hidden" value="<?php echo($data['staff_id']); ?>">
				  </form>
					<!--削除-->	
				  <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="dfrm<?php echo $i;?>">
					<td><a href="javascript:document.forms['dfrm<?php echo $i;?>'].submit();" onclick="return deleteChk('削除しますか？')" title="削除" class="icon-2 info-tooltip"></a></td>
					<input name="action" type="hidden" value="delete">
					<input name="id" type="hidden" value="<?php echo($data['id']); ?>">
  				  </form>
	</tr>
<?php
	} //while
}
?>
				
			</table>
			<font size=-1 color=red>* ID及びﾊﾟｽﾜｰﾄﾞを変更する際は慎重に行ってください！！</font>
		<!--  end content-table  -->
      <!--  start paging..................................................... -->
      <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
      <tr>
      	<td><?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?></td>
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