<?php include_once("../library/invite/edit.php"); ?>
<?php include_once("../include/header_menu.html"); ?>
</form>
<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox();
  $('input[name=refund_date],input[name=refund_request],input[name=refund_contact]').datepicker({
    duration: 'slow',
    dateFormat: 'yy-mm-dd'
  })
});
</script>
<link rel="stylesheet" href="css/style.css">
<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
  <div id="content">
    <div id="page-heading">
      <h1>お友達紹介キャンペーン 紹介先/紹介元詳細</h1>
      <div>※このページはCCまたは本社スタッフのみ閲覧可能です。</div>
    </div>
    <?php if($authority_view === 'vielis' || $authority_view === 'cc_staff'){ ?>

      <?php if($authority_view === 'cc_staff'){ ?>
        <fieldset disabled="disabled">
      <?php } ?>
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
                    <!-- CONTENT >>-->
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
                      <form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
                        <input type="hidden" name="action" value="edit">
                        <div class="i__grid">
                          <div class="i__grid-half">
                            <div class="i__heading">
                              <h2>紹介先顧客情報</h2>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $data["customer_id"];?>" />
                            <table border="0" cellpadding="0" cellspacing="0"  id="id-form">
                              <tr>
                                <th valign="top">会員番号:</th>
                                <td><?php echo htmlspecialchars($data['join_saki_customer']['no']) ?></td>
                              </tr>
                              <tr>
                                <th valign="top">名前:</th>
                                <td>
                                  <div><?php echo htmlspecialchars($data['join_saki_customer']['name']) ?></div>
                                  <div>（<?php echo htmlspecialchars($data['join_saki_customer']['name_kana']) ?>）</div>
                                </td>
                              </tr>
                              <tr>
                                <th valign="top">店舗:</th>
                                <td><?php echo htmlspecialchars($shop_list[$data['join_saki_contract']['shop_id']]) ?></td>
                              </tr>
                              <tr>
                                <th valign="top">コース:</th>
                                <td><?php echo htmlspecialchars($course_list[$data['join_saki_contract']['course_id']]) ?></td>
                              </tr>
                              <tr>
                                <th valign="top">契約状況:</th>
                                <td><?php echo htmlspecialchars($status_list[$data['join_saki_contract']['status']]) ?></td>
                              </tr>
                              <!-- 「施術日」 -->
                              <tr>
                                <th valign="top">予約状況:</th>
                                <td></td>
                              </tr>
                              <tr>
                                <td colspan="2">
                                  <div class="i__scroll-box">
                                    <?php echo $rsv_html ?>
                                  </div>
                                </td>
                              </tr>
                            </table>
                          </div>
                          <div class="i__grid-half">
                            <div class="i__heading">
                              <h2>紹介元顧客情報</h2>
                            </div>
                            <table border="0" cellpadding="0" cellspacing="0"  id="id-form">
                              <tr>
                                <th valign="top">会員番号:</th>
                                <td><?php echo htmlspecialchars($data['join_moto_customer']['no']) ?></td>
                              </tr>
                              <tr>
                                <th valign="top">名前:</th>
                                <td>
                                  <div><?php echo htmlspecialchars($data['join_moto_customer']['name']) ?></div>
                                  <div>（<?php echo htmlspecialchars($data['join_moto_customer']['name_kana']) ?>）</div>
                                </td>
                              </tr>
                              <tr>
                                <th valign="top">電話番号:</th>
                                <td><input type="text" name="tel" value="<?php echo htmlspecialchars($data['join_moto_customer']['tel']) ?>" maxlength="63"></td>
                              </tr>
                              <tr>
                                <th valign="top">メールアドレス:</th>
                                <td><input type="text" name="mail" value="<?php echo htmlspecialchars($data['join_moto_customer']['mail']) ?>" maxlength="63"></td>
                              </tr>
                              <tr>
                                <th valign="top">契約日:</th>
                                <td><?php echo htmlspecialchars($data['join_moto_contract']['contract_date']) ?></td>
                              </tr>
                              <tr>
                                <th valign="top">コース:</th>
                                <td><?php echo htmlspecialchars($course_list[$data['join_moto_contract']['course_id']]) ?></td>
                              </tr>
                              <tr>
                                <th valign="top">契約状況:</th>
                                <td><?php echo htmlspecialchars($status_list[$data['join_moto_contract']['status']]) ?></td>
                              </tr>
                              <tr>
                                <td colspan="2" style="height: 20px;"> </td>
                              </tr>
                              <tr>
                                <th valign="top">返金対象:</th>
                                <td>
                                    <?php
                                    // 消化回数・月額来店回数が存在し0以上かつ両顧客の契約ステータスが存在しどちらも2,3,6ではない
                                    if ($data['join_saki_contract']['r_times'] !== null && $data['join_saki_contract']['status'] !== null && $data['join_moto_contract']['status']  !== null&&
                                        $data['join_saki_contract']['r_times'] > 0 && $data['join_moto_contract']['r_times'] > 0 && !preg_match("/^[1236]{1}$/", $data['join_saki_contract']['status']) && !preg_match("/^[1236]{1}$/", $data['join_moto_contract']['status'])):?>
                                      対象
                                    <?php
                                    // 消化回数・月額来店回数が存在し0回かつ両顧客の契約ステータスが存在しどちらも2,3,6ではない
                                    elseif($data['join_saki_contract']['r_times']!== null && $data['join_saki_contract']['status'] !== null && $data['join_moto_contract']['status']  !== null&&
                                           ($data['join_saki_contract']['r_times'] == 0 || $data['join_moto_contract']['r_times'] == 0) && !preg_match("/^[1236]{1}$/", $data['join_saki_contract']['status']) && !preg_match("/^[1236]{1}$/", $data['join_moto_contract']['status'])): ?>
                                      対象外（施術なし）
                                    <?php
                                    // 消化回数・月額来店回数が存在しないまたは両顧客の契約ステータスがどちらも存在しないまたは両顧客の契約ステータスのどちらかが2,3,6である
                                    elseif($data['join_saki_contract']['r_times'] == null || $data['join_saki_contract']['status'] == null || $data['join_moto_contract']['r_times'] == null || $data['join_moto_contract']['status'] == null ||
                                      preg_match("/^[1236]{1}$/", $data['join_saki_contract']['status']) || preg_match("/^[1236]{1}$/", $data['join_moto_contract']['status'])): ?>
                                      対象外（退会済み）
                                    <?php endif ?>
                                </td>
                              </tr>
                              <tr>
                                <th valign="top">返金額:</th>
                                <td>
                                <?php echo number_format($refund_list[$data['join_saki_contract']['course_id']]) ?></td>
                              </tr>
                              <tr>
                                <th valign="top">申請受付連絡日:</th>
                                <td><input type="text" name="refund_request" value="<?php echo ($data['refund_request'] !== "0000-00-00")? $data['refund_request'] : '' ?>" maxlength="10" readonly></td>
                              </tr>
                              <tr>
                                <th valign="top">返金日:</th>
                                <td><input type="text" name="refund_date" value="<?php echo ($data['refund_date'] !== "0000-00-00")? $data['refund_date'] : '' ?>" maxlength="10" readonly></td>
                              </tr>
                              <tr>
                                <th valign="top">返金連絡日:</th>
                                <td><input type="text" name="refund_contact" value="<?php echo ($data['refund_contact'] !== "0000-00-00")? $data['refund_contact'] : '' ?>" maxlength="10" readonly></td>
                              </tr>
                              <tr>
                                <td colspan="2" style="height: 10px;"> </td>
                              </tr>
                              <tr>
                                <th valign="top" colspan="2"><h2 class="i__lead">口座情報</h2></th>
                              </tr>
                              <tr>
                                <th valign="top">銀行名：</th>
                                <td><input type="text" name="bank_name" value="<?php echo htmlspecialchars($data['join_moto_bank']['bank_name']) ?>" maxlength="128"></td>
                              </tr>
                              <tr>
                                <th valign="top">支店名：</th>
                                <td><input type="text" name="bank_branch" value="<?php echo htmlspecialchars($data['join_moto_bank']['bank_branch']) ?>" maxlength="128"></td>
                              </tr>
                              <tr>
                                <th valign="top">口座種別：</th>
                                <td>
                                  <label class="i__label"><input type="radio" name="bank_account_type" value="1" <?php echo ($data['join_moto_bank']['bank_account_type'] == 1)? 'checked' : '' ?>>普通</label>
                                  <label class="i__label"><input type="radio" name="bank_account_type" value="2" <?php echo ($data['join_moto_bank']['bank_account_type'] == 2)? 'checked' : '' ?>>当座</label>
                                </td>
                              </tr>
                              <tr>
                                <th valign="top">口座番号：</th>
                                <td><input type="text" name="bank_account_no" value="<?php echo htmlspecialchars($data['join_moto_bank']['bank_account_no']) ?>" maxlength="7"></td>
                              </tr>
                              <tr>
                                <th valign="top">口座名義：</th>
                                <td><input type="text" name="bank_account_name" value="<?php echo htmlspecialchars($data['join_moto_bank']['bank_account_name']) ?>" maxlength="128"></td>
                              </tr>
                              <tr>
                                <th valign="top">口座状況：</th>
                                <td>
                                  <label class="i__label"><input type="radio" name="bank_status" value="0" <?php echo ($data['join_moto_bank']['status'] !== null && $data['join_moto_bank']['status'] == 0)? 'checked' : '' ?>>未確認</label>
                                  <label class="i__label"><input type="radio" name="bank_status" value="1" <?php echo ($data['join_moto_bank']['status'] == 1)? 'checked' : '' ?>>有効</label>
                                  <label class="i__label"><input type="radio" name="bank_status" value="2" <?php echo ($data['join_moto_bank']['status'] == 2)? 'checked' : '' ?>>無効</label>
                                </td>
                              </tr>
                            </table>
                          </div>
                        </div>

                        <?php if($authority_view === 'vielis'){ ?>
                          <input type="submit" value="Submit" class="form-submit" />
                          <input type="reset" value="Reset" class="form-reset" />
                        <?php } ?>

                      </form>

                    <!--<< CONTENT -->
                  </td>
                </tr>
                <tr><td><img src="../images/shared/blank.gif" width="695" height="1" alt="blank" /></td></tr>
              </table>
              <div class="clear"></div>
            </div>
            <!--  end content-table-inner  -->
          </td>
          <td id="tbl-border-right"></td>
        </tr>
        <tr>
          <th class="sized bottomleft"></th>
          <td id="tbl-border-bottom">&nbsp;</td>
          <th class="sized bottomright"></th>
        </tr>
      </table>
      <?php if($authority_view == 'cc_staff'){ ?>
        </fieldset>
      <?php } ?>
    <?php } ?>
  </div>
  <!--  end content -->
  <div class="clear">&nbsp;</div>
</div>
<?php include_once("../include/footer.html"); ?>