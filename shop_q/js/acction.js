/* form */
  /* checkebox,radio btn */
  function input_list(){ /* input全体の設定 */
    var input,same_name,next;
    input = document.getElementsByTagName("input");
    for(var i=0; i<input.length; i++){
      checked(input[i]);
      input[i].onclick = function(){
        if(this.type == "checkbox"){
          input_checkbox(this);/* checkedクラス設定 */
          focus_t(this);/* 直後にテキスト入力があった場合フォーカス移動 */
        }else if(this.type == "radio"){
          input_radio(this);/* checkedクラス設定 */
          focus_t(this);/* 直後にテキスト入力があった場合フォーカス移動 */
        };
      };
    };
    /* checkedクラス設定 */
    function checked(e){
      if(e.checked == true){
        e.parentNode.classList.add("checked")
      }
    };
    function input_checkbox(e){
      e.parentNode.classList.toggle("checked");
    };
    function input_radio(e){
      same_name = e.name;
      same_name = document.getElementsByName(same_name);
      e.parentNode.classList.add("checked");
      for(i=0; i<same_name.length; i++){
        if(same_name[i].checked == false && same_name[i].parentNode.classList.contains("checked")){
          same_name[i].parentNode.classList.remove("checked");
        };
      };
    };
    /* 直後にテキスト入力があった場合フォーカス移動 */
    function focus_t(e){
      next = e.parentNode.nextElementSibling;
      if(e.checked == true && next !== null && next.type == "text"){
        next.focus();
      }
    }
  };

/* step navi */
  function step_nav(){
    var allH4,winH4,nowY,step1,step2,step3;
    allH4 = document.documentElement.clientHeight,
    allH4 = Math.floor(allH4/4),
    winH4 = document.getElementById("shop_q").clientHeight
    winH4 = (Math.floor(winH4/4) - 100),
    nowY = document.documentElement.scrollTop || document.body.scrollTop,
    stepAll = document.getElementById("step"),
    step1 = document.getElementById("step1"),
    step2 = document.getElementById("step2"),
    step3 = document.getElementById("step3"),
    step4 = document.getElementById("step4");
    step1.style.cssText += ";height:" + (allH4 - 20) + "px;";
    step2.style.cssText += ";height:" + (allH4 - 20) + "px;";
    step3.style.cssText += ";height:" + (allH4 - 20) + "px;";
    step4.style.cssText += ";height:" + (allH4 - 20) + "px;";
    if(nowY < 50){
      stepAll.style.cssText += ";top:50px;";
    }else{
      stepAll.style.cssText += ";top:0;";
    };
    if(nowY < (winH4 - 500)){
      step1.classList.add("now");
      step2.classList.remove("now");
      step3.classList.remove("now");
      step4.classList.remove("now");
    }else if((winH4 - 500) <= nowY && nowY < (winH4*2)){
      step2.classList.add("now");
      step1.classList.remove("now");
      step3.classList.remove("now");
      step4.classList.remove("now");
    }else if((winH4*2) <=nowY && nowY < (winH4*3)){
      step3.classList.add("now");
      step1.classList.remove("now");
      step2.classList.remove("now");
      step4.classList.remove("now");
    }else if((winH4*3) <=nowY && nowY < (winH4*4)){
      step4.classList.add("now");
      step1.classList.remove("now");
      step2.classList.remove("now");
      step3.classList.remove("now");
    };
  };
  window.onscroll = function(){
    step_nav();
  }

  /* 入力内容のチェック */
  function submit_checke(){

  }

  /* submit押下げ後二重押下げ禁止処理 */
  function after_push(target){
    var $target,h,cover,inner;
    $target = $(target),
    cover = document.createElement('div'), /* 全体の覆いを作成 */
    cover.id = 'cover_box',
    inner = document.createElement('div'), /* loading表示を作成 */
    inner.id = 'cover_inner_box',
    inner.innerHTML = '送信中';
    cover.appendChild(inner); /* 覆いの中にloading表示を追加 */
    $target.on('click',function(){
      $('body').append(cover);
    })
  }