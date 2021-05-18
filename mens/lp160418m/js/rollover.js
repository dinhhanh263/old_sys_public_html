function initRollovers() {
	if (!document.getElementById) return
	
	var aPreLoad = new Array();
	var sTempSrc;
	var aImages = document.getElementsByTagName('img');
	var aInputs = document.getElementsByTagName('input');

	doRollover(aImages);
	doRollover(aInputs);

	function doRollover(array) {
		for (var i = 0; i < array.length; i++) {
			if (array[i].className == 'rollover') {
				var src = array[i].getAttribute('src');
				var ftype = src.substring(src.lastIndexOf('.'), src.length);
				var hsrc = src.replace(ftype, '_on'+ftype);

				array[i].setAttribute('hsrc', hsrc);
				
				aPreLoad[i] = new Image();
				aPreLoad[i].src = hsrc;
				
				array[i].onmouseover = function() {
					sTempSrc = this.getAttribute('src');
					this.setAttribute('src', this.getAttribute('hsrc'));
				}
				
				array[i].onmouseout = function() {
					if (!sTempSrc) sTempSrc = this.getAttribute('src').replace('_on'+ftype, ftype);
					this.setAttribute('src', sTempSrc);
				}
			}
		}
	}
}

window.onload = initRollovers;