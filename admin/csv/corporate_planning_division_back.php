<?php
$DOC_ROOT = '';
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dirs = explode('public_html', dirname(__FILE__));
    $DOC_ROOT = str_replace($dirs[1], '', dirname(__FILE__));
} else {
    $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
}
require_once $DOC_ROOT . '/config/config.php';
require_once LIB_DIR . 'function.php';
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth.php" );
include_once("../include/header_menu.html");
?>
</form>
<script type="text/javascript">
function csv_export (i) {
      document.forms[i].action = "data"+i+"_csv.php";
	  document.forms[i].submit();
}

</script>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">
	<!--  start page-heading -->
	<div id="page-heading">
		<h1>CSVエクスポートリンク集	</h1>
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
				  <table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
					<tr>
						<th class="table-header-repeat line-left minwidth-1"><a href="">No</a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href="">依頼部署</a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href="">依頼者</a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href="">タイトル</a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href="">説明</a>	</th>
						<th class="table-header-repeat line-left minwidth-1"><a href="">開始日(月)</a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href="">終了日(月)</a></th>
						<th class="table-header-repeat line-left minwidth-1"><a href="">CSVエクスポート</a></th>
					</tr>
					<tr>
					  <form action="" method="post" name="frm1">
					  	<td>1</td>
						<td>経営企画</td>
						<td>黒田</td>
						<td>旧月額→新月額プラン変更の入金ミス確認用</td>
						<td>毎日出力</td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date("Y-m-d",strtotime("-1 day")); ?>" /></td>
						<td><input class="inp-form" type="text" name="date2" value="<?php echo date('Y-m-d'); ?>" /></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(1);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm2">
						<td>2</td>
						<td>経営企画</td>
						<td>黒田</td>
						<td>旧月額契約者・新月額契約者数</td>
						<td>毎日出力</td>
						<td></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(2);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm3">
					  	<td>3</td>
						<td>営業部</td>
						<td>槇島</td>
						<td>月額からパックへのデータ(変更店舗基準)</td>
						<td>毎週月曜日出力</td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo "2016-10-01"; ?>" /></td>
						<td><input class="inp-form" type="text" name="date2" value="<?php echo date('Y-m-d'); ?>" /></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(3);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm4">
					  	<td>4</td>
						<td>営業部</td>
						<td>槇島</td>
						<td>月額からパックへのデータ(変更店舗基準、変更時支払金額有り)</td>
						<td>毎週月曜日出力</td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo "2016-10-01"; ?>" /></td>
						<td><input class="inp-form" type="text" name="date2" value="<?php echo date('Y-m-d'); ?>" /></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(4);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm5">
					  	<td>5</td>
						<td>営業部</td>
						<td>槇島</td>
						<td>契約終了一覧</td>
						<td>毎週月曜日出力</td>
						<td></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(5);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm6">
					  	<td>6</td>
						<td>営業部</td>
						<td>岸</td>
						<td>店別コース別月別契約者数</td>
						<td>月初出力</td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo "2015-08"; ?>" /></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(6);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm7">
					  	<td>7</td>
						<td>営業部</td>
						<td>槇島</td>
						<td>パック契約3ヶ月以上来店なし</td>
						<td>月初出力</td>
						<td></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(7);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm8">
					  	<td>8</td>
						<td>営業部</td>
						<td>槇島</td>
						<td>売上データと契約データの担当者不一致</td>
						<td>月初出力</td>
						<td></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(8);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm9">
					  	<td>9</td>
						<td>経営企画</td>
						<td>黒田</td>
						<td>月額契約継続無（契約月指定なし）</td>
						<td>月二回出力</td>
						<td></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(9);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm10">
					  	<td>10</td>
						<td>営業部</td>
						<td>岸</td>
						<td>カード情報一覧</td>
						<td>週二回出力</td>
						<td></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(10);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm11">
					  	<td>11</td>
						<td>営業部</td>
						<td>岸</td>
						<td>カード情報未入力一覧</td>
						<td>週二回出力</td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date("Y-m-d",strtotime("-7 day")); ?>" /></td>
						<td><input class="inp-form" type="text" name="date2" value="<?php echo date("Y-m-d",strtotime("-1 day")); ?>" /></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(11);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm12">
					  	<td>12</td>
						<td>経営企画</td>
						<td>黒田</td>
						<td>在職者所属一覧</td>
						<td></td>
						<td></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(12);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm13">
					  	<td>13</td>
						<td>経営企画</td>
						<td>黒田</td>
						<td>カウンセリング電話つながった人の来店状況</td>
						<td></td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date('Y-m')."-01"; ?>" /></td>
						<td><input class="inp-form" type="text" name="date2" value="<?php echo date('Y-m-d'); ?>" /></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(13);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm14">
					  	<td>14</td>
						<td>経営企画</td>
						<td>黒田</td>
						<td>カウンセリング電話つながらなかった人の来店状況</td>
						<td></td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date('Y-m')."-01"; ?>" /></td>
						<td><input class="inp-form" type="text" name="date2" value="<?php echo date('Y-m-d'); ?>" /></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(14);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm15">
					  	<td>15</td>
						<td>経営企画</td>
						<td>黒田</td>
						<td>月額パック契約者の解約情報(解約日ペース)</td>
						<td></td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date("Y-m",strtotime("-1 month"))."-01"; ?>" /></td>
						<td><input class="inp-form" type="text" name="date2" value="<?php echo date('Y-m-d'); ?>" /></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(15);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm16">
					  	<td>16</td>
						<td>経営企画</td>
						<td>黒田</td>
						<td>店舗別カウンセリング予約時間集計</td>
						<td>翌月データ出力</td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date("Y-m",strtotime("+1 month")); ?>" /></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(16);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm17">
					  	<td>17</td>
						<td>経営企画</td>
						<td>黒田</td>
						<td>店舗別施術予約時間集計</td>
						<td>翌月データ出力</td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date("Y-m",strtotime("+1 month")); ?>" /></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(17);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm18">
					  	<td>18</td>
						<td>経営企画</td>
						<td>黒田</td>
						<td>契約中の一般会員契約情報</td>
						<td>前日までのデータ出力</td>
						<td></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(18);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm19">
					  	<td>19</td>
						<td>経営企画</td>
						<td>黒田</td>
						<td>店別コース別施術人数</td>
						<td></td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date("Y-m",strtotime("-1 month")); ?>" /></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(19);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm20">
					  	<td>20</td>
						<td>営業部</td>
						<td>槇島</td>
						<td>月中解約数</td>
						<td></td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date("Y-m",strtotime("-1 month")); ?>" /></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(20);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm21">
					  	<td>21</td>
						<td>営業部</td>
						<td>槇島</td>
						<td>パックの継続契約データ（継続契約日基準）</td>
						<td></td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo "2016-10-01"; ?>" /></td>
						<td><input class="inp-form" type="text" name="date2" value="<?php echo date('Y-m-d'); ?>" /></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(21);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm22">
					  	<td>22</td>
						<td>営業部</td>
						<td>尾崎</td>
						<td>解約の既払金データ（解約日基準）</td>
						<td></td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo "2016-10-01"; ?>" /></td>
						<td><input class="inp-form" type="text" name="date2" value="<?php echo date('Y-m-d'); ?>" /></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(22);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm23">
					  	<td>23</td>
						<td>営業部</td>
						<td>槇島</td>
						<td>パックの継続契約データ（継続契約日基準、継続時支払金額有り）</td>
						<td></td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo "2016-10-01"; ?>" /></td>
						<td><input class="inp-form" type="text" name="date2" value="<?php echo date('Y-m-d'); ?>" /></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(23);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm24">
					  	<td>24</td>
						<td>新規事業部</td>
						<td>運萬</td>
						<td>ジュエルズ(BIG)経由の契約状況</td>
						<td></td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date(j)<10 ? date("Y-m",strtotime("-2 month")) : date("Y-m",strtotime("-1 month")); ?>" /></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(24);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm25">
					  	<td>25</td>
						<td>経理</td>
						<td>成田</td>
						<td>新月額契約者 支払方法の入力漏れ確認用(契約日基準)</td>
						<td>毎日出力</td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date('Y-m-d'); ?>" /></td>
						<td><input class="inp-form" type="text" name="date2" value="<?php echo date('Y-m-d'); ?>" /></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(25);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm26">
					  	<td>26</td>
						<td>経営企画</td>
						<td>黒田</td>
						<td>プラン変更へのデータ(変更店舗基準)</td>
						<td>毎週水曜日出力、ほか営業部も出力予定</td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date('Y-m')."-01"; ?>" /></td>
						<td><input class="inp-form" type="text" name="date2" value="<?php echo date('Y-m-d'); ?>" /></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(26);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm27">
					  	<td>27</td>
						<td>経企</td>
						<td>津嶋</td>
						<td>施術インセンティブ</td>
						<td></td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date(j)<10 ? date("Y-m",strtotime("-2 month")) : date("Y-m",strtotime("-1 month")); ?>" /></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(27);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
						<tr>
					  <form action="" method="post" name="frm28">
					  	<td>28</td>
						<td>経企</td>
						<td>津嶋</td>
						<td>カウンセリングインセンティブ</td>
						<td></td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date(j)<10 ? date("Y-m",strtotime("-2 month")) : date("Y-m",strtotime("-1 month")); ?>" /></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(28);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
					  <form action="" method="post" name="frm29">
					  	<td>29</td>
						<td>経企</td>
						<td>津嶋</td>
						<td>プラン変更インセンティブ</td>
						<td></td>
						<td><input class="inp-form" type="text" name="date1" value="<?php echo date(j)<10 ? date("Y-m",strtotime("-2 month")) : date("Y-m",strtotime("-1 month")); ?>" /></td>
						<td></td>
						<td><input type='button' value='　CSV　' onclick='csv_export(29);' style="height:25px;" /></td>
					  </form>
					</tr>
					<tr>
                                          <form action="" method="post" name="frm30">
                                                <td>30</td>
                                                <td>人事</td>
                                                <td>山脇</td>
                                                <td>未成年契約者一覧</td>
                                                <td>不定期出力</td>
                                                <td><input class="inp-form" type="text" name="date1" value="<?php echo date('Y-m')."-01"; ?>" /></td>
                                                <td><input class="inp-form" type="text" name="date2" value="<?php echo date('Y-m-d'); ?>" /></td>
                                                <td><input type='button' value='　CSV　' onclick='csv_export(30);' style="height:25px;" /></td>
                                          </form>
                                        </tr>
				  </table>
				<!--  end product-table................................... -->
			</div>
			<!--  end content-table  -->
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
