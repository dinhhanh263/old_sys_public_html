<?php include_once('../library/adcode/adcode.php');?>
<?php include_once('../include/header_menu.html');?>
<script type="text/javascript" src="../js/zclip/jquery.zclip.js"></script>
<!-- CSS -->
<style type="text/css">
   .para { padding:5px; border:1px dotted #ccc; }
   #demo a { color:#666!important; border:1px solid #666; text-decoration:none; padding:3px 5px; }
   #demo a:hover, #demo a.hover { color:#ff6699!important; border:1px solid #ff6699; }
   #demo a:active, #demo a.active { color:#fff!important; background:#ff6699; border:1px solid #ff6699; }
</style>

<script type="text/javascript">
function deleteChk () {
    var name=prompt("削除パスワードを入力して下さい。", "");
    var password="mL7RtTJ8";
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
    <h1>広告コード管理</h1>
  </div>
  <!-- end page-heading -->
    <div id="content-table">
    <!--  start content-table-inner ...................................................................... START -->
      <div id="content-table-inner">

        <!--  start table-content  -->
        <div id="table-content">
          <?php if($gMsg) echo $gMsg;?>
          <table border="0" width="100%" cellpadding="0" cellspacing="0" id="adcod-table">
            <tr>
              <th class="table-header-repeat b_right">ID</th>
              <th class="table-header-repeat">広告コード</th>
              <th class="table-header-repeat">媒体(ブログ)名</th>
              <th class="table-header-repeat">種類</th>
              <th class="table-header-repeat" >サイト名</th>
              <th class="table-header-repeat">LP名</th>
              <th class="table-header-repeat">集客/求人</th>
              <th class="table-header-repeat">グループ</th>
              <th class="table-header-repeat">請求媒体</th>
              <!--<th class="table-header-repeat"><a href="">単価</a></th>-->
              <th class="table-header-repeat">チラシ番号</th>
              <th class="table-header-repeat">発行日</th>
              <th class="table-header-repeat">代理店</th>
              <th class="table-header-repeat line-left minwidth-1 b_right" colspan="2">オプション</th>
            </tr>
            <form action="<?php echo($_SERVER['SCRIPT_NAME']); ?>" method="post" name="frm">
              <input name="action" type="hidden" value="new">
              <input name="start" type="hidden" value="<?php echo($_POST['start']); ?>">
              <input name="keyword" type="hidden" value="<?php echo($_POST['keyword']); ?>">
              <input name="daili_id" type="hidden" value="<?php echo($_POST['daili_id']); ?>">
              <tr>
                 <td rowspan="2" class="b_right"></td>
                 <td><input class="registration-form w3" type="text" name="adcode" value="<?php echo($_POST['id'] ? "" : $_POST['adcode']); ?>"/></td>
                 <td><input class="registration-form" type="text" name="name"  value="<?php echo($_POST['id'] ? "" : $_POST['name']); ?>"/></td>
                 <td><select style="height:30px;" name="type"><option value=""></option><?php Reset_Select_Key( $gAdType ,$_POST['id'] ? "" : $_POST['type'] ) ?></select></td>
                 <td><select  style="height:30px;width:60px;" name="site"><?php Reset_Select_Key( $gSites ,"" ) ?></select></td>
                 <td><select style="height:30px;width:150px;" name="page_name"><option value=""></option><?php Reset_Select_Key( $item_landing_list ,$_POST['id'] ? "" : $_POST['page_name'] ) ?></select></td>
                 <td><select style="height:30px;" name="job_flg"><option value=""></option><?php Reset_Select_Key( $gJobFlag ,$_POST['id'] ? "" : $_POST['job_flg'] ) ?></select></td>
                 <td><select style="height:30px;" name="ad_group"><option value=""></option><?php Reset_Select_Key( $gADGroup ,$_POST['id'] ? "" : $_POST['ad_group'] ) ?></select></td>
                 <td><select style="height:30px;width:150px;" name="request_id"><?php Reset_Select_Array_Group( getDatalistArray2("m_ad","ad_group") , "-",$gADGroup);?></select></td>
                 <!--<td><input class="inp-form2" type="text" name="cost" value="" /></td>-->
                 <td><input class="registration-form w3" type="text" name="flyer_no" value="<?php echo($_POST['id'] ? "" : $_POST['flyer_no']); ?>" /></td>
                 <td><input class="registration-form w7" type="text" name="release_date" value="<?php echo date('Y/n/j'); ?>" /></td>
                 <td class="b_right"><select style="height:30px;width:150px;" name="agent_id"><option value=""></option><?php Reset_Select_Key( $gAgent ,$_POST['id'] ? "" : $_POST['agent_id'] ) ?></select></td>
                 <td rowspan="2" class="b_right"><a href="javascript:document.forms['frm'].submit();" onclick="return confirm('新規登録しますか？')" title="新規" class="icon-1 info-tooltip"></a></td>
              </tr>
              <tr>
                <td colspan="7"></td>
                <td colspan="4" class="b_right">
                  店舗表示文章
                  <input class="registration-form w20" type="text" name="memo" value="<?php echo($_POST['id'] ? "" : $_POST['memo']); ?>" />
                </td>
              </tr>
            </form>
            <?php
            if ( $dRtn3->num_rows >= 1 ) {
              $i = 1 ;
              while ( $data = $dRtn3->fetch_assoc() ) {
              $i++;
            ?>
            <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="adfrm<?php echo $i;?>">
             <input name="action" type="hidden" value="update">
             <input name="id" type="hidden" value="<?php echo($data['id']); ?>">
             <input name="start" type="hidden" value="<?php echo($_POST['start']); ?>">
             <input name="keyword" type="hidden" value="<?php echo($_POST['keyword']); ?>">
             <input name="daili_id" type="hidden" value="<?php echo($_POST['daili_id']); ?>">

              <tr class="border_t3">
                <td rowspan="2" class="b_right"><?php echo(  $data['id']  ); ?></td>
                <td><input class="registration-form w3" type="text" name="adcode" value="<?php echo(  $data['adcode']  ); ?>" /></td>
                <td><input class="registration-form" type="text" name="name" value="<?php echo(  $data['name']  ); ?>" /></td>
                <td><select style="height:30px;" name="type" ><option value=""></option><?php Reset_Select_Key( $gAdType , View_Cook( $data['type'] )) ?></select></td>

                <td><select style="height:30px;width:60px;" name="site" ><?php Reset_Select_Key( $gSites , View_Cook( $data['site'] )) ?></select></td>
                <td><select style="height:30px;width:150px;" name="page_name" ><option value=""></option><?php Reset_Select_Key( $item_landing_list , View_Cook( $data['page_name'] )) ?></select></td>
                <td><select style="height:30px;" name="job_flg"><option value=""></option><?php Reset_Select_Key( $gJobFlag , View_Cook( $data['job_flg'] )) ?></select></td>
                <td><select style="height:30px;" name="ad_group"><option value=""></option><?php Reset_Select_Key( $gADGroup , View_Cook( $data['ad_group'] )) ?></select></td>
                <td><select style="height:30px;width:150px;" name="request_id"><?php Reset_Select_Array_Group(getDatalistArray2("m_ad","ad_group") , $data['request_id'],$gADGroup);?></select></td>
                 <!--<td><input class="inp-form2" type="text" name="cost" value="<?php echo( View_Cook( $data['cost'] ) ); ?>" /></td>-->
                 <td><input class="registration-form w3" type="text" name="flyer_no" value="<?php echo( View_Cook( $data['flyer_no'] ) ); ?>" /></td>
                <td><input class="registration-form w7" type="text" name="release_date" value="<?php echo( View_Cook( $data['release_date'] ) ); ?>" /></td>
                <td class="b_right"><select style="height:30px;width:150px;" name="agent_id"><option value=""></option><?php Reset_Select_Key( $gAgent , View_Cook( $data['agent_id'] )) ?></select></td>
                <td class="b_right">
                  <a href="javascript:document.forms['adfrm<?php echo $i;?>'].submit();" onclick="return confirm('<?php echo(  $data['adcode'].".". $data['name'] ); ?>を変更しますか？')" title="変更" class="icon-1 info-tooltip"></a>
                </td>
              </tr>
              <tr>
                <?php
                  if($data['type']==4){
//                    $ad_url = $home_url."lp/".$data['adcode'];
                    $ad_url = "https://kireimo.jp/lp/".$data['adcode'];
                  }else{
                    $ad_url = "https://kireimo.jp/".($data['page_name'] ? $data['page_name']."/" : "").( ($data['type']==3 || $data['id']==916) ? "" : "?adcode=".$data['adcode']);
                  }
                ?>
                <td colspan="7" style="text-align:left;">
                  <div style="position: relative;">
                    広告コード付きURL:
                    <span id="dynamic<?php echo $data['id'];?>"><?php echo $ad_url ;?></span>
                    <button style="height:23px;" id="copy-dynamic<?php echo $data['id'];?>" >URLコピー</button>
                    <script type="text/javascript">
                      $(function(){
                        $("#copy-dynamic<?php echo $data['id'];?>").zclip({
                          path:"../js/zclip/ZeroClipboard.swf",
                          copy:function(){ return $("#dynamic<?php echo $data['id'];?>").text(); }
                        });
                      });
                    </script>
                    <a href ="<?php echo $ad_url ;?>" target="_blank"><input type="button" style="height:23px;" value="プレビュー"></a>
                  </div>
                </td>
                <td colspan="4" class="b_right">
                  店舗表示文章
                  <input class="registration-form w20" type="text" name="memo" value="<?php echo( View_Cook( $data['memo'] ) ); ?>" />
                </td>
                <td class="b_right">
                    <a href="javascript:document.forms['frm<?php echo($data['id']); ?>'].submit();" onclick="return deleteChk('<?php echo(  $data['adcode'].".". $data['name'] ); ?>を削除しますか？')" title="削除" class="icon-2 info-tooltip"></a>
                </td>
              </tr>
            </form>
            <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" name="frm<?php echo($data['id']); ?>">
              <input name="action" type="hidden" value="delete">
              <input name="id" type="hidden" value="<?php echo($data['id']); ?>">
              <input name="start" type="hidden" value="<?php echo($_POST['start']); ?>">
              <input name="keyword" type="hidden" value="<?php echo($_POST['keyword']); ?>">
              <input name="daili_id" type="hidden" value="<?php echo($_POST['daili_id']); ?>">
            </form>
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
          <td>
          <?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
          </td>
          </tr>
        </table>
        <!--  end paging................ -->
      </div>
      <!--  end content-table-inner ............................................END  -->
    </div>
    <!--  end content-table ............................................END  -->
</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html");?>