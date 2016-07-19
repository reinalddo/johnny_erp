<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para Facturas Tama�o A4 -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="root" />
  {literal}
  <style type="text/css">
        .padding10{padding:10px;}
        .empresa{height:50px;font-size:20px;font-weight:bold;text-align:center;vertical-align:middle;}
        .titulo{font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;}
        .parrafo{text-align:justify; vertical-align:middle; text-transform:uppercase;}
        .bordeCompleto {border: #FFFFFF solid 1px;}
        .bordeDer {border-right: #FFFFFF solid 1px;}
        .bordeIzq {border-left: #FFFFFF solid 1px;}
        .bordeSup {border-top: #FFFFFF solid 1px;}
        .bordeInf {border-bottom: #FFFFFF solid 1px;}
        .izq{text-align:left;}
        .der{text-align:right;}
        .cen{text-align:center;}
        .espacioCol1{padding-left:70px;}
        .cabTabla{height:40px;}
        .espacioDer{padding-right:20px !important;}
        .espacioIzq{padding-left:20px !important;}
		.resalta {font-weight:900; font-style:italic}
        @media print {
            body { font-size: 11pt; }

          }
	@page factura {size:18cm 22cm;}
	pagina {page: factura;}

    </style>
  {/literal}
  
  <!--<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
    <title>Factura de Venta - Smart NET</title>
  
</head>
<body id="top" style="font-family:'Arial' !important; font-size:11pt; font-weight:bold " onload="window.print();">
  <div id="paginaA4" style="margin-left:0pt; height:25cm">  <!--  17cm -->
{report recordset=$agData record=rec	groups="TIPO,COMPR" resort=true}
{report_header}
    <!--<hr/>-->
    {assign var=numCol value=7}
    
    
    {*<div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:12;">
        <strong>{$smarty.session.g_empr}</strong><br>
        PRODUCTORES<br>
        {$subtitulo}
    </p>
    </div>*}
    <div id = "cuerpo" style="height:20.5cm; border:#FFFFFF solid 1px; margin-top:120pt;"> <!--  9cm    padding-left: 70px -->
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 150px; border:#FFFFFF solid 1px; width:800px; height:'10.5cm'" >
<!--        <tr>
            <td colspan={$numCol} style="height:50px;font-size:16px;font-weight:bold;text-align:center;vertical-align:middle;">
                {$smarty.session.g_empr}</td>            
        </tr>    -->
        <tr>
            <td colspan=1 class="resalta" nowrap style="width:100pt">&nbsp;</td>
            <td colspan=1 class="" nowrap  style="width:400pt">&nbsp;</td>
            <td colspan=1 class="resalta " nowrap style="width:60pt">&nbsp;</td>
            <td colspan=1 class="" style="width:95pt">&nbsp;</td>
            <td colspan=1 style="width:85pt">&nbsp;</td>
        </tr>
        <tr>
            <td colspan=1 class="resalta" nowrap style="width:100pt">&nbsp;</td>
            <td colspan=1 class="" nowrap  style="width:400pt">&nbsp;</td>
            <td colspan=1 class="resalta " nowrap style="width:60pt">&nbsp;</td>
            <td colspan=1 class="" style="width:95pt">&nbsp;</td>
            <td colspan=1 style="width:85pt">&nbsp;</td>
        </tr>
        <tr>
            <td colspan=1 class="resalta" nowrap style="width:100pt">&nbsp;</td>
            <td colspan=1 class="" nowrap  style="width:400pt">&nbsp;</td>
            <td colspan=1 class="resalta " nowrap style="width:60pt">&nbsp;</td>
            <td colspan=1 class="" style="width:95pt">&nbsp;</td>
            <td colspan=1 style="width:85pt">&nbsp;</td>
        </tr>
        <tr>
	    <td colspan=1 class="resalta" nowrap style="width:100pt"><strong>CLIENTE:</strong></td>
            <td colspan=1 class="" nowrap  style="width:400pt">{$rec.RECEP|truncate:80}</td>
            <td colspan=1 class="resalta " nowrap style="width:60pt"><strong>FECHA:</strong></td>
	    <td colspan=1 class="" style="width:95pt">{$rec.FECHA}</td>
	    <td colspan=1 style="width:85pt">&nbsp;</td>
        </tr>
        <tr>
	  <td colspan=1 class="resalta" nowrap><strong>DIRECCION:</strong></td>
            <td colspan=1 class="" nowrap>{$rec.direccion|truncate:80}</td>
            <td colspan=1>&nbsp;</td>
        </tr>
        <tr>
	  <td colspan=1 class="resalta" nowrap><strong>CIUDAD:</strong></td>
            <td colspan=1 class="">{$rec.CIU}</td>
            <td colspan=1 class="resalta " nowrap style="width:60pt"><strong>TELEF:</strong></td>
            <td colspan=2 class="">{$rec.telefono}</td>
	    <td colspan=1>&nbsp;</td>
        </tr>
        <tr>
	    <td colspan=1 class="resalta" nowrap><strong>RUC:</strong></td>
            <td colspan=1 class="">{$rec.ruc}</td>
            <td colspan=2>&nbsp;</td>
	    <td colspan=1>&nbsp;</td>
        </tr>
        <tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase; padding-left: 30px; height: 70px; ">
	  <td class="headerrow cabTabla resalta" width="30" style="text-align:center">&nbsp;<strong>CANTIDAD</strong></td>
	  <td class="headerrow cabTabla resalta" width="660">&nbsp;<strong>DESCRIPCION</strong></td>
	  <td class="headerrow cabTabla resalta" width="130">&nbsp;<strong>VALOR UNITARIO</strong></td>
	  <td class="headerrow cabTabla resalta" width="170">&nbsp;<strong>TOTAL</strong></td>
	  <td colspan=1>&nbsp;</td>
	</tr>
        
{/report_header}

{report_detail}
    <tr><!-- style="border:#000000 solid 1px;">-->
        <td class="colnum der" style="padding: 0px 55px 0px 0px">{$rec.CANTI|number_format:0}</td>  
	     <td nowrap class="coldata espacioIzq">{$rec.ITEM|truncate:80}</td>
        <td class="colnum espacioDer der" >{$rec.vunit|number_format:2}</td>        
        <td class="colnum espacioDer der" >{$rec.VALOR|number_format:2}</td>
	<td colspan=1>&nbsp;</td>
    </tr>
{/report_detail}

{report_footer}

    <tr><!-- style="border:#000000 solid 1px;">-->
        <td class="colnum der" >&nbsp</td>  
	<td nowrap class="coldata espacioIzq">REF.: {$rec.REFOP}</td>
        <td class="colnum espacioDer der" >&nbsp</td>        
        <td class="colnum espacioDer der" >&nbsp</td>
	<td colspan=1>&nbsp;</td>
    </tr>
  <tr>
                <td class="der bordeIzq bordeDer" >&nbsp</td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
		<td colspan=1>&nbsp;</td>
     </tr>

      {*{if (($empresa eq "COSTAFRUT S.A." or $empresa eq "AMENEGSA S.A." or $empresa eq "LIGHTFRUIT S.A." or $empresa eq "FORZAFRUT S.A." or $empresa eq "MUNDIPAK S.A."or $empresa eq "CONTABAN S.A."))}*}
	      <tr>
		  <td class="izq bordeDer"></td>
		  <td colspan=1 align="left"  class="coldata espacioIzq">{$rec.CONCEP|regex_replace:"/[\n]/":"<br>"}</td> <!--Reemplaza los enter por salto de lineas-->
	      </tr>
     {*{/if} 
    {assign var=cuenta value=$count.CANTI ban=0}
    {foreach from=$agFilas item=value1}
        {if ($value1 > $cuenta) }
            <tr>
                <td class="der bordeIzq bordeDer" >&nbsp</td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
		<td colspan=1>&nbsp;</td>
            </tr>
        {/if}
    {/foreach}*}
	
	
    </table>
</div>
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 70px; border:#FFFFFF solid 1px; width:800px; height:'8.5cm'" >
    <tr style="padding-top:20px;">
    <td colspan=2 rowspan=5 class="espacioCol1 resalta" style="vertical-align: top; width:460pt"<strong>SON: </strong>{$letras}</td>
	<td class ="resalta" style="width:80pt; ">&nbsp;</td>
	<td class ="resalta" style="width:110pt">&nbsp;</td>
	<td colspan=1 style="width:150pt" >&nbsp; </td>
	</tr>
	
    <tr>
        <td class ="resalta" style="text-align:right"><strong>SUBTOTAL:</strong></td>
        <td class="colnum der">{$sum.VALOR|number_format:2}</td>
	<td colspan=1>&nbsp;</td>
    </tr>
    <tr>
        <!--<td><strong>IVA:</strong></td>-->
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class ="resalta" style="text-align:right"><strong>IVA:</strong></td>
        <td class="colnum der" style="padding-top; :0px; text-align:right">{$iva|number_format:2}</td>
    </tr>
    <tr>
        <td class ="resalta" style="width:150px; text-align:right"><strong>TOTAL:</strong></td>
        <td class="colnum der" style="padding-top:5px;">{$valorTot|number_format:2}{*$sum.VALOR|number_format:2*}</td>
    </tr>
	<!-- <tr style="height: 1.5cm">
        <td style="height: 1.5cm;"> &nbsp</td>
    </tr> -->
	<tr >
        <td colspan=2 align="center">______________________&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;______________________</td>
    </tr>
	<tr style="">
        <td colspan=2 align="center"style="">&nbsp;Autorizado &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Recibido</td>
		<td colspan=2 align="right" style="font-size:0.8em;">{$smarty.session.g_user}, {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} &nbsp;/ &nbsp;{$rec.COMPR} </td>
    </tr>

   </table>
   
    
{/report_footer}

{/report}
</div>
</body>