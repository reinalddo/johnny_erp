<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Reporte de Repuestos en Garantia
		Desarrollo para al compañia Farbem S.A.
		Presenta los Items en STOCK de la Bodega de Garantia, siempre y cuando su fecha de Ingreso a la bodega
		este dentro del periodo de dias de CUSTODIA -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="Johnny Valencia" content="smart" />
  {literal}
  <style type="text/css">
        .num{text-align:right;}
	.subtitulo{font-size:10px;}
	.subtotal{font-style:italic; font-weight:bold;}
	.total{font-size:12px; font-weight:bold;margin-top:20px; padding-top:20px;}
    </style>
  {/literal}    
  <title>REPORTE DE REPUESTOS EN GARANTIA</title> 
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec resort=true}


{report_header}
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>-->
    <hr/>
    <!--<div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>-->
    </br>   
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{*$smarty.session.g_empr*}</strong><br>
        <strong>REPORTE DE REPUESTOS EN GARANTIA<strong><br>
        <p style="line-height:0.5em;"><strong>Usuario: </strong>{$smarty.session.g_user}</p>
        <p style="line-height:0.5em;"><strong>Fecha Imp.: </strong>{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</p>
	<p style="line-height:0.5em;">{$agArchivo}</p>
    </p>
    </div>    
    {assign var=cols value=10}
    <center>
    <table border=1 cellspacing=0 >
	

  
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">#.</td>
            <td class="headerrow">Fecha Transaccion</td>
            <td class="headerrow">Nombre Empresa</td>
            <td class="headerrow">Pais</td>
            <td class="headerrow">Tipo</td>
            <td class="headerrow">Comprobante</td>
            <td class="headerrow">Orden de trabajo</td>
            <td class="headerrow">Ref. Interna</td>
            <td class="headerrow">Etiqueta</td>
            <td class="headerrow">Contenedor</td>
            <td class="headerrow">Item</td>
            <td class="headerrow">Descripcion</td>
            <td class="headerrow">Serie</td>
            <td class="headerrow">Serie Nueva</td>
            <td class="headerrow">Garantia</td>      
        </tr>
{/report_header}



{report_detail}
    <tr>
        <td class="coldata">{$rec.SEC}</td>
        <td class="coldata">{$rec.FTR}</td>
        <td class="coldata">{$rec.SHO}</td>
        <td class="coldata">{$rec.COU}</td>
        <td class="coldata">{$rec.TDC}</td>
        <td class="coldata">{$rec.NDC}</td>
        <td class="coldata">{$rec.WOR}</td>        
        <td class="coldata">{$rec.INR}</td>                
        <td class="coldata">{$rec.TAG}</td>                
        <td class="coldata">{$rec.CON}</td>               
		  <td class="coldata">{$rec.ITE}</td>                
        <td class="coldata">{$rec.NAM}</td>                
        <td class="coldata">{$rec.SER}</td>
        <td class="coldata">{$rec.DSN}</td>
        <td class="coldata">{$rec.PER}</td>  
    </tr>
    
{/report_detail}
 </table>
</center>
{/report}
  
</body>