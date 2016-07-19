/*  Ext.ux.PrinterFriendly, Version 0.2
 *  (c) 2008 Steffen Hiller (http://www.extjswithrails.com)
 *
 *  License: Ext.ux.PrinterFriendly is licensed under the terms of
 *  the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 *  that the code/component(s) do NOT become part of another Open Source or Commercially
 *  licensed development library or toolkit without explicit permission.
 *
 *  License details: http://www.gnu.org/licenses/lgpl.html
 *
 *  This is an extension for the Ext JS Library, for more information see http://www.extjs.com.
 *--------------------------------------------------------------------------*/

Ext.namespace('Ext.ux.PrinterFriendly.util.CSS');

Ext.ux.PrinterFriendly.util.CSS.addStyleSheet = function(url){
    // var ss = { tag: 'link', rel: 'stylesheet', type: 'text/css', href: url };
    // Not working in Safari (DOM Exception 9):
    // Ext.DomHelper.append(document.getElementsByTagName("head")[0], ss);
    var ss = document.createElement("link");
    ss.setAttribute("rel", "stylesheet");
    ss.setAttribute("type", "text/css");
    ss.setAttribute("href", url);
    document.getElementsByTagName("head")[0].appendChild(ss);
};