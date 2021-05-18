;(function($) {
  $.fn.guidance = function(options){
    var settings = $.extend(true,{
      Itargetval : "", /* <input>説明文を表示させたいvalueの値 */
      Stargetval : "", /* <select></select>説明文を表示させたいvalueの値 */
      SguidanceNo : {"":""}, /*<select></select>番号別で表示させたい説明文 {no:text}*/
      guidanceAll : "" /*共通で表示させたい説明文*/
    },options);
    var $document,$span,text;
    $document = $(document),
    $span = $("<span>",{id: "guidance",class: "transparency", html: text}); //説明文エリアspan
    function make_guidance(text){ //説明文エリアの生成
      $span.html(text);
      var $body;
      $body = $("body");
      $body.append($span);
      setTimeout(function(){
        $span.removeClass('transparency')
      },50);
    }
    $span.on("click",close);
    function close(){ //説明文エリアの削除
      $span.addClass('transparency')
      $span.detach();
      return false;
    };

    var show_text,type,target_value,Ivalue,Svalue,all_text,Svalue2,Seach_text;
    Ivalue = settings.Itargetval.split(","); //data-Itargetvalの数値を分割・配列化
    Svalue = settings.Stargetval.split(","); //data-Stargetvalの数値を分割・配列化
    all_text = settings.guidanceAll; //data-guidance-allを表示用textに設定
    show_text = all_text;
    Svalue2 = []; /*番号別説明文のvalue設定*/
    Seach_text = settings.SguidanceNo; //data-Sguidance-noを表示用textに設定
    for(key in Seach_text){ // 説明文の設定されている番号をSvalue2へ格納
      Svalue2.push(key);
    }
    // $document.bind('make_guidance',function(e,target_value,value,value2,show_text,show_text2){
    this.bind('make_guidance',function(e,target_value,value,value2,show_text,show_text2){
      if($.inArray(target_value,value) !== -1){ //チェックした値が指定値、または値が設定されていない時
        make_guidance(show_text);
        return false;
      }else if($.inArray(target_value,value2) !== -1){ //チェックした値が番号別指定値の時
        make_guidance(show_text2[target_value]);
        return false;
      }else{
        close();
        return false;
      }
    });
    if(this.is("body")){
      this.trigger('make_guidance',[1,[1],"",all_text,""]);;
    }else{
      this.on("change",function(){
        if(this.type == "checkbox" && $(this).is(":checked")){
          $(this).trigger('make_guidance',[this.value,Ivalue,"",all_text,""]); /* タグ名、値、チェックボックス用値、説明文 */
        }else{
          $(this).trigger('make_guidance',[this.value,Svalue,Svalue2,all_text,Seach_text]); /* タグ名、値、セレクトタグ用値、説明文 */
        }
      });
      this.on("focus",function(){
          close();
      });
    }
  };
})(jQuery);