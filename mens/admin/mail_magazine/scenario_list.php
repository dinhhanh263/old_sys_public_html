<?php include_once('../library/mail_magazine/scenario_list.php');?>
<?php include_once('../include/header_menu.html');?>
</form>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
    <!--  start page-heading -->
    <div id="page-heading">
        <h1>シナリオ一覧</a></h1>
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
                  <th class="table-header-repeat line-left minwidth-1"><a href="">シナリオ名</a></th>
                  <th class="table-header-repeat line-left minwidth-1"><a href="">ﾀｲﾌﾟ</a></th>
                  <th class="table-header-repeat line-left minwidth-1"><a href="">ｼﾞｬﾝﾙ</a></th>
                  <th class="table-header-repeat line-left minwidth-1"><a href=""></a></th>
                  <th class="table-header-repeat line-left minwidth-1"><a href="">件数</a></th>
                  <th class="table-header-repeat line-left minwidth-1"><a href="">登録日付</a></th>
                  <th class="table-header-options line-left"><a href="">オプション</a></th>            
             </tr>

<?php
if ( $list ) {
	$i = 0;
	foreach($list as $key=>$val){
	$i++;
	$cnt = $val['total'];
    $total +=$cnt;
    $link = ($val['memo']) ? '<a href="memo.php?id='.$val['id'].'" onclick="m_win(this.href,null,400,600); return false;">'.$val['id'].'</a>' : $val['id'] ;
	
?>
			  <tr id="aaa-2" <?php echo($i%2==0 ? 'class="bgb"' : 'class="bgb2"')?> >
    		      <td align="center"><?php echo $link ?></td>
    		      
	  			<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="" onSubmit="return edit_data('<?php echo($val['id']); ?>','<?php echo($val['data_name']); ?>');">
    		      <td align="left"><input id="i10-1" name="data_name" type="text" class="imeon" size="50" value="<?php echo(  $val['name']  ); ?>" ></td>
    		      <input name="action" type="hidden" value="update">
    		      <input name="id" type="hidden" value="<?php echo($val['id']); ?>">
    		      <td><select name="type"><?php Reset_Select_Key( $gType ,  $val['type'] ) ?></select></td>
    		      <td><select name="genre"><?php Reset_Select_Key( $gGenre ,  $val['genre'] ) ?></select></td>
    		      <td align="center"><input type="submit" class="px12" value="変更"></td>
	  			</form>
    		      <td align="right"><?php echo number_format($cnt) ?></td>
    		      <td align="center"><?php echo $val['date'] ?></td>
    		      <!-- 
    		      <form action="scenario_detail.php" method="post" name="" >
    		        <td align="center"><input type="submit" class="px12" value="詳細">
    		      	<input name="mode" type="hidden" value="edit">
    		      	<input name="id" type="hidden" value="<?php echo($val['id']); ?>">
    		        </td>
    		      </form>-->
              <td>

              <!--<a href="scenario_detail.php?mode=edit&id=<?php echo $val['id']?>" title="詳細" class="icon-1 info-tooltip"></a>-->
  
              <a href="scenario_list.php?mode=delete&id=<?php echo $val['id']?>&data_name=<?php echo $val['name']?>" onclick="return confirm('削除しますか？')" title="削除" class="icon-2 info-tooltip"></a>

              <a href="csv.php?id=<?php echo $val['id']?>" onclick="return confirm('ダウンロードしますか？')" title="ダウンロード" class="icon-3 info-tooltip"></a>
    		      </td>
			</tr>
<?php
	}
}else{
	echo("<tr><td colspan='10'>　※　データがありません。</td></tr>");
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