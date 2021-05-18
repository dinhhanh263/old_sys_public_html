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
define_approot($_SERVER['SCRIPT_FILENAME'],2);
require_once LIB_DIR . 'db.php';
include_once( "../../lib/auth3.php" );
include_once( "../library/menu.php" );

//担当リスト------------------------------------------------------------------------
$tantou_sql = $GLOBALS['mysqldb']->query( "select * from tantou WHERE status<>1 order by id" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $tantou_sql->fetch_assoc() ) {
  $tantou_list[$result['id']] = $result['name'];
}
	
$customer_sql = $GLOBALS['mysqldb']->query( "select * from customer WHERE lat>0 and lng>0 order by lat,lng" ) or die('query error'.$GLOBALS['mysqldb']->error);
while ( $result = $customer_sql->fetch_assoc() ) {
  if( $_REQUEST['diagnosis_type'] ) $diagnosis_type_Where .= " AND exam.diagnosis_type = '".addslashes($_REQUEST['diagnosis_type'])."'";
  $exam = Get_Table_Row("exam"," WHERE customer_id = '".addslashes($result['id'])."'".$diagnosis_type_Where." order by no desc limit 1");

  //総売上の取得
  $total = Get_Table_Col("exam","sum(amount_cash)+sum(amount_card)+sum(amount_loan)"," where customer_id='".$result['id']."'");

	/*************************************
	＜表示の色分方法＞
	・総売上10万以上＝赤
	・総売上3万以上10万円未満＝黄色
	・総売上3万円未満＝青
 
	以上の色分けをした上で
	・晴れまたは曇り＝ベタ塗り（総売上で分けた色）
	・雨＝黒い縞模様（総売上で分けた色と黒の縞模様）
	・雪＝白い縞模様（総売上で分けた色と白の縞模様）
	***************************************/
	if($total>=100000){
		if($result['weather'] == 3) $color_no = 0; //red_black
		elseif($result['weather'] == 4) $color_no = 1; //red_white
		else $color_no = 2; //red
	}
	elseif($total>=30000){
		if($result['weather'] == 3) $color_no = 3; //yellow_black
		elseif($result['weather'] == 4) $color_no = 4; //yellow_white
		else $color_no = 5; //yellow
	}
	else{
		if($result['weather'] == 3) $color_no = 6; //blue_black
		elseif($result['weather'] == 4) $color_no = 7; //blue_white
		else $color_no = 8; //blue
	}

  if($result['weather']) $img_weather = '<img src="../img/weather_'.$result['weather'].'.gif" width="16" height="16" />';
  else $img_weather = "";
	
	$kml_body .= '<Placemark>
				<name>' . $result['customer_name'] . $img_weather . '</name>
				<description>
            		<![CDATA[
					TEL：' . $result['tel'] . '<br />
					住所：' . $gPref[$result['prefecture']] . $result['address']. '<br />
          診療分類：' . $gDiagnosis_type[$exam['diagnosis_type']] . '<br />
					総売上：￥' . number_format($total) . '<br />
					担当医：' . $tantou_list[$exam['tantou']] . '
					]]>
          		</description>
          		<styleUrl>#myDefaultStyles' . $color_no . '</styleUrl>
          		<Point>
          			<coordinates>' . $result['lng'] . ', ' . $result['lat'] . ', 0</coordinates>
          		</Point>
          	</Placemark>';
}

