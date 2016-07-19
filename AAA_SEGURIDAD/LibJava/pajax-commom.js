function XmlHttp() {
    var req;

    try {
        if (window.XMLHttpRequest) {
            req = new XMLHttpRequest();

            if (req.readyState == null) {
                req.readyState = 1;

                req.addEventListener("load", function () {
                    req.readyState = 4;

                    if (typeof req.onreadystatechange == "function")
                        req.onreadystatechange();
                }, false);
            }

            return req;
        }

        if (window.ActiveXObject) {
            var prefixes = ["MSXML5", "MSXML4", "MSXML3", "MSXML2", "MSXML", "Microsoft"];

            for (var i = 0; i < prefixes.length; i++) {
                try {
                    req = new ActiveXObject(prefixes[i] + ".XmlHttp");
                    return req;
                } catch (ex) {}
            }
        }
    } catch (ex) {}

    throw new Error("XmlHttp Objects not supported by client browser");
}


XmlHttp.prototype = new Object;


XmlHttp.create = function () {
    return new XmlHttp();
}









function XmlDocument() {
    var req;

    try {
        if (window.ActiveXObject) {
            var libraries = ["MSXML2.DOMDocument.5.0", "MSXML2.DOMDocument.4.0", "MSXML2.DOMDocument.3.0", "MSXML2.DOMDocument", "Microsoft.XmlDom"];

            for (var i = 0; i < libraries.length; i++) {
                try {
                    req = new ActiveXObject(libraries[i]);
                    return req;
                } catch (ex) {}
            }
        } else if (document.implementation && document.implementation.createDocument) {
            req = document.implementation.createDocument("", "", null);
            req.addEventListener("load", function () { this.readyState = 4; }, false);
            //req.readyState = 4;

            return req;
        }
    } catch (ex) { }

    throw new Error("XmlDocument Objects not supported by client browser");
}


XmlDocument.prototype = new Object;


XmlDocument.create = function () { return new XmlDocument(); }


