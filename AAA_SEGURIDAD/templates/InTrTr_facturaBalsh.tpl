<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla de Impresion de Factura para Baloschi
  @rev esl  28/03/2012  AGREGAR CALCULOS DEL IVA DEPENDIENDO DE act_IvaFlag , separar el valor de la factura en base 0 y base iva
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
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
        .cabTabla{height:30px;}
        .espacioDer{padding-right:20px !important;}
        .espacioIzq{padding-left:20px !important;}
		.resalta {font-weight:900; font-style:italic}
        @media print {
            body { font-size: 9pt; }

          }
	@page factura {size:21cm 28cm;}
	pagina {page: factura;}

    </style>
  {/literal}
  
    <title>Factura Comercial</title>
  
</head>
<body id="top" style="font-family:sans-serif !important; font-size:9pt; font-weight:bold " onload="window.print();">
  <div id="pagina" style="margin-left:0pt; height:23cm"> 
{report recordset=$agData record=rec	groups="TIPO,COMPR" resort=false}
{report_header}
    <!--<hr/>-->
    {assign var=numCol value=7}
 			 <!--  18.5cm -->   			
    <div id = "cuerpo" style="height:18cm; border:#FFFFFF solid 1px; margin-top:111pt;">
    <table border=0 cellpadding=2pt; style="padding-left: 50pt; border:#FFFFFF solid 1px;" >
        <tr>
	 <td style="width:110pt;"><!--<strong>CLIENTE:</strong>--></td>
               <td style="width:500pt;" nowrap>{$rec.RECEP|truncate:80}</td>	 
	 <!--<td>{$rec.telefono}</td>-->
	 <td style="width:80pt;"><!--<strong>GUIA REMISION:</strong>--></td>
	 <!--<td style="width:100pt;">&nbsp;</td>-->
        </tr>
        <tr>
	 <td colspan=1 class="resalta"><!--<strong>REFERENCIA:</strong>--></td>
                <td colspan=3>&nbsp;</td>
        </tr>
        <tr>
	      <td><!--<strong>DIRECCION:</strong>--></td>
               <td colspan="3" nowrap>{$rec.direccion|truncate:40}</td>
               <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$rec.telefono}</td>
               <!--<td><strong>TELEFONO:</strong></td>-->
	 <!--<td>{$rec.telefono}</td>-->
        </tr>
        <tr>
	<td><!-- <strong>FECHA:</strong>--></td>
              <td>{$rec.FECHALetra}</td>
	<td><!--<strong>RUC:</strong>--></td>
              <td colspan="2">{$rec.ruc|truncate:40}</td>
        </tr>
  </table>
  <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 50pt; border:#FFFFFF solid 1px; width:'21cm' ; height:'16.5cm'" > 
	<tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow cabTabla resalta" style="width:60pt;text-align:center;"><!--&nbsp;<strong>N0</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="width:70pt;text-align:center;"><!--&nbsp;<strong>CANTIDAD</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="width:80pt;text-align:center;"><!--&nbsp;<strong>Unidad</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="width:400pt" ><!--&nbsp;<strong>DESCRIPCION</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="width:100pt"><!--&nbsp;<strong>V. UNITARIO</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="width:100pt"><!--&nbsp;<strong>TOTAL</strong>--></td>
	</tr>
        
{/report_header}
<br />
{report_detail}
      <tr style="height:0.6cm;"><!-- style="border:#000000 solid 1px;">-->
	  <td class="colnum der" style="text-align:center;">{*{$rec.SECUE|number_format:0}*}</td>
	  <td class="colnum der" style="text-align:center;">{$rec.CANTI|number_format:2}</td>  
	  <td class="coldata der" style="text-align:center;">{*{$rec.uni_Abreviatura}*}</td>  
	  <td nowrap class="coldata espacioIzq" >{$rec.ITEM}</td>
	  <td class="colnum espacioDer der" >{$rec.vunit|number_format:2}</td>        
	  <td class="colnum espacioDer der" >{$rec.VALOR|number_format:2}</td>
      </tr>
{/report_detail}

{report_footer}
      <tr style="height:0.5cm;">
	<td colspan=3>&nbsp;</td>
	<!--<td align="left" class="coldata espacioIzq" colspan=3 nowrap>{$rec.CONCEP|regex_replace:"/[\n]/":"<br>"}</td>--><!--Reemplaza los enter por salto de lineas-->
	<td align="left" class="coldata espacioIzq" nowrap>{$rec.CONCEP|regex_replace:"/[\n]/":"</td><tr style='height:0.5cm;'><td colspan=3>&nbsp;</td>
	<td align='left' class='coldata espacioIzq'>"}</td>
      </tr>
    </table>
</div>
	<!--  padding-bottom:40px; -->
    <table border=0 cellspacing=1 style=" padding-left: 50pt; table-layout:fixed; padding-top:10px; margin-bottom:167px; border:#FFFFFF solid 1px; width:'21cm'; height:'3.5cm'" > 
    <tr style="height:0.7cm;"> 
        <td rowspan=9  class="resalta" style="vertical-align: top; width:580pt; padding-top:35pt; padding-left:70pt;">{$letras}</td>
        <td class ="resalta"       style="width:71pt"><!--<strong>SUBTOTAL:</strong>--></td>
        <td class="espacioDer der" style="width:71pt ; padding-top:25pt;">{$sum.BASEIMP|number_format:2}&nbsp;</td>
    </tr>
    <tr style="height:0.7cm;"> <!-- Descuento -->
        <td><strong>&nbsp;</strong></td>
        <td class="colnum espacioDer der" style="padding-top:5px;">{$sum.VALDscto|number_format:2}&nbsp;</td>
    </tr>
    <tr style="height:0.7cm;"> <!-- Subtotal 0 -->
        <td><strong>&nbsp;</strong></td>
        <td class="colnum espacioDer der" style="padding-top:9px;">{$sum.BASE0|number_format:2}&nbsp;</td>
    </tr>
    <tr style="height:0.7cm;">
        <td class ="resalta"><!--<strong>IVA:</strong>--></td>
        <td class="colnum espacioDer der" style="padding-top:9px;">{$iva|number_format:2}&nbsp;</td>
    </tr>
    <tr style="height:0.7cm;">
        <td class ="resalta" style="width:100px;"><!--<strong>TOTAL :</strong>--></td>
        <td class="colnum espacioDer der" style="padding-top:9px;">{$valorTot|number_format:2}{*$sum.VALOR|number_format:2*}</td>
    </tr>
   </table> 
{/report_footer}

{/report}
</div>
</body>