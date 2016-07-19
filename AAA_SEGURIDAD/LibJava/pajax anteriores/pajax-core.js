pAjax.__debugMode__ = false;

pAjax.debug = function (msg) {
    if (pAjax.__debugMode__) alert("pAjax Debug Console:\n\n" + msg);
}

pAjax.getDebugMode = function () { return pAjax.__debugMode__; }
pAjax.setDebugMode = function (bValue) { pAjax.__debugMode__ = bValue; }
pAjax.enableDebugMode = function () { pAjax.setDebugMode(true); }
pAjax.disableDebugMode = function () { pAjax.setDebugMode(false); }


function pAjax() {
    this.__request__ = null;

    if (typeof this.onInit == "function") this.onInit();
}

var _p = pAjax.prototype;


_p.prepare = function (sFuncName, sRequestType) {
    return this.__request__ = new pAjaxRequest(this, sFuncName, sRequestType);
}


_p.getRequest = function () { return this.__request__; }
_p.getReadyState = function () { return this.__request__.getReadyState(); }
_p.getResponse = function () { return this.__request__.getResponse(); }
_p.getXML = function () { return this.__request__.getXML(); }
_p.getText = function () { return this.__request__.getText(); }
_p.getError = function () { return this.__request__.getError(); }
// Backward compatibility
_p.getData = function () { return this.getResponse(); }
_p.toString = function () { return "[object pAjax]"; }




pAjaxRequest.GET = String("GET");
pAjaxRequest.POST = String("POST");

pAjaxRequest.ASYNC = true;
pAjaxRequest.SYNC = false;


function pAjaxRequest(oAjax, sFuncName, sRequestType) {
    this.__ajax__ = oAjax;
    this.__params__ = [];
    this.__URI__ = document.location.href;

    pAjax.debug("XmlHttp Object s being created");

    this.__setFunctionName__(sFuncName);
    this.__xmlHttp__ = XmlHttp.create();

    var oThis = this;
    this.__onreadystatechange__ = function () { oThis.__onreadystatechange(); };

    this.setRequestType(sRequestType);
    this.setUsername(null);
    this.setPassword(null);
}

var _p = pAjaxRequest.prototype;


_p.abort = function () { if (this.__xmlHttp__) this.__xmlHttp__.abort(); }


_p.execute = function (syncType) {
    if (syncType == null) syncType = pAjaxRequest.ASYNC;

    if (typeof syncType == "string")
        syncType = (syncType.toUpperCase() == "SYNC") ? pAjaxRequest.SYNC : pAjaxRequest.ASYNC;

    this.__exec__(syncType);
}


_p.async = function () { this.__exec__(pAjaxRequest.ASYNC); }
_p.sync = function () { this.__exec__(pAjaxRequest.SYNC); }


_p.__exec__ = function (bSync) {
    delete this.__cachedResponse__;

    if (typeof this.__ajax__.onCreate == "function") this.__ajax__.onCreate();

    var xmlDoc = this.__compileXML__();
    var uri = this.__compileURI__();
    var data = (this.__requestType__ == pAjaxRequest.POST) ? xmlDoc.xml : "null";

    this.abort();
    this.__xmlHttp__.open(this.__requestType__, String(uri), bSync, this.__username__, this.__password__);

    // Using Sync calls, Mozilla does not fire onreadystatechange event, but fires onload
    // Also, IE fires onreadystatechange and does not have onload event defined
    if (bSync) this.__xmlHttp__.onreadystatechange = this.__onreadystatechange__;
    else if (typeof this.__xmlHttp__.onload == "undefined") {
        var oThis = this;

        this.__xmlHttp__.onreadystatechange = function () {
            if (oThis.getReadyState() == 4) oThis.__onreadystatechange__();
        };
    } else this.__xmlHttp__.onload = this.__onreadystatechange__;

    pAjax.debug("FUNCTION: " + this.__funcName__ + "\n\nMODE: " + (bSync ? "Asynchronous" : "Synchronous") + "\n\nURI: " + uri + "\n\nPOST:\n" + data);

    // Addon to prevent weird Apache behaviour (encode is necessary to work in Mozilla)
    if (this.__requestType__ == pAjaxRequest.POST)
        this.__xmlHttp__.setRequestHeader("Post-Data", pAjaxParser.encodeURI(data));

    this.__xmlHttp__.send(xmlDoc);
}


_p.__compileXML__ = function () {
    var xmlDoc;

    if (this.__requestType__ == pAjaxRequest.POST) {
        xmlDoc = XmlDocument.create();
        xmlDoc.appendChild(xmlDoc.createElement("pAjaxCall"));

        var methodCall = xmlDoc.documentElement;

        var methodName = xmlDoc.createElement("pAjaxMethod");
        methodName.appendChild(xmlDoc.createTextNode(this.__funcName__));
        methodCall.appendChild(methodName);

        var methodParams = xmlDoc.createElement("pAjaxParams");
        methodCall.appendChild(methodParams);

        for (var item in this.__params__) {
        	if (typeof this.__params__[item] != "function")
		    	methodParams.appendChild(pAjaxParser.jsToXmlValueNode(item, this.__params__[item], xmlDoc));
        }

        return xmlDoc;
    }

    return null;
}


