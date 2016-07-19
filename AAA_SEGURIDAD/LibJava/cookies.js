//-------------------------------------------------------------------------
// Function to set a cookie
//-------------------------------------------------------------------------
function setCookie(name, value) {
   document.cookie = name + "=" + escape(value) ;
}

//-------------------------------------------------------------------------
// Function to retrieve a cookie
//-------------------------------------------------------------------------
function getCookie(Name, Default) {
   var search = Name + "="
   if (!Default) Default = false;
   if (document.cookie.length > 0) { // if there are any cookies
      offset = document.cookie.indexOf(search)
      if (offset != -1) { // if cookie exists
         offset += search.length
         // set index of beginning of value
         end = document.cookie.indexOf(";", offset)
         // set index of end of cookie value
         if (end == -1)
            end = document.cookie.length
         return unescape(document.cookie.substring(offset, end))

      }
      return Default;
   }
   return Default;
}
//-------------------------------------------------------------------------
// Function to retrieve a parameter from url
//-------------------------------------------------------------------------
function getFromurl(Name, Def) {
   if (arguments.length < 2 ) Def=false;
   var search = Name + "="
   var surl   = new String(document.URL);
   
   if (surl.length > 0) { 	// if there are any Url
      offset = surl.indexOf(search)
      if (offset != -1) { 			// if var exists
         offset += search.length
							        // set index of beginning of value
         end = surl.indexOf("&", offset)
						           // set index of end of cookie value
         if (end == -1)
            end = surl.length;
         return unescape(surl.substring(offset, end))
         }
   }
   return Def;
}

