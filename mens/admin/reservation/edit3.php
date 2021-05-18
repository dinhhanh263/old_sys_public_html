<?php include_once("../library/reservation/edit.php");?>
<?php include_once("../include/header_menu.html");?>

<link href="../js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="../js/facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox() 
})
</script>

</form>
<!-- start content-outer -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading"><h1>予約詳細<span style="float:right;margin-right:25px;"><a href="./edit.php?mode=new_rsv&customer_id=<?php echo $customer['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>&memo=<?php echo $data['memo'];?>&memo2=<?php echo $data['memo2'];?>&memo3=<?php echo $data['memo3'];?>" onclick="return confirm('次の予約をしますか？')" class="side" title="次の予約" >次の予約へ</a></span></h1></div><!--予約新規?次回予約新規?予約詳細?顧客新規以外顧客情報を右側に-->
		<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
<!-- 			<tr>
				<th rowspan="3" class="sized"><img src="../images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
				<th class="topleft"></th>
				<td id="tbl-border-top">&nbsp;</td>
				<th class="topright"></th>
				<th rowspan="3" class="sized"><img src="../images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
			</tr> -->
			<tr>
				<!-- <td id="tbl-border-left"></td> -->
				<td>
					<!--  start content-table-inner -->
					<div id="content-table-inner">
							<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr valign="top">
								<td>
									<!-- start id-form -->
									<form action="" method="post" name="form1" enctype="multipart/form-data" onSubmit="return conf1('');">
										<input type="hidden" name="action" value="edit" />
										<input type="hidden" name="mode" value="<?php echo $_POST["mode"];?>" />
										<input type="hidden" name="id" value="<?php echo $data["id"];?>" />
										<input type="hidden" name="customer_id" value="<?php echo $customer["id"];?>" />
										<input type="hidden" name="contract_id" value="<?php echo $contract["id"];?>" />
										<input type="hidden" name="course_id" value="<?php echo $contract["course_id"];?>" />
										<input type="hidden" name="from_cc" value="<?php echo $_POST["from_cc"];?>" />
										<?php echo $gMsg;?>
										<table border="0" cellpadding="0" cellspacing="0"  id="id-form" class="resev_form">
											<tr>
												<th valign="top">区分:</th>
												<td><select name="type" class="styledselect_form_1"><?php Reset_Select_Key( $gResType4 , $_POST['type'] ? $_POST['type'] : $data['type']);?></select></td>
											</tr>
											<tr>
												<th valign="top">来店状況:</th>
												<td><select name="status" class="styledselect_form_1"><?php Reset_Select_Key( $gBookStatus , $_POST['status'] ? $_POST['status'] : $data['status']);?></select></td>
											
											</tr>

											<?php if($_POST['id'] &&  $data['type']==1){?>	
											
											<!--<tr>
												<th valign="top">確認状況:</th>
												<td><select name="con_status" class="styledselect_form_1"><?php Reset_Select_Key( $gConfirmStatus , $_POST['con_status'] ? $_POST['con_status'] : $data['con_status']);?></select></td>
									
											</tr>-->
											<tr>
												<th valign="top">確認状況(3日前ﾒｰﾙ):</th>
												<td><select name="3dmail_status" class="styledselect_form_1"><?php Reset_Select_Key( $g3DMailStatus , $_POST['3dmail_status'] ? $_POST['3dmail_status'] : $data['3dmail_status']);?></select></td>
									
											</tr>
											<tr>
												<th valign="top">確認状況(前日ﾒｰﾙ):</th>
												<td><select name="premail_status" class="styledselect_form_1"><?php Reset_Select_Key( $gPreMailStatus , $_POST['premail_status'] ? $_POST['premail_status'] : $data['premail_status']);?></select></td>
									
											</tr>
											<?php } ?>
											<?php if($data['type']<=1){?>	
											<tr>
												<th valign="top">確認状況(前日tel):</th>
												<td><select name="preday_status" class="styledselect_form_1"><?php Reset_Select_Key( $gPreDayStatus , $_POST['preday_status'] ? $_POST['preday_status'] : $data['preday_status']);?></select></td>
									
											</tr>
											<tr>
												<th valign="top">確認状況(予約時tel):</th>
												<td><select name="today_status" class="styledselect_form_1"><?php Reset_Select_Key( $gTodayStatus , $_POST['today_status'] ? $_POST['today_status'] : $data['today_status']);?></select></td>
									
											</tr>
											<tr>
												<th valign="top">カウンセリング担当:</th>
												<td><select name="cstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['cstaff_id'],getDatalist3("shop",$_POST['shop_id']));?></select></td>
											</tr>
											<?php } ?>	

											<?php if($_POST['id'] &&  $data['type']==2){?>	

												<th valign="top">契約コース:</th>
												<!--<td><select name="course_id" class="styledselect_form_3" disabled><?php Reset_Select_Key( $course_list , $contract['course_id']);?></select></td>-->
												<td><select name="course_id" class="styledselect_form_3" ><option></option><?php Reset_Select_Key_Group( $course_list , $contract['course_id'],$gCourseGroup);?></select></td>

											<?php } ?>	
											<tr>
												<th valign="top">店舗:</th>
												<!--<td><select name="shop_id" class="styledselect_form_1" <?php echo $authority_shop['id'] ? "disabled" : "" ?> ><?php Reset_Select_Key( $shop_list , $authority_shop['id'] ? $authority_shop['id'] : ($data['shop_id'] ? $data['shop_id'] : 1));?></select></td>-->
												<td><select name="shop_id" class="styledselect_form_1"><?php Reset_Select_Key( $shop_list , $data['shop_id']  ? $data['shop_id']  : ($_POST['shop_id'] ? $_POST['shop_id'] : $authority_shop['id']));?></select></td>
											</tr> 
											<tr>
												<th valign="top">ルーム:</th>
												<td><select name="room_id" class="styledselect_form_1"><?php Reset_Select_Key( $room_list , $data['room_id']);?></select></td>
										
											</tr>

											

											<tr>
												<th valign="top">予約日程:</th>
												<td class="noheight">
													<table border="0" cellpadding="0" cellspacing="0">
														<tr  valign="top">
															<td><input  class="inp-form" name="hope_date" type="text" id="day" value="<?php echo $data['hope_date'];?>" placeholder="<?php echo date('Y-m-d');?>" readonly /></td>
														</tr>
													</table>
												</td>
												<td></td>
											</tr>
											<tr>
												<th valign="top">予約時間:</th>
												<td>
													<select size="1" name="hope_time" class="styledselect_form_1"><?php Reset_Select_Key( $gTime  , $data['hope_time']);?></select>
												</td>
											</tr> 
											<tr>
												<th valign="top">人数:</th>
												<td><select name="persons" class="styledselect_form_1"><?php Reset_Select_Key( $gPersons , $data['persons']);?></select></td>
											</tr>
											<tr>
												<th valign="top">所要時間:</th>
												<td><select name="length" class="styledselect_form_1"><?php Reset_Select_Key( $gLength , $data['length'] ? $data['length'] : 2);?></select></td>
											</tr>
											
											
										<?php if(!$_POST['id'] && !$_POST['customer_id']){?>	
											<tr>
												<th valign="top">会員タイプ:</th>
												<td><select name="ctype" class="styledselect_form_1"><?php Reset_Select_Key( $gCustomerType , $_POST['ctype'] ? $_POST['ctype'] : $customer['ctype']);?></td>
											</tr> 
											<tr>
												<th valign="top">名前:</th>
												<td><input type="text" name="name" value="<?php echo TA_Cook($_POST['name'] ? $_POST['name'] : $customer['name']) ;?>" id="Name"  class="inp-form" /></td>
											</tr>
											<tr>
												<th valign="top">名前(カナ):</th>
												<td><input type="text" name="name_kana" value="<?php echo TA_Cook($_POST['name_kana'] ? $_POST['name_kana'] : $customer['name_kana']) ;?>" id="NameKana" class="inp-form" /></td>
											</tr>
											<!--<tr>
												<th valign="top">生年月日:</th>
												<td><input type="text" name="birthday" value="<?php echo TA_Cook($customer['birthday']) ;?>" id="fm" class="inp-form" /></td>
											</tr>-->
											<tr>
												<th valign="top">年齢:</th>
												<td><input type="text" name="age" value="<?php echo TA_Cook($_POST['age'] ? $_POST['age'] : $customer['age']) ;?>" id="fm" class="inp-form" /></td>
											</tr>

											<tr>
												<th valign="top">電話番号:</th>
												<td><input type="text" name="tel" value="<?php echo TA_Cook($_POST['tel'] ? $_POST['tel'] : $customer['tel']) ;?>" id="fm" class="inp-form" /></td>
											</tr> 
											<tr>
												<th valign="top">メールアドレス:</th>
												<td><input type="text" name="mail" value="<?php echo TA_Cook($_POST['mail'] ? $_POST['mail'] : $customer['mail']) ;?>" id="fm" class="inp-form" /></td>
											</tr> 
											
											<!--<tr>
												<th valign="top">反響:</th>
												<td><input  class="inp-form" name="echo" type="text" value="<?php echo $data['echo'];?>" /></td>
											</tr>-->
											<tr>
												<th valign="top">紹介者:</th>
												<td><input  class="inp-form" name="introducer" type="text" value="<?php echo $data['introducer'];?>" /></td>
											</tr>
											<tr>
												<th valign="top">紹介者タイプ:</th>
												<td>
													<select name="introducer_type" class="styledselect_form_1"><?php Reset_Select_Key( $gIntroducerType , $data['introducer_type']);?></select>
												</td>
											</tr>
										<?php if($authority_level<=1){?>
											<tr>
												<th valign="top">特別紹介者:</th>
												<td>
													<select name="special" class="styledselect_form_1"><?php Reset_Select_Key( $special_list , $data['special']);?></select>
												</td>
											</tr>

										<?php } } ?>	
											<tr>
												<th valign="top">経由:</th>
												<td><select name="route" class="styledselect_form_1"><option>-</option><?php Reset_Select_Key( $gRoute , $data['route']);?></select></td>
											</tr>
											<tr>
												<th valign="top">連絡希望時間帯:</th>
												<td><input  class="inp-form" name="hope_time_range" type="text" value="<?php echo $data['hope_time_range'];?>" readonly /></td>
											</tr>
											<tr>
												<th valign="top">キャンペーン特典:</th>
												<td><select name="hope_campaign" class="styledselect_form_1"><?php Reset_Select_Name( $gHopeCapaign , $data['hope_campaign']);?></select></td>
											</tr>

										<?php if($data['type']==2){?>	
											<tr>
												<th valign="top">施術主担当:</th>
												
												<td><select name="tstaff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_id'],getDatalist3("shop",$_POST['shop_id']));?></select></td>
											</tr>
											<tr>
												<th valign="top">施術サブ担当1:</th>
												
												<td><select name="tstaff_sub1_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_sub1_id'],getDatalist3("shop",$_POST['shop_id']));?></select></td>
											</tr>
											<tr>
												<th valign="top">施術サブ担当2:</th>
												<td><select name="tstaff_sub2_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['tstaff_sub2_id'],getDatalist3("shop",$_POST['shop_id']));?></select></td>
											</tr>
										<?php } ?>	
											<tr>
												<th valign="top">受付担当:</th>
												<td><select name="staff_id" class="styledselect_form_3" ><?php Reset_Select_Array_Group( getDatalistArray3("staff","shop_id") , $data['staff_id'],getDatalist3("shop",$_POST['shop_id']));?></select></td>
											</tr>
											<tr>
												<th valign="top">CC担当:</th>
												<td><select name="ccstaff_id" class="styledselect_form_3" ><?php Reset_Select_Key( $ccstaff_list , $data['ccstaff_id'] );?></select></td>
											</tr>

											<tr>
												<th valign="top">予約表記載:</th>
												<td><textarea name="memo2" class="form-textarea2"><?php echo TA_Cook($data['memo2'] ? $data['memo2'] : $_POST['memo2']) ;?></textarea></td>
											</tr>
											<tr>
												<th valign="top">備考:</th>
												<td><textarea name="memo" class="form-textarea2"><?php echo TA_Cook($data['memo'] ? $data['memo'] : $_POST['memo']) ;?></textarea></td>
											</tr>
											<tr>
												<th valign="top">備考(本社用):</th>
												<td><textarea name="memo3" class="form-textarea2"><?php echo TA_Cook($data['memo3'] ? $data['memo3'] : $_POST['memo3']) ;?></textarea></td>
											</tr>
											<tr>
												<th>&nbsp;</th>
												<td valign="top">
													<input type="submit" value="" class="form-submit" />
													<input type="reset" value="" class="form-reset" />
												</td>
											</tr>
										</table>
												<div id="shift_min">
													<?php if($contract["course_id"]<20){?>
														<div style="padding-left:20px;"><iframe src="../main/shift_d2.php?shop_id=<?php echo $_POST['shop_id']?>&hope_date=<?php echo $_POST['hope_date']?>" height="59" width="100%"></iframe></div>
													<?php }?>
													<div style="padding-left:20px;"><iframe src="../main/mini.php?shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>&hope_date=<?php echo $_POST['hope_date'] ? $_POST['hope_date'] : $data['hope_date']?>" scrolling=no height=410 width="100%"></iframe></div>
													<?php if( $course['type']){?>
													<div style="padding-left:20px;"><iframe src="../reservation/pay_monthly.php?id=<?php echo ($_POST['id'] ? $_POST['id'] : $data['id']);?>&hope_date=<?php echo $_POST['hope_date'] ? $_POST['hope_date'] : $data['hope_date']?>" scrolling=yes height=200 width="100%"></iframe></div>
													<?php }?>
												<div>
									</form>
									<!-- end id-form  -->
								</td>
								<!--右サイト-->
								<?php if($_POST['id'] || $_POST['customer_id']){?>
									<td class="edit_right">
										<!--  start related-activities -->
										<div id="related-activities">
											<!--  start related-act-top -->
											<div id="related-act-top">
												<div class="title"><a href="../customer/edit.php?id=<?php echo $customer['id']?>" class="side_title" title="顧客詳細へ"><?php echo $customer['name_kana'] ? $customer['name_kana'] : ($customer['name'] ? $customer['name'] : "無名")?></a></div>
											</div>
											<!-- end related-act-top -->
		
											<!--  start related-act-bottom -->
											<div id="related-act-bottom">
												<!--  start related-act-inner -->
												<div id="related-act-inner">
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>会員番号 : <?php echo $customer['no']?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<!--<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>名前 : <?php echo ($customer['name'] ? $customer['name'] : $customer['name_kana']);?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>-->

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>会員タイプ : <?php echo TA_Cook($gCustomerType[$customer['ctype']])?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>年齢 : <?php  echo ($customer['birthday']!='0000-00-00' && $customer['birthday']!='') ? floor((date('Ymd')-str_replace('-','',$customer['birthday']))/10000)."歳" : $customer['age']."歳";?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>電話番号 : <?php echo $customer['tel']?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>メールアドレス : <?php echo $customer['mail']?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<?php if( $contract['id']){ ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>契約状況 : <?php echo $gContractStatus[$contract['status']]?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>契約コース : <br /><?php echo $course_list[$contract['course_id']]?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>


													<?php if( $customer['introducer']){ ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>紹介者 : <?php echo $customer['introducer']?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>

													<?php if( $customer['introducer_type']){ ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>紹介者タイプ : <?php echo $gIntroducerType[$customer['introducer_type']]?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>

													<?php if( $customer['special']){ ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>特別紹介者 : <?php echo $special_list[$customer['special']]?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>

													<?php if( $data['type']==2 ){ ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>消化(来店)回数 : <?php echo $contract['r_times']?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>

													<?php if( $contract['balance'] ){ ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>売掛金 : ￥<?php echo number_format($contract['balance'])?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
													<?php } ?>
												
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>カウンセリング担当 : <?php echo  $data['cstaff_id'] ? $staff_list[$data['cstaff_id']] : $staff_list[$contract['staff_id']] ;?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>登録日時 : <?php echo ( $customer['reg_date'] );?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>


											</div><!-- end related-act-inner -->
												<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->


										<!--  start related-activities -->
										<div id="related-activities">
											<!--  start related-act-top -->
											<div id="related-act-top">
												<!--<img src="../images/forms/header_related_act.gif" width="271" height="43" alt="" />-->
												<div class="title">関連書類</div>
											</div>
											<!-- end related-act-top -->
											<!--  start related-act-bottom -->
											<div id="related-act-bottom">
												<!--  start related-act-inner -->
												<div id="related-act-inner">	
													<div class="left"><a href="javascript:void(0)"><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
													<!--<div class="right"><h5><a href="../customer/sheet.php?customer_id=<?php echo $customer['id']?>" class="side" target="_blank">問診表</a></h5></div>-->
													<div class="right"><h5><a href="#" class="side" onclick="window.open('../customer/sheet.php?customer_id=<?php echo $customer['id']?>', '_blank');window.open(window.location, '_self').close();">問診表</a></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
												
												<?php //if( $data['type']==1 ){ ?>
													
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5><a href="karte_c.php?customer_id=<?php echo $customer['id']?>" class="side">カウンセリングカルテ</a></h5></div>
													<div class="clear"></div>
													
												<?php //} ?>
												<?php if( $data['type']==2 ){ ?>
													<div class="lines-dotted-short"></div>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5><a href="karte.php?reservation_id=<?php echo $_POST['id']?>" class="side">トリートメントカルテ</a><?php if($karte['id'])echo "(済)"?></h5></div>
													<div class="clear"></div>
													
												<?php } ?>

												<?php //if($data['sales_id']){?>
													

<div class="lines-dotted-short"></div>
													<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5><a href="javascript:void(0);" onclick="window.open('../../pdf_out2.php<?php echo $pdf_param;?>', '_blank', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" class="side" >契約書類出力</a></h5></div>
													<div class="clear"></div>

<!--
<div class="lines-dotted-short"></div>
													<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5><a href="javascript:void(0);" onclick="window.open('../../doc1.php<?php echo $pdf_param;?>', '_blank', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" class="side" >概要書面</a></h5></div>
													<div class="clear"></div>

													<div class="lines-dotted-short"></div>
													<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5><a href="javascript:void(0);" onclick="window.open('../../doc2.php<?php echo $pdf_param;?>', '_blank', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" class="side" >契約書</a></h5></div>
													<div class="clear"></div>

													<div class="lines-dotted-short"></div>
													<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5><a href="javascript:void(0);" onclick="window.open('../../doc3.php<?php echo $pdf_param;?>', '_blank', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" class="side">個人情報</a></h5></div>
													<div class="clear"></div>

													<div class="lines-dotted-short"></div>
													<div class="left"><a href=""><img src="../images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5><a href="javascript:void(0);" onclick="window.open('../../doc4.php<?php echo $pdf_param;?>', '_blank', 'width=900, height=1000, menubar=no, toolbar=no, scrollbars=yes');" class="side" >同意書</a></h5></div>
													<div class="clear"></div>-->
												<?php //} ?>

												</div><!-- end related-act-inner -->
												<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->
										<?php //if($data['customer_id'] && $authority_level<=1){?>
										<?php if($data['customer_id'] ){?>
										<!--  start related-activities -->
										<div id="related-activities">
											<!--  start related-act-top -->
											<div id="related-act-top">
												<!--<img src="../images/forms/header_related_act.gif" width="271" height="43" alt="" />-->
												<div class="title">各種処理</div>
											</div>
											<!-- end related-act-top -->
											<!--  start related-act-bottom -->
											<div id="related-act-bottom">
												<!--  start related-act-inner -->
												<div id="related-act-inner">
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right">
														<h5>
															<!--来店前また来店なし,未契約が非表示-->
															<?php if($authority_level<=1 || ($data['hope_date']==date("Y-m-d")) || ($data['hope_date']<=date('Y-m-d') && $data['status']<>1 && $data['status']<>2)){
																if($data['type']==1){?>
																	<a href="../account/reg_detail.php?id=<?php echo $data['id'];?>" class="side">レジ精算</a><?php if($data['reg_flg'])echo "(済)"?>
																<?php //}elseif($contract['id'] && $data['type']<>3){ ?>
																<?php }else{ ?>
																	<a href="../service/detail.php?id=<?php echo $data['id'];?>" class="side">レジ精算</a><?php if($data['sales_id'])echo "(済)"?><?php if($sales['r_times'])echo "、役務消化(済)"?>
															<?php } }?>
														</h5>
														初回入金、役務消化、売掛回収等
													</div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5><a href="./edit.php?mode=new_rsv&customer_id=<?php echo $customer['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>&memo=<?php echo $data['memo'];?>&memo2=<?php echo $data['memo2'];?>&memo3=<?php echo $data['memo3'];?>" onclick="return confirm('次の予約をしますか？')" class="side" >次の施術予約</a></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
											<?php if($authority_level<=1){?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5><a href="../service/cooling_off.php?customer_id=<?php echo $customer['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>" onclick="return confirm('クーリングオフ処理をしましか？')" class="side"　>クーリングオフ</a><?php if($contract['status']==2)echo "(済)"?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5><a href="../service/cancel_detail.php?customer_id=<?php echo $customer['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>"　onclick="return confirm('中途解約処理をしましか？')" class="side"　>中途解約</a><?php if($contract['status']==3)echo "(済)"?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
												<?php if($contract['payment_loan']){?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<!--<div class="right"><h5><a rel="facebox" href="../service/confirm_loan.php?customer_id=<?php echo $customer['id'];?>&reservation_id=<?php echo $_POST['id'];?>&hope_date=<?php echo $_POST['hope_date']?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>"　onclick="return confirm('ローン承認処理をしましか？')" class="side"　>ローン承認処理</a>(<?php echo $gLoanStatus[$contract['loan_status']];?>)</h5></div>-->
													<div class="right"><h5><a rel="facebox" href="../service/confirm_loan.php?id=<?php echo $data['contract_id'];?>&reservation_id=<?php echo $_POST['id'];?>&hope_date=<?php echo $_POST['hope_date']?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>"　onclick="return confirm('ローン承認処理をしましか？')" class="side"　>ローン承認処理</a>(<?php echo $gLoanStatus[$contract['loan_status']];?>)</h5></div>

													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5><a href="../service/cancel_loan.php?customer_id=<?php echo $customer['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>"　onclick="return confirm('ローン取消処理をしましか？')" class="side"　>ローン取消</a><?php if($contract['status']==5)echo "(済)"?></h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

											<?php }}elseif($contract['status']==2){ ?>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>クーリングオフ(済)</h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
											<?php }elseif($contract['status']==3){ ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>中途解約(済)</h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

											<?php }elseif($contract['status']==5){ ?>
													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
													<div class="right"><h5>ローン取消(済)</h5></div>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>
											<?php } ?>

													<div class="left"><a href=""><img src="../images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
											<!--ローン取消前にプラン変更禁止-->
											<?php if($authority_level>1 && $contract['status']==0 && $contract['payment_loan'] && $contract['loan_status']<>4){ ?>		
													<div class="right"><h5>プラン変更</h5><font size=-2>※ローンがある場合、本社でのローン取消処理が必要</font></div>
											<?php }else{?>	
												<div class="right"><h5><a href="../service/change.php?customer_id=<?php echo $customer['id'];?>&shop_id=<?php echo ($_POST['shop_id'] ? $_POST['shop_id'] : $data['shop_id']);?>"　onclick="return confirm('プラン変更をしましか？')" class="side">プラン変更</a><?php if($contract['status']==4 || $contract['old_contract_id'])echo "(済)"?></h5></div>
											<?php }	?>
													<div class="clear"></div>
													<div class="lines-dotted-short"></div>

												</div><!-- end related-act-inner -->
												<div class="clear"></div>
											</div><!-- end related-act-bottom -->
										</div><!-- end related-activities -->
										<?php } ?>
									</td>
								<?php } ?>	
							</tr>
							<tr><td><img src="../images/shared/blank.gif" width="695" height="1" alt="blank" /></td></tr>
						</table>
 						<div class="clear"></div>
					</div>
					<!--  end content-table-inner  -->
				</td>
				<!-- <td id="tbl-border-right"></td> -->
			</tr>
<!-- 			<tr>
				<th class="sized bottomleft"></th>
				<td id="tbl-border-bottom">&nbsp;</td>
				<th class="sized bottomright"></th>
			</tr> -->
		</table>
		<div class="clear">&nbsp;</div>
	</div>
	<!--  end content -->
	<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer -->
<script type="text/javascript">
new AutoKana('Name', 'NameKana', {katakana: true, toggle: false});
	new AutoKana('FirstName', 'FirstNameKana', {katakana: true, toggle: false});
	new AutoKana('LastName', 'LastNameKana', {katakana: true, toggle: false});
</script>
<?php include_once("../include/footer.html");?>