$kml_header = '<kml>
	<Document>
	  <!-- Begin Style Definitions -->
        <Style id="myDefaultStyles0">
          <IconStyle>
            <color>a1ff00ff</color>
            <scale>3.399999976158142</scale>
            <Icon>
              <href>'.$home_url.'manage/img/marker_color/red_black.png</href>
            </Icon>
          </IconStyle>
          <LabelStyle>
            <color>7fffaaff</color>
            <scale>1.5</scale>
          </LabelStyle>
          <LineStyle>
            <color>ff0000ff</color>
            <width>15</width>
          </LineStyle>
          <PolyStyle>
            <color>7f7faaaa</color>
            <colorMode>random</colorMode>
          </PolyStyle>
        </Style>
        <Style id="myDefaultStyles1">
          <IconStyle>
            <color>a1ff00ff</color>
            <scale>3.399999976158142</scale>
            <Icon>
              <href>'.$home_url.'manage/img/marker_color/red_white.png</href>
            </Icon>
          </IconStyle>
          <LabelStyle>
            <color>7fffaaff</color>
            <scale>1.5</scale>
          </LabelStyle>
          <LineStyle>
            <color>ff0000ff</color>
            <width>15</width>
          </LineStyle>
          <PolyStyle>
            <color>7f7faaaa</color>
            <colorMode>random</colorMode>
          </PolyStyle>
        </Style>
        <Style id="myDefaultStyles2">
          <IconStyle>
            <color>a1ff00ff</color>
            <scale>3.399999976158142</scale>
            <Icon>
              <href>'.$home_url.'manage/img/marker_color/red.png</href>
            </Icon>
          </IconStyle>
          <LabelStyle>
            <color>7fffaaff</color>
            <scale>1.5</scale>
          </LabelStyle>
          <LineStyle>
            <color>ff0000ff</color>
            <width>15</width>
          </LineStyle>
          <PolyStyle>
            <color>7f7faaaa</color>
            <colorMode>random</colorMode>
          </PolyStyle>
        </Style>
        <Style id="myDefaultStyles3">
          <IconStyle>
            <color>a1ff00ff</color>
            <scale>2.399999976158142</scale>
            <Icon>
              <href>'.$home_url.'manage/img/marker_color/yellow_black.png</href>
            </Icon>
          </IconStyle>
          <LabelStyle>
            <color>7fffaaff</color>
            <scale>1.5</scale>
          </LabelStyle>
          <LineStyle>
            <color>ff0000ff</color>
            <width>15</width>
          </LineStyle>
          <PolyStyle>
            <color>7f7faaaa</color>
            <colorMode>random</colorMode>
          </PolyStyle>
        </Style>
        <Style id="myDefaultStyles4">
          <IconStyle>
            <color>a1ff00ff</color>
            <scale>2.399999976158142</scale>
            <Icon>
              <href>'.$home_url.'manage/img/marker_color/yellow_white.png</href>
            </Icon>
          </IconStyle>
          <LabelStyle>
            <color>7fffaaff</color>
            <scale>1.5</scale>
          </LabelStyle>
          <LineStyle>
            <color>ff0000ff</color>
            <width>15</width>
          </LineStyle>
          <PolyStyle>
            <color>7f7faaaa</color>
            <colorMode>random</colorMode>
          </PolyStyle>
        </Style>
        <Style id="myDefaultStyles5">
          <IconStyle>
            <color>a1ff00ff</color>
            <scale>2.399999976158142</scale>
            <Icon>
              <href>'.$home_url.'manage/img/marker_color/yellow.png</href>
            </Icon>
          </IconStyle>
          <LabelStyle>
            <color>7fffaaff</color>
            <scale>1.5</scale>
          </LabelStyle>
          <LineStyle>
            <color>ff0000ff</color>
            <width>15</width>
          </LineStyle>
          <PolyStyle>
            <color>7f7faaaa</color>
            <colorMode>random</colorMode>
          </PolyStyle>
        </Style>
        <Style id="myDefaultStyles6">
          <IconStyle>
            <color>a1ff00ff</color>
            <scale>1.399999976158142</scale>
            <Icon>
              <href>'.$home_url.'manage/img/marker_color/blue_black.png</href>
            </Icon>
          </IconStyle>
          <LabelStyle>
            <color>7fffaaff</color>
            <scale>1.5</scale>
          </LabelStyle>
          <LineStyle>
            <color>ff0000ff</color>
            <width>15</width>
          </LineStyle>
          <PolyStyle>
            <color>7f7faaaa</color>
            <colorMode>random</colorMode>
          </PolyStyle>
        </Style>
        <Style id="myDefaultStyles7">
          <IconStyle>
            <color>a1ff00ff</color>
            <scale>1.399999976158142</scale>
            <Icon>
              <href>'.$home_url.'manage/img/marker_color/blue_white.png</href>
            </Icon>
          </IconStyle>
          <LabelStyle>
            <color>7fffaaff</color>
            <scale>1.5</scale>
          </LabelStyle>
          <LineStyle>
            <color>ff0000ff</color>
            <width>15</width>
          </LineStyle>
          <PolyStyle>
            <color>7f7faaaa</color>
            <colorMode>random</colorMode>
          </PolyStyle>
        </Style>
        <Style id="myDefaultStyles8">
          <IconStyle>
            <color>a1ff00ff</color>
            <scale>1.399999976158142</scale>
            <Icon>
              <href>'.$home_url.'manage/img/marker_color/blue.png</href>
            </Icon>
          </IconStyle>
          <LabelStyle>
            <color>7fffaaff</color>
            <scale>1.5</scale>
          </LabelStyle>
          <LineStyle>
            <color>ff0000ff</color>
            <width>15</width>
          </LineStyle>
          <PolyStyle>
            <color>7f7faaaa</color>
            <colorMode>random</colorMode>
          </PolyStyle>
        </Style>
      <!-- End Style Definitions -->
      <Folder>';
$kml_footer = '</Folder></Document></kml>';
$kml_data = $kml_header.$kml_body.$kml_footer;

//ファイルを作成する。
$output_path ='./area5.kml';
$handle = fopen($output_path, "w");		//ファイル書き出し処理
fwrite($handle, $kml_data);
fclose($handle);
?>