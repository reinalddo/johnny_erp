<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para el REPORTE COSTOS: COMISIONES-TRANSPORTE (formato de Aplesa) -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>COSTOS</title>
  {assign var=nGrp value=1}
</head> 

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec groups="TxtipoVariable" resort=false}

{report_header}
  <table border=1 cellspacing=0 >   
  <tbody>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:10pt;">
      <td class="headerrow" colspan=4>{$subtitulo} {$rec.EGNombre}</td>   
    </tr>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:8pt;">
      <td class="headerrow" colspan=4>{$subtitulo2}</td>   
    </tr>
{/report_header}

{report_header group="TxtipoVariable"}
    {assign var=nGrp value=$rec.TxtipoVariable}
    <tr style="font-weight:bold; text-align: left; vertical-align:middle; font-size:8pt;">
      <td colspan=4 style="background-color: #CED8F6; font-size:10;">{$rec.TxtipoVariable} WK {$rec.lde_semana}</td>
    </tr>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:8pt;">
      <td class="headerrow" style="background-color: #BDBDBD;">Comisionista</td>
      <td class="headerrow" style="background-color: #BDBDBD;">Cajas</td>
      <td class="headerrow" style="background-color: #BDBDBD;">Precio</td>
      <td class="headerrow" style="background-color: #BDBDBD;">Total</td>
    </tr>
    
{/report_header}


{report_detail}
  <tr style="vertical-align:middle; font-size:8pt;">
	<td class="coldata" nowrap style="width:2cm;">{$rec.Txauxiliar}</td>
        <td class="colnum" nowrap style="width:4cm;">{$rec.lde_cajas|number_format:2:".":","}</td>
        <td class="colnum" nowrap style="width:3cm;">{$rec.lde_precio|number_format:2:".":","}</td>
	<td class="colnum " nowrap style="width:3cm;">{$rec.PrecioTotal|number_format:2:".":","}</td>
    </tr>
{/report_detail}

{report_footer group="TxtipoVariable"}
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; ">
	<td colspan="1" style="text-align:left;">Total {$rec.TxtipoVariable}:</td>
	<td>{$sum.lde_cajas|number_format:2:".":","}<br><br></td>
	<td>{$sum.PrecioTotal/$sum.lde_cajas|number_format:2:".":","}<br><br></td>
	<td>{$sum.PrecioTotal|number_format:2:".":","}<br><br></td>
    </tr>
    
{/report_footer}

{report_footer}
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; background-color: #BDBDBD;">
	<td colspan="1">TOTAL COSTOS:</td>
	<td>{$sum.lde_cajas|number_format:2:".":","}</td>
	<td>{$sum.PrecioTotal/$sum.lde_cajas|number_format:2:".":","}</td>
	<td>{$sum.PrecioTotal|number_format:2:".":","}</td>
    </tr>
  </tbody>
  </table>
      
{/report_footer}

{/report}
</body>