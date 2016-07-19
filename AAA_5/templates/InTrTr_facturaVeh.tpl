<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para imprimir factura de vehículos en formato html -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Erika Suarez" />
  {literal}
  <style type="text/css">
        .padding10{padding:10px;}
	.padding5{padding:4px;}
        .empresa{height:50px;font-size:20px;font-weight:bold;text-align:center;vertical-align:middle;}
        .titulo{font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;}
        .parrafo{text-align:justify; vertical-align:middle; text-transform:uppercase;}
        .bordeCompleto {border: #FFFFFF solid 1px;}
        .bordeDer {border-right: #FFFFFF solid 1px;}
        .bordeIzq {border-left: #FFFFFF solid 1px;}
        .bordeSup {border-top: #FFFFFF solid 1px;}
        .bordeInf {border-bottom: #FFFFFF solid 1px;}
	.fuente{font-size:12px;}
        .izq{text-align:left;}
        .der{text-align:right;}
        .cen{text-align:center;}
        .espacioCol1{padding-left:120px;}
	.cabTabla{height:40px;}
        .espacioDer{padding-right:25px !important;}
        .espacioIzq{padding-left:55px !important;}
	.espacioIzq2{padding-left:85px !important;}
	.espacioTipo{padding-left:25px !important;}
        @media print {
            body { font-size: 10pt; }
          }

    </style>
  {/literal}
  
  <!--<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
    <title>Factura</title>
  
</head>
<body id="top" style=" font:'sans-serif'; !important;  font-size:10px;" onload="window.print();">
{report recordset=$agData record=rec	groups="TIPONOM" resort=false}
{report_header}
    <!--<hr/>-->
    {assign var=numCol value=7}
    
    
    {*<div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        PRODUCTORES<br>
        {$subtitulo}
    </p>
    </div>*}
    <table width="850" border=0 cellspacing=0 style="border:#FFFFFF solid 1px; margin-top:60px;">
	  
	<!--<tr>
            <td colspan={$numCol} style="height:50px;font-size:16px;font-weight:bold;text-align:center;vertical-align:middle;">
                {$smarty.session.g_empr}</td>            
        </tr>-->
	  <tr style="vertical-align:middle;">
	      <td colspan=4 class="espacioCol1 padding5 fuente" nowrap>{$rec.RECEP|truncate:32}</td>
	      <td colspan=2 class="espacioCol1 fuente" >{$rec.FECHA}</td>
	  </tr>
	  <tr>
	        <td colspan=5 class="espacioCol1 fuente padding5">{$rec.RUC}</td>
	      <td colspan=2 class="espacioCol1 fuente" >{$rec.TIPOPAGO} </td>
	  </tr>
	  <tr>
	      <td colspan=4 class="espacioCol1 fuente padding5"  nowrap>{$rec.direccion|truncate:50}</td>
	      <td colspan=2 class="espacioCol1 fuente" >{$rec.telefono}</td>
	  </tr>
	  <tr>
	      <td colspan=3 class="espacioCol1 padding5 fuente" style="padding-left:150px;" nowrap>{$rec.FECHAING}</td>
	      <td colspan=2 class="espacioIzq fuente" >{$rec.FECHASAL}</td>
	      <td colspan=1 class="espacioCol1 fuente">{$rec.ORDEN}</td>
	  </tr>
	  <tr>
	      <td colspan=3 class="espacioCol1 fuente padding5" nowrap>{$rec.DISCO}</td>
	      <td colspan=2 class="fuente" style="padding-left:-60px;">{$rec.MARCA}</td>
	      <td colspan=1 class="espacioCol1 fuente">{$rec.PRON}</td>
	  </tr>
	  <tr>
	      <td colspan=3 class="espacioCol1 fuente padding5" nowrap>{$rec.PLACA}</td>
	      <td colspan=3 class="fuente " style="padding-left:-60px;">{$rec.MODELO}</td>
	  </tr>
	  <tr>
	      <td colspan=3 class="espacioCol1 fuente padding5" nowrap>{$rec.MOTOR}</td>
	      <td colspan=2 class="fuente" style="padding-left:-60px;" >{$rec.A_O}</td>
	      <td colspan=1 class="espacioCol1 fuente">{$rec.KILOMETRAJE}</td>
	  </tr>
	  <tr>
	      <td colspan=3 class="espacioCol1 padding5 fuente" nowrap>{$rec.CHASIS}</td>
	      <td colspan=3 class=" fuente" style="padding-left:-60px;">{$rec.COLOR}</td>
	  </tr>
	  
	  
	  <tr style="font-weight:bold; text-align:center; vertical-align:middle; /*text-transform:uppercase;*/">
	    <td class="headerrow cabTabla" width="40">&nbsp;<!--<strong>CODIGO DE ITEM</strong>--></td>
	    <td class="headerrow cabTabla" width="30">&nbsp;<!--<strong>CANTIDAD</strong>--></td>
	    <td class="headerrow cabTabla" width="300">&nbsp;<!--<strong>ITEM</strong>--></td>
	    <td class="headerrow cabTabla" width="100">&nbsp;<!--<strong>VALOR SIN DESCUENTO</strong>--></td>
	    <td class="headerrow cabTabla" width="100">&nbsp;<!--<strong>DESCUENTO</strong>--></td>
	    <td class="headerrow cabTabla" width="100">&nbsp;<!--<strong>VALOR CON DESCUENTO</strong>--></td>
	  </tr>

{/report_header}



{report_header group="TIPONOM"}
	  
	  <tr style="font-weight:bold;  vertical-align:middle;">
            <td class="coldata espacioTipo izq" colspan=3>{$rec.TIPONOM}</td>
	  </tr>
{/report_header}

{report_detail}
	   <tr>
            <td class="coldata espacioIzq der">{$rec.CODIT}</td>
            <td class="colnum espacioIzq der">{$rec.CANTE|number_format:0}</td>
	    <td class="coldata espacioIzq" nowrap>{$rec.ITEM}</td>
            <td class="colnum espacioIzq der">{$rec.VALSINDES|number_format:2}</td>
	    <td class="coldata espacioIzq2 der" nowrap>{$rec.DESCU}</td>
            <td class="colnum der" style="padding-left:-100px;">{$rec.VALOR|number_format:2}</td>
	    <td>&nbsp;</td>
	   </tr>
{/report_detail}


{report_footer}
   <!-- 
    <tr>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
    </tr>-->
    <tr>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
    </tr>
    <tr>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
    </tr>
     <tr>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
    </tr>
     <tr>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>  
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
		 <td class="colnum der" >&nbsp</td>
    </tr>
    
     
     <!--Reemplaza los enter por salto de lineas-->
     <!-- {if (($empresa eq "COSTAFRUT S.A." or $empresa eq "AMENEGSA S.A." or $empresa eq "LIGHTFRUIT S.A." or $empresa eq "FORZAFRUT S.A." or $empresa eq "MUNDIPAK S.A."or $empresa eq "CONTABAN S.A."))}
	      <tr>
		  <td class="izq bordeDer"></td>
		  <td colspan=2 align="left" nowrap class="coldata espacioIzq">{$rec.CONCEP|regex_replace:"/[\n]/":"<br>"}</td> 
	      </tr>
     {/if} -->
     
    {assign var=cuenta value=$count.CANTI ban=0}
    {foreach from=$agFilas item=value1}
        {if ($value1 > $cuenta) }
            <tr>
                <td class="der bordeIzq bordeDer" >&nbsp</td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
		<td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
            </tr>
        {/if}
    {/foreach}
	  <!-- Valor en letras:-->
	  <tr>
		  <td colspan=5 rowspan=20 class="espacioCol1" style="vertical-align:top;"><!--<strong>SON:</strong>-->{$letras}</td>
	  </tr>
	  
	  <!-- Subtotales -->
	  {foreach from=$tipos item=tipoNom}
	  <tr>
		  <!--<td><strong>VALOR SIN DESCUENTO:</strong></td>-->  
		  <td  class="colnum espacioDer der padding5">{$valsindes[$tipoNom]|number_format:2|default:"0.00"}</td>
	  </tr>
	  <tr>
		  <!--<td><strong>DESCUENTO:</strong></td>-->  
		  <td  class="colnum espacioDer der padding5">{$valdes[$tipoNom]|number_format:2|default:"0.00"}</td>
	  </tr>
	  <tr>	  <td"></td>  </tr>
	  <tr>
		  <!--<td><strong>IVA:</strong></td>-->  
		  <td class="colnum espacioDer der padding5">{$valiva[$tipoNom]|number_format:2|default:"0.00"}</td>
	  </tr>
	  <tr>	  <td">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>  </tr>
	  <tr>
		  <!--<td><strong>TOTAL POR TIPO:</strong></td>-->  
		  <td class="colnum espacioDer der padding5">{$valor[$tipoNom]|number_format:2|default:"0.00"}</td>
	  </tr>
	  <tr>	  <td">&nbsp;</td>  </tr>
	  {/foreach}
	  <tr>
		    <!--<td><strong>TOTAL A PAGAR:</strong></td>-->
		    <td class="colnum espacioDer der padding5" style="padding-top:5px;">{$valorTot|number_format:2|default:"0.00"}</td>
	  </tr>
</table>
</br>
<!-- <div style="font-size:0.8em; text-align:left;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div> -->
{/report_footer}
{/report}
</body>