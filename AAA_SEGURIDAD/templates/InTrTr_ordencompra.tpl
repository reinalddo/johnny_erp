<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Johnny Valencia" />
  {literal}
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
      <style type="text/css">
        body{font-size:9px;}
        
        .padding10{padding:10px;}
        .empresa{height:30px;font-size:12px;font-weight:bold;text-align:center;vertical-align:middle;}
        .direccion{height:30px;font-size:11px;font-weight:bold;text-align:center;vertical-align:middle;}
        .titulo{font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;}
        .parrafo{text-align:justify; vertical-align:middle; text-transform:uppercase;}
        .bordeCompleto {border: #000000 solid 1px;}
        .bordeDer {border-right: #000000 solid 1px;}
        .bordeIzq {border-left: #000000 solid 1px;}
        .bordeSup {border-top: #000000 solid 1px;}
        .bordeInf {border-bottom: #000000 solid 1px;}
        .izq{text-align:left;}
        .der{text-align:right;}
        .cen{text-align:center;}
        td {
        font-size: 10px;
    }
	
	@media screen{#exportar{display:block !important;}}

    </style>
  {/literal}
  
    <title>Orden de Compra</title>
   
</head>
<body id="top" style="font-family:'Arial'" onload="window.print();">
{report recordset=$agData record=rec	groups="com_NumComp,det_Secuencia" resort=true}
{report_header}
    <div id="exportar" style="display:none;">
      <a href="InTrTr_ordencompra.rpt.php?pQryCom={$query}&pExcel=1">Exportar a Excel</a>
    </div>
    <hr/>
    {assign var=numCol value=8}
    
    
    {*<div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        PRODUCTORES<br>
        {$subtitulo}
    </p>
    </div>*}
    <table border=1 cellspacing=0 style="border:#000000 solid 1px;">
        <tr>
            <td colspan={$numCol} class="empresa bordeCompleto">
                {$smarty.session.g_empr} - {$subtitulo} </td>
        </tr>
        <tr>
            <td colspan={$numCol} class="direccion bordeCompleto">
                {$direccion} </td>
        </tr>
        <tr>
            <td colspan=4 class="padding10 bordeCompleto"><strong>ORDEN DE COMPRA N°:</strong>   {$rec.com_NumComp}</td>
            <td colspan=1 class="padding10 bordeCompleto"><strong>EMISION:</strong>   {$rec.com_FecTrans}</td>
            <td colspan=3 class="padding10 bordeCompleto"><strong>SEMANA:</strong>   {$rec.semana}</td>
        </tr>
        <tr>
            <td colspan=5 class="padding10 bordeCompleto"><strong>PROVEEDOR:</strong>   {$rec.com_Receptor}</td>
            <td colspan=3 class="padding10 bordeCompleto"><strong>RUC:</strong>   {$rec.ruc}</td>
        </tr>
        <tr>
            <td colspan=8 class="padding10 bordeCompleto"><strong>FORMA DE PAGO:</strong>
                {if ($rec.credito > 0)}
                    Credito {$rec.credito} dias
                {/if}
            </td>
        </tr>
        <tr>
            <td colspan=5 class="padding10 bordeCompleto"><strong>USO:</strong><br>   {$rec.com_Concepto}</td>
            <td colspan=3 class="padding10 bordeCompleto"><strong>FECHA DE ENTREGA:</strong>   {$rec.com_FecContab}</td>
        </tr>
        <tr>
            <td colspan=5 class="padding10 bordeCompleto"><strong>SOLICITA:</strong>    </td>
            <td colspan=3 class="padding10 bordeCompleto"><strong>TIPO DE FORMA:</strong>   </td>
        </tr>
        <tr style="height:40px;">
            <td colspan=8 class="padding10 bordeCompleto"><strong>CON CARGO A NUESTRA CUENTA SIRVASE DESPACHAR LO SIGUIENTE DE ACUERDO A COTIZACION PACTADA CON:</strong></td>
        </tr>
        
	<tr class="titulo bordeCompleto">
	  <td class=" bordeCompleto" style="width:4%;"><strong>Secuencia</strong></td>
	  <td class=" bordeCompleto" style="width:5%;"><strong>Codigo</strong></td>
          <td class=" bordeCompleto" style="width:50%;"><strong>Descripcion</strong></td>
	  <td class=" bordeCompleto" style="width:10%;"><strong>Unidad de Medida</strong></td>
	  <td class=" bordeCompleto" style="width:10%;"><strong>Cantidad</strong></td>	  
          <td class=" bordeCompleto" style="width:1%;"><strong>IVA</strong></td>
	  <td class=" bordeCompleto" style="width:10%;"><strong>Precio Unitario</strong></td>
	  <td class=" bordeCompleto" style="width:10%;"><strong>Importe Total</strong></td>
	</tr>
        
{/report_header}

{report_detail}
    <tr><!-- style="border:#000000 solid 1px;">headerrow-->
        <td class="der bordeIzq bordeDer" >{$rec.det_Secuencia}</td>  
	<td nowrap class="der bordeDer">{$rec.cod}</td>
        <td class="izq bordeDer">{$rec.descripcion}</td>
        <td class="izq bordeDer">{$rec.uni_Abreviatura}</td>        
        <td class="der bordeDer">{$rec.det_CanDespachada|number_format:0}</td>
        
        <td class="cen bordeDer">
            {if (2 == $rec.iva)}
                *
            {/if}
        </td>
	<td class="der bordeDer">{$rec.ValUnitario|number_format:6}</td>
	<td class="der bordeDer">{$rec.ValTotal|number_format:2}</td>
    </tr>
{/report_detail}

{report_footer}
    {*{for start=0 stop=10 step=2 value=current }
    We are on number { $current }
    {/for }*}
    {assign var=cuenta value=$count.det_CanDespachada}
    {foreach from=$agFilas item=value}
        {if ($value > $cuenta)}
            <tr>
                <td class="der bordeIzq bordeDer" >&nbsp</td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
            </tr>
        {/if}
    {/foreach}
    
    <tr>
        <td class="der bordeIzq bordeDer bordeInf" ></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
        <td class="izq bordeDer bordeInf"></td>
    </tr>
    <tr class="bordeSup">
        <td colspan=5 rowspan=4 class="bordeInf bordeIzq"><strong>SON:</strong>  {$letras}</td>
        <td colspan=2><strong>SUBTOTAL BASE 0%:</strong></td>
        <td class="der bordeDer">{$sum.iva0|number_format:2}</td>
    </tr>
    <tr>
        <td colspan=2><strong>SUBTOTAL BASE IVA:</strong></td>
        <td class="der bordeInf bordeDer">{$sum.iva12|number_format:2}</td>
    </tr>
    <tr>
        <td colspan=2><strong>IVA:</strong></td>
        <td class="der bordeInf bordeDer">{$iva|number_format:2}</td>
    </tr>
    <tr>
        <td colspan=2 class="bordeInf"><strong>TOTAL A PAGAR:</strong></td>
        <td class="der bordeInf bordeDer">{$valorTot|number_format:2}</td>
    </tr>
    <tr>
        <td colspan=8 class="parrafo padding10 bordeCompleto"><strong>NOTA: </strong>FAVOR FACTURAR ORIGINAL Y DOS COPIAS, E INCLUIR
                UNA COPIA DE ESTA ORDEN DE COMPRA PARA AGILITAR PAGO. TODOS LOS PAQUETES, FACTURAS,
                DOCUMENTOS DE EMBARQUE Y CORRESPONDENCIA DEBEN CONTENER EL NUMERO DE ESTA ORDEN DE COMPRA.
        </td>
    </tr>
    <tr style="text-align:center;">
        <td colspan=4 class="bordeCompleto"><strong>ELABORADO</strong></td>
        <td colspan=2 class="bordeCompleto"><strong>REVISADO</strong></td>
        <td colspan=2 class="bordeCompleto"><strong>APROBADO</strong></td>
    </tr>
    <tr style="text-align:center; height:60px;">
        <td colspan=4 class="bordeCompleto">{$rec.com_Usuario}, {$smarty.session.g_user}
                        <p>{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</p>
        </td>
        <td colspan=2 class="bordeCompleto"></td>
        <td colspan=2 class="bordeCompleto"></td>
    </tr>
    <tr style="text-align:center;">
        <td colspan=4 class="bordeCompleto"><strong>COMPRAS</strong></td>
        <td colspan=2 class="bordeCompleto"><strong>SOLICITANTE/ADMIN HCDA</strong></td>
        <td colspan=2 class="bordeCompleto"><strong>GERENCIA</strong></td>
    </tr>
    </table>
    </br>
    {*<div style="font-size:0.8em; text-align:left;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>*}
    
{/report_footer}

{/report}
</body>
