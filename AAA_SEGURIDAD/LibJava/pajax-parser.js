/**
 *	@rev fah 16/07/2011	  Caso Servidores con PHP 5.3: Evitar errores de generacion xml, se elimina codificacion uri para caracteres <>/
 */
pAjaxParser.getJsType = function (v) {
    switch (typeof v) {
        case "object":
            if (v == null || v == undefined)
                throw new Error("Could not set null value as RPC param");
            else if (v.constructor == Date)
                return "dateTime";
            else if (v.constructor == Array)
                return "array";
            else return "struct";

        case "string":
            if (v.substr(0, 1) != "0" && parseFloat(v) == v)
                return "number";
            else if (v.length > 100 || (v.indexOf("<") >= 0 || v.indexOf(">") >= 0))
                return "text";

            return "string";

        default:
            return typeof v;
    }
}


pAjaxParser.jsDateToIso8601 = function (d) {
    function preZero(n) { return (n > 9) ? String(n) : "0" + n; };

    return d.getFullYear() + preZero(d.getMonth() + 1) + preZero(d.getDate()) + "T" + preZero(d.getHours()) + ":" + preZero(d.getMinutes()) + ":" + preZero(d.getSeconds());
}


pAjaxParser.iso8601ToJsDate = function (s) {
    var d = new Date;

    d.setFullYear(s.substring(0, 4), s.substring(4, 6) - 1, s.substring(6, 8));
    d.setHours(s.substring(9, 11), s.substring(12, 14), s.substring(15, 17), 0);

    return d;
}


pAjaxParser.jsToXmlNode = function (v, xmlDoc) {
    switch (pAjaxParser.getJsType(v)) {
        case "number":
        case "string":
            return xmlDoc.createTextNode(v);

        case "text":
            return xmlDoc.createCDATASection(v);

        case "boolean":
            return xmlDoc.createTextNode(v ? "true" : "false");

        case "dateTime":
            return xmlDoc.createTextNode(pAjaxParser.jsDateToIso8601(v));

        case "array":
            var el = xmlDoc.createElement("data");

            for (var i = 0; i < v.length; i++)
                el.appendChild(pAjaxParser.jsToXmlValueNode("pAjaxItem-" + i, v[i], xmlDoc));

            return el;

        case "struct":
            var el = xmlDoc.createElement("data");

            for (var p in v) {
            	if (typeof v[p] != "function")
				    el.appendChild(pAjaxParser.jsToXmlValueNode(p, v[p], xmlDoc));
            }

            return el;
    }

    throw new Error("Unknown JavaScript Type");
}


pAjaxParser.jsToXmlValueNode = function (n, v, xmlDoc) {
    n = n.replace("[", "__91__");
    n = n.replace("]", "__93__");

	var el = xmlDoc.createElement(n);
    var o = pAjaxParser.jsToXmlNode(v, xmlDoc);

	el.setAttribute("type", pAjaxParser.getJsType(v));

    if (o.nodeName == "data" && !o.getAttribute("type")) {
       	while (o.hasChildNodes())
			el.appendChild(o.firstChild);
	} else el.appendChild(o);

    return el;
}


pAjaxParser.jsToUrlString = function (n, v) {
    var str = "";

    switch (pAjaxParser.getJsType(v)) {
        case "string":
        case "text":
            str += "&" + n + "=" + pAjaxParser.encodeURI(v);
            break;

        case "number":
            str += "&" + n + "=" + v;
            break;

        case "boolean":
            str += "&" + n + "=" + (v ? "true" : "false");
            break;

        case "dateTime":
            str += "&" + n + "=" + pAjaxParser.encodeURI(pAjaxParser.jsDateToIso8601(v));
            break;

        case "array":
            for (var i = 0; i < v.length; i++)
                str += pAjaxParser.jsToUrlString(n + "[" + i + "]", v[i]);
            break;

        case "struct":
            for (var p in v)
                str += pAjaxParser.jsToUrlString(n + "[" + p + "]", v[p]);
            break;
    }

    return str;
}


