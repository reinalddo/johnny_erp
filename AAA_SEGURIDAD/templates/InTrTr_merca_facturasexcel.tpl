<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Reporte de Repuestos en Garantia
		Desarrollo para al compa�ia Farbem S.A.
		Presenta los Items en STOCK de la Bodega de Garantia, siempre y cuando su fecha de Ingreso a la bodega
		este dentro del periodo de dias de CUSTODIA -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Smart" />    
 
    <title>REPORTE DE FACTURAS</title>
     {literal}
  <style type="text/css">
        .num{text-align:right;}
	.subtitulo{font-size:10px;}
	.subtotal{font-style:italic; font-weight:bold;}
	.total{font-size:12px; font-weight:bold;margin-top:20px; padding-top:20px;}
    </style>
  {/literal}    
</head>

<body id="top">
{report recordset=$agData record=rec resort=true}


{report_header}
   
    <hr/>
    <!--<div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>-->
    </br>   
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{*$smarty.session.g_empr*}</strong><br>
        <strong>REPORTE DE FACTURA<strong><br>
        <p style="line-height:0.5em;"><strong>Usuario: </strong>{$smarty.session.g_user}</p>
        <p style="line-height:0.5em;"><strong>Fecha Imp.: </strong>{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</p>
	<p style="line-height:0.5em;">{$agArchivo}</p>
    </p>
    </div>
    {assign var=cols value=10}
    <center>
        <input type ="button"  onClick="fActualiza()" value="Filtrar"> 
  
    <table id="example" >
	

  <thead>
    <tr>            
            <td>Fecha Factura</td>
            <td>Numero de Factura</td>
            <td>Orden</td>
            <td>Semana</td>
            <td>Valor</td>            
        </tr>
      </thead>
{/report_header}
<tbody>


{report_detail}
    <tr>
        <td>{$rec.SEC}</td>
        <td>{$rec.FTR}</td>
        <td>{$rec.SHO}</td>
        <td>{$rec.COU}</td>
        <td>{$rec.TDC}</td>        
    </tr>
    
{/report_detail}
</tbody>
    </table>
</center>
{/report}
        
</body>