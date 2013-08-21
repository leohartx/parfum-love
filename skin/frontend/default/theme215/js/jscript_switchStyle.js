function switchStyle(styleName){
	if (styleName){
		jQuery('body').removeClass('style1');
		jQuery('body').removeClass('style2');
		jQuery('body').removeClass('style3');
		jQuery('body').removeClass('style4');
		jQuery('body').removeClass('style5');
		jQuery('body').removeClass('style6');
		jQuery('#styleSwitch li').removeClass('selected');
		jQuery('#' + styleName).addClass('selected');
		jQuery('body').addClass(styleName);
	}
	createCookie('style', styleName, 365);
}

jQuery(document).ready(function(){
	jQuery('#styleSwitch li#style1').addClass('selected');
	jQuery('#styleSwitch li').bind('click', function(){
		switchStyle(this.id);
		return false;
	});
	var c = readCookie('style');
	if (c) switchStyle(c);
});

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}
function eraseCookie(name) {
	createCookie(name,"",-1);
}