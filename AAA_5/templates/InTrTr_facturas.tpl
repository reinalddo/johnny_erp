<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>{$gsSubTitul}</title>
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/report.css" title="CSS para pantalla" />
</head>
<body align:"center" id="top" style="font-family:'Arial'; ">
{report recordset=$agData record=rec	groups="bodega, productor" resort=true}
<table width="98%" border="1" cellpadding="0" cellspacing="0">
{report_header}
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr >
			  <td class="colhead" colspan=11 align="center" style="text-align: center; font-weight:bold; ">REPORTE DE FACTURACION DE INSUMOS</td>
			</tr>
			<tr>
			  <td class="colhead" colspan=11 align="center" style="text-align: center; font-weight:bold; ">MODULO INVENTARIO</td>
			</tr>
			<tr>
			  <td class="colhead"  colspan=11 align="center" style="text-align: center; font-weight:bold; ">Rango: {$dh} </td>
			</tr>
	      		    
			<tr>
			<td>Semana</td>
			<td>Bodega</td>
			<td>Productor</td>
			<td>Libro</td>
			<td>Emisión</td>
			<td>Factura</td>
			<td colspan=2 align="left">Item</td>
			<td>Cantidad</td>
			<td>Valor_Uni</td>
			<td>Base_Impo</td>
			<td>%_IVA</td>
			<td>IVA_Grab</td>
			<td>Total</td>
			
			</tr>
         </thead>	 
         <tbody>
{/report_header}


{report_detail}
	<tr style="white-space:nowrap;">
			<td>{$rec.semana}</td>
			<td>{$rec.bodega}</td>
			<td>{$rec.productor}</td>
			<td>{$rec.Libro}</td>
			<td>{$rec.fecContab}</td>
			<td>{$rec.factura}</td>
			<td>{$rec.coditem}</td>
			<td>{$rec.item}</td>
			<td>{$rec.cantidad|number_format:0}</td>	
			<td>{$rec.valor_unitario|number_format:2}</td>
			<td>{$rec.BASE_IMPONIBLE|number_format:2}</td>
			<td>{$rec.PORCENTAJE_IVA}</td>
			<td>{$rec.IVA}</td>
			<td>{$rec.TOTAL|number_format:2}</td>
         </tr>
{/report_detail}


{report_footer}
		  <tr> 
			<td colspan="14" style="text-align:right">TOTAL : {$sum.TOTAL|number_format:2}</td>
		  </tr>
		  <tr> 
			<td colspan="13" style="text-align:left">{$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</td>
			<td>{$PiePagina}</td>
		  </tr>
  
{/report_footer}

</table>

{/report}
</body>
</html>