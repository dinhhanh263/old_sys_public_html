<?php include_once("../library/contract/index.php"); ?>
<?php include_once("../include/header_menu.html"); ?>

<script type="text/javascript" src="../js/chosen/chosen.jquery.js"></script>
<link href="../js/chosen/chosen.css" media="screen" rel="stylesheet" type="text/css"/>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('a[rel*=facebox]').facebox()
	})
</script>
<script type="text/javascript">
    function change_bad_debt_flg(num) {
		let contract_id = document.getElementById(`contract_id_${num}`).value;
		let url = '/admin/contract/change_bad_debt_flg.php';

	        $.ajax({
                url:url,
                type:'POST',
                data:{
					'bad_debt_flg':$(`#bad_debt_flg_${num}`).val(),
					'contract_id':contract_id
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
					if(data==contract_id) {
						alert("登録に成功しました。");
					} else {
						alert("登録に失敗しました。時間をおいてもう一度お試しください");
					}

                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                    alert("登録に失敗しました。時間をおいてもう一度お試しください");
                })
                // Ajaxリクエストが成功・失敗どちらでも発動
                .always( (data) => {

			});
		}

	function change_terminate_pending_status(num) {
		let contract_id = document.getElementById(`contract_id_${num}`).value;
		let url = '/admin/contract/change_terminate_pending_status.php';

	        $.ajax({
                url:url,
                type:'POST',
                data:{
					'terminate_pending_status':$(`#terminate_pending_status_${num}`).val(),
					'contract_id':contract_id
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
                    alert("登録に成功しました。");

                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                    alert("登録に失敗しました。時間をおいてもう一度お試しください");
                })
                // Ajaxリクエストが成功・失敗どちらでも発動
                .always( (data) => {

            });
		}

	function change_loan_delay_flg(num) {
		let contract_id = document.getElementById(`contract_id_${num}`).value;
		let url = '/admin/contract/change_loan_delay_flg.php';

	        $.ajax({
                url:url,
                type:'POST',
                data:{
					'loan_delay_flg':$(`#loan_delay_flg_${num}`).val(),
					'contract_id':contract_id
                    }
                })
                // Ajaxリクエストが成功した時発動
                .done( (data) => {
                    alert("登録に成功しました。");

                })
                // Ajaxリクエストが失敗した時発動
                .fail( (data) => {
                    alert("登録に失敗しました。時間をおいてもう一度お試しください");
                })
                // Ajaxリクエストが成功・失敗どちらでも発動
                .always( (data) => {

            });
	}

	function regist_finance_request(num) {
		let contract_id = document.getElementById(`contract_id_${num}`).value;
		let customer_id = document.getElementById(`customer_id`).value;
		let shop_id = document.getElementById(`shop_id`).value;
		let url = '/admin/contract/regist_finance_request.php';
		var result = confirm("登録して宜しいですか？");
		if (result) {

	        $.ajax({
                url:url,
                type:'POST',
                data:{
					'cc_request_status':$(`#cc_request_status_${num}`).val(),
					'shop_request_status':$(`#shop_request_status_${num}`).val(),
					'contract_id':contract_id,
					'customer_id':customer_id,
					'shop_id':shop_id
                    }
				})
					// Ajaxリクエストが成功した時発動
					.done( (data) => {
							alert("登録に成功しました。");
							location.href = "../reservation/cc_request.php?request_id=" + data;
          			})
					// Ajaxリクエストが失敗した時発動
					.fail( (data) => {
							alert("登録に失敗しました。時間をおいてもう一度お試しください");
					})
					// Ajaxリクエストが成功・失敗どちらでも発動
					.always( (data) => {

				});
		}
	}
    function introducer_staff(num) {
        let result = confirm("登録して宜しいですか？");
        if (result) {
            let contract_id = document.getElementById(`contract_id_${num}`).value;
            let url = '/admin/contract/change_introducer_staff.php';
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    'introducer_staff_id': $(`#introducer_staff_id_${num}`).val(),
                    'contract_id': contract_id
                }
            })
                // Ajaxリクエストが成功した時発動
                .done((data) => {
                    alert("登録に成功しました。");
                })
                // Ajaxリクエストが失敗した時発動
                .fail((data) => {
                    alert("登録に失敗しました。時間をおいてもう一度お試しください");
                })
                // Ajaxリクエストが成功・失敗どちらでも発動
                .always((data) => {
                });
        }
    }

	$(function(){
		$('.styledselect_form_3').change(
			function() {
				if ($(this).val() != "") {
					location.href = $(this).val();
				}
			}
		);
	});
    $jQuery341(function() {
        //プラグイン(Chosen)を有効化
        $jQuery341('.chosen-select').chosen();
    });