if (window.XMLHttpRequest) {
    (function () {
        var _xmlDocumentPrototype = XMLDocument.prototype;

        _xmlDocumentPrototype.__proto__ = { __proto__ : _xmlDocumentPrototype.__proto__ };

        var _p = _xmlDocumentPrototype.__proto__;


        _p.createNode = function (aType, aName, aNamespace) {
            switch (aType) {
                case 1:
                    if (aNamespace && aNamespace != "")
                        return this.createElementNS(aNamespace, aName);
                    else
                        return this.createElement(aName);

                case 2:
                    if (aNamespace && aNamespace != "")
                        return this.createAttributeNS(aNamespace, aName);
                    else
                        return this.createAttribute(aName);

                case 3:
                default:
                    return this.createTextNode("");
            }
        };


        _p.loadXML = function (sXml) {
            var d = (new DOMParser()).parseFromString(sXml, "text/xml");

            while (this.hasChildNodes())
                this.removeChild(this.lastChild);

            for (var i = 0; d < d.childNodes.length; i++)
                this.appendChild(this.importNode(d.childNodes[i], true));
        };


        _p.__load__ = _xmlDocumentPrototype.load;


        _p.load = function (sURI) {
            this.readyState = 0;
            this.__load__(sURI);
        };


        _p.setProperty = function (sName,sValue) {
            if (sName == "SelectionNamespaces") {
                this.__selectionNamespaces__ = {};
                var parts = sValue.split(/\s+/);
                var re = /^xmlns\:([^=]+)\=((\"([^\"]*)\")|(\'([^\']*)\'))$/;

                for (var i = 0; i < parts.length; i++) {
                    re.test(parts[i]);
                    this.__selectionNamespaces__[RegExp.$1] = RegExp.$4 || RegExp.$6;
                }
            }
        };


        _p.__defineSetter__("onreadystatechange", function (f) {
            if(this.__onreadystatechange__)
                this.removeEventListener("load", this.__onreadystatechange__, false);

            this.__onreadystatechange__ = f;

            if (f) this.addEventListener("load", f, false);

            return f;
        });


        _p.__defineGetter__("onreadystatechange", function () {
            return this.__onreadystatechange__;
        });


        XmlDocument.__mozHasParseError__ = function (oDoc) {
            return !oDoc.documentElement ||
                    oDoc.documentElement.localName == "parsererror" &&
                    oDoc.documentElement.getAttribute("xmlns") == "http://www.mozilla.org/newlayout/xml/parsererror.xml";
        };


        _p.__defineGetter__("parseError", function() {
            var hasError = XmlDocument.__mozHasParseError__(this);
            var res = { errorCode: 0, filepos: 0, line: 0, linepos: 0, reason: "", srcText: "", url:"" };

            if (hasError) {
                res.errorCode= -1;

                try {
                    res.srcText = this.getElementsByTagName("sourcetext")[0].firstChild.data;
                    res.srcText = res.srcText.replace(/\n\-\^$/,"");
                } catch (ex) { res.srcText = ""; }

                try {
                    var s = this.documentElement.firstChild.data;
                    var re = /XML Parsing Error\:(.+)\nLocation\:(.+)\nLine Number(\d+)\,Column(\d+)/;
                    var a = re.exec(s);
                    res.reason = a[1];
                    res.url = a[2];
                    res.line = a[3];
                    res.linepos = a[4];
                } catch (ex) { res.reason = "Unknown"; }
            }

            return res;
        });


        var _nodePrototype = Node.prototype;


        _nodePrototype.__proto__ = { __proto__ : _nodePrototype.__proto__ };


        _p = _nodePrototype.__proto__;


        _p.__defineGetter__("xml", function() {
            return (new XMLSerializer()).serializeToString(this, "text/xml");
        });


        _p.__defineGetter__("baseName", function() {
            var lParts = this.nodeName.split(":");

            return lParts[lParts.length - 1];
        });


        _p.__defineGetter__("text", function() {
            var sb = new Array(this.childNodes.length);

            for (var i = 0; i < this.childNodes.length; i++)
                sb[i] = this.childNodes[i].text;

            return sb.join("");
        });


        _p.selectNodes = function (sExpr) {
            var d = (this.nodeType == 9) ? this : this.ownerDocument;
            var nsRes = d.createNSResolver((this.nodeType == 9) ? this.documentElement : this);
            var nsRes2;

            if (d.__selectionNamespaces__) {
                nsRes2 = function (s) {
                    if (s in d.__selectionNamespaces__)
                        return d.__selectionNamespaces__[s];

                    return nsRes.lookupNamespaceURI(s);
                };
            } else nsRes2 = nsRes;

            var xpRes = d.evaluate(sExpr, this, nsRes2, 5, null);
            var res = [];
            var item;

            while ((item = xpRes.iterateNext()))
                res.push(item);

            return res;
        };


        _p.selectSingleNode = function (sExpr) {
            var d = (this.nodeType == 9) ? this : this.ownerDocument;
            var nsRes = d.createNSResolver((this.nodeType == 9) ? this.documentElement : this);
            var nsRes2;

            if(d.__selectionNamespaces__) {
                nsRes2 = function (s) {
                    if (s in d.__selectionNamespaces__)
                        return d.__selectionNamespaces__[s];

                    return nsRes.lookupNamespaceURI(s);
                };
            } else nsRes2 = nsRes;

            var xpRes = d.evaluate(sExpr, this, nsRes2, 9, null);

            return xpRes.singleNodeValue;
        };


        _p.transformNode = function (oXsltNode) {
            var d = (this.nodeType == 9) ? this : this.ownerDocument;
            var processor = new XSLTProcessor();

            processor.importStylesheet(oXsltNode);

            var df = processor.transformToFragment(this, d);

            return df.xml;
        };


        _p.transformNodeToObject = function (oXsltNode, oOutputDocument) {
            var d = (this.nodeType == 9) ? this : this.ownerDocument;
            var outDoc = (oOutputDocument.nodeType == 9) ? oOutputDocument : oOutputDocument.ownerDocument;
            var processor = new XSLTProcessor();

            processor.importStylesheet(oXsltNode);

            var df = processor.transformToFragment(this, d);

            while (oOutputDocument.hasChildNodes())
                oOutputDocument.removeChild(oOutputDocument.lastChild);

            for (var i = 0; i < df.childNodes.length; i++)
                oOutputDocument.appendChild(outDoc.importNode(df.childNodes[i],true));
        };


        var _attrPrototype = Attr.prototype;


        _attrPrototype.__proto__ = { __proto__ : _attrPrototype.__proto__ };


        _p = _attrPrototype.__proto__;


        _p.__defineGetter__("xml", function() {
            var nv = (new XMLSerializer()).serializeToString(this);

            return this.nodeName + "=\"" + nv.replace(/\"/g,"&quot;") + "\"";
        });


        var _textPrototype = Text.prototype;


        _textPrototype.__proto__ = { __proto__ : _textPrototype.__proto__ };


        _p = _textPrototype.__proto__;


        _p.__defineGetter__("text", function() {
            return this.nodeValue;
        });
    })();
}
