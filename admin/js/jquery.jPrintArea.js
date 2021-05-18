jQuery.jPrintArea=function(el,target)
{
var iframe=document.createElement('IFRAME');
var doc=null;
$(iframe).attr('style','position:absolute;width:0px;height:0px;left:-500px;top:-500px;');
document.body.appendChild(iframe);
doc=iframe.contentWindow.document;
var links=window.document.getElementsByTagName('link');
for(var i=0;i<links.length;i++)
if(links[i].rel.toLowerCase()=='stylesheet')
doc.write('<link type="text/css" rel="stylesheet" href="'+links[i].href+'"></link>');
var elhtml = $(el).html();
doc.write('<div class="'+$(el).attr("class")+'">'+elhtml+'</div>');
var targets = doc.getElementsByClassName(target);
  for(var i2=0;i2<targets.length;i2++){
    targets[i2].style.cssText += ";display:none;";
  };
doc.close();
iframe.onload = function(){
  iframe.contentWindow.focus();
  iframe.contentWindow.print();
  // alert('印刷しています...');
  document.body.removeChild(iframe);
}
}