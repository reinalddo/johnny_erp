<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
    <title>Orden de Compra</title>
  
</head>
<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="com_NumComp,det_Secuencia" resort=true}
{report_header}
    <hr/>
    {assign var=numCol value=7}
    
    
    {*<div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        PRODUCTORES<br>
        {$subtitulo}
    </p>
    </div>*}
    <table border=1 cellspacing=0 style="border:#000000 solid 1px;">
        <tr>
            <td colspan={$numCol} style="height:50px;font-size:16px;font-weight:bold;text-align:center;vertical-align:middle;">
                {$smarty.session.g_empr}</td>            
        </tr>
        <tr>
            <td colspan=3><strong>ORDEN DE COMPRA N°:</strong>   {$rec.com_NumComp}</td>
            <td colspan=4><strong>EMISION:</strong>   {$rec.com_FecTrans}</td>
        </tr>
        <tr>
            <td colspan=5><strong>PROVEEDOR:</strong>   {$rec.com_Receptor}</td>
            <td colspan=2><strong>RUC:</strong>   {$rec.ruc}</td>
        </tr>
        <tr>
            <td colspan=7><strong>FORMA DE PAGO:</strong>   </td>
        </tr>
        <tr>
            <td colspan=5><strong>USO:</strong><br>   {$rec.com_Concepto}</td>
            <td colspan=2><strong>FECHA DE ENTREGA:</strong>   {$rec.com_FecContab}</td>
        </tr>
        <tr>
            <td colspan=5><strong>SOLICITA:</strong>    </td>
            <td colspan=2><strong>TIPO DE FORMA:</strong>   </td>
        </tr>
        <tr>
            <td colspan=7><strong>CON CARGO A NUESTRA CUENTA SIRVASE DESPACHAR LO SIGUIENTE DE ACUERDO A COTIZACION PACTADA CON:</strong></td>
        </tr>
	<tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow"><strong>Item</strong></td>
	  <td class="headerrow"><strong>Codigos</strong></td>
	  <td class="headerrow"><strong>Unidad de Medida</strong></td>
	  <td class="headerrow"><strong>Cantidad</strong></td>
	  <td class="headerrow"><strong>Descripcion</strong></td>
	  <td class="headerrow"><strong>Precio Unitario</strong></td>
	  <td class="headerrow"><strong>Importe Total</strong></td>
	</tr>
        
{/report_header}

{report_detail}
    <tr><!-- style="border:#000000 solid 1px;">-->
        <td class="colnum ">{$rec.det_Secuencia}</td>  
	<td nowrap class="colnum ">{$rec.det_CodItem}</td>
        <td class="coldata ">{$rec.uni_Abreviatura}</td>        
        <td class="colnum ">{$rec.det_CanDespachada|number_format:0}</td>
        <td class="coldata ">{$rec.descripcion}</td>
	<td class="colnum ">{$rec.det_CosUnitario|number_format:4}</td>
	<td class="colnum ">{$rec.det_CosTotal|number_format:2}</td>
    </tr>
{/report_detail}

{report_footer}
    <tr>
        <td colspan=5 rowspan=3 ><strong>SON:</strong>  {$letras}</td>
        <td><strong>SUBTOTAL:</strong></td>
        <td class="colnum ">{$sum.det_CosTotal|number_format:2}</td>
    </tr>
    <tr>
        <td><strong>IVA:</strong></td>
        <td></td>
    </tr>
    <tr>
        <td><strong>TOTAL A PAGAR:</strong></td>
        <td class="colnum ">{$sum.det_CosTotal|number_format:2}</td>
    </tr>
    <tr style="text-align:center;">
        <td colspan=3 ><strong>ELABORADO</strong></td>
        <td colspan=2 ><strong>REVISADO</strong></td>
        <td colspan=2 ><strong>APROBADO</strong></td>
    </tr>
    <tr style="text-align:center; height:60px;">
        <td colspan=3>{$rec.com_Usuario}, {$smarty.session.g_user}</td>
        <td colspan=2></td>
        <td colspan=2></td>
    </tr>
    <tr style="text-align:center;">
        <td colspan=3><strong>DEPTO. COMPRAS</strong></td>
        <td colspan=2><strong>CONTRALORIA</strong></td>
        <td colspan=2><strong>GERENCIA GENERAL</strong></td>
    </tr>
    </table>
    </br>
    <div style="font-size:0.8em; text-align:left;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>
    
{/report_footer}

{/report}
</body>