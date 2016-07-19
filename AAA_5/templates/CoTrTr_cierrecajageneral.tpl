<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  {literal}
    <style type="text/css">
        .tot{background-color:#D9D9D9;}
    </style>
    <script type="text/javascript">
      /*function autofitIframe(id){
	if (!window.opera && !document.mimeType && document.all && document.getElementById){
	  parent.document.getElementById(id).style.height=this.document.body.offsetHeight+"px";
	}
	else if(document.getElementById) {
	  parent.document.getElementById(id).style.height=this.document.body.scrollHeight+"px"
	}
      }*/
    </script>
    <script type="text/javascript">

	/***********************************************
	* IFrame SSI script II- © Dynamic Drive DHTML code library (http://www.dynamicdrive.com)
	* Visit DynamicDrive.com for hundreds of original DHTML scripts
	* This notice must stay intact for legal use
	***********************************************/
	
	//Input the IDs of the IFRAMES you wish to dynamically resize to match its content height:
	//Separate each ID with a comma. Examples: ["myframe1", "myframe2"] or ["myframe"] or [] for none:
	var iframeids=["fraCheques"]
	
	//Should script hide iframe from browsers that don't support this script (non IE5+/NS6+ browsers. Recommended):
	var iframehide="yes"
	
	var getFFVersion=navigator.userAgent.substring(navigator.userAgent.indexOf("Firefox")).split("/")[1]
	var FFextraHeight=parseFloat(getFFVersion)>=0.1? 16 : 0 //extra height in px to add to iframe in FireFox 1.0+ browsers

	function resizeCaller() {
	  var dyniframe=new Array()
	  for (i=0; i<iframeids.length; i++){
	    if (document.getElementById)
	      resizeIframe(iframeids[i])
	    //reveal iframe for lower end browsers? (see var above):
	    if ((document.all || document.getElementById) && iframehide=="no"){
	      var tempobj=document.all? document.all[iframeids[i]] : document.getElementById(iframeids[i])
	      tempobj.style.display="block"
	    }
	  }
	}

	function resizeIframe(frameid){
	  var currentfr=document.getElementById(frameid)
	  if (currentfr && !window.opera){
	    currentfr.style.display="block"
	    if (currentfr.contentDocument && currentfr.contentDocument.body.offsetHeight) //ns6 syntax
	      currentfr.height = currentfr.contentDocument.body.offsetHeight+FFextraHeight; 
	    else if (currentfr.Document && currentfr.Document.body.scrollHeight) //ie5+ syntax
	      currentfr.height = currentfr.Document.body.scrollHeight;
	    if (currentfr.addEventListener)
	      currentfr.addEventListener("load", readjustIframe, false)
	    else if (currentfr.attachEvent){
	      currentfr.detachEvent("onload", readjustIframe) // Bug fix line
	      currentfr.attachEvent("onload", readjustIframe)
	    }
	  }
	}

	function readjustIframe(loadevt) {
	  var crossevt=(window.event)? event : loadevt
	  var iframeroot=(crossevt.currentTarget)? crossevt.currentTarget : crossevt.srcElement
	  if (iframeroot)
	    resizeIframe(iframeroot.id);
	}
	  
	function loadintoIframe(iframeid, url){
	    if (document.getElementById)
	      document.getElementById(iframeid).src=url
	}

	if (window.addEventListener)
	  window.addEventListener("load", resizeCaller, false)
	else if (window.attachEvent)
	  window.attachEvent("onload", resizeCaller)
	else
	  window.onload=resizeCaller

</script>
  {/literal}
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CONSOLIDADO CIERRE DE CAJA</title>
  
</head>

<body id="top" style="font-family:'Arial'" onLoad="autofitIframe('fraCheques')">
{report recordset=$agData record=rec	groups="empresa" resort=true}
{assign var=acum value=0}
{*assign var=acumula value=0*}
{report_header}
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>-->
    <hr/>
    <!--<div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>-->
    </br>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{*$smarty.session.g_empr*}</strong><br>
        CONSOLIDADO CIERRE DE CAJA<br>
        {$subtitulo}
    </p>
    </div>
    <table border=1 cellspacing=0 >
	<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	  <td></td>
	  <td colspan={$agTotCabCaja}>CAJA</td>
	  <td colspan={$agTotCabBancos}>BANCOS</td>
	  <td></td>
	</tr>
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">CAJA/BANCOS</td>
            {*foreach key=cid item=con from=$agCab*}
                {*<a href="contact.php?contact_id={$cid}">{$con.name} - {$con.nick}</a><br />*}
               <!-- <td class="headerrow"><strong>{$con.1}</strong></td>-->
            {*/foreach*}
            {foreach from=$agCab item=curr_id}
                <!--id: {$curr_id}<br />-->
                <td class="headerrow"><strong>{$curr_id}</strong></td>
            {/foreach}
        </tr>

{/report_header}


{report_detail}
    <tr>
        <td>{$rec.empresa}</td>
        
        {foreach from=$agCodCab item=curr_id}
            {assign var=col value=$curr_id}
            {assign var=empr value=$rec.empresa}
            {assign var=sal value=$agSaldos.$empr.$curr_id|default:0}
            {if ($sal == 0)}
              <td class="colnum ">{0}</td>
            {else}
                
              <td class="colnum"
                    {if (-1 == $curr_id || -2 == $curr_id)}
                        style='font-weight:bold; font-style:italic;'
                    {elseif (-3 == $curr_id)}
                        style='font-weight:bold;'
                    {/if}
                  ">{$sal|number_format:2}</td>
            {/if}
        {/foreach}
        
        
    </tr>
    
{/report_detail}

{report_footer}
    <tr style="font-weight:bold;">
        <td style="font-style:italic;">Total</td>
        <!--<td class="colnum tot">{0}</td>-->
        {foreach from=$agCodCab item=curr_id}
            {assign var=empr value=-5}
            {assign var=sal value=$agSaldos.$empr.$curr_id|default:0}
            {if ($sal == 0)}
              <td class="colnum tot">{0}</td>
            {else}
              <td class="colnum tot">{$sal|number_format:2}</td>
            {/if}
        {/foreach}
    </tr>
    </table>
    <iframe id="fraCheques" frameborder="0" src="CoTrTr_chequeEstadoEmp.rpt.php?init=1&pOpc=&fIni={$fecIni}&fFin={$fecFin}" scrolling="no" marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0" style="overflow:visible; width:100%; display:none;"></iframe>
    {*<div style="font-size:0.7em; text-align:left; float:left;color:#000000; margin-top:20px;">
        <p style="line-height:0.5em;"><strong>Usuario: </strong>{$smarty.session.g_user}</p>
        <p style="line-height:0.5em;"><strong>Fecha Imp.: </strong>{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</p>
	<p style="line-height:0.5em;">{$agArchivo}</p>
    </div>*}
{/report_footer}

{/report}
</body>