/* reading files
    /account/reg_ditail.php カウンセリングレジ清算画面
    /account/one_ditail.php 単発レジ清算画面
    1.部位選択表示制御
    2.料金表示制御(含明細)
    3.カウンセリングレジ清算表示金額の固定（割引金額の固定）
 */

/* 1.部位選択表示制御-------------------------------------------------------------------------------- */
  /* --全体共通変数-- */
  var $selecter,$menu_single,$single_inner; //各select,input指定
  /* --コース名変更動作-- */
  $selecter = $("select[name^=course_id]");// コースの<select></select>
  $selecter.on("change",function(){
    select_course(this);
  });
  /* --「カスタマイズ」選択動作-- */
  $menu_single = $("#single_check"), //「カスタマイズ」ボタン
  $menu_single.on("change",function(){
    single_menu(this);
  });
  /* --部位選択動作-- */
  $single_inner = $(".single_inner input"); //25箇所ボタン部分
  $single_inner.on("click",function(){
    var $checked_all = $(this).parents(".single_inner").find("input:checked");
    var checked_num = $checked_all.length; //部位選択数
    if($(this).parents("tr").is(".menu_single") !== true){ //trのclass名が.menu_singleではない＝単発（部位自由）ではない
      contract_num(this,checked_num);
    }else{ // カスタマイズ（部位自由）の場合
      if(5 < checked_num){
        $(this).prop("checked",false);
        alert("※" + 5 + "箇所以上はチェックできません");
      };
    }
    checked_color(this);
  });

  function select_course(target){ //「選べる」コース部位選択表示制御
    var s_num,target_txt,target_part_txt,target_part_txt2,s_text,next,$next,target_face_part_txt,target_m_part_txt,target_l_part_txt,next2,$next2;
      s_num = target.options.selectedIndex, //どのコースを選択したか
      target_txt = '選';
      target_part_txt = '部';
      target_part_txt2 = '1パーツ';
      target_face_part_txt = '(顔)';
      target_m_part_txt = '(M)';
      target_l_part_txt = '(L)';
      s_text = target.options[s_num].text,
      next = target.parentNode.parentNode.nextElementSibling.getElementsByTagName("td"); //部位選択inputの含まれるtd
      $next = $(next).find("input");

      // 部位を出す条件
      if(s_text.indexOf(target_txt) !== -1 || s_text.indexOf(target_part_txt) !== -1 || s_text.indexOf(target_part_txt2) !== -1){ //コース名に「選」「部」という文字が含まれていたら　2017/05/17 [部]を追加 add by shimada
        next[0].classList.remove("parts_area");
      }else{ //コース名に「選」という文字が含まれていなかったら
        next[0].classList.add("parts_area");
      };

        $next.prop("checked", false); //部位選択のチェックを外す
        $next.parent().removeClass("c_green"); //チェック済のclass名を外す
  };
  function single_menu(target){ //「カスタマイズ」施術25か所の表示
    var inner,inner_btn,btn_num,i,$single_course_name,single_names;
    inner = target.parentNode.nextElementSibling, //25箇所ボタン格納部分
    inner_btn = inner.getElementsByTagName("input"), //25箇所ボタン部分
    btn_num = inner_btn.length - 1,
    $single_course_name = $('#single_course_name'); //明細のカスタマイズ部位名表示箇所
    if(target.checked == false){ //unchecked
      for(i=btn_num; 0<=i; i--){
        inner_btn[i].checked = false;
        checked_color(inner_btn[i]);
      };
      document.form1.single_fixed_price.value = 0;
      inner.classList.add("parts_area");
      keisan();
      $single_course_name.html(""); //カスタマイズ部位名（明細）非表示
    }else if(target.checked == true){ //checked
      inner.classList.remove("parts_area");
      $single_course_name.html("カスタマイズ"); //カスタマイズ部位名（明細）表示
    };
  };
  function checked_color(target){ //チェックが入った<label><input></label>の文字色変更
    if(target.checked == true){
      target.parentNode.classList.add("c_green");
    }else if(target.checked == false){
      target.parentNode.classList.remove("c_green");
    }
  };
  function contract_num(target,checked_num){ //部位選択数alert
    var $target,$input_btns,$checked_num;
    $target = $(target); //部位選択のinput
    var prev_slct = $target.parents("tr").prev().find("select")[0]; //部位選択inputの上段にあるselectを指定
    var s_num = prev_slct.options.selectedIndex; //選ばれているoptionを取得
    var s_text = prev_slct.options[s_num].text; //コース名を取得
    var max_num = /\d/.exec(s_text); //コース名の箇所数を取得
      if(max_num < checked_num){
        $target.prop("checked",false);
        alert("※" + max_num + "箇所以上はチェックできません");
      };
  };

