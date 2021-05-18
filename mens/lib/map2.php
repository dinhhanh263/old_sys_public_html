<?php

mb_internal_encoding( "UTF8" );
mb_http_output( "UTF8" );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Google Maps JavaScript API</title>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAAtNol0x45_KcYK12Ar9zDRTfGCZJIFeGoHoSPv1fuq-nhDZnNhTSf81oNIaxI53JQwyoF2cOb8gopg"
type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[

var gGeo;
var gMap;

function search() {
	//ジオコーディングするためのオブジェクト作成
	gGeo = new GClientGeocoder();
	//テキストボックスの値で検索
	gGeo.getLatLng( "<?= $_GET['address'] ?>" , onGeocoding );
}

function onGeocoding( result ) {
	//取得できたか判定
	if ( result ) {
		//Google Maps APIオブジェクト作成＆初期化
		gMap = new GMap2(document.getElementById("map"));
		gMap.addControl(new GLargeMapControl());
		gMap.addControl(new GMapTypeControl());
		gMap.addControl(new GScaleControl());
		gMap.setCenter(new GLatLng(36.566486260884,137.66196124364),17,G_NORMAL_MAP);
		
		//取得した位置に移動
		gMap.setCenter(result);
		//取得した位置にマーカー作成
		setMarker(result);
	} else {
		parent.document.getElementById("mapspace").innerHTML = '<img src="../share/img/no_map.gif" width="523" height="200">';
	}
}

function setMarker( result ) {
	//マーカーを作成
	var marker = new GMarker( result );
	gMap.addOverlay( marker );
}
//]]>
</script>
</head>
<body onload="search()" onunload="GUnload()">
<div id="wrap">
<div id="map" style="width:500px; height:495px; align:center"></div>
</div>
</body>
</html>
