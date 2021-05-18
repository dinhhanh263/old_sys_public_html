<?php include_once("../library/job/edit.php");?>
<?php include_once("../include/header_menu.html");?>
<script src="../js/ajaxzip2/ajaxzip2.js" charset="UTF-8"></script>
<script type="text/javascript">
    AjaxZip2.JSONDATA = '../js/ajaxzip2/data';
</script>
</form>
<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>求人詳細</h1></div>
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
									<!-- start id-form -->
									<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
										<input type="hidden" name="action" value="edit" />
										<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
										<div id="inp-form">
											<ul class="registration-cnt">
												<li><!-- 区分 -->
													<span class="registration-ttl">採用対象：</span>
													<?php
														// if(TA_Cook($data['type']) > 7){ /* 8以上（店舗スタッフ以外） */
														// 	echo 'CC';
														// }else if(TA_Cook($data['type']) < 6){ /* 5以下（新卒） */
														// 	echo '<span class="registration-ttl2">新卒</span>'.$gGraduation[TA_Cook($data['type'])];
														// }else{ /* 6,7（中途） */
														// 	echo '<span class="registration-ttl2">中途</span>'.$gSkill[TA_Cook($data['type'])];
														// };

														// if(TA_Cook($data['type']) > 7){ /* 8以上（店舗スタッフ以外） */
														//	echo 'CC';
														// }else if(!TA_Cook($data['type'])){ /* スタッフ区分が存在しない */
														//	echo '店舗スタッフ応募'.$gSkill[TA_Cook($data['type'])];
														// }else if(TA_Cook($data['type']) < 6){ /* 5以下（新卒） */
														//	echo '<span class="registration-ttl2">新卒</span>'.$gGraduation[TA_Cook($data['type'])];
														// }else{ /* 6,7（中途） */
														//	echo '<span class="registration-ttl2">中途</span>'.$gSkill[TA_Cook($data['type'])];
														// };

														echo $gRecruitType[$data['type']];
													?>
												</li>
												<li><!-- セイメイ -->
													<span class="registration-ttl">名前（カナ）：</span>
													<div class="registration-box">
														<input type="text" name="entry_name_kana" value="<?php echo TA_Cook($data['entry_name_kana']) ;?>" class="registration-form w7" />
														<input type="text" name="entry_name_kana_2" value="<?php echo TA_Cook($data['entry_name_kana_2']) ;?>" class="registration-form w7" />
													</div>
												</li>
												<li><!-- 姓名 -->
													<span class="registration-ttl">名前（漢字）：</span>
													<div class="registration-box">
														<input type="text" name="entry_name" value="<?php echo TA_Cook($data['entry_name']) ;?>" class="registration-form w7" />
														<input type="text" name="entry_name_2" value="<?php echo TA_Cook($data['entry_name_2']) ;?>" class="registration-form w7" />
													</div>
												</li>
												<li><!-- 性別 -->
													<span class="registration-ttl">性別：</span>
													<!-- <?php echo $gSex[$data['sex']];?> -->
													<label class="registration-radio">
														<input type="radio" class="form-checkbox2" name="sex" value="2" <?php echo $data['sex']=="2" ? "checked" : "";?> />女性
													</label>
													<label class="registration-radio">
														<input type="radio" class="form-checkbox2" name="sex" value="1" <?php echo $data['sex']=="1" ? "checked" : "";?> />男性
													</label>
												</li>
												<li><!-- 生年月日 -->
													<span class="registration-ttl">生年月日：</span>
													<input type="text" name="birthday" value="<?php echo TA_Cook($data['birthday']) ;?>" class="registration-form" />
												</li>
												<li><!-- 年齢 -->
														<span class="registration-ttl">年齢：</span>
														<input type="text" name="age" value="<?php echo TA_Cook($data['age']) ;?>" class="registration-form w3" />
												</li>
												<li><!-- 出身校 -->
														<span class="registration-ttl">出身校：</span>
														<input type="text" name="school_name" value="<?php echo $data['school_name'];?>" class="registration-form" />
												</li>
												<li><!-- 卒業年月 -->
														<span class="registration-ttl">卒業年月：</span>
														<input type="text" name="age" value="<?php echo $data['graduation_ym'] ;?>" class="registration-form w7" />
												</li>
												<li>
													<span class="registration-ttl">住所：</span>
													<ul class="registration-list">
														<li>〒<input type="text" name="zip" value="<?php echo TA_Cook($data['zip']) ;?>" class="registration-form w7" /></li><!-- 郵便番号 -->
														<li class="registration-list-sub">
															<select name="pref" class="registration-form" ><?php Reset_Select_Key($gPref2 ,$data['pref']); ?></select><!-- 都道府県 -->
														</li>
														<li class="registration-list-sub">
															<input type="text" name="now_address_1" value="<?php echo TA_Cook($data['now_address_1']) ;?>" class="registration-form w20" /><!-- 市区町村 -->
														</li>
														<li class="registration-list-sub">
															<input type="text" name="now_address_2" value="<?php echo TA_Cook($data['now_address_2']) ;?>" class="registration-form w20" /><!-- 番地、ビル、マンション -->
														</li>
													</ul>
												</li>
												<li>
													<span class="registration-ttl">電話番号：</span>
														<input type="text" name="now_tel_1" value="<?php echo TA_Cook($data['now_tel_1']) ;?>" class="registration-form w11" />

													<div class="registration-box2">
													<span class="previous-item">
														携帯番号：<input type="text" name="now_tel_2" value="<?php echo TA_Cook($data['now_tel_2']) ;?>" class="registration-form w11" />
													</span>
													</div>
												</li>
												<li>
													<span class="registration-ttl">メールアドレス：</span>
														<input type="text" name="now_email" value="<?php echo TA_Cook($data['now_email']) ;?>" class="registration-form w20" />
												</li>
												<li>
														<span class="registration-ttl">ご希望店舗：</span>
														<input type="text" name="shop" value="<?php echo $data['shop_num'] ? $shop_list[$data['shop_num']]:TA_Cook($data['shop']) ;?>" class="registration-form w11" />
												</li>
												<li class="previous-item">
													脱毛サロン勤務経験：<input type="text" name="exeperience_c" value="<?php if($data['exeperience_c'] !== '0'){echo 'あり';};?>" class="registration-form w3" />
													<div class="registration-box2">
														路線/最寄り駅：
														<div class="registration-box">
															<input type="text" name="line" value="<?php echo TA_Cook($data['line']) ;?>" class="registration-form w11" /> / <input type="text" name="station" value="<?php echo TA_Cook($data['station']) ;?>" class="registration-form w11" />
														</div>
													</div>
												</li>
												<li>
													<span class="registration-ttl">ご応募のきっかけ</span>
													<div class="registration-list">
														<span>求人媒体：</span>
														<select name="job_media_id" class="registration-form">
															<?php Reset_Select_Key( $job_media_list , $data['job_media_id'] ) ?>
														</select>
													</div>
													<div class="registration-list">
														<span>その他：</span>
														<input type="text" name="opportunity" value="<?php echo TA_Cook($data['opportunity']) ;?>" class="registration-form w20" />
													</div>
													<span class="registration-ttl">自己PR</span>
													<textarea name="input_form_title_tab_self_pr" class="registration-text2"><?php echo TA_Cook($data['input_form_title_tab_self_pr']) ;?></textarea>
													<span class="registration-ttl">ご質問等</span>
													<textarea name="comment" class="registration-text2"><?php echo TA_Cook($data['comment']) ;?></textarea>
												</li>
											</ul>
											<span class="btn-area">
												<input type="reset" value="reset" class="reset" />
												<input type="submit" value="登録する" class="submit" />
											</span>
										</div>
									</form>
									<!-- end id-form  -->
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
		<div class="clear">&nbsp;</div>
	</div>
	<!--  end content -->
	<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer -->
<script type="text/javascript">
	new AutoKana('Name', 'NameKana', {katakana: true, toggle: false});
</script>
<?php include_once("../include/footer.html");?>