<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DETALLE DE TARJAS {$gsSubTitul}</title>
</head>
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
<body align:"center" id="top" style="font-family:'Arial'; ">
{report recordset=$agData record=rec}
<table width="100%" border="1" cellpadding="0" cellspacing="0">
{report_header}
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr>
			<td>Fecha</td>
			<td>Semana</td>
			<td>Codigo</td>
			<td>Productor</td>
			<td>Empresa</td>
			<td>Zona</td>
			<td>Producto</td>
			<td>Marca</td>
			<td>Vapor</td>
			<td>Puerto</td>
			<td>Tarja #</td>
			<td>Embar cadas</td>			
			<td>A Pagar</td>
			</tr>
         </thead>	 
{/report_header}

{report_detail}
	<tr style="white-space:nowrap">
			<td>{$rec.txp_Fecha}</td>
			<td>{$rec.txp_Semana}</td>
			<td>{$rec.txp_Embarcador}</td>
			<td>{$rec.txp_Productor}</td>
			<td>{$rec.txp_Shipper}</td>
			<td>{$rec.txp_NombZona}</td>
			<td>{$rec.txp_Producto}</td>
			<td>{$rec.txp_Marca}</td>
			<td>{$rec.txp_NombBuque}</td>
			<td>{$rec.txp_PtoDescripcion}</td>
			<td>{$rec.txp_NumTarja}</td>
			<td class="colnum">{$rec.txp_CantNeta|number_format:0}</td>
			<td class="colnum">{$rec.txp_CantPagar|number_format:0}</td>
			
         </tr>
{/report_detail}

</table>
{/report}
</body>
</html>
