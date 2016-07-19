<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para Factura - Importadora Nieto - Impresión en Matricial, formato A4 Factura en blanco -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
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
        .cabTabla{height:32px; border: solid; font-weight:bold; text-align:center;}
        .espacioDer{padding-right:24px !important;}
        .espacioIzq{padding-left:10px !important;}
		.resalta {font-weight:900; font-style:italic}
        @media print {
            body { font-size: 9pt; }

          }
	@page factura {size:15cm 22cm;}
	pagina {page: factura;}

    </style>
  {/literal}
  
 <title>Factura Comercial A4</title>
</head>

<body id="top" style="font-family:'Sans serif' !important; font-size:10pt; " onload="window.print()"> 
<div id="pagina" style="margin-left:0pt; height:9cm">
{report recordset=$agData record=rec	groups="TIPO,COMPR" resort=true}
{report_header}
    <div id = "cuerpo" style="height:10.4cm; border:#FFFFFF solid 1px;margin-top:4cm;">
    
    <table border=0 cellspacing=0 style="width:900pt;" >
	<tr>
	    <td colspan=1 style="width:12%" class="resalta"><strong>Cliente:</strong></td>
            <td colspan=1 class="" style="width:300pt; ">{$rec.RECEP}</td>
	    <td colspan=1 class="resalta" ><strong>Origen:</strong></td>
	    <td colspan=1>{$rec.ECIU}  </td>
        </tr>
        <tr>
	    <td colspan=1 style="width:12%"class="resalta"><strong>Ruc</strong></td>
            <td colspan=1 style="width:48%">{$rec.ruc}      </td>
            <td colspan=1 style="width:20%"class="resalta" ><strong>Fecha de Emisión:</strong></td>
	    <td colspan=1 style="width:20%">{$rec.TXFECHA}    </td>
        </tr>
	<tr>
	    <td colspan=1 class="resalta" ><strong>Direccion:</strong></td>
            <td colspan=1>{$rec.direccion|truncate:60}</td>
	    <td colspan=1 class="resalta" ><strong>Teléfono:</strong></td>
            <td colspan=1 >{$rec.telefono}</td>
        </tr>
	<tr>
	    <td colspan=1 class="resalta" ><strong>Destino:</strong></td>
            <td colspan=1 >{$rec.ciudad}  </td>
	    <td colspan=1 class="resalta" ><strong>Orden/Venta #:</strong></td>
            <td colspan=1 >{$rec.PR_NUM}</td>
        </tr>
    </table>
    
    <table cellspacing=0 style="border: solid; width:900pt; height:8.5cm">
        <tr style="font-size:8pt">
	  <td class="headerrow cabTabla resalta" style="width:12%;">CODIGO</td>
	  <td class="headerrow cabTabla resalta" style="width:38%;">DESCRIPCION</td>
	  <td class="headerrow cabTabla resalta" style="width:10%;">MARCA</td>
	  <td class="headerrow cabTabla resalta" style="width:10%;">CANTIDAD</td>
	  <td class="headerrow cabTabla resalta" style="width:15%;">V. UNIT</td>
	  <td class="headerrow cabTabla resalta" style="width:15%;">V. DE VENTA</td>
	</tr>
{/report_header}

{report_detail}
	<tr style="height:0.4cm; font-size:10pt;">
	  <td class="colnum espacioDer cen" 	style="border-left:solid thin; border-right:solid thin; font-size:8pt;">{$rec.ITEM_Ant}</td>  
	  <td class="coldata espacioIzq cen"	style="border-left:solid thin; border-right:solid thin;">{$rec.ITEM}</td>
	  <td class="coldata espacioIzq cen"	style="border-left:solid thin; border-right:solid thin; font-size:8pt;">{$rec.Marca}</td>
	  <td class="colnum espacioDer der" 	style="border-left:solid thin; border-right:solid thin;">{$rec.CANTI|number_format:0}</td>  
	  <td class="colnum espacioDer der"	style="border-left:solid thin; border-right:solid thin;">${$rec.vunit}</td>        
	  <td class="colnum espacioDer der"	style="border-left:solid thin; border-right:solid thin;">${$rec.VALOR}</td>
	</tr>
{/report_detail}

