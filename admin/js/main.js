function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}


function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}


function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}


function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}


function MM_goToURL() { //v3.0
	var i;
	var args = MM_goToURL.arguments;
	document.MM_returnValue = false;

	for ( i=0; i<(args.length-1); i+=2 ) {
		eval( args[i] + ".location='" + args[i+1] + "'" );
	}
}


function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}


function MM_reloadPage( init ) {  //reloads the window if Nav4 resized
	if ( init==true ) {
		with ( navigator ) {
			if ( appName=="Netscape" && parseInt(appVersion)==4 ) {
    			document.MM_pgW = innerWidth;
    			document.MM_pgH = innerHeight;
    			onresize = MM_reloadPage;
    		}
    	}
    } else if ( innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH ) {
		location.reload();
	}
}
MM_reloadPage(true);


function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}


function formClear(targetElement){
	if(targetElement.value == targetElement.defaultValue){
		targetElement.value = "";
	}
}

function submit_search( num ) {
	document.search.start.value = num;
	document.search.submit();
}

function checkad(comment)
{
	if ( document.frm.id.value == "" ){
		alert('IDが空欄です');
		document.frm.action.value = "";
		return;
	}
	if (document.frm.password.value == ''){
		alert('PASSが空欄です');
		document.frm.action.value = "";
		return;
	}
	if (document.frm.name.value == ''){
		alert('名称が空欄です');
		document.frm.action.value = "";
		return;
	}
	if (confirm(comment)) {
		return true;
	}else{
		return false;
	}
}

function checkad2(comment)
{
	if ( document.frm.adcode.value == "" ){
		alert('IDが空欄です');
		document.frm.action.value = "";
		return;
	}
	if (document.frm.name.value == ''){
		alert('名称が空欄です');
		document.frm.action.value = "";
		return;
	}
	if (document.frm.agent_id.value == ''){
		alert('代理店が必須です');
		document.frm.action.value = "";
		return;
	}
	if (confirm(comment)) {
		return true;
	}else{
		return false;
	}
}
function checkad3(comment)
{
	if ( document.frm.id.value == "" ){
		alert('IDが空欄です');
		document.frm.action.value = "";
		return;
	}
	if (document.frm.password.value == ''){
		alert('PASSが空欄です');
		document.frm.action.value = "";
		return;
	}
	if (document.frm.name.value == ''){
		alert('代理店名が空欄です');
		document.frm.action.value = "";
		return;
	}
	if (confirm(comment)) return;
}
function new_data(){
	if( confirm( "新規登録しますか？" ) ){
		return true;
	}else{
		return false;
	}
}
function edit_data( num , name ) {
	if ( confirm( num + "." + name + " を変更しますか？" ) ){
		return true;
	}else{
		return false;
	}
}
function delete_data( num , name ) {
	if ( confirm( num + "." + name + " を削除してよろしいですか？" ) ) {
		return true;
	} else {
		return false;
	}
}
function issue(form1){
	if (confirm("選択されたコード を発行しますか？")){
		form1.action = "ad_issue_detail.php";
		form1.submit();
		return false;
	}else{
		return false;
	}
}
function edit_data_issue(form1) {
	if ( confirm( "選択されたコード を発行済にしますか？" ) ){
		form1.action = "ad_issue_list.php";
		form1.submit();
		return true;
	}else{
		return false;
	}
}
function search(){
	document.search.mode.value = "";
	document.search.action = "list.php";
	document.search.submit();
	return false;
}

function check_form(){
	if( confirm( "データを処理しますか？" ) ){
		return true;
	}else{
		return false;
	}
}
function check_form1(){
	if( confirm( "NGメールアドレスが一斉配信不可をしますか？" ) ){
		return true;
	}else{
		return false;
	}
}
function back(form1){
	form1.action = "index.php";
	form1.submit();
	return false;
}
function send() {
	if ( !confirm( "本送信して宜しいですか？" ) ){
		return false;
	}
	document.form1.mode.value = "send";
	document.form1.submit();
	return false;
}
function test_send(){
	if ( !confirm( "テスト送信して宜しいですか？" ) ){
		return false;
	}
	document.form1.mode.value = "test";
	document.form1.submit();
	return false;
}
function goto_delete(){
	if( confirm( "このデータを削除してよろしいですか？" ) ){
		return true;
	}else{
		return false;
	}
}
function m_win(url,windowname,width,height) {
	 var features="location=no, menubar=no, status=yes, scrollbars=yes, resizable=yes, toolbar=no";
	 if (width) {
	  if (window.screen.width > width)
	   features+=", left="+(window.screen.width-width)/2;
	  else width=window.screen.width;
	  features+=", width="+width;
	 }
	 if (height) {
	  if (window.screen.height > height)
	   features+=", top="+(window.screen.height-height)/2;
	  else height=window.screen.height;
	  features+=", height="+height;
	 }
	 window.open(url,windowname,features);
}
function daily(){
	document.search.mode.value = "daily";
	document.search.submit();
	return false;
}
function monthly(){
	document.search.mode.value = "monthly";
	document.search.submit();
	return false;
}
function byad(){
	document.search.mode.value = "byad";
	document.search.submit();
	return false;
}
function bypage(){
	document.search.mode.value = "bypage";
	document.search.submit();
	return false;
}
function adcode(form1){
	form1.action = "adcode.php";
	form1.submit();
	return false;
}
function conf(name) {
	if ( confirm( name + "様に送信して宜しいですか？" ) ){
		return true;
	}else{
		return false;
	}
}
function conf1(name) {
	if ( confirm( name + " 登録して宜しいですか？" ) ){
		return true;
	}else{
		return false;
	}
}
function conf_loan() {
	var date = document.form1.loan_date.value ;
	if(date ==1) {
		confirm('処理日を入力してください！');
		return false;
	}else if ( confirm( name + " 処理して宜しいですか？" ) ){
		return true;
	}else{
		return false;
	}
}

// カウンセリングレジ清算時、登録時の確認アラート
function conf_reg() {
	var price = document.form1.fixed_price.value ;

	// ローン不備のチェック判定用
	var loan_deficiency_value = document.form1.if_loan_deficiency.value ;
	var loan_deficiency_checked = document.form1.if_loan_deficiency.checked ;
	// ローンステータスが5.ローン不備で、チェックがなかった時の確認アラート
	if(loan_deficiency_value ==1 && loan_deficiency_checked == false){
		confirm( "ローン不備の確認をしてください。\n確認済みの場合は「ローン不備確認済」にチェックを入れてください。" );
	}

	if(price==0){
		if ( confirm( " 本当に削除して宜しいですか？" ) ){
			return true;
		}else{
			return false;
		}
	}else{
		if ( confirm( " 登録して宜しいですか？" ) ){
			return true;
		}else{
			return false;
		}
	}
}

// トリートメントレジ清算時、登録時の確認アラート
function conf_detail(name) {
	// ローン不備のチェック判定用
	var loan_deficiency_value = document.form1.if_loan_deficiency.value ;
	var loan_deficiency_checked = document.form1.if_loan_deficiency.checked ;
	// ローンステータスが5.ローン不備で、チェックがなかった時の確認アラート
	if(loan_deficiency_value ==1 && loan_deficiency_checked == false){
		confirm( "ローン不備の確認をしてください。\n確認済みの場合は「ローン不備確認済」にチェックを入れてください。" );
	}

	if ( confirm( name + " 役務消化の有無を確認して登録して宜しいですか？" ) ){
		return true;
	}else{
		return false;
	}
}

function top(form1){
	form1.action = "../";
	form1.submit();
	return false;
}