/* 2.金額計算-------------------------------------------------------------------------------- */
  /* --全体共通変数-- */
  var fixed_price,fixed_price2,fixed_price3,fixed_price4,single_fixed_price; /* --各コース金額の取得-- */
  var discount,discount2,discount3,discount4,single_discount; /* --値引き-- */
  discount = 0,discount2 = 0, discount3 = 0, discount4 = 0, single_discount = 0; /* --数値型宣言-- */
  var course_id,course_id2,course_id3,course_id4,single_price,couse1_price,couse2_price,couse3_price,couse4_price;
  couse1_price = 0,couse2_price = 0,couse3_price = 0,couse4_price = 0,single_price = 0; /* --数値型宣言-- */

  /* 金額計算-------------------------------------------------------------------------------- */
  function keisan(){/* --カウンセリング・1回コース当日レジ清算-- */
    course_id = document.form1.course_id.selectedIndex, // コース金額（税込）
    couse1_price = course_prices[course_id],
    document.form1.fixed_price.value = couse1_price;
      course_id2 = document.form1.course_id2.selectedIndex ; // コース金額2（税込）
      couse2_price = course_prices[course_id2],
      document.form1.fixed_price2.value = couse2_price;
      course_id3 = document.form1.course_id3.selectedIndex ; // コース金額3（税込）
      couse3_price = course_prices[course_id3],
      document.form1.fixed_price3.value = couse3_price;
      course_id4 = document.form1.course_id4.selectedIndex ; // コース金額4（税込）
      couse4_price = course_prices[course_id4],
      document.form1.fixed_price4.value = couse4_price;
      single_price = document.form1.single_fixed_price.value; //部位カスタマイズ（税込）
    if(document.form1.discount_rate.disabled !== true){ /* --カウンセリングレジ清算・1回コース当日レジ清算の場合-- */
      cut_rates(); //割引率換算が有効な場合、自動計算する
    }
    statement();
  };
  /* 割引率計算-------------------------------------------------------------------------------- */
  function cut_rates(){/* --カウンセリング・単発レジ清算・プラン変更-- */
    var total_price,discount_rate,total_discount,price;
    total_discount = 0;
    couse1_price = document.form1.fixed_price.value; //コース料金
    couse2_price = document.form1.fixed_price2.value,
    couse3_price = document.form1.fixed_price3.value,
    couse4_price = document.form1.fixed_price4.value,
    single_price = document.form1.single_fixed_price.value;
    total_price = (Number(couse1_price) + Number(couse2_price) + Number(couse3_price) + Number(couse4_price) + Number(single_price)); //合計金額
    discount = document.form1.discount,
    total_discount = discount;//値引き合計
    if(document.getElementById("discount2") !== null){ //カウンセリングレジ清算の場合
      //値引き率計算
      discount = discount_box(discount,Number(couse1_price));
      discount2 = discount_box(discount2,Number(couse2_price)),
      discount3 = discount_box(discount3,Number(couse3_price)),
      discount4 = discount_box(discount4,Number(couse4_price)),
      single_discount = discount_box(single_discount,Number(single_price));
      //値引き金額に代入
      document.form1.discount.value = discount;
      document.form1.discount2.value = discount2,
      document.form1.discount3.value = discount3,
      document.form1.discount4.value = discount4,
      document.form1.single_discount.value = single_discount;
      total_discount += (discount2 + discount3 + discount4 + single_discount); //値引き合計に加算
    }else{ //1日コース当日レジ清算の場合
      //値引き率計算
      discount = discount_box(discount,Number(total_price));
      //値引き金額に代入
      document.form1.discount.value = discount;
    };
    price = total_price - total_discount;
    document.form1.price.value = price; // 請求金額（税込）
    reduce();
  };
  /* 割引率換算boxの処理-------------------------------------------------------------------------------- */
  function discount_box(target,target_price){
    discount_rate = document.form1.discount_rate.options[document.form1.discount_rate.selectedIndex].value; //割引率の選択
    if(discount_rate == 0){
      target = Math.round(target_price * 0);
    }else if(discount_rate == 1){// 5%
      target = Math.round(target_price * 0.05);
    }else if (discount_rate == 2 ){// 10%
      target = Math.round(target_price * 0.1);
    }else if (discount_rate == 3 ){// 20%
      target = Math.round(target_price * 0.2);
    }else if (discount_rate == 4 ){// 50%
      target = Math.round(target_price * 0.5);
    }else if (discount_rate == 9 ){// 80%
      target = Math.round(target_price * 0.8);
    } else if (discount_rate == 10 ){// 35%
      target = Math.round(target_price * 0.35);
    } else if (discount_rate == 11 ){// 40%
      target = Math.round(target_price * 0.4);
    } else if (discount_rate == 12 ){// 45%
      target = Math.round(target_price * 0.45);
    } else if (discount_rate == 13 ){// 25% 2017/08/22 add by shimada
      target = Math.round(target_price * 0.25);
    } else if (discount_rate == 14 ){// 30% 2017/08/22 add by shimada
      target = Math.round(target_price * 0.3);
    } else if (discount_rate == 15 ){// 70% 2017/08/22 add by shimada
      target = Math.round(target_price * 0.7);
    }
    return target;
  };

  /* 部位カスタマイズ金額計算-------------------------------------------------------------------------------- */
  function one_detail(target){
    var $target,checked_price,$single_course_name,single_names;
    $target = $(".menu_single>td input:checked"); //部位のチェック箇所取得
    checked_price = 0; //数値型宣言
    $single_course_name = $('#single_course_name'); //明細のカスタマイズ部位名表示箇所
    // single_names = (!single_names == null ? single_names : "") + course_single[$(target).val()-1]; //明細に表示する部位名の追加
    $target.each(function(){
      checked_price += Number(part_course_prices_str[$(this).val()]);
    });
    // $single_course_name.html(single_names); //カスタマイズ部位名（明細）表示
    document.form1.single_fixed_price.value = checked_price; //部位カスタマイズ金額（税込）
    keisan();
  }

  /* 値引き金額のみ変更-------------------------------------------------------------------------------- */
  function reduce(){
    var discount_price,total_discount;
    fixed_price = Number(document.form1.fixed_price.value),
    fixed_price2 = Number(document.form1.fixed_price2.value),
    fixed_price3 = Number(document.form1.fixed_price3.value);
    fixed_price4 = Number(document.form1.fixed_price4.value);
    if(document.getElementById("single_check") !== null){//部位カスタマイズ
      single_fixed_price = Number(document.form1.single_fixed_price.value);
    }else{
      single_fixed_price = 0;
    };
    discount = Number(document.form1.discount.value),
    total_discount = discount;
    if(document.getElementById("discount2") !== null){ //カウンセリングレジ清算の場合
      discount2 = Number(document.form1.discount2.value);
      discount3 = Number(document.form1.discount3.value);
      discount4 = Number(document.form1.discount4.value);
      single_discount = Number(document.form1.single_discount.value);
      total_discount += (discount2 + discount3 + discount4 + single_discount);
    };
    discount_price = (fixed_price + fixed_price2 + fixed_price3 + fixed_price4 + single_fixed_price) - total_discount;
    document.form1.price.value = discount_price;
    statement();
  };

  /* オプション金額計算-------------------------------------------------------------------------------- */
  var s_price = 5000; //シェービング代（一律）
  function statement_option(){ //カウンセリングレジ清算旧keisan4()
    var option_num,option_name;
    option_num = document.form1.option_name.selectedIndex;
    option_name = document.form1.option_name.options[option_num].value; //オプション選択の値を取得
    if(option_name == 0){
      $('#option_name').html("オプション");
    }else{
      $('#option_name').html(option[option_name]);
    };
    if (option_name == 1){ //シェービング
      document.form1.option_price.value = s_price; // オプション金額を表示
      shaving_btn(0);
    }else if (option_name == 10){ //顔パック体験
      document.form1.option_price.value = 5400;
      shaving_btn(1);
    }else if (option_name == 11){ //脚パック体験
      document.form1.option_price.value = 6480;
      shaving_btn(1);
    }else if (option_name == 12){ //VIOパック体験
      document.form1.option_price.value = 7560;
      shaving_btn(1);
    }else if (option_name == 13){ //選べる3か所体験
      document.form1.option_price.value = 8100;
      shaving_btn(1);
    }else if (option_name == 14) { //ヒゲ脱毛1回体験
      document.form1.option_price.value = 980; 
      shaving_btn(0);
    }else{
      document.form1.option_price.value = "";
    }
    statement();
  }
  if(document.getElementById("shaving")!== null){ //「＋シェービング代」ボタンがあれば
    $("#shaving").on("click",shaving_price);
  }
  function shaving_price(){
    document.form1.option_price.value = Number(document.form1.option_price.value) + s_price;
    document.form1.memo.value += "オプションに体験シェービング代込";
    return false;
  };
  function shaving_btn(flg){
    var s_button;
    if(document.getElementById("shaving")!== null){
      s_button = document.getElementById("shaving");
      if(flg == 1){
        s_button.classList.remove("parts_area");
      }else if(flg == 0){
        s_button.classList.add("parts_area");
      }
    }else{
      return false;
    }
  }
  function option_payment(){ // トリートメントレジ清算旧keisan2()オプション支払金額の合計
    var option_price,option_transfer,option_card;
    option_price=0,option_transfer=0,option_card=0;
    if(document.form1.option_transfer !== null){
      option_price = document.form1.option_price.value ; // オプション支払（現金）
      option_transfer = document.form1.option_transfer.value ; // オプション支払（振込）
      option_card = document.form1.option_card.value ; // オプション支払（カード）
    }
    var option_price0,option_price2;
    option_price0 = Number(option_price) + Number(option_transfer) + Number(option_card); // オプション支払合計
    option_price2 = comma(option_price0);
    $('#option_price').html(option_price2);
    return option_price0;
  }

  /* 売掛金額変更-------------------------------------------------------------------------------- */
  function remaining(){ //トリートメントレジ清算売掛金ありの場合
    var remaining_price;
    remaining_price = document.form1.price.value; //売掛金合計を取得
    remaining_price = Number(remaining_price);
    $('#remaining').html(remaining_price);
    statement();
  }
  /* 明細-------------------------------------------------------------------------------- */
  var f_payment,c_payment,t_payment,l_payment,price;/* --入金種類-- */
  f_payment = 0,c_payment = 0,t_payment = 0,l_payment = 0,price = 0; /* --数値型宣言-- */

  function comma(numbers){ //数値1234→1,234
    numbers = String(numbers).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' );
    return numbers;
  }
  function statement(){ //明細書計算 トリートメントレジ清算旧keisan() プラン変更旧keisan()
    if(document.getElementById("f_payment") !== null){
      f_payment = document.form1.payment_cash.value, // 入金(現金)or残金支払(現金)
      c_payment = document.form1.payment_card.value; // 入金(カード)or残金支払(カード)
    // var p_payment = document.form1.payment_coupon.value ; // 初回入金(クーポン)
    };
    if(document.getElementById("t_payment") !== null){
      t_payment = document.form1.payment_transfer.value, // 入金(振込)or残金支払(振込)
      l_payment = document.form1.payment_loan.value ; // 入金(ローン)or残金支払(ローン)
      price = Number(document.form1.price.value); //ご請求金額（税込）を取得
    };
    var payment = Number(f_payment) + Number(c_payment) + Number(t_payment) + Number(l_payment)/*+ Number(p_payment)*/; //入金金額（明細）or残金支払（明細）

    if(document.getElementById("course_name") !== null){                  /* --カウンセリング・1回コース当日レジ清算・プラン変更の場合-- */
      discount = document.form1.discount.value;           //値引き金額を取得
      var course_id,course_id2,course_id3,course_id4,course_single; /* --コース名の取得-- */
      course_id = document.form1.course_id.options[document.form1.course_id.selectedIndex].textContent,
      $('#course_name').text(course_id); //コース1名（明細）
      fixed_price = document.form1.fixed_price.value;/* --コース金額の取得-- */
      var option_price,option_card,option_price2; //オプションを定義
      option_price =0,option_card=0; //数値型宣言
      $("#course_price").text(comma(fixed_price)); //コース1金額（明細）
        if(document.getElementById("course_name2") == null){              /* --プラン変更の場合-- */
          price = fixed_price - remained_price - discount ;// 請求金額（税込）=コース金額-消化済金額
          document.form1.price.value = price;
        }else{                                                            /* --カウンセリング・1回コース当日レジ清算の場合-- */
          price = Number(document.form1.price.value); //ご請求金額（税込）を取得
          /* --各コース名の取得-- */
          course_id2 = document.form1.course_id2.options[document.form1.course_id2.selectedIndex].textContent;
          course_id3 = document.form1.course_id3.options[document.form1.course_id3.selectedIndex].textContent;
          course_id4 = document.form1.course_id4.options[document.form1.course_id4.selectedIndex].textContent;
          $('#course_name2').text(course_id2), //コース2名（明細）
          $('#course_name3').text(course_id3); //コース3名（明細）
          $('#course_name4').text(course_id4); //コース4名（明細）
          /* --各コース金額の取得-- */
          fixed_price2 = document.form1.fixed_price2.value,
          fixed_price3 = document.form1.fixed_price3.value;
          fixed_price4 = document.form1.fixed_price4.value;
          single_fixed_price = Number(document.form1.single_fixed_price.value);
          $("#course_price2").text(comma(fixed_price2)), //コース2金額（明細）
          $("#course_price3").text(comma(fixed_price3)); //コース3金額（明細）
          $("#course_price4").text(comma(fixed_price4)); //コース4金額（明細）
          $("#single_price").text(comma(single_fixed_price)); //部位カスタマイズ金額（明細）
          /* --オプション-- */
          option_price = Number(document.form1.option_price.value); //オプション金額（現金払い）を取得
          option_card = Number(document.form1.option_card.value); //オプション金額（カード払い）を取得
          option_price2 = comma(option_price + option_card);
          $('#option_price').html(option_price2);
        };
      var discount_comma,discount_comma2,discount_comma3,discount_comma4,single_discount_commma;/* --値引き-- */
      discount_comma = comma(discount),discount_comma2 = comma(discount2),discount_comma3 = comma(discount3),discount_comma4 = comma(discount4),single_discount_commma = comma(single_discount);
      $('#discount').html(discount_comma),
      $('#discount2').html(discount_comma2),
      $('#discount3').html(discount_comma3);
      $('#discount4').html(discount_comma4);
      $('#single_discount').html(single_discount_commma);         //値引き（明細）

      var total,total2; /* --合計金額-- */
      total = price + option_price;
      total2 = comma(total);
      $('#total').html(total2);         //合計：請求金額（明細）

      var duty,tax; /* --税金-- */
      duty = total - Math.round( total / tax2 );
      tax = comma(duty);
      $('#tax').html(tax); //内税（明細）

      var balance = price - payment ; //契約残金（明細）

    }else{ /* --トリートメントレジ清算の場合-- */
      var total,total2,option_price0;
      option_price0 = option_payment();
      total = Number(payment) + Number(option_price0); //支払合計（明細）
      total2 = comma(total);
      $('#total').html(total2);

      var balance = price - payment ; //残金（明細）

    }

    /* --支払金額-- */
    var payment2 = comma(payment); //支払合計の表示
    $('#payment').html(payment2);
    var balance2 = comma(balance); //支払後残金の表示
    $('#balance').html(balance2);

  };

