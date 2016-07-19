<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para el reporte de Ventas - Asisbane -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Alejandro" />
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="print" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />
  
    <title>CUADRO DE VENTAS</title>
  
</head>
<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec resort=false}
{report_header}
    <hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        CUADRO DE VENTAS<br>
        {$subtitulo}
    </p>
    </div>
    <table border=1 cellspacing=0 >
	<tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow"><strong>FECHA</strong></td>
	  <td class="headerrow"><strong>MES</strong></td>
	  <td class="headerrow"><strong>RUC</strong></td>
	  <td class="headerrow"><strong>CLIENTE</strong></td>
	  <td class="headerrow"><strong>TIPO DE VENTA</strong></td>
	  <td class="headerrow"><strong>REF.</strong></td>
	  <td class="headerrow"><strong>No FACTURA</strong></td>
	  <td class="headerrow"><strong>COD ITEM</strong></td>
	  <td class="headerrow"><strong>ITEM</strong></td>
	  <td class="headerrow"><strong>U. MEDIDA</strong></td>
	  <td class="headerrow"><strong>CANTIDAD</strong></td>
	  <td class="headerrow"><strong>P. UNIT</strong></td>
	  <td class="headerrow"><strong>TOTAL</strong></td>
	  <td class="headerrow"><strong>% DSCTO</strong></td>
	  <td class="headerrow"><strong>DESCUENTO</strong></td>
	  <td class="headerrow"><strong>TARIFA 0%</strong></td>
	  <td class="headerrow"><strong>% IVA</strong></td>
	  <td class="headerrow"><strong>TARIFA IVA</strong></td>
	  <td class="headerrow"><strong>IVA</strong></td>
	  <td class="headerrow"><strong>TOTAL FACTURA</strong></td>
	  <td class="headerrow"><strong>RET IR</strong></td>
	  <td class="headerrow"><strong>RET IVA</strong></td>
	  
	</tr>
        
{/report_header}

{report_detail}
    <tr>
        <td class="coldata">{$rec.com_FecContab}</td>  
	<td class="coldata">{$rec.mes}</td>
        <td class="coldata">{$rec.RUC}</td>        
        <td class="coldata">{$rec.cliente}</td>        
        <td class="coldata">{$rec.libro}</td>
        <td class="coldata">{$rec.com_RefOperat}</td>
	<td class="coldata">{$rec.com_NumComp}</td>
	<td class="colnum">{$rec.det_CodItem}</td>
	<td class="coldata">{$rec.item}</td>
	<td class="coldata">{$rec.uniMedida}</td>  
	<td class="colnum">{$rec.det_CanDespachada|number_format:2}</td>
        <td class="colnum">{$rec.det_valunitario|number_format:4}</td>        
        <td class="colnum">{$rec.det_ValTotal|number_format:4}</td>
        <td class="colnum">{$rec.det_porceDesc|number_format:2}</td>
        <td class="colnum">{$rec.Dscto|number_format:2}</td>
        <td class="colnum ">{$rec.base0|number_format:2}</td>
	<td class="colnum ">{$rec.pIVA}</td>
	<td class="colnum ">{$rec.baseIva|number_format:2}</td>
	<td class="colnum">{$rec.montoIva|number_format:2}</td>  
	<td class="colnum">{$rec.totalFac|number_format:2}</td>
        <td class="colnum">{$rec.valorRetRenta|number_format:2}</td>        
        <td class="colnum">{$rec.valorRetIva|number_format:2}</td>
    </tr>
{/report_detail}

{report_footer}
    <tr>
        <td class="coldata">&nbsp;</td>  
	<td class="coldata">&nbsp;</td>
        <td class="coldata" colspan=9> TOTAL GENERAL</td>
        <td class="colnum">{$sum.det_valunitario|number_format:4}</td>        
        <td class="colnum">{$sum.det_ValTotal|number_format:4}</td>
	<td class="colnum "></td>
        <td class="colnum">{$sum.Dscto|number_format:2}</td>
        <td class="colnum ">{$sum.base0|number_format:2}</td>
	<td class="colnum "></td>
	<td class="colnum ">{$sum.baseIva|number_format:2}</td>
	<td class="colnum">{$sum.montoIva|number_format:2}</td>  
	<td class="colnum">{$sum.totalFac|number_format:2}</td>
        <td class="colnum">{$sum.valorRetRenta|number_format:2}</td>        
        <td class="colnum">{$sum.valorRetIva|number_format:2}</td>
    </tr>
    </table>
{/report_footer}

{/report}
</body>