<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
  <head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
    <meta name="author" content="Fausto Astudillo" />
    <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
    <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CONFIRMACION</title>
  </head>
  
  <body id="top" style="font-family:'Arial'; font-size:10;">
    <!--,txt_Nombre,cnf_Fecha,det_TipoComp,det_NumComp,det_Valor,c_total-->
    {report recordset=$agData record=rec	groups="det_Banco" resort=true}
    
    {report_header}
        <hr/>
        <!--{$rec.gsEmpre}-->
        <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:16; font-weight:bold"> PRUEBA</span>
        <div style="float:left;width:350px; font-size:0.9em;">
          <p style="text-align: center; display:block; width=60%">ENTREGA DE CHEQUES<br>
          FECHA DE ENTREGA: {$rec.cnf_Fecha}</p>
        </div>
        <p style="text-align: left; font:12; display:block; width=60%; font-size:0.8em;">
              No.: <strong>{$rec.cnf_NumReg}</strong><br>
              Fecha de Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}
        </p>
        <br>
        <table border=1 cellspacing=0 >
    {/report_header}
    
    {report_header group="det_Banco"}
        <tr>
            <td class="colhead headerrow">BANCO</td>
            <td class="colhead headerrow">CHEQUE</td>
            <td class="colhead headerrow" colspan=2>EGRESO</td>
            <td class="colhead headerrow">PROVEEDOR</td>
            <td class="colhead headerrow">VALOR</td>
        </tr>
    {/report_header}
    
        
    {report_detail}
            <tr>
                <td nowrap class="coldat">{$rec.det_Banco}</td>
                <td class="coldat">{$rec.det_Cheque}</td>
                <td class="coldat">{$rec.det_TipoComp}</td>
                <td class="coldat colnum ">{$rec.det_NumComp}</td>
                <td class="coldat " style="width:200px;">{$rec.txt_Nombre}</td>
                <td class="coldat colnum ">{$rec.det_Valor|string_format:"%.2f"}</td>
            </tr>
    {/report_detail} 
        
    
    {report_footer group="det_Banco"}
        <tr>
            <td class="colnum " colspan=5>TOTAL </td>
            <td class="colnum headerrow">{$sum.det_Valor|string_format:"%.2f"}</td>
        </tr>
        <tr></tr>
    {/report_footer}
    
    {report_footer}
        </table>
        <p>&nbsp</p>
        <table border=0 cellspacing=0 width=50%>
          <tr><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td></tr>
          <tr>
            <td class="coldat" style="width:150px;"><p style="border-top:solid;">ELABORADO POR</p></td>
            <td>&nbsp</td>
            <td class="coldat" style="width:150px;"><p style="border-top:solid;">AUTORIZADO POR</p></td>
            <td>&nbsp</td>
            <td class="coldat" style="width:180px;"><p style="border-top:solid;">RECIBI CONFORME</p></td>
          </tr>
        </table>
        <hr/>
    {/report_footer}
    
    {/report}
  </body>