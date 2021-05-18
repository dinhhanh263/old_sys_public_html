$(function() {
    $("#searchBtn").on("click",function() {
      searchResult();
    });
});

    function searchResult(sort) {
        var hope_campaign_checked = $("[name=hope_campaign_checked]:checked").val();
        var tenpo1 = document.frm.tenpo1.value;
        var tenpo2 = document.frm.tenpo2.value;
        var tenpo3 = document.frm.tenpo3.value;
        var tenpo4 = document.frm.tenpo4.value;
        var tenpo5 = document.frm.tenpo5.value;
        var tenpo6 = document.frm.tenpo6.value;
        var tenpo7 = document.frm.tenpo7.value;
        var tenpo8 = document.frm.tenpo8.value;
        var tenpo9 = document.frm.tenpo9.value;
        var tenpo10 = document.frm.tenpo10.value;

        var nittei1 = document.frm.nittei1.value;
        var nittei2 = document.frm.nittei2.value;
        var nittei3 = document.frm.nittei3.value;

        $('#tenpo_error').html("");
        $('#nittei_error').html("");
        if(tenpo1=="" && tenpo2=="" && tenpo3=="" && tenpo4==""  && tenpo5=="" &&
           tenpo6=="" && tenpo7=="" && tenpo8=="" && tenpo9=="" && tenpo10==""){
             $('#tenpo_error').text("ご希望店舗を指定してください");
             $('#search_error').html('<a href="#step1" class="error"><input type="button" value="再選択する"></a>');
             $('#output').html('');
             document.getElementById("tenpo_error").style.display="block";
        }else if(nittei1=="" && nittei2=="" && nittei3=="" ){
             $('#nittei_error').text("ご希望日程を指定してください");
             $('#search_error').html('<a href="#step1" class="error"><input type="button" value="再選択する"></a>');
             $('#output').html('');
             document.getElementById("search_error").style.display="block";
        }else{
            $('#output').html('');
             var params = {hope_campaign_checked:hope_campaign_checked,
                          tenpo1:tenpo1,
                          tenpo2:tenpo2,
                          tenpo3:tenpo3,
                          tenpo4:tenpo4,
                          tenpo5:tenpo5,
                          tenpo6:tenpo6,
                          tenpo7:tenpo7,
                          tenpo8:tenpo8,
                          tenpo9:tenpo9,
                          tenpo10:tenpo10,

                          nittei1:nittei1,
                          nittei2:nittei2,
                          nittei3:nittei3,
                          sort:sort,
                          ts: new Date().getTime()};
            $.getJSON("getSearchResult.html", params, function(data){
              var html = "";
              for(var i in data){
                  html += data[i].html;
              }
              $('#output').html(html);
            });
            $.ajaxSetup({ async: true });

            $('#tenpo_error').html="";
            $('#search_error').html='';
             document.getElementById("tenpo_error").style.display="none";
             document.getElementById("search_error").style.display="none";
        }
          document.getElementById("step1").style.display="block";
          document.getElementById("step1-day").style.display="block";
          document.getElementById("step2").style.display="none";
    }
    function result(shop,hope_date,hope_time,shop_id,length) {
        $('#salon').html(shop);
        $('#hope_date').html(hope_date);
        $('#hope_time').html(hope_time);
        document.frm.shop.value =shop;
        document.frm.hope_date.value =hope_date;
        document.frm.hope_time.value =hope_time;
        document.frm.shop_id.value =shop_id;
        $('#hope_campaign').html(document.frm.hope_campaign.value);

        document.getElementById("step1").style.display="none";
        document.getElementById("step1-day").style.display="none";
        document.getElementById("step2").style.display="block";

        // if(length > 2){
        //   $('#pair').stop(true).slideDown('slow');
        //   document.getElementById("pair").style.display="";
        // }else{
        //   $('#pair').slideUp('slow');
        //   document.getElementById("pair").style.display="none";
        // }
    }


/*全角英数字、ハイフン->半角*/

$(function() {
  $('#fm,#fm2').change(function(){
    var result  = $(this).val();
    for(var i = 0; i < result.length; i++){
        var char = result.charCodeAt(i);
        if(char >= 0xff10 && char <= 0xff19 ){
            //全角数値なら
            result = result.replace(result.charAt(i),String.fromCharCode(char-0xfee0));
        }
        if(char == 0xff0d || char == 0x30fc || char == 0x2015 || char == 0x2212){
            //全角ハイフンなら
            result = result.replace(result.charAt(i),String.fromCharCode(0x2d));
        }
    }
    $(this).val(result);
  });
});

// function change_hidden(){
//   if ( document.frm.elements['echo[2]'].checked ) {
//     $('#target').stop(true).slideDown('slow');
//     document.getElementById("target").style.display="";
//   }else{
//     $('#target').slideUp('slow');
//     document.getElementById("target").style.display="none";
//   }
// }


function returnStep(){
      document.getElementById("step1").style.display="block";
      document.getElementById("step1-day").style.display="block";
      document.getElementById("step2").style.display="none";
}