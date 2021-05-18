<?php include_once('../library/mail_magazine/sent_list.php');?>
<?php include_once('../include/header_menu.html');?>
</form>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
    <!--  start page-heading -->
    <div id="page-heading">
        <h1>配信履歴</a></h1>
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
                <form id="mainform" action="">
                <table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
                <tr>
                  <th class="table-header-repeat line-left minwidth-1"><a href="">ID</a></th>
                  <th class="table-header-repeat line-left minwidth-1"><a href="">タイトル</a></th>
                  <th class="table-header-repeat line-left minwidth-1"><a href="">送信予定日時</a></th>
                  <th class="table-header-repeat line-left minwidth-1"><a href="">配信数</a></th>
                  <th class="table-header-repeat line-left minwidth-1"><a href="">実際送信日時</a></th>
                  <th class="table-header-options line-left"><a href="">オプション</a></th>           
             </tr>

<?php
if ( $dRtn3->num_rows >= 1 ) {
	$i = 0;
	while ( $data = $dRtn3->fetch_assoc() ) {
	$i++;
?>
			  <tr <?php echo $i%2==0 ? ' class="alternate-row"' : '' ;?> >
    		      <td><?php echo($data['id']); ?></td>
    		      <td><?php echo($data['title']); ?></td>
    		      <td><?php echo($data['plan_date']); ?></td>
    		      <td><?php echo($data['total']); ?></td>
    		     <!-- <td><?php echo($data['success_cnt']); ?></td>
    		      <td><?php echo($data['err_cnt']); ?></td>-->
    		      <td><?php echo($data['start_date'] =="0000-00-00 00:00:00" ? "-" : $data['start_date'] ); ?></td>
    		      <td class="options-width">
                 <a href="sent_detail.php?id=<?php echo $data['id']?>" title="詳細" class="icon-1 info-tooltip"></a>
    		         <a href="sent_list.php?action=delete&id=<?php echo $data['id']?>" onclick="return confirm(\'削除しますか？\')" title="削除" class="icon-2 info-tooltip"></a>
    		      </td>

	</tr>
<?php
	} //while
}
?>
				</table>
                <!--  end product-table................................... --> 
                </form>
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