/* 3.カウンセリングレジ清算表示金額の固定（割引金額の固定）-------------------------------------------------------------------------------- */
  function reg_btn(target){
    document.getElementById("f_payment").readOnly = false,
    document.getElementById("c_payment").readOnly = false;
    if(document.getElementById("t_payment") !== null){/* カウンセリングレジ清算 */
      document.getElementById("t_payment").readOnly = false,
      document.getElementById("l_payment").readOnly = false;
    };
    document.form1.course_id.classList.add("disabled"),
    document.form1.course_id2.classList.add("disabled"),
    document.form1.course_id3.classList.add("disabled");
    document.form1.course_id4.classList.add("disabled");
    document.form1.fixed_price.readOnly = true,
    document.form1.fixed_price2.readOnly = true,
    document.form1.fixed_price3.readOnly = true,
    document.form1.fixed_price4.readOnly = true,
    document.form1.single_fixed_price.readOnly = true;
    document.form1.discount.readOnly = true,
    document.form1.discount2.readOnly = true,
    document.form1.discount3.readOnly = true,
    document.form1.discount4.readOnly = true,
    document.form1.single_discount.readOnly = true;
    document.form1.discount_rate.disabled = true;
    document.getElementById("single_check").onclick = function(){return false};
    $(target).addClass('disabled');
    $(target).text('戻る');
    $(target).parents("tr").next().find("input").focus();
  };
  function release(target){
    document.getElementById("f_payment").readOnly = true,
    document.getElementById("c_payment").readOnly = true;
    if(document.getElementById("t_payment") !== null){/* カウンセリングレジ清算 */
      document.getElementById("t_payment").readOnly = true,
      document.getElementById("l_payment").readOnly = true;
    }
    document.form1.course_id.classList.remove("disabled"),
    document.form1.course_id2.classList.remove("disabled"),
    document.form1.course_id3.classList.remove("disabled");
    document.form1.course_id4.classList.remove("disabled");
    document.form1.fixed_price.readOnly = false,
    document.form1.fixed_price2.readOnly = false,
    document.form1.fixed_price3.readOnly = false,
    document.form1.fixed_price4.readOnly = false,
    document.form1.single_fixed_price.readOnly = false;
    document.form1.discount.readOnly = false,
    document.form1.discount2.readOnly = false,
    document.form1.discount3.readOnly = false,
    document.form1.discount4.readOnly = false,
    document.form1.single_discount.readOnly = false;
    document.form1.discount_rate.disabled = false;
    document.getElementById("single_check").onclick = function(){return true};
    $(target).removeClass('disabled');
    $(target).text('入金入力へ');
  }