// Correctly handle URI encoding, preventing bug of browsers to convert special chars (ie: ü as Ã¼)
pAjaxParser.encodeURI = function (str) {
    var newStr = ""; var dec;
    var itens = "<>/0123456789ABCDEF";

    for (var i = 0; i < str.length; i++) {
        dec = (str.charAt(i)).charCodeAt(0);

        // 0-9 (48-57), A-Z (65-90), a-z (97-122)
        if (!(dec >= 48 && dec <= 57) && !(dec >= 65 && dec <= 90) && !(dec >= 97 && dec <= 122))
            newStr += "%" + String(itens.charAt((dec - (dec % 16)) / 16) + itens.charAt(dec % 16));
        else newStr += str.charAt(i);
    }

    return newStr;
}


// Handle URI decoding
pAjaxParser.decodeURI = function (str) {
    var newStr = ""; var hex;
    var itens = "<>/0123456789ABCDEF";  //#fah 16/07/2011	  Evitar errores de generacion xml

    for (var i = 0; i < str.length; i++) {
        if (str.charAt(i) == "%") {
            hex = String(str.charAt(i + 1) + str.charAt(i + 2));
            newStr += String.fromCharCode(parseInt(hex, 16));
            i = i + 2;
        } else {
            newStr += str.charAt(i);

            if (str.charAt(i) == "%" && str.charAt(i + 1) == "%") i++;
        }
    }

    return newStr;
}


pAjaxParser.parseXmlResponse = function (xml) {
    if (xml.parseError && xml.parseError.reason != "") {
        var error = xml.parseError;

        alert("Detailed Description of XML Parse Error:\n\nError Code: " + error.errorCode +
          "\nFile Pos: " + error.filePos + "\nLine: " + error.line +
          "\nLine Pos: " + error.linePos + "\nURL: " + error.url +
          "\nSRC Text: " + error.srcText + "\nReason: " + error.reason);

        throw new Error("XML Parse Error\n\nReason: " + xml.parseError.reason);
    } else if (xml && xml.documentElement != null) {
        var root = xml.documentElement;

        if (root.tagName == "pAjaxError") {
            var e = new Error(pAjaxParser.__getFirstChildElement__(root).text);
            pAjaxParser.__setError__(e);
        }

        return pAjaxParser.xmlRootNodeToJs(root);
    }

    throw new Error("Invalid XML document returned from RPC server");
}


pAjaxParser.xmlNodeToJs = function (oNode) {
    if (oNode.nodeType == 3)
        return oNode.data;

    switch (oNode.getAttribute("type")) {
        case "string":
            return oNode.text;

        case "text":
            return oNode.firstChild.text;

        case "dateTime":
            return pAjaxParser.iso8601ToJsDate(oNode.text);

        case "boolean":
            return (oNode.text == "true") ? 1 : 0;

        case "number":
            return Number(oNode.text);

        case "array":
            var nodeList = oNode.childNodes;
            var res = [];

            for (var i = 0; i < nodeList.length; i++) {
                if (nodeList[i].nodeType == 1)
                    res.push(pAjaxParser.xmlNodeToJs(nodeList[i]));
            }

            return res;

        case "struct":
            var members = oNode.childNodes;
            var o = {};
            var name, value;
            var re = /pAjaxItem-([0-9]*)/i;

            for (var i = 0; i < members.length; i++) {
                if (members[i].nodeType == 1) {
                    name = (!(re.test(members[i].tagName))) ? members[i].tagName : (String(members[i].tagName)).replace(re, "$1");
                    value = pAjaxParser.xmlNodeToJs(members[i]);

                    o[name] = value;
                }
            }

            return o;

        default:
            return undefined;
    }
}


pAjaxParser.xmlValueNodeToJs = function (n) {
    var c = pAjaxParser.__getFirstChildElement__(n) || n.firstChild;

    if (c) return pAjaxParser.xmlNodeToJs(c);

    return "";
}


pAjaxParser.xmlRootNodeToJs = function (oNode) {
    var o = {};
    var name, value;
    for (var i = 0; i < oNode.childNodes.length; i++) {
        name = oNode.childNodes[i].tagName;
        value = pAjaxParser.xmlNodeToJs(oNode.childNodes[i]);

        o[name] = value;
    }

    return o.result;
}


pAjaxParser.__getFirstChildElement__ = function (p) {
    var c = p.firstChild;

    while (c) {
        if (c.nodeType == 1) return c;
        c = c.nextSibling;
    }

    return null;
}


pAjaxParser.__getLastChildElement__ = function (p) {
    var c = p.lastChild;

    while (c) {
        if (c.nodeType == 1) return c;
        c = c.previousSibling;
    }

    return null;
}


function pAjaxParser() {}