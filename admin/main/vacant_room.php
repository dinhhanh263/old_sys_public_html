<?php
include_once("../library/main/vacant_room.php");
header("Content-type: text/html; charset=UTF-8");
?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>KIREIMO SYSTEM</title>
<link rel="stylesheet" href="../css/screen.css" type="text/css" media="screen" title="default" />
<script type="text/javascript" src="../js/main.js"></script>
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="css/pro_dropline_ie.css" />
<![endif]-->

<!--  jquery core -->
<script src="../js/jquery/jquery-1.8.2.min.js" type="text/javascript"></script>
<!-- Custom jquery scripts -->
<script src="../js/jquery/custom_jquery.js" type="text/javascript"></script>


<!--  date picker script -->
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.all.css" />
<link rel="stylesheet" type="text/css" href="../js/datepicker/themes/flick/ui.datepicker.css" />

<script type="text/javascript" src="../js/datepicker/ui.datepicker.js"></script>
<script type="text/javascript" src="../js/datepicker/ui.datepicker-ja.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    // 時間ピッカー
    $("input#hope_date,input#hope_date1,input#hope_date2").datepicker(
      {dateFormat: 'yy-mm-dd',numberOfMonths: [1,2],duration: 0,showAnim: 'slideDown'}
    );
    $("input#hope_date_area").datepicker(
      {dateFormat: 'yy-mm-dd',numberOfMonths: [1,2],duration: 0,showAnim: 'slideDown'}
    );
    $("input#hope_date_one").datepicker(
      {dateFormat: 'yy-mm-dd',duration: 0,showAnim: 'slideDown'}
    );

  });
</script>
<style type="text/css">
  span.ui-datepicker-year {
    margin-right:1em;
  }
</style>
</head>
<body>

  <style type="text/css" media="screen">
    select{min-height: 27px}
  </style>
  <div id="content-table-inner" class="vacant_room_box">
    <div id="fixed_top">
      <div class="description">※人数は早番・中番・遅番など全ての合計人数です。時間帯によって接客可能人数は異なります。</div>
      <?php if(!isset($_GET['type']) && !isset($_POST['type'])){ ?>
        <div class="color_sample fill">予約あり</div>
        <div class="color_sample fill_pack fill_month1">パック・VIP・新月額（上）</div>
        <div class="color_sample fill_month2">…新規枠+旧月額（下）</div>
        <div class="color_sample fill_ng">予約不可</div>
      <?php } ?>
    </div>
    <?php if($_GET['type'] == 'reservation' || $_POST['type'] == 'reservation'){ ?>
    <div id="mass_top1">
      <div class="title_box">
        <form name="select_one" id="select_one" method="post" action="./vacant_room.php" class="daybox">
          <input type="hidden" id="action" name="action" value="rooms">
          <input type="hidden" id="choose" name="choose" value="period">
          <input type="hidden" id="type" name="type" value="reservation">
          <input type="hidden" name="customer_id" value="<?php echo $_POST['customer_id']; ?>">
          <a href="./vacant_room.php?type=reservation&shop_id=<?php echo $_POST['shop_id']; ?>&hope_date=<?php echo $pre_date; ?>&customer_id=<?php echo $customer_id; ?>"><img src="../images/table/paging_left.gif" title="前日" /></a>
          <input name="hope_date" type="text" id="hope_date_one" value="<?php echo $_POST['hope_date'];?>" class="registration-form b_g1 w7" readonly />
          <a href="./vacant_room.php?type=reservation&shop_id=<?php echo $_POST['shop_id']; ?>&hope_date=<?php echo $next_date; ?>&customer_id=<?php echo $customer_id; ?>""><img src="../images/table/paging_right.gif" title="翌日" /></a>
          <select id="shop_id1" name="shop_id">
            <option value="">-</option>
            <?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'], $gArea_Group, "area_group"); ?>
          </select>
            <input type="button" id="submit_link" value=" 表示 " class="button" />
        </form>
      </div>
      <!--  start table-content  -->
      <div id="table1" class="d_table">
      </div>
    </div>

    <?php }else{ ?>

    <div id="mass_top">
      <div class="title_box">
        日付選択
        <form name="select_3" id="select_3" method="post" action="" class="daybox">
          <input type="hidden" name="action" value="rooms">
          <input type="hidden" id="choose" name="choose" value="choose">
          <input name="hope_date1" type="text" id="hope_date1" value="<?php echo $_POST['hope_date'];?>" class="registration-form b_g1 w7" readonly />～
          <input name="hope_date2" type="text" id="hope_date2" value="<?php echo $_POST['hope_date'];?>" class="registration-form b_g1 w7" readonly />
          <select id="shop_id1" name="shop_id">
            <option value="">-</option>
            <?php Reset_Select_Key_ShopGroup( $shop_lists , $_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id'], $gArea_Group, "area_group"); ?>
          </select>
          <input type="button" id="submit1" value=" 表示 " class="button" />
        </form>
      </div>
      <!--  start table-content  -->
      <div id="table1" class="d_table">
      </div>
    </div>
    <div id="mass_under">
      <div class="title_box">
        <h1>エリア一括表示</h1>
        <form name="daybox_area" id="daybox_area" method="post" action="" class="daybox">
          <input type="hidden" name="action" value="rooms">
          <input type="hidden" id="choose" name="choose" value="all">
          日付選択
          <input name="hope_date" type="text" id="hope_date_area" value="<?php echo $_POST['hope_date'];?>" class="registration-form b_g1 w7" readonly />
          <select id="shop_area" name="shop_area"><?php Reset_Select_Key($gArea_Group, "area_group"); ?></select>
          <input type="button" id="submit_area" value=" 表示 " class="button" ;" />
        </form>
      </div>
      <div id="table2" class="d_table">
      </div>
    </div>
    <?php } ?>
    <div id="loading_box">
      <div id="loading">
        <div class="cssload-loading">
          <i></i>
          <i></i>
          <i></i>
          <i></i>
        </div>
      </div>
    </div>
<script type="text/javascript">
  $(function(){
    function room_load(button,form_id,target){
      var $button;
        $button = $(document.getElementById(button));
        $button.on("click",function(){
          display_trigger(form_id,target);
          return false;
        });
    }
    function display_trigger(form_id,target){
      var $target,loading;
      $target = $(document.getElementById(target)),
      loading = document.getElementById("loading_box");
      loading.style.display = "block";
      var shop,data1,full_url;
      shop = document.getElementById(form_id),
      data1 = new FormData(shop);
      $.ajax({
        url:"../library/main/vacant_room.php",
        type:"post",
        dataType:"html",
        data:data1,
        processData: false,
        contentType: false
      }).done(function(response){
        loading.style.display = "none";
        $target.html(response);
      }).fail(function(){
        loading.style.display = "none";
        $target.html("読み込みに失敗しました。");
      })
    }
    room_load("submit1","select_3","table1");
    room_load("submit_area","daybox_area","table2");
    function one_read(form,button,remove){
      var $form,$button,$remove;
      $form = $('#' + form),
      $button = $('#' + button),
      $remove = $('#' + remove);
      $button.on('click',function(){
        $remove.remove();
        $form.submit();
      })
    }
    <?php if(isset($_GET['hope_date']) || isset($_POST['hope_date']) && ($_GET['type'] == 'reservation' || $_POST['type'] == 'reservation') ){ ?>
      display_trigger("select_one","table1");
      one_read('select_one','submit_link','action');
    <?php } ?>
  })
</script>

    <!--  end content-table  -->
  </div>
</body>
</html>
