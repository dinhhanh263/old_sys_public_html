<?php include_once("../library/invite/index.php"); ?>
<?php include_once("../include/header_menu.html"); ?>

<link rel="stylesheet" href="css/style.css">
<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox();
  var use_datepicker_selecter_names =
    'input[name=search_contract_date_rb],' +
    'input[name=search_contract_date_ra],' +
    'input[name=search_refund_request_rb],' +
    'input[name=search_refund_request_ra],' +
    'input[name=search_refund_date_rb],' +
    'input[name=search_refund_date_ra],' +
    'input[name=search_refund_contact_rb],' +
    'input[name=search_refund_contact_ra]';
  $(use_datepicker_selecter_names).datepicker({
    duration: 'slow',
    dateFormat: 'yy-mm-dd'
  });

  if ( $('input[name=search_refund_request_rb]').prop('disabled') == false ) {
      $('input[name=search_refund_request_rb]').prev().attr('href', 'index.php?search_refund_request_rb=<?php echo $pre_date['refund_request'] ?>');
      $('input[name=search_refund_request_ra]').next().attr('href', 'index.php?search_refund_request_rb=<?php echo $next_date['refund_request'] ?>');
  }
  $('input[name=search_refund_request]').click(function() {
    if ( $(this).val() !== 'done' ) {
      $('input[name=search_refund_request_rb]').prop('disabled', true);
      $('input[name=search_refund_request_ra]').prop('disabled', true);
      $('input[name=search_refund_request_rb]').val('');
      $('input[name=search_refund_request_ra]').val('');
      $('input[name=search_refund_request_rb]').prev().attr('href', 'javascript:void(0)');
      $('input[name=search_refund_request_ra]').next().attr('href', 'javascript:void(0)');
    } else {
      $('input[name=search_refund_request_rb]').prop('disabled', false);
      $('input[name=search_refund_request_ra]').prop('disabled', false);
      $('input[name=search_refund_request_rb]').prev().attr('href', 'index.php?search_refund_request_rb=<?php echo $pre_date['refund_request'] ?>');
      $('input[name=search_refund_request_ra]').next().attr('href', 'index.php?search_refund_request_rb=<?php echo $next_date['refund_request'] ?>');
    }
  });

  if ( $('input[name=search_refund_date_rb]').prop('disabled') == false ) {
      $('input[name=search_refund_date_rb]').prev().attr('href', 'index.php?search_refund_date_rb=<?php echo $pre_date['refund_date'] ?>');
      $('input[name=search_refund_date_ra]').next().attr('href', 'index.php?search_refund_date_rb=<?php echo $next_date['refund_date'] ?>');
  }
  $('input[name=search_refund_date]').click(function() {
    if ( $(this).val() !== 'done' ) {
      $('input[name=search_refund_date_rb]').prop('disabled', true);
      $('input[name=search_refund_date_ra]').prop('disabled', true);
      $('input[name=search_refund_date_rb]').val('');
      $('input[name=search_refund_date_ra]').val('');
      $('input[name=search_refund_date_rb]').prev().attr('href', 'javascript:void(0)');
      $('input[name=search_refund_date_ra]').next().attr('href', 'javascript:void(0)');
    } else {
      $('input[name=search_refund_date_rb]').prop('disabled', false);
      $('input[name=search_refund_date_ra]').prop('disabled', false);
      $('input[name=search_refund_date_rb]').prev().attr('href', 'index.php?search_refund_date_rb=<?php echo $pre_date['refund_date'] ?>');
      $('input[name=search_refund_date_ra]').next().attr('href', 'index.php?search_refund_date_rb=<?php echo $next_date['refund_date'] ?>');
    }
  });

  if ( $('input[name=search_refund_contact_rb]').prop('disabled') == false ) {
      $('input[name=search_refund_contact_rb]').prev().attr('href', 'index.php?search_refund_contact_rb=<?php echo $pre_date['refund_contact'] ?>');
      $('input[name=search_refund_contact_ra]').next().attr('href', 'index.php?search_refund_contact_rb=<?php echo $next_date['refund_contact'] ?>');
  }
  $('input[name=search_refund_contact]').click(function() {
    if ( $(this).val() !== 'done' ) {
      $('input[name=search_refund_contact_rb]').prop('disabled', true);
      $('input[name=search_refund_contact_ra]').prop('disabled', true);
      $('input[name=search_refund_contact_rb]').val('');
      $('input[name=search_refund_contact_ra]').val('');
      $('input[name=search_refund_contact_rb]').prev().attr('href', 'javascript:void(0)');
      $('input[name=search_refund_contact_ra]').next().attr('href', 'javascript:void(0)');
    } else {
      $('input[name=search_refund_contact_rb]').prop('disabled', false);
      $('input[name=search_refund_contact_ra]').prop('disabled', false);
      $('input[name=search_refund_contact_rb]').prev().attr('href', 'index.php?search_refund_contact_rb=<?php echo $pre_date['refund_contact'] ?>');
      $('input[name=search_refund_contact_ra]').next().attr('href', 'index.php?search_refund_contact_rb=<?php echo $next_date['refund_contact'] ?>');
    }
  });

  if ( $('input[name=search_contract_date_rb]').prop('disabled') == false ) {
      $('input[name=search_contract_date_rb]').prev().attr('href', 'index.php?search_contract_date_rb=<?php echo $pre_date['contract_date'] ?>');
      $('input[name=search_contract_date_ra]').next().attr('href', 'index.php?search_contract_date_rb=<?php echo $next_date['contract_date'] ?>');
  }
  $('input[name=search_contract_date]').click(function() {
    if ( $(this).val() !== 'done' ) {
      $('input[name=search_contract_date_rb]').prop('disabled', true);
      $('input[name=search_contract_date_ra]').prop('disabled', true);
      $('input[name=search_contract_date_rb]').val('');
      $('input[name=search_contract_date_ra]').val('');
      $('input[name=search_contract_date_rb]').prev().attr('href', 'javascript:void(0)');
      $('input[name=search_contract_date_ra]').next().attr('href', 'javascript:void(0)');
    } else {
      $('input[name=search_contract_date_rb]').prop('disabled', false);
      $('input[name=search_contract_date_ra]').prop('disabled', false);
      $('input[name=search_contract_date_rb]').prev().attr('href', 'index.php?search_contract_date_rb=<?php echo $pre_date['contract_date'] ?>');
      $('input[name=search_contract_date_ra]').next().attr('href', 'index.php?search_contract_date_rb=<?php echo $next_date['contract_date'] ?>');
    }
  });

  $('#checkAll').click(function() {
    if (this.checked) {
      $('#product-table input[type=checkbox]').attr('checked', 'checked');
    } else {
      $('#product-table input[type=checkbox]').removeAttr('checked');
    }
  });
})

