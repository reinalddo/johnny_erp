<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para el REPORTE CUADRO DE PRECIOS (formato de Aplesa) -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CUADRO DE PRECIOS</title>
  {assign var=nGrp value=1}
</head> 

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec groups="txTipoCja" resort=false}

{report_header}
  <table border=1 cellspacing=0 >   
  <tbody>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:10pt;">
      <td class="headerrow" colspan=6>{$subtitulo} {$rec.EGNombre}</td>   
    </tr>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:8pt;">
      <td class="headerrow" colspan=6>{$subtitulo2}</td>   
    </tr>
{/report_header}

{report_header group="txTipoCja"}
    {assign var=nGrp value=$rec.txTipoCja}
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:8pt;">
      <td class="headerrow" style="background-color: #BDBDBD;">Zona</td>
      <td class="headerrow" style="background-color: #BDBDBD; text-align:left;">Productores {$rec.txTipoCja}</td>
      <td class="headerrow" style="background-color: #BDBDBD;">Producto</td>
      <td class="headerrow" style="background-color: #BDBDBD;">Cajas</td>
      <td class="headerrow" style="background-color: #BDBDBD;">Precio</td>
      <td class="headerrow" style="background-color: #BDBDBD;">Total</td>
    </tr>
    
{/report_header}


{report_detail}
  <tr style="vertical-align:middle; font-size:8pt;">
	<td class="coldata"  nowrap style="width:2cm;">{$rec.txzona}</td>
	<td class="coldata" nowrap style="width:2cm;">{$rec.productor}</td>
	<td class="coldata" nowrap style="width:2cm;">{$rec.Producto}</td>
        <td class="colnum" nowrap style="width:4cm;">{$rec.CajEmb|number_format:2:".":","}</td>
        <td class="colnum" nowrap style="width:3cm;">{$rec.pCaj|number_format:2:".":","}</td>
	<td class="colnum " nowrap style="width:3cm;">{$rec.tFruta|number_format:2:".":","}</td>
    </tr>
{/report_detail}

{report_footer group="txTipoCja"}
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; ">
	<td colspan="3" style="text-align:left;">TOTAL {$rec.txTipoCja}:</td>
	<td>{$sum.CajEmb|number_format:2:".":","}<br><br></td>
	<td>{$sum.tFruta/$sum.CajEmb|number_format:2:".":","}<br><br></td>
	<td>{$sum.tFruta|number_format:2:".":","}<br><br></td>
    </tr>
    
{/report_footer}

{report_footer}
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; background-color: #BDBDBD;">
	<td colspan="3" style="text-align:left;">TOTAL GENERAL:</td>
	<td>{$sum.CajEmb|number_format:2:".":","}</td>
	<td>{$sum.tFruta/$sum.CajEmb|number_format:2:".":","}</td>
	<td>{$sum.tFruta|number_format:2:".":","}</td>
    </tr>
  </tbody>
  </table>
      
{/report_footer}

{/report}
</body>