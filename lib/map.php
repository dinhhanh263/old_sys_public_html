<?php

mb_internal_encoding("EUC");
mb_http_output( "UTF8" );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title>Google Maps JavaScript API Example</title>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAAtNol0x45_KcYK12Ar9zDRTfGCZJIFeGoHoSPv1fuq-nhDZnNhTSf81oNIaxI53JQwyoF2cOb8gopg"
type="text/javascript"></script>
<script type="text/javascript">

//<![CDATA[
function load() {
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("map"));

		//ポイント座標設定
		var lon   = <?php echo($_GET['log']); ?> ; //経度
		var lat   = <?php echo($_GET['lat']); ?> ; //緯度
		var point = new GPoint(lon,lat);
		
		map.setCenter(new GLatLng(lat, lon), 17, G_NORMAL_MAP);

		//コントロール追加
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.addControl(new GScaleControl());
		
		//マーカーを作成
		var marker = new GMarker( point );
		map.addOverlay( marker );
	}
}

//]]>
</script>
</head>
<body onload="load()" onunload="GUnload()">
<div id="wrap">
<div id="map" style="width:500px; height:495px; align:center"></div>
</div>
</body>
</html>
