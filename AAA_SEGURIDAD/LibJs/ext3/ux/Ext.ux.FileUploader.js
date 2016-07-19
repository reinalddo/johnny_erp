<!-- vim: ts=2:sw=2:nu:fdc=2:spell
 
Source viewer
 
@author    Ing.Jozef Sakáloš
@copyright (c) 2008, by Ing. Jozef Sakáloš
@date      9. April 2008
@version   $Id: source.php 630 2009-03-16 18:21:10Z jozo $
 
@license application.html is licensed under the terms of the Open Source
LGPL 3.0 license. Commercial use is permitted to the extent that the 
code/component(s) do NOT become part of another Open Source or Commercially
licensed development library or toolkit without explicit permission.
 
License details: http://www.gnu.org/licenses/lgpl.html
-->
 
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="../ext/resources/css/ext-all.css">
  <link rel="shortcut icon" href="../img/extjs.ico" />

  <script type="text/javascript" src="../ext/adapter/ext/ext-base.js"></script>
  <script type="text/javascript" src="../ext/ext-all-debug.js"></script>

  <title id="page-title">Source of Ext.ux.FileUploader.js</title>
  <style type="text/css">
	#ct {
		margin:8px;
	}
	#src-name {
		font-size:13px;
		font-family:arial, sans-serif;
	}
	#code {
		position:relative;
		padding:10px;
		border:1px solid silver;
		border-left-width:8px;
		background:#f8f8f8;
		font-size:12px;
		font-family:monospace;
		margin: 0 0 8px 0;
	}
	#code ol {
		list-style:decimal outside;
		margin: 0px 0px 0px 44px;
	}
	#code li {
		white-space:pre;
	}
  #code li:hover {
		background:#e8e8e8;
	}
	#themect {
		position:absolute;
		top:100px;
		right:9px;
	}
	.adsense {
		margin: 0 0 10px 0;
	}
  </style>
	<script type="text/javascript">
		Ext.BLANK_IMAGE_URL = '../ext/resources/images/default/s.gif';
		Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
		Ext.onReady(function() {
			var theme = Ext.state.Manager.get('srctheme') || 'typical';
			var tc = new Ext.form.ComboBox({
				store:new Ext.data.SimpleStore({
					 id:0
					,fields:['theme', 'themeName']
					,data:[["acid","Acid"],["bipolar","Bipolar"],["blacknblue","Blacknblue"],["bright","Bright"],["contrast","Contrast"],["darkblue","Darkblue"],["darkness","Darkness"],["desert","Desert"],["easter","Easter"],["emacs","Emacs"],["golden","Golden"],["greenlcd","Greenlcd"],["ide-anjuta","Ide-anjuta"],["ide-codewarrior","Ide-codewarrior"],["ide-devcpp","Ide-devcpp"],["ide-eclipse","Ide-eclipse"],["ide-kdev","Ide-kdev"],["ide-msvcpp","Ide-msvcpp"],["ide-xcode","Ide-xcode"],["kwrite","Kwrite"],["lucretia","Lucretia"],["matlab","Matlab"],["moe","Moe"],["navy","Navy"],["nedit","Nedit"],["neon","Neon"],["night","Night"],["orion","Orion"],["pablo","Pablo"],["peachpuff","Peachpuff"],["print","Print"],["rand01","Rand01"],["seashell","Seashell"],["the","The"],["typical","Typical"],["vampire","Vampire"],["vim-dark","Vim-dark"],["vim","Vim"],["whitengrey","Whitengrey"],["zellner","Zellner"]]
				})
				,renderTo:'themect'
				,valueField:'theme'
				,displayField:'themeName'
				,forceSelection:true
				,editable:false
				,mode:'local'
				,triggerAction:'all'
				,value:theme
				,listeners:{
					select:function() {
						var theme = this.getValue();
						Ext.getDoc().dom.cookie = 'srctheme=' + theme;
						Ext.state.Manager.set('srctheme', theme);
						window.location.search = "srctheme=" + theme + '&file=' + 'js/Ext.ux.FileUploader.js';
					}
				}
			});
		});
	</script>
</head>
<body>
<div id="ct">
	<div id="themect"></div>
	<div class="adsense">
		<script type="text/javascript"><!--
		google_ad_client = "pub-2768521146228687";
		/* 728x90, for sources */
		google_ad_slot = "3128262044";
		google_ad_width = 728;
		google_ad_height = 90;
		//-->
		</script>
		<script type="text/javascript"
		src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>
	</div>
	<span id="src-name" style="line-height:22px;">Source of <b>js/Ext.ux.FileUploader.js:</b></span>
	<div id="code"><ol><li style="color:#666666"><span style="color:#666666; font-style:italic">// vim: ts=4:sw=4:nu:fdc=4:nospell</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> * Ext.ux.FileUploader</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> *</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> * &#64;author  Ing. Jozef Sakáloš</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> * &#64;version $Id: Ext.ux.FileUploader.js 302 2008-08-03 20:57:33Z jozo $</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> * &#64;date    15. March 2008</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> *</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> * &#64;license Ext.ux.FileUploader is licensed under the terms of</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> * that the code/component(s) do NOT become part of another Open Source or Commercially</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> * licensed development library or toolkit without explicit permission.</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> *</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> * License details: http://www.gnu.org/licenses/lgpl.html</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> */</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">/*global Ext */</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> * &#64;class Ext.ux.FileUploader</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> * &#64;extends Ext.util.Observable</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> * &#64;constructor</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic"> */</span></li>
