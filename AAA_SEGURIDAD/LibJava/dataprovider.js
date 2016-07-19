function dataProvider(pTxt) {
    pAjax.apply(this);
    this.searchText = pTxt;
    this.controller = null;
    this.typeAhead = true;
//    alert(this.searchText);
}

var _p = dataProvider.prototype = new pAjax;

_p.requestSuggestions = function (oAutoSuggestController, bTypeAhead) {
    this.controller = oAutoSuggestController;
    this.typeAhead = bTypeAhead;
    if ((this.controller.data && this.controller.data.length <= 1 )||           // si no hasy datos,
//         this.controller.searchSuggestion(this.controller.textbox.value) < 0 || // no hay coincidencia
         this.controller.textbox.value.length <= this.controller.minLength ) {  // se ha forzado la cantidad minima de letras                                  // **fah Oct/22/05   Buscar en la Bd solo si no esta en lista
        var req = this.prepare("suggestData", pAjaxRequest.POST);
//        req.setURI(this.controller.uri + escape(this.controller.textbox.value));
//        String(this.controller.textbox.value).replace('*', '%');
//xx;
        var slQry = this.controller.textbox.value;
		slQry.replace( /\*/g, "%", "g")
        var slUri=this.controller.uri;
//alert (unescape(slUri));
        if(slUri.indexOf("%7B") >= 0 ) {            // Soportar SQL texto con 2 parametros embebidos
		    slUri=slUri.replace("%7B1%7D", slQry + "%" );
//		    alert ("---" + unescape(slUri));
		}
		else    {
		    if (this.controller.staticData) slUri +="'%'" ;
		    else  slUri +="'" + slQry + "%'" ;
		}
	if (getFromurl("pAjaxDbg",false) > 0) {
		slUri=slUri.replace("?", "?pAjaxDbg=1&" );
		if (getFromurl("pAjaxDbg",false) == 2) alert ("---" + unescape(slUri));
	}
//	alert (">>>>>" + unescape(slUri));
	req.setURI(slUri);
//        req.setParam("text", escape(this.controller.textbox.value));
        req.setParam("text", this.controller.textbox.value);
        req.async();
        if (this.controller.addData) this.controller.addData();
   }
//   else this.controller.autoSuggest(this.controller.data, false)
}


_p.onLoad = function () {
        aResp= [];
        aResp = this.getResponse() ;
        if (!aResp) return;
        if (aResp.length < 0) return;
        this.controller.data = aResp[0];  // La primera columna es el texto
        for (j=1; j< aResp.length; j++) { // desde la segunda columna en adelante, los datos extra
            this.controller.columns[j]= aResp[j];
        }
//        this.controller.data = this.getResponse() ;
        this.controller.autoSuggest(this.controller.data, this.typeAhead);
    }