{report_footer}
	<tr>
	  <td class="colnum espacioDer der" 	style="border-left:solid thin; border-right:solid thin;">&nbsp;</td>  
	  <td class="coldata espacioIzq izq"	style="border-left:solid thin; border-right:solid thin;">&nbsp;</td>
	  <td class="coldata espacioIzq izq"	style="border-left:solid thin; border-right:solid thin;">&nbsp;</td>
	  <td class="coldata espacioIzq izq"	style="border-left:solid thin; border-right:solid thin;">&nbsp;</td>
	  <td class="colnum espacioDer der"	style="border-left:solid thin; border-right:solid thin;">&nbsp;</td>        
	  <td class="colnum espacioDer der"	style="border-left:solid thin; border-right:solid thin;">&nbsp;</td>
	</tr>
    </table>
    </div>
    <table border=0 cellspacing=0 style="width:900pt; border-top:solid thin; font-size:8pt;">
      <tr colspan=5>
	<td>&nbsp;</td>
      </tr>
      <tr style="height:0.5cm;">
	<td colspan=2 rowspan=9 style=" vertical-align:top; width:400pt; text-align:justify;">
	    <strong>SON:</strong><u>{$letras|upper}</u>
	    <br><br><b>FORMA DE PAGO:</b>&nbsp;<b>{$rec.Fpago}</b>,&nbsp;Debo y pagaré la suma constante en el total por igual valor de los productos, a la orden de
	    <b>{$rec.Benef|upper}</b> pago que lo hare según lo pactado.
	    <br><br>Favor emitir y enviar las retenciones dentro de los <b>5 días</b> de emitido la factura, según lo dispuesto por la ley de régimen tributario
	    interno, <b>ART, 50 OBLIGACIONES AGENTE DE RETENCION.</b>
	    <br><br>Emitida la factura NO SE ACEPTARA cualquier corrección de datos, ni devolución alguna de los productos, serán aceptadas hasta 6 días de emitida
	    la factura.
	</td>
	<td rowspan=9 style="width:20pt">&nbsp;</td>
	<td class ="resalta" style="width:90pt"><strong>SUBTOTAL:</strong></td>
	<td class="colnum espacioDer der" style="width:95pt;font-size:10pt;">${$sum.VALOR|number_format:2}</td>
      </tr>
      <tr style="height:0.5cm;">
	<td class ="resalta"><strong>DESCUENTO:</strong></td>
	
	<td class="colnum espacioDer der" style="font-size:10pt;">${if ($sum.VALDscto)==0} 0.00{/if} {$sum.VALDscto}</td>
      </tr>
      <tr style="height:0.5cm;">
	<td class ="resalta" style="width:90pt"><strong>SUBTOTAL NETO:</strong></td>
	{assign var=SubNeto value=$sum.VALOR-$sum.VALDscto}
	<td class="colnum espacioDer der" style="font-size:10pt;">${$SubNeto|number_format:2}</td>
      </tr>	
      <tr style="height:0.5cm;">
	<td class ="resalta"><strong>IVA:</strong></td>
	{assign var=IvaNeto value=$SubNeto*0.12}
	<td class="colnum espacioDer der" style="font-size:10pt;">${$IvaNeto|number_format:2}</td>
      </tr>
      <tr style="height:0.5cm; font-weight:bold;">
	<td class ="resalta" style="width:150px">TOTAL :</td>
	<td class="colnum espacioDer der" style="font-size:10pt;">${$SubNeto+$IvaNeto|number_format:2}</td>
      </tr>
      <!-- Para que ajuste el espacio por la glosa de la forma de pago-->
      <tr>
	<td class ="resalta" style="width:150px">&nbsp;</td>
	<td class="colnum espacioDer der" style="padding-top:7px;">&nbsp;</td>
      </tr>
  </table>
  <table cellspacing=0 style="width:900pt; padding-top:1cm;">
      <tr style="text-align:center; font-weight:bold;">
	<td style="width:33%">__________________________<br>Firma Autorizada</td>
	<td style="width:33%">__________________________<br>Cliente</td>
	<td style="width:33%">__________________________<br>Negociador</td>
      </tr>
  </table>
    
{/report_footer}

{/report}
</div>
</body>