<li style="color:#666666">Ext<span style="color:#ff0000">.</span>ux<span style="color:#ff0000">.</span>FileUploader <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>config<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">    Ext<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">apply</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">,</span> config<span style="color:#ff0000">);</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// call parent</span></li>
<li style="color:#666666">    Ext<span style="color:#ff0000">.</span>ux<span style="color:#ff0000">.</span>FileUploader<span style="color:#ff0000">.</span>superclass<span style="color:#ff0000">.</span>constructor<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">apply</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">,</span> arguments<span style="color:#ff0000">);</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// add events</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">addEvents</span><span style="color:#ff0000">(</span></li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * &#64;event beforeallstart</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * Fires before an upload (of all files) is started. Return false to cancel the event.</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * &#64;param {Ext.ux.FileUploader} this</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         */</span></li>
<li style="color:#666666">         <span style="color:#ff0000">'beforeallstart'</span></li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * &#64;event allfinished</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * Fires after upload (of all files) is finished</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * &#64;param {Ext.ux.FileUploader} this</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         */</span></li>
<li style="color:#666666">        <span style="color:#ff0000">,</span><span style="color:#ff0000">'allfinished'</span></li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * &#64;event beforefilestart</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * Fires before the file upload is started. Return false to cancel the event.</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * Fires only when singleUpload = false</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * &#64;param {Ext.ux.FileUploader} this</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * &#64;param {Ext.data.Record} record upload of which is being started</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         */</span></li>
<li style="color:#666666">        <span style="color:#ff0000">,</span><span style="color:#ff0000">'beforefilestart'</span></li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * &#64;event filefinished</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * Fires when file finished uploading.</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * Fires only when singleUpload = false</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * &#64;param {Ext.ux.FileUploader} this</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * &#64;param {Ext.data.Record} record upload of which has finished</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         */</span></li>
<li style="color:#666666">        <span style="color:#ff0000">,</span><span style="color:#ff0000">'filefinished'</span></li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * &#64;event progress</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * Fires when progress has been updated</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * &#64;param {Ext.ux.FileUploader} this</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * &#64;param {Object} data Progress data object</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         * &#64;param {Ext.data.Record} record Only if singleUpload = false</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">         */</span></li>
<li style="color:#666666">        <span style="color:#ff0000">,</span><span style="color:#ff0000">'progress'</span></li>
<li style="color:#666666">    <span style="color:#ff0000">);</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666"><span style="color:#ff0000">};</span> <span style="color:#666666; font-style:italic">// eo constructor</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">Ext<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">extend</span><span style="color:#ff0000">(</span>Ext<span style="color:#ff0000">.</span>ux<span style="color:#ff0000">.</span>FileUploader<span style="color:#ff0000">,</span> Ext<span style="color:#ff0000">.</span>util<span style="color:#ff0000">.</span>Observable<span style="color:#ff0000">, {</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// configuration options</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;cfg {Object} baseParams baseParams are sent to server in each request.</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">     baseParams<span style="color:#ff0000">:{</span>cmd<span style="color:#ff0000">:</span><span style="color:#ff0000">'upload'</span><span style="color:#ff0000">,</span>dir<span style="color:#ff0000">:</span><span style="color:#ff0000">'.'</span><span style="color:#ff0000">}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;cfg {Boolean} concurrent true to start all requests upon upload start, false to start</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * the next request only if previous one has been completed (or failed). Applicable only if</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * singleUpload = false</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>concurrent<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">true</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;cfg {Boolean} enableProgress true to enable querying server for progress information</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>enableProgress<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">true</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;cfg {String} jsonErrorText Text to use for json error</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>jsonErrorText<span style="color:#ff0000">:</span><span style="color:#ff0000">'Cannot decode JSON object'</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;cfg {Number} Maximum client file size in bytes</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>maxFileSize<span style="color:#ff0000">:</span><span style="color:#a900a9">524288</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;cfg {String} progressIdName Name to give hidden field for upload progress identificator</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>progressIdName<span style="color:#ff0000">:</span><span style="color:#ff0000">'UPLOAD_IDENTIFIER'</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;cfg {Number} progressInterval How often (in ms) is progress requested from server</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>progressInterval<span style="color:#ff0000">:</span><span style="color:#a900a9">2000</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;cfg {String} progressUrl URL to request upload progress from</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>progressUrl<span style="color:#ff0000">:</span><span style="color:#ff0000">'progress.php'</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;cfg {Object} progressMap Mapping of received progress fields to store progress fields</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>progressMap<span style="color:#ff0000">:{</span></li>
<li style="color:#666666">         bytes_total<span style="color:#ff0000">:</span><span style="color:#ff0000">'bytesTotal'</span></li>
<li style="color:#666666">        <span style="color:#ff0000">,</span>bytes_uploaded<span style="color:#ff0000">:</span><span style="color:#ff0000">'bytesUploaded'</span></li>
<li style="color:#666666">        <span style="color:#ff0000">,</span>est_sec<span style="color:#ff0000">:</span><span style="color:#ff0000">'estSec'</span></li>
<li style="color:#666666">        <span style="color:#ff0000">,</span>files_uploaded<span style="color:#ff0000">:</span><span style="color:#ff0000">'filesUploaded'</span></li>
<li style="color:#666666">        <span style="color:#ff0000">,</span>speed_average<span style="color:#ff0000">:</span><span style="color:#ff0000">'speedAverage'</span></li>
<li style="color:#666666">        <span style="color:#ff0000">,</span>speed_last<span style="color:#ff0000">:</span><span style="color:#ff0000">'speedLast'</span></li>
<li style="color:#666666">        <span style="color:#ff0000">,</span>time_last<span style="color:#ff0000">:</span><span style="color:#ff0000">'timeLast'</span></li>
<li style="color:#666666">        <span style="color:#ff0000">,</span>time_start<span style="color:#ff0000">:</span><span style="color:#ff0000">'timeStart'</span></li>
<li style="color:#666666">    <span style="color:#ff0000">}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;cfg {Boolean} singleUpload true to upload files in one form, false to upload one by one</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>singleUpload<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">false</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;cfg {Ext.data.Store} store Mandatory. Store that holds files to upload</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;cfg {String} unknownErrorText Text to use for unknow error</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>unknownErrorText<span style="color:#ff0000">:</span><span style="color:#ff0000">'Unknown error'</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;cfg {String} url Mandatory. URL to upload to</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// private</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * uploads in progress count</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;private</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>upCount<span style="color:#ff0000">:</span><span style="color:#a900a9">0</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// methods</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * creates form to use for upload.</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;private</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;return {Ext.Element} form</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>createForm<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> progressId <span style="color:#ff0000">=</span> <span style="color:#000000; font-weight:bold">parseInt</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">Math</span><span style="color:#ff0000">.</span><span style="color:#ec7f15">random</span><span style="color:#ff0000">() *</span> <span style="color:#a900a9">1</span>e10<span style="color:#ff0000">,</span> <span style="color:#a900a9">10</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> form <span style="color:#ff0000">=</span> Ext<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">getBody</span><span style="color:#ff0000">().</span><span style="color:#000000; font-weight:bold">createChild</span><span style="color:#ff0000">({</span></li>
<li style="color:#666666">             tag<span style="color:#ff0000">:</span><span style="color:#ff0000">'form'</span></li>
<li style="color:#666666">            <span style="color:#ff0000">,</span>action<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>url</li>
<li style="color:#666666">            <span style="color:#ff0000">,</span><span style="color:#ec7f15">method</span><span style="color:#ff0000">:</span><span style="color:#ff0000">'post'</span></li>
<li style="color:#666666">            <span style="color:#ff0000">,</span>cls<span style="color:#ff0000">:</span><span style="color:#ff0000">'x-hidden'</span></li>
<li style="color:#666666">            <span style="color:#ff0000">,</span>id<span style="color:#ff0000">:</span>Ext<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">id</span><span style="color:#ff0000">()</span></li>
<li style="color:#666666">            <span style="color:#ff0000">,</span>cn<span style="color:#ff0000">:[{</span></li>
<li style="color:#666666">                 tag<span style="color:#ff0000">:</span><span style="color:#ff0000">'input'</span></li>
<li style="color:#666666">                <span style="color:#ff0000">,</span><span style="color:#ec7f15">type</span><span style="color:#ff0000">:</span><span style="color:#ff0000">'hidden'</span></li>
<li style="color:#666666">                <span style="color:#ff0000">,</span><span style="color:#ec7f15">name</span><span style="color:#ff0000">:</span><span style="color:#ff0000">'APC_UPLOAD_PROGRESS'</span></li>
<li style="color:#666666">                <span style="color:#ff0000">,</span><span style="color:#ec7f15">value</span><span style="color:#ff0000">:</span>progressId</li>
<li style="color:#666666">            <span style="color:#ff0000">},{</span></li>
<li style="color:#666666">                 tag<span style="color:#ff0000">:</span><span style="color:#ff0000">'input'</span></li>
<li style="color:#666666">                <span style="color:#ff0000">,</span><span style="color:#ec7f15">type</span><span style="color:#ff0000">:</span><span style="color:#ff0000">'hidden'</span></li>
<li style="color:#666666">                <span style="color:#ff0000">,</span><span style="color:#ec7f15">name</span><span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressIdName</li>
<li style="color:#666666">                <span style="color:#ff0000">,</span><span style="color:#ec7f15">value</span><span style="color:#ff0000">:</span>progressId</li>
<li style="color:#666666">            <span style="color:#ff0000">},{</span></li>
<li style="color:#666666">                 tag<span style="color:#ff0000">:</span><span style="color:#ff0000">'input'</span></li>
<li style="color:#666666">                <span style="color:#ff0000">,</span><span style="color:#ec7f15">type</span><span style="color:#ff0000">:</span><span style="color:#ff0000">'hidden'</span></li>
<li style="color:#666666">                <span style="color:#ff0000">,</span><span style="color:#ec7f15">name</span><span style="color:#ff0000">:</span><span style="color:#ff0000">'MAX_FILE_SIZE'</span></li>
<li style="color:#666666">                <span style="color:#ff0000">,</span><span style="color:#ec7f15">value</span><span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>maxFileSize</li>
<li style="color:#666666">            <span style="color:#ff0000">}]</span></li>
<li style="color:#666666">        <span style="color:#ff0000">});</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'form'</span><span style="color:#ff0000">,</span> form<span style="color:#ff0000">);</span></li>
<li style="color:#666666">            record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'progressId'</span><span style="color:#ff0000">,</span> progressId<span style="color:#ff0000">);</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">else</span> <span style="color:#ff0000">{</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressId <span style="color:#ff0000">=</span> progressId<span style="color:#ff0000">;</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">return</span> form<span style="color:#ff0000">;</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function createForm</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>deleteForm<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>form<span style="color:#ff0000">,</span> record<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">        form<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">remove</span><span style="color:#ff0000">();</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'form'</span><span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">null</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function deleteForm</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * Fires event(s) on upload finish/error</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;private</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>fireFinishEvents<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">options</span><span style="color:#ff0000">) {</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">true</span> <span style="color:#ff0000">!==</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>eventsSuspended <span style="color:#ff0000">&amp;&amp; !</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>singleUpload<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">fireEvent</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'filefinished'</span><span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">,</span> <span style="color:#ec7f15">options</span> <span style="color:#ff0000">&amp;&amp;</span> <span style="color:#ec7f15">options</span><span style="color:#ff0000">.</span>record<span style="color:#ff0000">);</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">true</span> <span style="color:#ff0000">!==</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>eventsSuspended <span style="color:#ff0000">&amp;&amp;</span> <span style="color:#a900a9">0</span> <span style="color:#ff0000">===</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>upCount<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">stopProgress</span><span style="color:#ff0000">();</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">fireEvent</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'allfinished'</span><span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function fireFinishEvents</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * Geg the iframe identified by record</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;private</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;param {Ext.data.Record} record</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;return {Ext.Element} iframe or null if not found</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>getIframe<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> iframe <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">null</span><span style="color:#ff0000">;</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> form <span style="color:#ff0000">=</span> record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">get</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'form'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span>form <span style="color:#ff0000">&amp;&amp;</span> form<span style="color:#ff0000">.</span>dom <span style="color:#ff0000">&amp;&amp;</span> form<span style="color:#ff0000">.</span>dom<span style="color:#ff0000">.</span><span style="color:#ec7f15">target</span><span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            iframe <span style="color:#ff0000">=</span> Ext<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">get</span><span style="color:#ff0000">(</span>form<span style="color:#ff0000">.</span>dom<span style="color:#ff0000">.</span><span style="color:#ec7f15">target</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">return</span> iframe<span style="color:#ff0000">;</span></li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function getIframe</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * returns options for Ajax upload request</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;private</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;param {Ext.data.Record} record</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;param {Object} params params to add</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>getOptions<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">,</span> params<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> o <span style="color:#ff0000">= {</span></li>
<li style="color:#666666">             url<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>url</li>
<li style="color:#666666">            <span style="color:#ff0000">,</span><span style="color:#ec7f15">method</span><span style="color:#ff0000">:</span><span style="color:#ff0000">'post'</span></li>
<li style="color:#666666">            <span style="color:#ff0000">,</span>isUpload<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">true</span></li>
<li style="color:#666666">            <span style="color:#ff0000">,</span>scope<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">this</span></li>
<li style="color:#666666">            <span style="color:#ff0000">,</span>callback<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>uploadCallback</li>
<li style="color:#666666">            <span style="color:#ff0000">,</span>record<span style="color:#ff0000">:</span>record</li>
<li style="color:#666666">            <span style="color:#ff0000">,</span>params<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">getParams</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">,</span> params<span style="color:#ff0000">)</span></li>
<li style="color:#666666">        <span style="color:#ff0000">};</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">return</span> o<span style="color:#ff0000">;</span></li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function getOptions</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * get params to use for request</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;private</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;return {Object} params</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>getParams<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">,</span> params<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> p <span style="color:#ff0000">= {</span>path<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>path<span style="color:#ff0000">};</span></li>
<li style="color:#666666">        Ext<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">apply</span><span style="color:#ff0000">(</span>p<span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>baseParams <span style="color:#ff0000">|| {},</span> params <span style="color:#ff0000">|| {});</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">return</span> p<span style="color:#ff0000">;</span></li>
<li style="color:#666666">    <span style="color:#ff0000">}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * processes success response</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;private</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;param {Object} options options the request was called with</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;param {Object} response request response object</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;param {Object} o decoded response.responseText</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>processSuccess<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">options</span><span style="color:#ff0000">,</span> response<span style="color:#ff0000">,</span> o<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> record <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">false</span><span style="color:#ff0000">;</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// all files uploadded ok</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>singleUpload<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>store<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">each</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>r<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                r<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'state'</span><span style="color:#ff0000">,</span> <span style="color:#ff0000">'done'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">                r<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'error'</span><span style="color:#ff0000">,</span> <span style="color:#ff0000">''</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">                r<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">commit</span><span style="color:#ff0000">();</span></li>
<li style="color:#666666">            <span style="color:#ff0000">});</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">else</span> <span style="color:#ff0000">{</span></li>
<li style="color:#666666">            record <span style="color:#ff0000">=</span> <span style="color:#ec7f15">options</span><span style="color:#ff0000">.</span>record<span style="color:#ff0000">;</span></li>
<li style="color:#666666">            record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'state'</span><span style="color:#ff0000">,</span> <span style="color:#ff0000">'done'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">            record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'error'</span><span style="color:#ff0000">,</span> <span style="color:#ff0000">''</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">            record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">commit</span><span style="color:#ff0000">();</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">deleteForm</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">options</span><span style="color:#ff0000">.</span>form<span style="color:#ff0000">,</span> record<span style="color:#ff0000">);</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo processSuccess</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * processes failure response</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;private</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;param {Object} options options the request was called with</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;param {Object} response request response object</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;param {String/Object} error Error text or JSON decoded object. Optional.</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>processFailure<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">options</span><span style="color:#ff0000">,</span> response<span style="color:#ff0000">,</span> error<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> record <span style="color:#ff0000">=</span> <span style="color:#ec7f15">options</span><span style="color:#ff0000">.</span>record<span style="color:#ff0000">;</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> records<span style="color:#ff0000">;</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// singleUpload - all files uploaded in one form</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>singleUpload<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            <span style="color:#666666; font-style:italic">// some files may have been successful</span></li>
<li style="color:#666666">            records <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>store<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">queryBy</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>r<span style="color:#ff0000">){</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">var</span> state <span style="color:#ff0000">=</span> r<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">get</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'state'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">return</span> <span style="color:#ff0000">'done'</span> <span style="color:#ff0000">!==</span> state <span style="color:#ff0000">&amp;&amp;</span> <span style="color:#ff0000">'uploading'</span> <span style="color:#ff0000">!==</span> state<span style="color:#ff0000">;</span></li>
<li style="color:#666666">            <span style="color:#ff0000">});</span></li>
<li style="color:#666666">            records<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">each</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">var</span> e <span style="color:#ff0000">=</span> error<span style="color:#ff0000">.</span>errors ? error<span style="color:#ff0000">.</span>errors<span style="color:#ff0000">[</span>record<span style="color:#ff0000">.</span>id<span style="color:#ff0000">] :</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>unknownErrorText<span style="color:#ff0000">;</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span>e<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                    record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'state'</span><span style="color:#ff0000">,</span> <span style="color:#ff0000">'failed'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">                    record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'error'</span><span style="color:#ff0000">,</span> e<span style="color:#ff0000">);</span></li>
<li style="color:#666666">                    Ext<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">getBody</span><span style="color:#ff0000">().</span><span style="color:#000000; font-weight:bold">appendChild</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">get</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'input'</span><span style="color:#ff0000">));</span></li>
<li style="color:#666666">                <span style="color:#ff0000">}</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">else</span> <span style="color:#ff0000">{</span></li>
<li style="color:#666666">                    record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'state'</span><span style="color:#ff0000">,</span> <span style="color:#ff0000">'done'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">                    record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'error'</span><span style="color:#ff0000">,</span> <span style="color:#ff0000">''</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">                <span style="color:#ff0000">}</span></li>
<li style="color:#666666">                record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">commit</span><span style="color:#ff0000">();</span></li>
<li style="color:#666666">            <span style="color:#ff0000">},</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">deleteForm</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">options</span><span style="color:#ff0000">.</span>form<span style="color:#ff0000">);</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// multipleUpload - each file uploaded in it's own form</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">else</span> <span style="color:#ff0000">{</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span>error <span style="color:#ff0000">&amp;&amp;</span> <span style="color:#ff0000">'object'</span> <span style="color:#ff0000">===</span> Ext<span style="color:#ff0000">.</span><span style="color:#ec7f15">type</span><span style="color:#ff0000">(</span>error<span style="color:#ff0000">)) {</span></li>
<li style="color:#666666">                record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'error'</span><span style="color:#ff0000">,</span> error<span style="color:#ff0000">.</span>errors <span style="color:#ff0000">&amp;&amp;</span> error<span style="color:#ff0000">.</span>errors<span style="color:#ff0000">[</span>record<span style="color:#ff0000">.</span>id<span style="color:#ff0000">]</span> ? error<span style="color:#ff0000">.</span>errors<span style="color:#ff0000">[</span>record<span style="color:#ff0000">.</span>id<span style="color:#ff0000">] :</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>unknownErrorText<span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#ff0000">}</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">else if</span><span style="color:#ff0000">(</span>error<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'error'</span><span style="color:#ff0000">,</span> error<span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#ff0000">}</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">else if</span><span style="color:#ff0000">(</span>response <span style="color:#ff0000">&amp;&amp;</span> response<span style="color:#ff0000">.</span>responseText<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'error'</span><span style="color:#ff0000">,</span> response<span style="color:#ff0000">.</span>responseText<span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#ff0000">}</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">else</span> <span style="color:#ff0000">{</span></li>
<li style="color:#666666">                record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'error'</span><span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>unknownErrorText<span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#ff0000">}</span></li>
<li style="color:#666666">            record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'state'</span><span style="color:#ff0000">,</span> <span style="color:#ff0000">'failed'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">            record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">commit</span><span style="color:#ff0000">();</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eof processFailure</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * Delayed task callback</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>requestProgress<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">() {</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> records<span style="color:#ff0000">,</span> p<span style="color:#ff0000">;</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> o <span style="color:#ff0000">= {</span></li>
<li style="color:#666666">             url<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressUrl</li>
<li style="color:#666666">            <span style="color:#ff0000">,</span><span style="color:#ec7f15">method</span><span style="color:#ff0000">:</span><span style="color:#ff0000">'post'</span></li>
<li style="color:#666666">            <span style="color:#ff0000">,</span>params<span style="color:#ff0000">:{}</span></li>
<li style="color:#666666">            <span style="color:#ff0000">,</span>scope<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">this</span></li>
<li style="color:#666666">            <span style="color:#ff0000">,</span>callback<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">options</span><span style="color:#ff0000">,</span> success<span style="color:#ff0000">,</span> response<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">var</span> o<span style="color:#ff0000">;</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">true</span> <span style="color:#ff0000">!==</span> success<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                    <span style="color:#0000ff; font-weight:bold">return</span><span style="color:#ff0000">;</span></li>
<li style="color:#666666">                <span style="color:#ff0000">}</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">try</span> <span style="color:#ff0000">{</span></li>
<li style="color:#666666">                    o <span style="color:#ff0000">=</span> Ext<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">decode</span><span style="color:#ff0000">(</span>response<span style="color:#ff0000">.</span>responseText<span style="color:#ff0000">);</span></li>
<li style="color:#666666">                <span style="color:#ff0000">}</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">catch</span><span style="color:#ff0000">(</span>e<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                    <span style="color:#0000ff; font-weight:bold">return</span><span style="color:#ff0000">;</span></li>
<li style="color:#666666">                <span style="color:#ff0000">}</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'object'</span> <span style="color:#ff0000">!==</span> Ext<span style="color:#ff0000">.</span><span style="color:#ec7f15">type</span><span style="color:#ff0000">(</span>o<span style="color:#ff0000">) ||</span> <span style="color:#0000ff; font-weight:bold">true</span> <span style="color:#ff0000">!==</span> o<span style="color:#ff0000">.</span>success<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                    <span style="color:#0000ff; font-weight:bold">return</span><span style="color:#ff0000">;</span></li>
<li style="color:#666666">                <span style="color:#ff0000">}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>singleUpload<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                    <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progress <span style="color:#ff0000">= {};</span></li>
<li style="color:#666666">                    <span style="color:#0000ff; font-weight:bold">for</span><span style="color:#ff0000">(</span>p <span style="color:#0000ff; font-weight:bold">in</span> o<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressMap<span style="color:#ff0000">[</span>p<span style="color:#ff0000">]) {</span></li>
<li style="color:#666666">                            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progress<span style="color:#ff0000">[</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressMap<span style="color:#ff0000">[</span>p<span style="color:#ff0000">]] =</span> <span style="color:#000000; font-weight:bold">parseInt</span><span style="color:#ff0000">(</span>o<span style="color:#ff0000">[</span>p<span style="color:#ff0000">],</span> <span style="color:#a900a9">10</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">                        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">                    <span style="color:#ff0000">}</span></li>
<li style="color:#666666">                    <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">true</span> <span style="color:#ff0000">!==</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>eventsSuspended<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                        <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">fireEvent</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'progress'</span><span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progress<span style="color:#ff0000">);</span></li>
<li style="color:#666666">                    <span style="color:#ff0000">}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">                <span style="color:#ff0000">}</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">else</span> <span style="color:#ff0000">{</span></li>
<li style="color:#666666">                    <span style="color:#0000ff; font-weight:bold">for</span><span style="color:#ff0000">(</span>p <span style="color:#0000ff; font-weight:bold">in</span> o<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressMap<span style="color:#ff0000">[</span>p<span style="color:#ff0000">] &amp;&amp;</span> <span style="color:#ec7f15">options</span><span style="color:#ff0000">.</span>record<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                            <span style="color:#ec7f15">options</span><span style="color:#ff0000">.</span>record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressMap<span style="color:#ff0000">[</span>p<span style="color:#ff0000">],</span> <span style="color:#000000; font-weight:bold">parseInt</span><span style="color:#ff0000">(</span>o<span style="color:#ff0000">[</span>p<span style="color:#ff0000">],</span> <span style="color:#a900a9">10</span><span style="color:#ff0000">));</span></li>
<li style="color:#666666">                        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">                    <span style="color:#ff0000">}</span></li>
<li style="color:#666666">                    <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">options</span><span style="color:#ff0000">.</span>record<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                        <span style="color:#ec7f15">options</span><span style="color:#ff0000">.</span>record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">commit</span><span style="color:#ff0000">();</span></li>
<li style="color:#666666">                        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">true</span> <span style="color:#ff0000">!==</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>eventsSuspended<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">fireEvent</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'progress'</span><span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">,</span> <span style="color:#ec7f15">options</span><span style="color:#ff0000">.</span>record<span style="color:#ff0000">.</span>data<span style="color:#ff0000">,</span> <span style="color:#ec7f15">options</span><span style="color:#ff0000">.</span>record<span style="color:#ff0000">);</span></li>
<li style="color:#666666">                        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">                    <span style="color:#ff0000">}</span></li>
<li style="color:#666666">                <span style="color:#ff0000">}</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressTask<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">delay</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressInterval<span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#ff0000">}</span></li>
<li style="color:#666666">        <span style="color:#ff0000">};</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>singleUpload<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            o<span style="color:#ff0000">.</span>params<span style="color:#ff0000">[</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressIdName<span style="color:#ff0000">] =</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressId<span style="color:#ff0000">;</span></li>
<li style="color:#666666">            o<span style="color:#ff0000">.</span>params<span style="color:#ff0000">.</span>APC_UPLOAD_PROGRESS <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressId<span style="color:#ff0000">;</span></li>
<li style="color:#666666">            Ext<span style="color:#ff0000">.</span>Ajax<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">request</span><span style="color:#ff0000">(</span>o<span style="color:#ff0000">);</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">else</span> <span style="color:#ff0000">{</span></li>
<li style="color:#666666">            records <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>store<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">query</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'state'</span><span style="color:#ff0000">,</span> <span style="color:#ff0000">'uploading'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">            records<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">each</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>r<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                o<span style="color:#ff0000">.</span>params<span style="color:#ff0000">[</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressIdName<span style="color:#ff0000">] =</span> r<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">get</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'progressId'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">                o<span style="color:#ff0000">.</span>params<span style="color:#ff0000">.</span>APC_UPLOAD_PROGRESS <span style="color:#ff0000">=</span> o<span style="color:#ff0000">.</span>params<span style="color:#ff0000">[</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressIdName<span style="color:#ff0000">];</span></li>
<li style="color:#666666">                o<span style="color:#ff0000">.</span>record <span style="color:#ff0000">=</span> r<span style="color:#ff0000">;</span></li>
<li style="color:#666666">                <span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">() {</span></li>
<li style="color:#666666">                    Ext<span style="color:#ff0000">.</span>Ajax<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">request</span><span style="color:#ff0000">(</span>o<span style="color:#ff0000">);</span></li>
<li style="color:#666666">                <span style="color:#ff0000">}).</span><span style="color:#000000; font-weight:bold">defer</span><span style="color:#ff0000">(</span><span style="color:#a900a9">250</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#ff0000">},</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function requestProgress</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * path setter</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;private</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>setPath<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>path<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>path <span style="color:#ff0000">=</span> path<span style="color:#ff0000">;</span></li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo setPath</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * url setter</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;private</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>setUrl<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>url<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>url <span style="color:#ff0000">=</span> url<span style="color:#ff0000">;</span></li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo setUrl</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * Starts progress fetching from server</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;private</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>startProgress<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">() {</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(!</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressTask<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressTask <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">new</span> Ext<span style="color:#ff0000">.</span>util<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">DelayedTask</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>requestProgress<span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressTask<span style="color:#ff0000">.</span>delay<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">defer</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressInterval <span style="color:#ff0000">/</span> <span style="color:#a900a9">2</span><span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressTask<span style="color:#ff0000">, [</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressInterval<span style="color:#ff0000">]);</span></li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function startProgress</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * Stops progress fetching from server</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;private</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>stopProgress<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">() {</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressTask<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>progressTask<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">cancel</span><span style="color:#ff0000">();</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function stopProgress</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * Stops all currently running uploads</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>stopAll<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">() {</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> records <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>store<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">query</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'state'</span><span style="color:#ff0000">,</span> <span style="color:#ff0000">'uploading'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">        records<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">each</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>stopUpload<span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function stopAll</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * Stops currently running upload</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;param {Ext.data.Record} record Optional, if not set singleUpload = true is assumed</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * and the global stop is initiated</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>stopUpload<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// single abord</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> iframe <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">false</span><span style="color:#ff0000">;</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            iframe <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">getIframe</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">stopIframe</span><span style="color:#ff0000">(</span>iframe<span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>upCount<span style="color:#ff0000">--;</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>upCount <span style="color:#ff0000">=</span> <span style="color:#a900a9">0</span> <span style="color:#ff0000">&gt;</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>upCount ? <span style="color:#a900a9">0</span> <span style="color:#ff0000">:</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>upCount<span style="color:#ff0000">;</span></li>
<li style="color:#666666">            record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'state'</span><span style="color:#ff0000">,</span> <span style="color:#ff0000">'stopped'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">fireFinishEvents</span><span style="color:#ff0000">({</span>record<span style="color:#ff0000">:</span>record<span style="color:#ff0000">});</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// all abort</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">else if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>form<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            iframe <span style="color:#ff0000">=</span> Ext<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">fly</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>form<span style="color:#ff0000">.</span>dom<span style="color:#ff0000">.</span><span style="color:#ec7f15">target</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">stopIframe</span><span style="color:#ff0000">(</span>iframe<span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>upCount <span style="color:#ff0000">=</span> <span style="color:#a900a9">0</span><span style="color:#ff0000">;</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">fireFinishEvents</span><span style="color:#ff0000">();</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function abortUpload</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * Stops uploading in hidden iframe</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;private</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;param {Ext.Element} iframe</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>stopIframe<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>iframe<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span>iframe<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">try</span> <span style="color:#ff0000">{</span></li>
<li style="color:#666666">                iframe<span style="color:#ff0000">.</span>dom<span style="color:#ff0000">.</span>contentWindow<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">stop</span><span style="color:#ff0000">();</span></li>
<li style="color:#666666">                iframe<span style="color:#ff0000">.</span>remove<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">defer</span><span style="color:#ff0000">(</span><span style="color:#a900a9">250</span><span style="color:#ff0000">,</span> iframe<span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#ff0000">}</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">catch</span><span style="color:#ff0000">(</span>e<span style="color:#ff0000">){}</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function stopIframe</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * Main public interface function. Preforms the upload</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>upload<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">() {</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> records <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>store<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">queryBy</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>r<span style="color:#ff0000">){</span><span style="color:#0000ff; font-weight:bold">return</span> <span style="color:#ff0000">'done'</span> <span style="color:#ff0000">!==</span> r<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">get</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'state'</span><span style="color:#ff0000">);});</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(!</span>records<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">getCount</span><span style="color:#ff0000">()) {</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">return</span><span style="color:#ff0000">;</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// fire beforeallstart event</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">true</span> <span style="color:#ff0000">!==</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>eventsSuspended <span style="color:#ff0000">&amp;&amp;</span> <span style="color:#0000ff; font-weight:bold">false</span> <span style="color:#ff0000">===</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">fireEvent</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'beforeallstart'</span><span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">)) {</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">return</span><span style="color:#ff0000">;</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>singleUpload<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">uploadSingle</span><span style="color:#ff0000">();</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">else</span> <span style="color:#ff0000">{</span></li>
<li style="color:#666666">            records<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">each</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>uploadFile<span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">true</span> <span style="color:#ff0000">===</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>enableProgress<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">startProgress</span><span style="color:#ff0000">();</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function upload</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * called for both success and failure. Does nearly nothing</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;private</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * but dispatches processing to processSuccess and processFailure functions</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>uploadCallback<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">options</span><span style="color:#ff0000">,</span> success<span style="color:#ff0000">,</span> response<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> o<span style="color:#ff0000">;</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>upCount<span style="color:#ff0000">--;</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>form <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">false</span><span style="color:#ff0000">;</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// process ajax success</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">true</span> <span style="color:#ff0000">===</span> success<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">try</span> <span style="color:#ff0000">{</span></li>
<li style="color:#666666">                o <span style="color:#ff0000">=</span> Ext<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">decode</span><span style="color:#ff0000">(</span>response<span style="color:#ff0000">.</span>responseText<span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#ff0000">}</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">catch</span><span style="color:#ff0000">(</span>e<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">processFailure</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">options</span><span style="color:#ff0000">,</span> response<span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>jsonErrorText<span style="color:#ff0000">);</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">fireFinishEvents</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">options</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">return</span><span style="color:#ff0000">;</span></li>
<li style="color:#666666">            <span style="color:#ff0000">}</span></li>
<li style="color:#666666">            <span style="color:#666666; font-style:italic">// process command success</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">true</span> <span style="color:#ff0000">===</span> o<span style="color:#ff0000">.</span>success<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">processSuccess</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">options</span><span style="color:#ff0000">,</span> response<span style="color:#ff0000">,</span> o<span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#ff0000">}</span></li>
<li style="color:#666666">            <span style="color:#666666; font-style:italic">// process command failure</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">else</span> <span style="color:#ff0000">{</span></li>
<li style="color:#666666">                <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">processFailure</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">options</span><span style="color:#ff0000">,</span> response<span style="color:#ff0000">,</span> o<span style="color:#ff0000">);</span></li>
<li style="color:#666666">            <span style="color:#ff0000">}</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// process ajax failure</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">else</span> <span style="color:#ff0000">{</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">processFailure</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">options</span><span style="color:#ff0000">,</span> response<span style="color:#ff0000">);</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">fireFinishEvents</span><span style="color:#ff0000">(</span><span style="color:#ec7f15">options</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function uploadCallback</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * Uploads one file</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;param {Ext.data.Record} record</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * &#64;param {Object} params Optional. Additional params to use in request.</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>uploadFile<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">,</span> params<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// fire beforestart event</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">true</span> <span style="color:#ff0000">!==</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>eventsSuspended <span style="color:#ff0000">&amp;&amp;</span> <span style="color:#0000ff; font-weight:bold">false</span> <span style="color:#ff0000">===</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">fireEvent</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'beforefilestart'</span><span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">,</span> record<span style="color:#ff0000">)) {</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">return</span><span style="color:#ff0000">;</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// create form for upload</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> form <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">createForm</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">);</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// append input to the form</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> inp <span style="color:#ff0000">=</span> record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">get</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'input'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">        inp<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">({</span><span style="color:#ec7f15">name</span><span style="color:#ff0000">:</span>inp<span style="color:#ff0000">.</span>id<span style="color:#ff0000">});</span></li>
<li style="color:#666666">        form<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">appendChild</span><span style="color:#ff0000">(</span>inp<span style="color:#ff0000">);</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// get params for request</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> o <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">getOptions</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">,</span> params<span style="color:#ff0000">);</span></li>
<li style="color:#666666">        o<span style="color:#ff0000">.</span>form <span style="color:#ff0000">=</span> form<span style="color:#ff0000">;</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// set state</span></li>
<li style="color:#666666">        record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'state'</span><span style="color:#ff0000">,</span> <span style="color:#ff0000">'uploading'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">        record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'pctComplete'</span><span style="color:#ff0000">,</span> <span style="color:#a900a9">0</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// increment active uploads count</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>upCount<span style="color:#ff0000">++;</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// request upload</span></li>
<li style="color:#666666">        Ext<span style="color:#ff0000">.</span>Ajax<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">request</span><span style="color:#ff0000">(</span>o<span style="color:#ff0000">);</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// todo:delete after devel</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>getIframe<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">defer</span><span style="color:#ff0000">(</span><span style="color:#a900a9">100</span><span style="color:#ff0000">,</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">, [</span>record<span style="color:#ff0000">]);</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function uploadFile</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// {{{</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">/**</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     * Uploads all files in single request</span></li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">     */</span></li>
<li style="color:#666666">    <span style="color:#ff0000">,</span>uploadSingle<span style="color:#ff0000">:</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">() {</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// get records to upload</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> records <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>store<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">queryBy</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>r<span style="color:#ff0000">){</span><span style="color:#0000ff; font-weight:bold">return</span> <span style="color:#ff0000">'done'</span> <span style="color:#ff0000">!==</span> r<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">get</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'state'</span><span style="color:#ff0000">);});</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">if</span><span style="color:#ff0000">(!</span>records<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">getCount</span><span style="color:#ff0000">()) {</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">return</span><span style="color:#ff0000">;</span></li>
<li style="color:#666666">        <span style="color:#ff0000">}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// create form and append inputs to it</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> form <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">createForm</span><span style="color:#ff0000">();</span></li>
<li style="color:#666666">        records<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">each</span><span style="color:#ff0000">(</span><span style="color:#0000ff; font-weight:bold">function</span><span style="color:#ff0000">(</span>record<span style="color:#ff0000">) {</span></li>
<li style="color:#666666">            <span style="color:#0000ff; font-weight:bold">var</span> inp <span style="color:#ff0000">=</span> record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">get</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'input'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">            inp<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">({</span><span style="color:#ec7f15">name</span><span style="color:#ff0000">:</span>inp<span style="color:#ff0000">.</span>id<span style="color:#ff0000">});</span></li>
<li style="color:#666666">            form<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">appendChild</span><span style="color:#ff0000">(</span>inp<span style="color:#ff0000">);</span></li>
<li style="color:#666666">            record<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">set</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'state'</span><span style="color:#ff0000">,</span> <span style="color:#ff0000">'uploading'</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">        <span style="color:#ff0000">},</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">);</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// create options for request</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">var</span> o <span style="color:#ff0000">=</span> <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">getOptions</span><span style="color:#ff0000">();</span></li>
<li style="color:#666666">        o<span style="color:#ff0000">.</span>form <span style="color:#ff0000">=</span> form<span style="color:#ff0000">;</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// save form for stop</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>form <span style="color:#ff0000">=</span> form<span style="color:#ff0000">;</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// increment active uploads counter</span></li>
<li style="color:#666666">        <span style="color:#0000ff; font-weight:bold">this</span><span style="color:#ff0000">.</span>upCount<span style="color:#ff0000">++;</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">        <span style="color:#666666; font-style:italic">// request upload</span></li>
<li style="color:#666666">        Ext<span style="color:#ff0000">.</span>Ajax<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">request</span><span style="color:#ff0000">(</span>o<span style="color:#ff0000">);</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666">    <span style="color:#ff0000">}</span> <span style="color:#666666; font-style:italic">// eo function uploadSingle</span></li>
<li style="color:#666666">    <span style="color:#666666; font-style:italic">// }}}</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666"><span style="color:#ff0000">});</span> <span style="color:#666666; font-style:italic">// eo extend</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666"><span style="color:#666666; font-style:italic">// register xtype</span></li>
<li style="color:#666666">Ext<span style="color:#ff0000">.</span><span style="color:#000000; font-weight:bold">reg</span><span style="color:#ff0000">(</span><span style="color:#ff0000">'fileuploader'</span><span style="color:#ff0000">,</span> Ext<span style="color:#ff0000">.</span>ux<span style="color:#ff0000">.</span>FileUploader<span style="color:#ff0000">);</span></li>
<li style="color:#666666">&nbsp;</li>
<li style="color:#666666"> <span style="color:#666666; font-style:italic">// eof</span></li>

