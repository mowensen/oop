function setCookie(name,value,expire,path,domain,secure) {		// expire : relative time in milliseconds
	today = new Date();fixCookieDate(today);			//fixes mac time problems for the session
	var expires = new Date();
	expires.setTime(today.getTime() + expire)
	document.cookie =	name + "=" + escape(value)+
					"; expires=" + expires.toGMTString()+
					((path)?"; path="+path:"")+
					((domain)?"; domain="+domain:"")+
					((secure)?"; secure":"")
}

function getCookie(name) {
	var search = name + "="
	if (document.cookie.length > 0) {
		offset = document.cookie.indexOf(search)
		if (offset != -1) {
			offset += search.length;
			end = document.cookie.indexOf(";", offset)
			if (end == -1)end = document.cookie.length
			return unescape(document.cookie.substring(offset, end))
		}
		else return ""
	}
	else return ""
}

//  Function to correct 2.x Mac date bug.
function fixCookieDate (date) {
  var base = new Date(0);
  var skew = base.getTime(); // dawn of (Unix) time - should be 0
  if (skew > 0)  // Except on the Mac - ahead of its time
    date.setTime (date.getTime() - skew);
}