_p.__compileURI__ = function () {
    var uri = this.__URI__;

    if (this.__requestType__ == pAjaxRequest.GET) {
        var params = (function (itens) {
            var aStrItens = [];

            for (var p in itens) {
				if (typeof p[itens] != "function")
					aStrItens.push(pAjaxParser.jsToUrlString(p, itens[p]));
			}

            return aStrItens.join("");
        })(this.__params__);

        uri += ((this.__URI__.indexOf("?") == -1) ? "?" : "&") + "function=" + this.__funcName__;
        uri += params + "&rnd=" + (new Date()).getTime();
    }

    return uri;
}


_p.__onreadystatechange = function () {
    if (typeof this.__ajax__.onChange == "function") this.__ajax__.onChange();

	//alert("Status: " + this.__xmlHttp__.status);
    if (this.__xmlHttp__.readyState == 4 && this.__xmlHttp__.status == 200) {
        this.__cachedResponse__ = this.__xmlHttp__.responseXML;

        if (!this.errorOccurred() && this.__cachedResponse__.xml == "")
            throw new Error("Unknown RPC Error\n\n\n" + this.getText());


        if (this.getError() != null && typeof this.__ajax__.onError == "function")
        	this.__ajax__.onError();
        else {
			pAjax.debug("Recieved:\n\n" + this.__cachedResponse__.xml);

			if (typeof this.__ajax__.onLoad == "function") this.__ajax__.onLoad();
		}

        this.__dispose__();
    }
}


_p.__dispose__ = function () {
    delete this.__xmlHttp__.onreadystatechange;
    this.__xmlHttp__ = null;
}


_p.__setFunctionName__ = function (sFuncName) { this.__funcName__ = sFuncName; }


_p.setFormParam = function (oFormName) {
	if (typeof oFormName == "string") {
		oFormName = document.forms[oFormName] || document.getElementById(oFormName);
    }
    
    var sFormName = oFormName.name || oFormName.id;
    var c_params = new Object();

	for (var i = 0; i < oFormName.elements.length; i++) {
		var el = oFormName.elements[i];

		if (el.type && el.type != undefined) {
			switch (el.type) {
				case "text": case "password": case "hidden":
				case "textarea": case "button": case "submit":
                    c_params[el.name] = el.value;
					//this.setParam(el.name, el.value);
					break;

				case "select-one":
					if (el.selectedIndex >= 0) {
						c_params[el.name] = el.options[el.selectedIndex].value;
                        //this.setParam(el.name, el.options[el.selectedIndex].value);
					}

					break;
					
				case "select-multiple":
					var a = [];
					
					for (var j = 0; j < el.options.length; j++) {
						if (el.options[j].selected) {
							a.push(el.options[j].value);
						}
					}
                    paramName = el.name.replace("[", "");
                    paramName = paramName.replace("]", "");
                    c_params[paramName] = a;
					//this.setParam(el.name, a);
					break;
					
				case "checkbox": case "radio":
					if (el.checked || el.selected) {
                        c_params[el.name] = el.value
						//this.setParam(el.name, el.value);
					}

					break;
					
				default:
					// Does nothing
					break;
			}
		}
	}

    this.setParam(sFormName, c_params);
}


_p.getError = function () {
	if (this.errorOccurred() && this.__xmlHttp__.responseXML != null) {
		return this.__xmlHttp__.responseXML.parseError;
	} else if (this.errorOccurred()) {
		return this.__xmlHttp__.responseText;
	}
	
	return null;
}


_p.errorOccurred = function () { 
	if (!(typeof this.__xmlHttp__.responseXML == "object" && this.__xmlHttp__.responseXML != null)) {
		return true;
	}
	
	return (this.__xmlHttp__.responseXML.parseError && this.__xmlHttp__.responseXML.parseError.errorCode != 0);
}


_p.setParam = function (sParamName, sParamValue) {
   if (sParamValue == null) delete this.__params__[sParamName];
   else this.__params__[sParamName] = sParamValue;
}

_p.getParam = function (sParamName) { return this.__params__[sParamName]; }
_p.delParam = function (sParamName) { this.setParam(sParamName); }
_p.getResponse = function () { return pAjaxParser.parseXmlResponse(this.__cachedResponse__); }
_p.getXML = function () { return this.__cachedResponse__.documentElement.childNodes[0].xml; }
_p.getText = function () { return this.__xmlHttp__.responseText; }
_p.getReadyState = function () { return this.__xmlHttp__.readyState; }
_p.getXmlHttp = function () { return this.__xmlHttp__; }
_p.getLoaded = function () { return this.getReadyState() == 4; }
_p.getLoading = function () { return this.getReadyState() < 4; }
_p.setRequestType = function (sRequestType) { this.__requestType__ = String(sRequestType) || pAjaxRequest.GET; }
_p.getRequestType = function () { return this.__requestType__; }
_p.setURI = function (sURI) { this.__URI__ = sURI; }
_p.getURI = function () { return this.__URI__; }
_p.setUsername = function (sUsername) { this.__username__ = sUsername; }
_p.setPassword = function (sPassword) { this.__password__ = sPassword; }
_p.toString = function () { return "[object pAjaxRequest]"; }




function pAjaxCall(uri, method, callback) {
    var ajax = new pAjax();
    var req = ajax.prepare(method, pAjaxRequest.GET);

    if (uri != null) req.setURI(uri);
    
    if (arguments.length > 3) {
        var a = [];

        for (var i = 3; i < arguments.length; i++)
            a.push(arguments[i]);

        req.setParam("param", ((a.length == 1) ? a.pop() : a));
    }

    ajax.onLoad = function () { callback.call(this, this.getResponse()); }
    req.async();
}