</ol></div>

	<div class="adsense">
		<script type="text/javascript"><!--
		google_ad_client = "pub-2768521146228687";
		/* 728x90, for sources */
		google_ad_slot = "3128262044";
		google_ad_width = 728;
		google_ad_height = 90;
		//-->
		</script>
		<script type="text/javascript"
		src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>
	</div>
</div>
<!-- delete following line if you don not want stats included -->
<!-- phpmyvisites -->
<a href="http://www.phpmyvisites.net/" style="text-decoration:none" title="Free web analytics, website statistics"
onclick="window.open(this.href);return(false);"><script type="text/javascript">
<!--
var a_vars = Array();
var pagename='sources/Ext_ux_FileUploader_js';

var phpmyvisitesSite = 1;
var phpmyvisitesURL = "http://extjs.eu/phpmv2/phpmyvisites.php";
//-->
</script>
<script language="javascript" src="http://extjs.eu/phpmv2/phpmyvisites.js" type="text/javascript"></script>
<object><noscript><p>Free web analytics, website statistics
<img src="http://extjs.eu/phpmv2/phpmyvisites.php" alt="Statistics" style="border:0" />
</p></noscript></object></a>
<!-- /phpmyvisites -->

<!-- Piwik -->
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://extjs.eu/piwik/" : "http://extjs.eu/piwik/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
    var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
    piwikTracker.setDocumentTitle(document.title);
    piwikTracker.trackPageView();
    piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://extjs.eu/piwik/piwik.php?idsite=1" style="border:0" alt=""/></p></noscript>
<!-- End Piwik Tag -->

<!-- Google Analytics -->
<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-12265428-1']);
_gaq.push(['_setDomainName', '.extjs.eu']);
_gaq.push(['_trackPageview']);

(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
})();
</script>
<!-- End Google Analytics -->

</body>
</html>
<!-- eof -->