function csv_export () {
  document.search.action = "csv_export.php";
  document.search.submit();
  /*
    var name=prompt("パスワードを入力して下さい。", "");
    var path = "<?php //echo $home_url;?>" + "admin/img/" + name + ".gif";
    var img = new Image();
    img.src = path;
    if( img.height > 0){ // 読み込みに失敗すれば 0 になる。
      document.search.action = "csv_export.php";
    document.search.submit();
    document.search.csv_pw.value = name;
    return true;
    }else{
      return false;
    }
  */
}
function i__update_refund_contact() {
  document.search.action = "update_refund_contact.php";
  document.search.submit();
}
function i__update_refund_date() {
  document.search.action = "update_refund_date.php";
  document.search.submit();
}
function i__update_refund_request() {
  document.search.action = "update_refund_request.php";
  document.search.submit();
}
function i__submit_search() {
  document.search.action = "index.php";
  document.search.submit();
}

</script>

<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
  <div id="content">
    <div id="page-heading">
      <h1>お友達紹介キャンペーン 紹介先/紹介元一覧</h1>
      <div>※このページはCCまたは本社スタッフのみ閲覧可能です。</div>
    </div>
    <?php if($authority_view === 'vielis' || $authority_view === 'cc_staff'){ ?>
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

            <!--  start content-table-inner -->
            <div id="content-table-inner">
              <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr valign="top">
                  <td>
                    <?php if ($_SESSION['success']): ?>
                      <?php foreach ($_SESSION['success'] as $message): ?>
                        <p class="i__success-message">※<?php echo $message ?></p><br>
                      <?php endforeach ?>
                      <?php unset($_SESSION['success']) ?>
                    <?php endif ?>
                    <?php if ($_SESSION['error']): ?>
                      <?php foreach ($_SESSION['error'] as $message): ?>
                        <p class="i__error-message">※<?php echo $message ?></p><br>
                      <?php endforeach ?>
                      <?php unset($_SESSION['error']) ?>
                    <?php endif ?>
                    <!-- CONTENT >>-->
                      <div class="i__heading">
                        <h2>検索条件設定</h2>
                      </div>
                      <table class="i__table-search">
                        <tr class="i__table-bottom-border">
                          <th>紹介先：</th>
                          <td>
                            <input type="text" name="search_saki_customer_no" value="<?php echo htmlspecialchars($_POST['search_saki_customer_no']) ?>" placeholder="会員番号" maxlenght="11">
                            <input type="text" name="search_saki_customer_name" value="<?php echo htmlspecialchars($_POST['search_saki_customer_name']) ?>" placeholder="名前" maxlenght="63">
                            <input type="text" name="search_saki_customer_kana" value="<?php echo htmlspecialchars($_POST['search_saki_customer_kana']) ?>" placeholder="名前（カナ）" maxlenght="63">
                            <label class="i__label">&nbsp;&nbsp;&nbsp;&nbsp;契約日：&nbsp;&nbsp;</label>
                            <label class="i__label"><input type="radio" name="search_contract_date" value="all" <?php echo ($_POST['search_contract_date'] == 'all')? 'checked' : (($_POST['search_contract_date'] == null && $_GET['search_contract_date_rb'] == null)? 'checked' : '') ?>>すべて</label>
                            <label class="i__label"><input type="radio" name="search_contract_date" value="done" <?php echo ($_POST['search_contract_date'] == 'done')? 'checked' : (($_GET['search_contract_date_rb'] != null)? 'checked' : '') ?>>あり</label>
                            <!--<a href="<?php //echo ($_POST['search_contract_date'] !== 'done')? 'javascript:void(0)' : 'index.php?search_contract_date_rb=' . $pre_date['contract_date'] ?>"><img src="../images/table/paging_left.gif" title="前日" /></a>-->
                            <input class="i__form-size i__w70" name="search_contract_date_rb" type="text" value="<?php echo htmlspecialchars($_POST['search_contract_date_rb']) ?>" readonly <?php echo ($_GET['search_contract_date_rb'] != null)? '' : ($_POST['search_contract_date'] !== 'done')? 'disabled' : '' ?>/>
                            ~
                            <input class="i__form-size i__w70" name="search_contract_date_ra" type="text" value="<?php echo htmlspecialchars($_POST['search_contract_date_ra']) ?>" readonly <?php echo ($_GET['search_contract_date_rb'] != null)? '' : ($_POST['search_contract_date'] !== 'done')? 'disabled' : '' ?>/>
                            <!--<a href="<?php //echo ($_POST['search_contract_date'] !== 'done')? 'javascript:void(0)' : 'index.php?search_contract_date_rb=' . $next_date['contract_date'] ?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>-->
                            <label class="i__label"><input type="radio" name="search_contract_date" value="yet" <?php echo ($_POST['search_contract_date'] == 'yet')? 'checked' : '' ?>>なし</label>
                          </td>
                        </tr>
                        <tr>
                          <th>紹介元：</th>
                          <td>
                            <input type="text" name="search_moto_customer_no" value="<?php echo htmlspecialchars($_POST['search_moto_customer_no']) ?>" placeholder="会員番号" maxlenght="11">
                            <input type="text" name="search_moto_customer_name" value="<?php echo htmlspecialchars($_POST['search_moto_customer_name']) ?>" placeholder="名前" maxlenght="63">
                            <input type="text" name="search_moto_customer_kana" value="<?php echo htmlspecialchars($_POST['search_moto_customer_kana']) ?>" placeholder="名前（カナ）" maxlenght="63">
                          </td>
                        </tr>
                        <tr>
                          <th>口座状況：</th>
                          <td>
                            <?php $bank_state = empty($_POST['search_bank_state']) ? [] : $_POST['search_bank_state']; ?>
                            <label class="i__label"><input type="checkbox" name="search_bank_state[]" value="1" <?php echo (in_array('1',$bank_state))? 'checked' : '' ?>>有効</label>
                            <label class="i__label"><input type="checkbox" name="search_bank_state[]" value="2" <?php echo (in_array('2',$bank_state))? 'checked' : '' ?>>無効</label>
                            <label class="i__label"><input type="checkbox" name="search_bank_state[]" value="0" <?php echo (in_array('0',$bank_state))? 'checked' : '' ?>>未確認</label>
                            <label class="i__label"><input type="checkbox" name="search_bank_state[]" value="-" <?php echo (in_array('-',$bank_state))? 'checked' : '' ?>>登録なし</label>
                          </td>
                        </tr>
                        <tr>
                          <th>返金対象：</th>
                          <td>
                            <?php $refund_scope = empty($_POST['search_refund_scope']) ? [] : $_POST['search_refund_scope']; ?>
                            <label class="i__label"><input type="checkbox" value="0" name="search_refund_scope[]" <?php echo (in_array('0',$refund_scope))? 'checked' : '' ?>>対象</label>
                            <label class="i__label"><input type="checkbox" value="1" name="search_refund_scope[]" <?php echo (in_array('1',$refund_scope))? 'checked' : '' ?>>対象外（施術なし）</label>
                            <label class="i__label"><input type="checkbox" value="2" name="search_refund_scope[]" <?php echo (in_array('2',$refund_scope))? 'checked' : '' ?>>対象外（退会済み）</label>
                          </td>
                        </tr>
                        <tr>
                          <th>申請受付連絡日：</th>
                          <td>
                            <label class="i__label"><input type="radio" name="search_refund_request" value="all" <?php echo ($_POST['search_refund_request'] == 'all')? 'checked' : (($_POST['search_refund_request'] == null && $_GET['search_refund_request_rb'] == null)? 'checked' : '') ?>>すべて</label>
                            <label class="i__label"><input type="radio" name="search_refund_request" value="done" <?php echo ($_POST['search_refund_request'] == 'done')? 'checked' : (($_GET['search_refund_request_rb'] != null)? 'checked' : '') ?>>済み</label>
                            <!--<a href="<?php //echo ($_POST['search_refund_request'] !== 'done')? 'javascript:void(0)' : 'index.php?search_refund_request_rb=' . $pre_date['refund_request'] ?>"><img src="../images/table/paging_left.gif" title="前日" /></a>-->
                            <input class="i__form-size i__w70" name="search_refund_request_rb" type="text" value="<?php echo htmlspecialchars($_POST['search_refund_request_rb']) ?>" readonly <?php echo ($_GET['search_refund_request_rb'] != null)? '' : ($_POST['search_refund_request'] !== 'done')? 'disabled' : '' ?> />
                            ~
                            <input class="i__form-size i__w70" name="search_refund_request_ra" type="text" value="<?php echo htmlspecialchars($_POST['search_refund_request_ra']) ?>" readonly <?php echo ($_GET['search_refund_request_rb'] != null)? '' : ($_POST['search_refund_request'] !== 'done')? 'disabled' : '' ?> />
                            <!--<a href="<?php //echo ($_POST['search_refund_request'] !== 'done')? 'javascript:void(0)' : 'index.php?search_refund_request_rb=' . $next_date['refund_request'] ?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>-->
                            <label class="i__label"><input type="radio" name="search_refund_request" value="yet" <?php echo ($_POST['search_refund_request'] == 'yet')? 'checked' : '' ?>>未済</label>
                          </td>
                        </tr>
                        <tr>
                          <th>返金日：</th>
                          <td>
                            <label class="i__label"><input type="radio" name="search_refund_date" value="all" <?php echo ($_POST['search_refund_date'] == 'all')? 'checked' : (($_POST['search_refund_date'] == null && $_GET['search_refund_date_rb'] == null)? 'checked' : '') ?>>すべて</label>
                            <label class="i__label"><input type="radio" name="search_refund_date" value="done" <?php echo ($_POST['search_refund_date'] == 'done')? 'checked' : (($_GET['search_refund_date_rb'] != null)? 'checked' : '') ?>>済み</label>
                            <!--<a href="<?php //echo ($_POST['search_refund_date'] !== 'done')? 'javascript:void(0)' : 'index.php?search_refund_date_rb=' . $pre_date['refund_date'] ?>"><img src="../images/table/paging_left.gif" title="前日" /></a>-->
                            <input class="i__form-size i__w70" name="search_refund_date_rb" type="text" value="<?php echo htmlspecialchars($_POST['search_refund_date_rb']) ?>" readonly <?php echo ($_GET['search_refund_date_rb'] != null)? '' : ($_POST['search_refund_date'] !== 'done')? 'disabled' : '' ?> />
                            ~
                            <input class="i__form-size i__w70" name="search_refund_date_ra" type="text" id="day2" value="<?php echo htmlspecialchars($_POST['search_refund_date_ra']) ?>" readonly <?php echo ($_GET['search_refund_date_rb'] != null)? '' : ($_POST['search_refund_date'] !== 'done')? 'disabled' : '' ?> />
                            <!--<a href="<?php //echo ($_POST['search_refund_date'] !== 'done')? 'javascript:void(0)' : 'index.php?search_refund_date_rb=' . $next_date['refund_date'] ?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>-->
                            <label class="i__label"><input type="radio" name="search_refund_date" value="yet" <?php echo ($_POST['search_refund_date'] == 'yet')? 'checked' : '' ?>>未済</label>
                          </td>
                        </tr>
                        <tr class="i__table-bottom-border">
                          <th>返金連絡日：</th>
                          <td>
                            <label class="i__label"><input type="radio" name="search_refund_contact" value="all" <?php echo ($_POST['search_refund_contact'] == 'all')? 'checked' : (($_POST['search_refund_contact'] == null && $_GET['search_refund_contact_rb'] == null)? 'checked' : '') ?>>すべて</label>
                            <label class="i__label"><input type="radio" name="search_refund_contact" value="done" <?php echo ($_POST['search_refund_contact'] == 'done')? 'checked' : (($_GET['search_refund_contact_rb'] != null)? 'checked' : '') ?>>済み</label>
                            <!--<a href="<?php //echo ($_POST['search_refund_contact'] !== 'done')? 'javascript:void(0)' : 'index.php?search_refund_contact_rb=' . $pre_date['refund_contact'] ?>"><img src="../images/table/paging_left.gif" title="前日" /></a>-->
                            <input class="i__form-size i__w70" name="search_refund_contact_rb" type="text" value="<?php echo htmlspecialchars($_POST['search_refund_contact_rb']) ?>" readonly <?php echo ($_GET['search_refund_contact_rb'] != null)? '' : ($_POST['search_refund_contact'] !== 'done')? 'disabled' : '' ?> />
                            ~
                            <input class="i__form-size i__w70" name="search_refund_contact_ra" type="text" value="<?php echo htmlspecialchars($_POST['search_refund_contact_ra']) ?>" readonly <?php echo ($_GET['search_refund_contact_rb'] != null)? '' : ($_POST['search_refund_contact'] !== 'done')? 'disabled' : '' ?> />
                            <!--<a href="<?php //echo ($_POST['search_refund_contact'] !== 'done')? 'javascript:void(0)' : 'index.php?search_refund_contact_rb=' . $next_date['refund_contact'] ?>"><img src="../images/table/paging_right.gif" title="翌日" /></a>-->
                            <label class="i__label"><input type="radio" name="search_refund_contact" value="yet" <?php echo ($_POST['search_refund_contact'] == 'yet')? 'checked' : '' ?>>未済</label>
                          </td>
                        </tr>
                        <tr>
                          <th>検索結果表示件数</th>
                          <td>
                            <input name="line_max" type="text" class="i__form-size i__w-30" value="<?php echo $_POST['line_max'];?>"> 件
                          </td>
                        </tr>
                      </table>
                      <div class="i__text-center">
                        <button class="i__btn-submit" type="button" name="action_search" onclick="i__submit_search();">この条件で検索</button>
                      </div>
                      <hr class="i__hr" noshade>
                      <div class="i__row">
                        <?php if($authority_view === 'vielis'){ ?>
                          <div class="i__action-group">
                            <ul class="i__list-inline">
                              <li>
                                <button class="i__btn-action" type="button" onclick="i__update_refund_request();">申請受付連絡処理</button>
                                <input type='hidden' name="action_refund_request" value="" />
                              </li>
                              <li>
                                <button class="i__btn-action" type="button" onclick="i__update_refund_date();">返金処理</button>
                                <input type='hidden' name="action_refund_date" value="" />
                              </li>
                              <li>
                                <button class="i__btn-action" type="button" onclick="i__update_refund_contact();">返金連絡処理</button>
                                <input type='hidden' name="action_refund_contact" value="" />
                              </li>
                              <li>
                                <button class="i__btn-action" type="button" onclick="csv_export();">CSV</button>
                                <input type='hidden' name="csv_pw" value="" />
                              </li>
                            </ul>
                          </div>
                        <?php } ?>
                        <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
                          <tr>
                            <td>
                            <?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
                            </td>
                          </tr>
                        </table>
                      </div>
                      <div class="i__row">
                        <table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
                            <tr>
                              <th colspan="4">
                                <div class="i__heading">
                                  <h2>紹介先会員情報</h2>
                                </div>
                              </th>
                              <th></th>
                              <th colspan="8">
                                <div class="i__heading">
                                  <h2>紹介元会員情報</h2>
                                </div>
                              </th>
                            </tr>
                            <tr>
                              <th class="minwidth-1" style="text-align:center;"><input type="checkbox" id="checkAll" title="全選択"></th>
                              <th class="table-header-repeat line-left minwidth-1" width="94"><a href=""><font size="-2">契約日</font></a>	</th>
                              <th class="table-header-repeat line-left minwidth-1" width="104"><a href=""><font size="-2">会員番号</font></a>	</th>
                              <th class="table-header-repeat line-left minwidth-1" width="169"><a href=""><font size="-2">名前</font></a>	</th>
                              <th class="minwidth-1"></th>
                              <th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">会員番号</font></a>	</th>
                              <th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">名前</font></a>	</th>
                              <th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">口座状況</font></a>	</th>
                              <th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">返金対象</font></a></th>
                              <th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">申請受付連絡日</font></a></th>
                              <th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">返金日</font></a></th>
                              <th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">返金連絡日</font></a></th>
                              <th class="table-header-repeat line-left minwidth-1"><a href=""><font size="-2">オプション</font></a></th>
                            </tr>
                            <?php $i = 1; while ( $row = $excute->fetch_assoc()): $i++ ?>

                              <tr <?php echo ($i % 2)? 'class="alternate-row"' : '' ?>>
                                <td class="i__td-first"><input type="checkbox" name="checks[]" value="<?php echo htmlspecialchars($row['id']) ?>"></td>
                                <td><?php echo ($row['saki_contract_date'] == '0000-00-00')? '-' : htmlspecialchars($row['saki_contract_date']) ?></td>
                                <td><?php echo htmlspecialchars($row['saki_no']) ?></td>
                                <td title="<?php echo htmlspecialchars($row['saki_name']) ?>">
                                  <!-- <a rel="facebox" href="../customer/mini.php?id=<?php echo htmlspecialchars($row['introducer_customer_id']) ?>"> -->
                                    <?php echo ($row['saki_name_kana'])? '<a href="/admin/customer/index.php?customer_id='.htmlspecialchars($row['introducer_customer_id']).'" target="_blank">'. htmlspecialchars($row['saki_name_kana']).'</a>' : ($row['saki_name'])? '<a href="/admin/customer/index.php?customer_id='.htmlspecialchars($row['introducer_customer_id']).'" target="_blank">'. htmlspecialchars($row['saki_name']).'</a>' : '無名' ?>
                                  <!-- </a> -->
                                </td>
                                <td class="i__td-first"></td>
                                <td><?php echo htmlspecialchars($row['moto_no']) ?></td>
                                <td title="<?php echo htmlspecialchars($row['moto_name']) ?>">
                                  <!-- <a rel="facebox" href="../customer/mini.php?id=<?php echo htmlspecialchars($row['customer_id']) ?>"> -->
                                    <?php echo ($row['moto_name_kana'])? '<a href="/admin/customer/index.php?customer_id='.htmlspecialchars($row['customer_id']).'" target="_blank">'. htmlspecialchars($row['moto_name_kana']).'</a>' : ($row['moto_name'])? '<a href="/admin/customer/index.php?customer_id='.htmlspecialchars($row['customer_id']).'" target="_blank">'. htmlspecialchars($row['moto_name']).'</a>'  : '無名' ?>
                                  <!-- </a> -->
                                </td>
                                <td data-debug="<?php echo htmlspecialchars($row['moto_bank_status']) ?>">
                                  <?php echo ($row['moto_bank_status'] != null && $row['moto_bank_status'] >= 0)? $labelBankState[$row['moto_bank_status']] : '登録なし' ?>
                                </td>
                                <td data-debug="<?php echo htmlspecialchars($row['saki_contract_r_times'].'-'.$row['saki_contract_status'].'-'.$row['moto_contract_r_times'].'-'.$row['moto_contract_status']) ?>">
                                  <?php
                                  // 消化回数・月額来店回数が存在し0以上かつ両顧客の契約ステータスが存在しどちらも1,2,3,6ではない
                                  if ($row['saki_contract_r_times'] !== null && $row['saki_contract_status'] !== null && $row['moto_contract_status']  !== null &&
                                      $row['saki_contract_r_times'] > 0 && $row['moto_contract_r_times'] > 0 && !preg_match("/^[1236]{1}$/", $row['saki_contract_status']) && !preg_match("/^[1236]{1}$/", $row['moto_contract_status'])):?>
                                    対象
                                  <?php
                                  // 消化回数・月額来店回数が存在し0回かつ両顧客の契約ステータスが存在しどちらも1,2,3,6ではない
                                  elseif($row['saki_contract_r_times'] !== null && $row['saki_contract_status'] !== null && $row['moto_contract_status']  !== null &&
                                         ($row['saki_contract_r_times'] == 0 || $row['moto_contract_r_times'] == 0) && !preg_match("/^[1236]{1}$/", $row['saki_contract_status']) && !preg_match("/^[1236]{1}$/", $row['moto_contract_status'])): ?>
                                    対象外（施術なし）
                                  <?php
                                  // 消化回数・月額来店回数が存在しないまたは両顧客の契約ステータスがどちらも存在しないまたは両顧客の契約ステータスのどちらかが1,2,3,6である
                                  elseif($row['saki_contract_r_times'] == null || $row['saki_contract_status'] == null || $row['moto_contract_r_times'] == null || $row['moto_contract_status'] == null ||
                                    preg_match("/^[1236]{1}$/", $row['moto_contract_status']) || preg_match("/^[1236]{1}$/", $row['saki_contract_status'])): ?>
                                    対象外（退会済み）
                                  <?php endif ?>
                                </td>
                                <td><?php echo ($row['refund_request'] == '0000-00-00')? '-' : htmlspecialchars($row['refund_request']) ?></td>
                                <td><?php echo ($row['refund_date'] == '0000-00-00')? '-' : htmlspecialchars($row['refund_date']) ?></td>
                                <td><?php echo ($row['refund_contact'] == '0000-00-00')? '-' : htmlspecialchars($row['refund_contact']) ?></td>
                                  <td>
                                    <a class="icon-1 info-tooltip" title="紹介先/紹介元詳細" href="edit.php?id=<?php echo $row['id'] ?>"></a>
                                    <?php if($authority_view === 'vielis'){ ?>
                                    <a class="icon-2 info-tooltip" title="仮削除" href="index.php?action=delete&id=<?php echo $row['id'] ?>" onclick="return confirm('仮削除しますか？')"></a>
                                    <?php } ?>
                                  </td>
                              </tr>
                            <?php endwhile ?>
                          </table>
                      </div>

                      <div class="i__row">
                        <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
                          <tr>
                            <td>
                            <?php Print_List_Menu( $dStart , $dLine_Max , $dGet_Cnt ); ?>
                            </td>
                          </tr>
                        </table>
                      </div>

                    <!--<< CONTENT -->

                  </td>
                </tr>
                <tr><td><img src="../images/shared/blank.gif" width="695" height="1" alt="blank" /></td></tr>
              </table>
              <div class="clear"></div>
            </div>
            <!--  end content-table-inner  -->
            </form>
          </td>
          <td id="tbl-border-right"></td>
        </tr>
        <tr>
          <th class="sized bottomleft"></th>
          <td id="tbl-border-bottom">&nbsp;</td>
          <th class="sized bottomright"></th>
        </tr>
      </table>
    <?php } ?>
    <div class="clear">&nbsp;</div>
  </div>
  <!--  end content -->
  <div class="clear">&nbsp;</div>
</div>
<?php include_once("../include/footer.html"); ?>