</script>


<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
	<!-- start content -->
	<div id="contract-content">
		<!--  start page-heading -->
		<div id="page-heading">
			<h1>契約一覧</h1>
		</div>
		<td>
			<dl class="w550 contract_head">
				<dt class="regster_title">会員番号</dt>
				<dd class="regster_cont"><?php echo $customer['no'];?></dd>
				<dt class="regster_title">お客様名</dt>
				<dd class="regster_cont"><a href="../customer/index.php?customer_id=<?php echo $customer['id'];?>" title="顧客概要へ" class="side"><?php echo $customer['name_kana'] ? $customer['name_kana'] : ($customer['name'] ? $customer['name'] : "") ?></a></dd>
			</dl>
		</td>
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
							<form id="mainform" action="" name="mainform">
								<input name="customer_id" type="hidden" id="customer_id" value="<?php echo $customer['id']; ?>">
								<?php echo $gMsg;?>
								<!-- <table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table"> -->
									<?php
									if ($dRtn3->num_rows >= 1) {
										$i = 1;
										$before_treatment_type = "none"; // 1つ前の契約の施術種別
										while ($data = $dRtn3->fetch_assoc()) {
											$course = Get_Table_Row("course", " WHERE del_flg=0 and id = " . addslashes($data['course_id']));
											$shop = Get_Table_Row("shop", " WHERE del_flg=0 and id = " . addslashes($data['shop_id']));
											$reservation = Get_Table_Row("reservation", " WHERE del_flg=0 and customer_id = '" . addslashes($data['customer_id']) . "' and contract_id ='" .addslashes($data['id'])."' order by hope_date desc,id desc limit 1");
											$finance_request_items = Get_Table_Row("request_items"," WHERE (type=1 OR type=2) AND del_flg=0 AND end_flg=0 AND contract_id ='" . addslashes($data['id']) . "' ORDER BY id DESC LIMIT 1");

											// 旧契約情報取得
											if ($data['old_contract_id'] != 0 && $data['conversion_flg'] == 0) {
												$old_contract = Get_Table_Row("contract", " WHERE customer_id <>0 AND del_flg=0 AND status=4 AND id='" . addslashes($data['old_contract_id']) . "'");
												$old_course =  Get_Table_Row("course", " WHERE del_flg=0 AND id='" . addslashes($old_contract['course_id']) . "'");
											}

											// 無料プラン付与可能期間を算出
											$base_end_date = !is_null($data['extension_end_date']) ? $data['extension_end_date'] : $data['end_date']; // 付与元の回数保証期間終了日
											$grant_base_date = ($data['latest_date'] != "0000-00-00" && $data['latest_date'] < $base_end_date && $data['status'] != 9) ? $data['latest_date'] : $base_end_date; // 付与基準日(最終消化日or回数保証期間終了日の早い方)
											$option_end_date = date("Y-m-d", strtotime($free_plan_end_days, strtotime($grant_base_date))); // 無料プラン付与可能期間

											// 最終仕上げプランかどうか判別
											$finish_flg = false;
											if ($course['group_id'] == 17) $finish_flg = true;
											elseif (!is_null($course['old_course_id'])) {
												$old_course = Get_Table_Row("course", " WHERE del_flg=0 and id = '".addslashes($course['old_course_id'])."'"); // 返金保証回数終了プランの場合、移行前のコース情報を取得
												if ($old_course['group_id'] == 17) $finish_flg = true;
											}

											// 施術種別ごとに表を作成
											if ($before_treatment_type != "none" && $course['treatment_type'] != $before_treatment_type) echo '</table>';
											if ($course['treatment_type'] != $before_treatment_type) {
												echo "<h2>" . $gtreatmentType[$course['treatment_type']] . "</h2>";
												?>
												<div class="sc-table">
												  <table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
														<tbody>
												<tr>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">契約ID</font>
														</a> </th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">前契約ID</font>
														</a> </th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">オプション<br />　契約ID</font>
														</a> </th>
													<!-- <th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">施術種別</font>
														</a> </th> -->
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">コース名</font>
														</a> </th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">契約回数</font>
														</a> </th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">消化回数</font>
														</a> </th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">ステータス</font>
														</a> </th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">契約日</font>
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">返金期間</font>
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">回数保証期間</font>
														</a></th>
													<!-- <th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">契約レジ</font>
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">契約書</font>
														</a></th> -->
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">契約レジ</font>
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">契約書類</font>
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">売掛金</font>
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">ローン金額
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">ローンステータス
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">ローン申込
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">契約店舗
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">次回予約取得</font>
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">貸し倒れ</font>
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">解約保留</font>
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">ローン延滞</font>
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">経理依頼事項</font>
														</a></th>
													<th class="table-header-repeat line-left minwidth-1"><a href="">
															<font size="-2">紹介元スタッフ</font>
														</a></th>
													<th class="table-header-<?php echo ($authority_level <= 1) ? "repeat" : "repeat" ?> line-left"><a href="">
															<font size="-2">本社処理</font>
														</a></th>
													<th class="table-header-repeat line-left"><a href="">
															<font size="-2">オプション</font>
														</a></th>
												</tr>
											<?php }
											$before_treatment_type = $course['treatment_type'];

											// ローン会社リスト----------------------------------------------------------------------------
											$loan_application_url_flg = false;
											$loan_apl_flg = 0;
											if ($data['loan_company_id']) {
												$loan_company_list = getDatalist("loan_company");

												$loan_info_id = '';
												$loan_info_id = Get_Table_Col("loan_info", "id", " WHERE del_flg=0 AND contract_id=" . $data['id']);
												if (!$loan_info_id) {
													$loan_info2_id = Get_Table_Col("loan_info2", "id", " WHERE del_flg=0 AND contract_id=" . $data['id']);
												}
												// ローンベリファイ確認状態
												$loan_verify_status = '';
												if ($loan_info_id) {
													$loan_verify_status = Get_Table_Col("loan_info", "verify_status", " WHERE del_flg=0 AND id=" . $loan_info_id);
													$loan_apl_flg = Get_Table_Col("loan_info", "apl_flg", " WHERE del_flg=0 AND id=" . $loan_info_id);
												} elseif ($loan_info2_id) {
													$loan_apl_flg = Get_Table_Col("loan_info2", "apl_flg", " WHERE del_flg=0 AND id=" . $loan_info2_id);
												}
												if($data['loan_company_id']==6) {
													$loan_application_url ="/admin/service/loan_application.php";
													$loan_application_url_flg=true;
                                                } else if($data['loan_company_id']==8) {
													$loan_application_url ='/admin/service/loan_application2.php';
													$loan_application_url_flg = true;
												}
											}

											// 契約書パス----------------------------------------------------------------------------
											$contract_pdf_name;
												// 旧通いホーダイ契約書（通いホーダイ文言あり）
											if ($data['course_id'] == 76 || $data['course_id'] == 77) {
												$contract_pdf_name = "pdf_out2_kayoi.php";
												// 旧月額コースの方の契約書
											} elseif ($course['type'] == 1 && $course['new_flg'] == 0) {
												$contract_pdf_name = "pdf_out2_old_monthly.php";
												// キレイモ全身脱毛新プランと平日とく得新プラン
											} elseif (($course['group_id']==15 || $course['group_id']==16 || $course['group_id']==17) && $course['sales_start_date'] >= '2020-10-01') {
												$contract_pdf_name = "pdf_out9.php";
												// 新パックコースと平日とく得コース(1年・2年・スペシャルプラン)
											} elseif ($course['interval_date'] != null && $course['sales_start_date'] >= '2019-11-06' && $course['treatment_type'] == 0) {
												$contract_pdf_name = "pdf_out7.php";
												// キレイモ全身脱毛月額定額制プランとU-19応援プラン
											} elseif ($course['id'] == 92 || $course['id'] == 102) {
												$contract_pdf_name = "pdf_out8.php";
												// 新月額・パックの新しい契約書、SPプラン(通いホーダイ)文言追記 ※パック・新月額同じ契約書に変更されました。
											} else {
												$contract_pdf_name = "pdf_out2.php";
											}
												// エステ
											if ($course['treatment_type'] == 1) {
												$contract_pdf_name = "pdf_out_esthetic.php";
											}
												// 整体
											if ($course['treatment_type'] == 2) {
												$contract_pdf_name = "pdf_out_esthetic.php";
											}

											// プラン組替通知書パス----------------------------------------------------------------------------
											$conversion_pdf_name;
											if (($course['group_id']==15 || $course['group_id']==16) && $course['sales_start_date'] >= '2020-10-01') {
												$conversion_pdf_name = "pdf_out_conversion3.php";
											} elseif ($course['interval_date'] != null && $course['sales_start_date'] >= '2019-11-06') {
												$conversion_pdf_name = "pdf_out_conversion2.php";
											} else {
												$conversion_pdf_name = "pdf_out_conversion.php";
											}

											echo '<tr' . ($data['status'] == 0 ? ' class="under-contract-row"' : '') . '">';
											if ($reservation['id']) {
												echo '<td id="contract_id" title="契約ID"><a href="/admin/reservation/edit.php?reservation_id=' . $reservation['id'] . '">' . ($data['id']) . '</a></td>';
											} else {
												echo '<td id="contract_id">' . ($data['id']) . '</td>';

											}
											echo  '<input name="contract_id_'.$i.'" type="hidden" id="contract_id_'.$i.'" value="'.$data['id'] .'">';
											if ($data['old_contract_id'] != 0) {
												echo '<td>' . $data['old_contract_id'] . '</td>';
											} elseif ($data['loan_cancel_before_contract_id'] != 0) {
												echo '<td>' . $data['loan_cancel_before_contract_id'] . '</td>';
											} else {
												echo '<td>-</td>';
											}
											if ($data['option_contract_id'] != 0) {
												echo '<td>' . $data['option_contract_id'] . '</td>';
											} else {
												echo '<td>-</td>';
											}
											// echo 	'<td>' . ($gtreatmentType[$course['treatment_type']]) . '</td>';
											echo 	'<td>' . ($course['name']) . '</td>';
											echo 	'<td style="width:40px;">' . ($course['type'] == 0 ? $data['times'] : '-') . '</td>';
											echo 	'<td style="width:40px;">' . $data['r_times'] . '</td>';
											echo 	'<td style="width:50px;">';
											if ($data['status'] == 4 && $data['conversion_flg'] == 1) {
												echo 'プラン組替</td>';
											} elseif ($course['type'] == 1) {
												echo $gContractStatus3[$data['status']] . '</td>';
											} else {
												echo $gContractStatus[$data['status']] . '</td>';
											}
											echo 	'<td>' . $data['contract_date'] . '</td>';
											echo 	'<td>' . (($course['type'] == 0 && $data['end_date'] != '0000-00-00') ? $data['end_date'] : '-') . '</td>';
											echo 	'<td>' . (($course['type'] == 0 && $course['zero_flg'] == 0 && $data['extension_end_date']) ? $data['extension_end_date'] : '-') . '</td>';
											// echo 	'<td style="width:40px; title="レジ詳細"><a href="/admin/account/reg_detail.php?id=' . $data['reservation_id'] . '">詳細</a></td>';
											// echo 	'<td style="width:40px; title="契約書詳細"><a href="/' . $contract_pdf_name . '?contract_id=' . $data['id'] . '">詳細</a></td>';
											// idをsalesに変更する

											// 契約レジ
											echo '<td style="width:50px;" title="契約レジ">';
											// 予約IDが紐付いている場合、契約レジのリンクを表示
											if ($course['id'] < 999 && $data['status'] != 7 && $data['wait_flg'] == 0 && $data['reservation_id'] != 0) {
												echo '<a href="/admin/account/reg_detail.php?reservation_id=' . $data['reservation_id'] . '">レジ</a></td>';
											} else {
												echo '-</td>';
											}

											// 契約書類
											echo '<td style="width:50px;" title="契約リンク">';
											// 以下のいずれかの場合、契約リンク非表示
											// 返金保証回数終了プラン、ステータスが契約待ち、ローン取消後に契約中を経由せずに契約待ちから別のステータス(プラン変更や中途解約)に移行
											if ($course['id'] >= 999 || $data['status'] == 7 || ($data['wait_flg'] == 1 && $data['pay_complete_date'] == "0000-00-00" && $data['loan_application_date'] == "0000-00-00")) {
												echo '-</td>';
											// プラン組替後の役務の場合、プラン組替通知書のリンクを表示
											} elseif ($data['conversion_flg'] == 1 && ($data['new_course_id'] == 0 || $data['status'] == 8)) {
												echo '<a href="/admin/pdf/' . $conversion_pdf_name . '?contract_id=' . $data['id'] . '" target="_blank">組替通知書</a></td>';
											// 上記以外の場合、契約書のリンクを表示
											} else {
												echo '<a href="/admin/pdf/' . $contract_pdf_name . '?contract_id=' . $data['id'] . '" target="_blank">契約書</a></td>';
											}

											echo 	'<td style="width:35px;">' . $data['balance'] . '</td>';

											// ローン関連
											// if ($authority_level <= 6 || ($authority['id'] == 106 || $authority['id'] == 1449) && $_POST['ctype'] < 2) {
												echo 	'<td style="width:45px;">' . $data['payment_loan'] . '</td>';
                                                if ($data['payment_loan']) {
																									if ($authority_level <> 17 && $data['loan_status'] != 4) {
													echo '<td id="connfirm_loan" title="ローンステータス"><a rel="facebox" href="/admin/service/confirm_loan.php?contract_id='. $data['id'] .'" class="side">' .$gLoanStatus[$data['loan_status']] . '</a></td>';
													// echo    '<a rel="facebox" href="/admin/service/confirm_loan.php?contract_id= '. $data['id'] .' onclick="return confirm(\'ローン承認処理をしますか？\') class="icon-2 info-tooltip">'. $gLoanContractStatus[$data['loan_status']] .</a>';
																										// echo 	'<td style="width:45px;">' . $gLoanContractStatus[$data['loan_status']] . '</td>';
																									} else {
																										echo '<td id="connfirm_loan" title="ローンステータス">' .$gLoanStatus[$data['loan_status']] . '</td>';
																									}
                                                } else {
													echo '<td style="width:45px;">-</td>';
												}
												if($loan_application_url_flg) {
													echo '<td style="width:45px;" title="ローン申込"><a href="'.$loan_application_url.'?contract_id='.$data['id'].'";">';
													if($loan_apl_flg)  {
														echo '(済)</a></td>';
													} else {
														echo '未</a></td>';
													}
												} else {
													echo '<td style="width:45px;">-</td>';
												}
											echo '<input name="shop_id" type="hidden" id="shop_id" value='.$data['shop_id'].'">';
											echo 	'<td style="width:40px;">' . $shop['name'] . '</td>';

											// 次回施術予約
											echo '<td style="width:40px;">';
												echo '<select class="styledselect_form_3">';
													echo '<option value=""> 予約タイプを選択 </option>';
													Print_Select_List_Next_Reserve($course, $data, $data['shop_id'], $cooling_off_flg);
												echo '</select>';
											echo '</td>';

											echo 	'<td style="width:40px;"><select name="bad_debt_flg" class="styledselect_form_5" id="bad_debt_flg_'.$i.'" onchange=change_bad_debt_flg('.$i.')>';
											Reset_Select_Key( $gBadDebtFlg , $data['bad_debt_flg']);
											echo    '</select></td>';
											echo    '<td style="width:40px;"><select name="terminate_pending_status" class="styledselect_form_6" id="terminate_pending_status_'.$i.'" onchange=change_terminate_pending_status('.$i.')>';
											Reset_Select_Key( $gTerminatePendingStatus , $data['terminate_pending_status']);
											echo    '</select></td>';
											echo    '<td style="width:40px;"><select name="loan_delay_flg" class="styledselect_form_6" id="loan_delay_flg_'.$i.'" onchange=change_loan_delay_flg('.$i.')>';
											Reset_Select_Key( $gLoanNG3 , $data['loan_delay_flg']);
											echo    '</select></td>';
											echo '<td style="width:40px;padding-right:40px;">';
											if ($authority_level <= 6) {
												echo '<select name="cc_request_status" class="styledselect_form_6" id="cc_request_status_'.$i.'" onchange=regist_finance_request('.$i.')>';
												Reset_Select_Key( $gCCRequest , 0);
												echo '</select>';
											} else if($authority_level == 17) {
												echo '<select name="shop_request_status" class="styledselect_form_6" id="shop_request_status_'.$i.'" onchange=regist_finance_request('.$i.')>';
												Reset_Select_Key( $gShopRequest , 0);
												echo '</select>';
											}
											echo '<a  rel="facebox" href="/admin/contract/finance_mini.php?contract_id=' . $data['id'] . '" title="依頼事項履歴" class="icon-history info-tooltip" style="float:right;margin:0 -30px 0 0;"></a>';
											echo '</td>';
											// 紹介元スタッフ
											echo	'<td style="width:40px;"><select style="display:none width: 350px;" data-placeholder="-" tabindex="-1" name="introducer_staff_id" class="chosen-select"  id="introducer_staff_id_'.$i.'" onchange=introducer_staff('.$i.')>';
											Reset_Select_Array_Group2(getStafflistArray2("staff", "shop_id", $data['contract_date']), $data['introducer_staff_id'], getDatalist5("shop", $authority_shop['id'] ? $authority_shop['id'] : $data['shop_id']));
											echo    '</select></td>';

											// 本社処理
											echo 	'<td style="width:80px;">';
											if ($authority_level <= 6) {
												// echo 		'<a rel="facebox" href="/admin/customer/mini.php?contract_id=' . $data['id'] .'" title="予約一覧" class="icon-1 info-tooltip"></a>';
												if (($data['status'] == 0 || $data['status'] == 7) && $course['id'] < 999) {
													echo '<a href="/admin/reservation/cal.php?contract_id=' . $data['id'] . '" title="レジ電卓(解約用)" class="icon-cal info-tooltip" target="_blank"></a>';
												}
												if (($data['status'] == 0 || $data['status'] == 7) && $course['id'] < 999 && $data['payment_loan'] == 0) {
														echo 		'<a href="/admin/service/cooling_off.php?contract_id=' . $data['id'] . '" onclick="return confirm(\'クーリングオフしますか？\')" title="クーリングオフ" class="icon-cooling_off info-tooltip"></a>';
												} else if($data['status'] == 2){
												echo 		'<a href="/admin/service/cooling_off.php?contract_id=' . $data['id'] . '" title="クーリングオフ" class="icon-cooling_off info-tooltip"></a>';
												}
												// echo 		'<a href="index.php?action=send&id='.$data['id'].'" onclick="return confirm(\'マイページ情報を送信しますか？\')" title="マイページ情報送信" class="icon-3 info-tooltip"></a>';
												// echo 		'<a href="../reservation/edit.php?mode=new_rsv&customer_id='.$data['id'].'&shop_id='.$rsv['shop_id'].'" onclick="return confirm(\'次の予約をしますか？\')" title="次の予約" class="icon-5 info-tooltip"></a>';
												// echo 		'<a href="../account/reg_detail.php?id='.$rsv['id'].'&shop_id='.$rsv['shop_id'].'" onclick="return confirm(\'レジ精算に移動しますか？\')" title="レジ精算" class="icon-4 info-tooltip"></a>';
												if (($data['status'] == 0 || $data['status'] == 7) && $course['id'] < 999 && $data['payment_loan'] == 0) {
													echo 		'<a href="/admin/service/cancel_detail.php?contract_id=' . $data['id'] . '" onclick="return confirm(\'中途解約処理をしますか？\')" title="中途解約処理" class="icon-cancel_detail info-tooltip"></a>';
												} else if($data['status'] == 3){
													echo 		'<a href="/admin/service/cancel_detail.php?contract_id=' . $data['id'] . '" title="中途解約処理" class="icon-cancel_detail info-tooltip"></a>';
												}
												if ($data['payment_loan'] > 0) echo '<a href="/admin/service/cancel_loan.php?contract_id=' . $data['id'] . '" title="ローン取消" class="icon-cancel_loan info-tooltip"></a>'; // ローン取消リンク
												if ($course['type'] == 1) echo '<a href="/admin/service/detail.php?contract_id=' . $data['id'] . '&type=8" title="月額支払" class="icon-monthly info-tooltip"></a>'; // 月額支払リンク
												echo '<a href="/admin/service/detail.php?contract_id=' . $data['id'] . '&type=11" title="その他新規精算" class="icon-other info-tooltip"></a>'; // その他精算リンク
											}
											echo 	'</td>';

											// オプション
											echo 	'<td style="width:80px;">';
											echo        '<a  rel="facebox" href="/admin/contract/mini.php?contract_id=' . $data['id'] . '" title="予約履歴" class="icon-history info-tooltip"></a>';
											if ($course['id'] < 999 && $course['treatment_type'] == 0 && ($data['status'] == 0 || $data['status'] == 7) && !($course['interval_date'] != null && $course['sales_start_date'] >= '2019-11-06' && date("Y-m-d") > $data['end_date']) && !$cooling_off_flg && !$finish_flg) {
												if ((($course['type'] == 0 && $data['r_times'] < $data['times']) || $course['type'] == 1) && $data['conversion_flg'] == 0) {
													echo '<a href="/admin/service/change.php?nosubmit_flg=1&contract_id=' . $data['id'] . '" title="プラン変更(金額確認用)" class="icon-change info-tooltip"></a>';
												}
												if ($course['type'] == 0 && $data['r_times'] < $data['times'] && !(isset($old_contract) && isset($old_course) && $old_course['type'] == 0)) {
													echo '<a href="/admin/service/conversion.php?nosubmit_flg=1&contract_id=' . $data['id'] . '" title="プラン組替(金額確認用)" class="icon-conversion info-tooltip"></a>';
												}
											}
											if ($data['option_contract_id'] == 0) {
												if ($course['type'] == 0 && $course['group_id'] != 11 && $course['group_id'] != 80 && $course['period'] != 0 && $course['zero_flg'] != 1 && $course['treatment_type'] == 0) {
													if (($data['status'] == 0 || $data['status'] == 1 || $data['status'] == 9) && ($data['r_times'] >= $data['times'] || ($data['status'] == 9 && $course['id'] > 1000)) && $option_end_date >= date("Y-m-d") && !$finish_flg) {
														echo '<a href="/admin/account/reg_detail.php?free_flg=1&base_contract_id=' . $data['id'] . '" title="無料プラン付与" class="icon-muryo info-tooltip"></a>';
													}
												}
											}
											echo        '<a  rel="facebox" href="/admin/contract/memo_mini.php?customer_id=' . $data['customer_id'] . '" title="備考" class="icon-memo info-tooltip"></a>';
											if ($course['type'] == 1) echo '<a  rel="facebox" href="/admin/contract/pay_monthly.php?customer_id=' . $data['customer_id'] . '&contract_id=' . $data['id'] . '" title="月額支払履歴" class="icon-pay_monthly info-tooltip"></a>';
											echo    '</td>';
											echo '</tr>';
											$i++;
										}
										echo '</tbody></table>';
									} else {
										echo "<font color='red' size='-1'>※契約がありません。</font>";
									}
									?>
								</div>
								<!--  end product-table................................... -->
							</form>
						</div>
						<!--  end content-table  -->
						<!--  start paging..................................................... -->
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
<script>
    $jQuery341(".chosen-select").chosen({search_contains:true});
    (function($jQuery341){
        $jQuery341('.chosen-search-input').on('compositionend', function() {
            setTimeout(function() {
                var text = $jQuery341(".chosen-search-input").val();
                $jQuery341(".chosen-select").trigger('chosen:close');
                $jQuery341(".chosen-search-input").val(text);
                $jQuery341(".chosen-select").trigger('chosen:open');
            },0);
        });
    })($jQuery341);
</script>
<!--  end content-outer........................................................END -->
<?php include_once("../include/footer.html"); ?>
