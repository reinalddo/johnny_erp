<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Movimientos de Cuentas</title>
  
</head>
<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="fechacont,proveedor,ruc" resort=true}
{report_header}
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        LIBRO DE COMPRAS<br>
        {$subtitulo}
    </p>
    <table border=1 cellspacing=0 >
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">Proveedor</td>
            <td class="headerrow"><strong>Ruc</strong></td>
            <td class="headerrow"><strong>T/D</strong></td>
            <td class="headerrow"><strong>No.Comp Venta</strong></td>
            <td class="headerrow"><strong>CC</strong></td>
            <td class="headerrow"><strong>Fecha Imp.</strong></td>
            <td class="headerrow"><strong>Fecha Cont.</strong></td>
            <td class="headerrow"><strong>Fecha Validez</strong></td>
            <td class="headerrow"><strong>Aut. SRI</strong></td>
            <td class="headerrow"><strong>N/D Aut.</strong></td>
            <td class="headerrow"><strong>Base Imp. 12%</strong></td>
            <td class="headerrow"><strong>Base Imp. 0%</strong></td>
            <td class="headerrow"><strong>IVA</strong></td>
            <td class="headerrow"><strong>Total Compra</strong></td>
            <td class="headerrow"><strong>Retención IVA%</strong></td>
            <td class="headerrow"><strong>Valor</strong></td>
            <td class="headerrow"><strong>Ret.Fte.(1%-2%)</strong></td>
            <td class="headerrow"><strong>Ret.Fte.5%</strong></td>
            <td class="headerrow"><strong>Ret.Fte.8%</strong></td>
            <td class="headerrow"><strong>Ret.Fte.25%</strong></td>
            <td class="headerrow"><strong>Total a Pagar</strong></td>
            <td class="headerrow"><strong>No.Comp.Ret.</strong></td>
        </tr>
{/report_header}

{report_detail}
    <tr><td nowrap>{$rec.proveedor}</td>
        <td class="colnum ">{$rec.ruc}</td>        
        <td class="colnum ">{$rec.td}</td>
        <td class="colnum ">{$rec.comp_venta}</td>
	<td class="colnum ">{$rec.cc}</td>
	<td class="colnum ">{$rec.fecha_imp}</td>
        <td class="colnum ">{$rec.fecha_cont}</td>
        <td class="colnum ">{$rec.fecha_validez}</td>
        <td class="colnum ">{$rec.aut_sri}</td>
        <td class="colnum ">{$rec.nd_aut}</td>
        <td class="colnum ">{$rec.base_imp12}</td>
        <td class="colnum ">{$rec.base_imp0}</td>
        <td class="colnum ">{$rec.iva}</td>
        <td class="colnum ">{$rec.total_compra}</td>
        <td class="colnum ">{$rec.retencion_iva}</td>
        <td class="colnum ">{$rec.valor}</td>
        <td class="colnum ">{$rec.ret_fte1_2}</td>
        <td class="colnum ">{$rec.ret_fte5}</td>
        <td class="colnum ">{$rec.ret_fte8}</td>
        <td class="colnum ">{$rec.ret_fte25}</td>
        <td class="colnum ">{$rec.total_pagar|number_format:2}</td>
        <td class="colnum ">{$rec.comp_ret}</td>
    </tr>
{/report_detail}

{report_footer}
    <tr><td colspan=22>&nbsp</td></tr>
    <tr>
        <td colspan=19>&nbsp</td>
        <td class="colnum"><strong>TOTAL GENERAL:</strong></td>
        <td class="colnum headerrow">{$sum.total_pagar|number_format:2}</td>
        <td></td>
    </tr>
    </table>
{/report_footer}

{/report}
</body>