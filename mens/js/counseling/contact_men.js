$(function() {
/* ご希望店舗 value */
  var shopArray = new Array();
  shopArray[0] = "",
  shopArray[1] = ["東京本店","mens_honten"];
  // 複数店舗の中から登録
  // $("[class^=tenpo]").on("change",function(){
  //   var $this,className,idName,$id,idNo,shopNo;
  //   $this = $(this),
  //   className = $this.attr("class"),
  //   idName = className.match(/tenpo\d*/),
  //   $id = $("#" + idName[0]),
  //   idNo = idName[0].replace(/tenpo/,""),
  //   shopNo = shopArray[idNo];
  //   if(!$this.children().is(":checked")){
  //       $this.removeClass("checked");
  //       $id.attr("value","");
  //    }else{
  //       $this.addClass("checked");
  //       $id.attr("value",shopNo[0]);
  //   };
  // });
/* 体験キャンペーン希望 checked */
    $("input[type=radio]").on("change",function(){ /*radio buttonの制御*/
        var $this,name,$name,$n_parent,$t_parent,val;;
        $this = $(this),
        name = "[name=" + $this.attr('name') + "]",$name = $(name), /* 同一nameのボタン取得 */
        $n_parent = $(name).parent(), /*同一nameの各親label取得*/
        $t_parent = $this.parent(),
        val = $this.val();
        if(!$this.is(":checked")){
            $t_parent.removeClass("checked");
        }else{
            $n_parent.removeClass("checked");
            $t_parent.addClass("checked");
            if(name.indexOf("hope_campaign_checked") != -1){
              campaign(val);
            };
        }
    });
    function campaign(val){
        var $target;
        $target = $("input[name=hope_campaign]");
        if(val > 1){
             $target.val("無料体験キャンペーン希望");
        }else{
             $target.val("");
        };
    };
/* 名前入力欄 */
    //お名前（全角文字）
    var $name_family1,$name_first1,$name;
        $name_family1 = $('input[name="name_family1"]'),//姓
        $name_first1 = $('input[name="name_first1"]'),//名
        $name = $('input[name="name"]');//「姓(全角スペース)名」合体
    $([$name_family1[0],$name_first1[0]]).blur(function(){
        names($name_family1,$name_first1,$name)
    });
    //おなまえ（ふりがな）
    var $name_family2,$name_first2,$name_kana,name_all;
        $name_family2 = $('input[name="name_family2"]'),//姓
        $name_first2 = $('input[name="name_first2"]'),//名
        $name_kana = $('input[name="name_kana"]');//「セイ(全角スペース)メイ」合体
    $([$name_family2[0],$name_first2[0]]).blur(function(){
        names($name_family2,$name_first2,$name_kana, true);
    });
    //名前の入力「姓」「名」を「姓(全角スペース)名」にする
    function names($form1, $form2, $form_all,opt_value){
        var val1,val2,name_all;
        val1 = $form1.val(),
        val2 = $form2.val(),
        name_all = (val1 + "　" + val2);
        if(opt_value == true){//ふりがなの入力をカタカナにする
            name_all = name_all.replace(/[ぁ-ん]/g, function(s){
               return String.fromCharCode(s.charCodeAt(0) + 0x60);
            });
        };
        $form_all.val(name_all)/*.text(name_all)*/;
    };
    //ふりがなの入力をカタカナにする
    // function re_kana(e,target){
    //     target.replace(/[ぁ-ん]/g, function(e) {
    //        return String.fromCharCode(s.charCodeAt(0) + 0x60);
    //     });
    //     return target;
    // };
/* 体験キャンペーン希望変更時のparams設定 */
    $('input[name=hope_campaign_checked]').on("change",function(){
       var params =  sessionStorage.getItem('params');
        if(params){
            params = JSON.parse(params);
            params.hope_campaign_checked = $(this).val();
            sessionStorage.setItem('params', JSON.stringify(params));
        }
    });
/* 各フォーム params */
    var setFormData = function(){
        var params =  sessionStorage.getItem('params');
        params = JSON.parse(params);
        if(!params) return ;
        //Set label
         if($("#salon").html() == ""){
            $("#hope_campaign").html(params.hope_campaign),
            $("#salon").html(params.shop);
            $("#hope_date").html(params.hope_date);
            $("#hope_time").html(params.hope_time);
         };
        //Fill form text
        $("#frm :input").each(function(i, obj){
            var fieldName = $(this).attr("name");
            var fieldValue = $(this).attr("value");
            if(params.hasOwnProperty(fieldName)
                && ("text,tel,email".indexOf($(this).attr("type"))>-1)){
                $(this).val(params[fieldName]);
            };
            // console.log(sessionStorage.getItem('params'));
        });
        //Set birthday for select tag
        if(params.hasOwnProperty("birthday")){
            var daysYMD = params.birthday.split('/'); //it's format: 2014/03/03
            $("#frm select[name=birthday_y]").val(daysYMD[0]);
            $("#frm select[name=birthday_m]").val(daysYMD[1]);
            $("#frm select[name=birthday_d]").val(daysYMD[2]);
        };
        //Set radio button
        $("#frm input[type=radio]").each(function(i, obj){
            var fieldName = $(this).attr("name");
            var fieldValue = $(this).attr("value");
            if(params.hasOwnProperty(fieldName) && fieldValue == params[fieldName]){
                $(this).prop('checked',true);
            };
        });
    };
/* 各STEP表示制御 */
    var  showHideSteps = function(){
        var currentStep = $(location).attr('href');
        //Show user info form at step2
        if(currentStep.indexOf("step2") > -1)  {
          $("#step1").css("display", "none");
          $("#step1-day").css("display", "none");
          $("#step2").css("display", "block");
        }else if(currentStep.indexOf("step1-day") > -1){
          $("#step1").css("display", "block");
          $("#step1-day").css("display", "block");
          $("#step2").css("display", "none");
        }else if(currentStep.indexOf("step1") > -1){
        //Hide user input form at step2
          $("#step1").css("display", "block");
          $("#step1-day").css("display", "none");
          $("#step2").css("display", "none");
        };
        //Fill step2 form data
        if($("#step2").is(":visible")){
            setFormData();
        }
    };
//Run it when URL change
$(document).ready(function(){
   showHideSteps();
});
$(window).on('hashchange', function() {
  showHideSteps();
});

/* カレンダー 複数選択機能 */
var PRJ = PRJ || {};
(function($, d, ns){
    'use strict';
    function pad2(arg) {
        var tmp = '00' + arg;
        return tmp.slice(-2, tmp.length);
    }
    var count = 1;
    var clndr = {
        initialize: function() {
            this.$elm = $('#calendar');
            this.$elm.datepicker({
				//defaultDate:"2014/03/01",
				//minDate:new Date(2014, 2, 2),//1日後～3ヶ月後まで選択可能
				minDate:"0d",//1日後～1ヶ月後まで選択可能
				maxDate:"3m",
                onSelect: $.proxy(this.handleSelect, this),
                beforeShowDay: $.proxy(this.beforeShowDay, this),
                dateFormat: 'yy/mm/dd'
            });
            this.selected = [];
            count = 1;
        },
        handleSelect: function(date) {
            var index = $.inArray(date, this.selected);

            if(index == -1 ) {
				if((""==$("#clndr1").val())&&(date!=$("#clndr2").val())&&(date!=$("#clndr3").val())){
	                this.selected.push(date);
					$("#clndr1").val(date);
				}
				if((""==$("#clndr2").val())&&(date!=$("#clndr1").val())&&(date!=$("#clndr3").val())){
	                this.selected.push(date);
					$("#clndr2").val(date);
				}
				if((""==$("#clndr3").val())&&(date!=$("#clndr1").val())&&(date!=$("#clndr2").val())){
	                this.selected.push(date);
					$("#clndr3").val(date);
				}
            } else {
		        delete this.selected[index];
				if(date==$("#clndr1").val()){
					$("#clndr1").attr("value","");
				}
				if(date==$("#clndr2").val()){
					$("#clndr2").attr("value","");
				}
				if(date==$("#clndr3").val()){
					$("#clndr3").attr("value","");
				}
            }
        },
        beforeShowDay: function(date) {
            var theday = date.getFullYear() + '/' +
                pad2(date.getMonth()+1)+'/'+
                pad2(date.getDate());
				if (date.getDay() == 0) {
		            return [true, $.inArray(theday, this.selected)>=0? 'sunday selected':'sunday'];
                }
                if (date.getDay() == 6) {
		            return [true, $.inArray(theday, this.selected)>=0? 'saturday selected':'saturday'];
                }
				return [true, $.inArray(theday, this.selected)>=0? 'selected':''];
        }
    };
    // Export
    ns.clndr = clndr;
})(jQuery, document, PRJ);
$(function() {
    PRJ.clndr.initialize();
    $(window).on('hashchange', function() {
        PRJ.clndr.initialize();
        //PRJ.count = 0;
        //$("#step1-day").css("display", "none");
        //$("#step2").css("display", "none");
        $("#clndr1").val("");
       $("#clndr2").val("");
       $("#clndr3").val("");
        $("#calendar .selected").attr( "class", "" );
    })
});



});
