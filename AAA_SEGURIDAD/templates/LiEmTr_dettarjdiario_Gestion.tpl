<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DETALLE DE TARJAS {$gsSubTitul}</title>
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/report.css" title="CSS para pantalla" />
<body align:"center" id="top" style="font-family:'Arial'; ">

{report recordset=$agData groups="txp_NombZona,txp_Shipper,txp_Productor,txp_Producto" record=rec}
<table width="98%" border="1" cellpadding="0" cellspacing="0">
{report_header}
	  
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  
	  <thead>
	    <tr>
	      <td style="text-align:center" colspan=9>PARA GESTION DE FACTURACION{$gsSubTitul}</td>
	  </tr>
			<tr>
			<td>ZONA_ORIGEN</td>
			<td>ZONA_PAGO</td>
			<td>ZONA_CORTE</td>
			<td>COMPAÑIA</td>
			<td>CODIGO</td>
			<td>PRODUCTOR</td>
			<td>MARCA</td>
			<td>TIPO</td>
			<td>CANTIDAD</td>
			<td>PRECIO PACTADO</td>
			<td>TOTAL PACTADO</td>
			<td>PRECIO FACTURA</td>
			<td>TOTAL  FACTURA</td>
			</tr>
         </thead>	 
{/report_header}
{report_detail}
	<tr style="white-space:nowrap">
			<td>{$rec.zona_origen}</td>
			<td>{$rec.ZONA_PAGO}</td>
			<td>{$rec.ZONA_CORTE}</td>
			<td>{$rec.txp_Shipper}</td>
			<td>{$rec.txp_Embarcador}</td>
			<td>{$rec.txp_Productor}</td>
			<td>{$rec.txp_Marca}</td>
			<td>{$rec.txp_Producto}</td>		
			<td class="colnum">{$rec.txp_CantPagar|number_format:0}</td>
			<td class="colnum">{$rec.txp_PrecUnit|number_format:4}</td>
			<td class="colnum">{$rec.txp_ValTotal|number_format:2}</td>
			<td class="colnum">{$rec.txp_PrecFac|number_format:4}</td>
			<td class="colnum">{$rec.txp_PrecFac*$rec.txp_CantPagar|number_format:2}</td>
         </tr>
{/report_detail}


</table>
{/report}
</body>
</html>
