var DecodedAction=DecodedAction||{},decodedaction;DecodedAction=function(){this.init()},function(o,e,n,t){DecodedAction.prototype={init:function(){this.ua=navigator.userAgent,this.windowOnloadInit(),o(e).load(function(){console.log("window on loaded")}),console.log("window init")},windowOnloadInit:function(){console.log("test"),Response.create({prop:"width",prefix:"min-width-",breakpoints:[641,0]})},sameHeight:function(o,e){}},DecodedAction.util=o.fn.extend({smoothScroll:function(t){function s(){e.addEventListener&&e.addEventListener("DOMMouseScroll",a,!1),e.onmousewheel=n.onmousewheel=a}function a(e){if(o("html, body").is(":animated")){if("on"!=i.wheel)return!1;o("html, body").stop()}}var i=o.extend({speed:2e3,easing:"swing",adjust:0,forthTop:!1,wheel:"off"},t);return this.on("click.smoothScroll",function(e){e.preventDefault();var n,t=this.hash;if("#"===t||0==i.forthTop&&""===t)throw new Error("指定の要素が見つかりませんでした。ページトップを指定する場合は、forthTopをtrueにしてください。");n=i.forthTop?0:o(t).offset().top-i.adjust,o("html,body").animate({scrollTop:n},i.speed,i.easing),s()}),this},fixedScroll:function(n){var s,a;return s=this,a=o.extend({classname:"fixed",offsetTop:100,isfade:!1,duration:Number.MAX_VALUE,start:t,end:t,leave:t},n),o(e).on("scroll.fixedScroll",function(){var e;e=Math.max(o("body").scrollTop(),o("html").scrollTop()),e>a.offsetTop&&e<=a.duration&&!s.hasClass(a.classname)&&(s.addClass(a.classname),a.isfade&&s.fadeIn("slow")),e<=a.offsetTop&&s.hasClass(a.classname)&&(a.isfade?s.fadeOut("slow",function(){s.removeClass(a.classname)}):s.removeClass(a.classname)),e>a.duration&&s.hasClass(a.classname)&&s.removeClass(a.classname)}),this}})}(jQuery,window,document),jQuery(document).ready(function(o){o("#js-pagetop").smoothScroll({speed:800,easing:"easeOutQuart",adjust:0,forthTop:!0,wheel:"off"})});