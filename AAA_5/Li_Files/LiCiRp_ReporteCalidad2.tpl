<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- esl 14/nov/2011 Plantilla para Reporte de Calidad formato de Don Julian-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8;" />
  <meta name="author" content="Gina Franco" />
  <!-- link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" /--> 
   <title>REPORT</title>
</head>

<body id="top" style="font-family:'Arial'">
  {assign var=num value=0}
{report recordset=$agData record=rec resort=true}
{report_header}

<!-- 
      <table cellspacing=0 style="font-size:1.0em; width:19cm;height:auto;">
	<tr style="vertical-align:middle; text-align: center;">
            <td style=" width:7cm; vertical-align:middle;"><img src="../Images/InformeCliente/Logo_84x72.jpg"></td>
	    <td valign="middle" style="width:7cm; vertical-align:middle; ">{$rec.EDir}<br>{$rec.ECiu}, {$rec.EPai}<br>Phone {$rec.ETel}</td>
	
	</tr>
      </table>
      <br><br>
      --> 
      <table border="0" style="font-weight:bold; font-size:1.4em;">
	<tr>
	  <td style="width:3cm; vertical-align:middle;" rowspan="3"><img src="../Images/InformeCliente/logo382x327.jpg" width="2.5cm" height="2cm"></td>
	  <td style="width:14cm; vertical-align:middle; text-align:center;font-size:1.4em;"><br>QUALITY DEPARTMENT</td>
	  <td style="vertical-align:middle;"></td>
	</tr>
	<tr>
	  <td style="width:14cm; vertical-align:middle; text-align:center;font-size:1.0em;"><br>LOADING SURVEY REPORT</td>
	  <td style="vertical-align:middle;"> </td>
	</tr>
	<tr>
	  <td style="width:14cm; vertical-align:middle; text-align:center;font-size:0.9em; font-family:cursive;"><i>{$rec.Sec1Tx1}<b>{$rec.Shipper}</b>.</i></td>
	  <td style="vertical-align:middle;"> </td>
	</tr>
      </table>
      <br><br>
      
      
      <table style="font-size:1.4em;">
	<tr>
	  <td style="width:14cm; vertical-align:middle; font-weight:bold;">I. MAIN PARTICULARS OF THE CARGO:</td>
	</tr>
      </table>
      <br><br>
      
      
      <table border="0" style="font-size:1.4em;">
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Shipper: </td>
	  <td style="width:12cm; vertical-align:middle;">{$rec.Shipper}</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> To: </td>
	  <td style=" vertical-align:middle;">{$rec.Cliente}</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Vessel: </td>
	  <td style=" vertical-align:middle;">{$rec.Vapor}</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Port of loading: </td>
	  <td style=" vertical-align:middle;">{$rec.PtoEmbarque}</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Port of destination: </td>
	  <td style=" vertical-align:middle;">{$rec.PtoDestino}</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Date of sailing: </td>
	  <td style=" vertical-align:middle;">{$rec.FechaSalida}</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Our reference: </td>
	  <td style=" vertical-align:middle;">{$rec.Semana}-Viaje:{$rec.Viaje}</td>
	</tr>
      </table>
      <br><br>
      <table style="font-size:1.4em;">
	<tr>
	  <td style="width:6cm; vertical-align:middle; font-weight:bold;" colspan=2>II. CARGO DESCRIPTION:</td>
	</tr>
      </table>
      <br><br>
      <table border="0" style="font-size:1.4em;">
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Quantity of Boxes: </td>
	  <td style="width:12cm; vertical-align:middle;">{$sum.CajasTotales|number_format:0}</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Described as: </td>
	  <td style=" vertical-align:middle;">{$rec.Producto}</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Weight: </td>
	  <td style=" vertical-align:middle;">{assign var=total1  value=$sum.Embarcadas*$avg.PesoNeto}{assign var=total2  value=$sum.Embarcadas*$avg.PesoTotal}{$avg.PesoNeto|number_format:2} Net per box<br>{$avg.PesoTotal|number_format:2} Gross per box<br>{$total1|number_format:2} Net loaded<br>{$total2|number_format:2} Gross loaded</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Type of cartons: </td>
	  <td style=" vertical-align:middle;">{$rec.TipoCaja}</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Brands: </td>
	  <td style=" vertical-align:middle;">{$rec.TodasMarcas}</td>
	</tr>
      </table>
      <br><br>
      
      
      <!-- 
      <table style="font-size:1.4em;">
	<tr>
	  <td style="vertical-align:middle; font-weight:bold;">WEIGHT:</td>
	</tr>
	<tr>
	  <td style="vertical-align:middle; text-align:justify;">The following results were obtained during the inspection carried out.</td>
	</tr>
      </table>
      <br><br>
      <table border="0" style="font-size:1.4em;">
	<tr>
	  <td style="width:3cm; vertical-align:middle; font-weight:bold; ">Weight:</td>
	  <td style="width:8cm; vertical-align:middle;font-weight:bold;text-align:center;">Total Boxes x Weight</td>
	  <td style="vertical-align:middle;font-weight:bold;text-align:center;">Total Weight</td>
	</tr>
      
	<tr>
	  <td style="vertical-align:middle;font-weight:bold;">Net Weight:</td>
	  <td style="vertical-align:middle;">{$sum.Embarcadas} Bxs of {$avg.PesoNeto} Kg. Net</td>
	  {assign var=total1  value=$sum.Embarcadas*$avg.PesoNeto}    
	  <td style="vertical-align:middle;">{$total1}</td>
	</tr>
	<tr>
	  <td style="vertical-align:middle;font-weight:bold;">Gross Weight:</td>
	  <td style="vertical-align:middle;">{$sum.Embarcadas} Bxs of {$avg.PesoTotal} Kg. Gross</td>
	  {assign var=total2  value=$sum.Embarcadas*$avg.PesoTotal}
	  <td style="vertical-align:middle;">{$total2}</td>
	</tr>
      </table>
      <br><br>
      -->
      
      
      <!-- <table style="font-size:1.4em;">
	<tr>
	  <td style="width:9cm; vertical-align:middle; font-weight:bold;"><u>PERSONNEL PRESENT DURING THE INTERVENTION:</u></td>
	</tr>
      </table>
      <br><br>
      <table>
	<tr>
	  <td style="width:6cm; vertical-align:middle;">{$rec.Evaluador}</td>
	  <td style="width:6cm; vertical-align:middle;">&nbsp;</td>
	</tr>
       </table>
      <br><br>-->
      <table style="font-size:1.4em;">
	<tr>
	  <!-- <td style="vertical-align:middle; font-weight:bold;font-size:1.4em;" colspan=4><u>CONTAINER RESULT OF LOADING SUPERVISION:</u></td> -->
	  <td style="vertical-align:middle; font-weight:bold;" colspan=4>III. CARGO DETAILS:</td>
	</tr>
      </table><br><br>
      
      
      
      <table border="0" style="font-size:1.4em;" width="11cm">
	<tr style="font-weight:bold;">
	  <td style="width:1.4cm; vertical-align:middle;text-align:left; background-color:#BDBDBD;">Farm Code</td>
	  <td style="width:1.8cm; vertical-align:middle;text-align:center;background-color:#BDBDBD;">Process Date</td>
	  <td style="width:2.5cm; vertical-align:middle;text-align:center;background-color:#BDBDBD;">Farm Name</td>
	  <td style="width:2cm; vertical-align:middle;text-align:center;background-color:#BDBDBD;">Brand</td>
	  <td style="width:2cm; vertical-align:middle;text-align:center;background-color:#BDBDBD;">Packing</td>
	  <td style="width:1.6cm; vertical-align:middle;text-align:center;background-color:#BDBDBD;">Total Boxes</td>
	  
	  <td style="width:2.1cm; vertical-align:middle;text-align:center;background-color:#BDBDBD;">Avergage Calibration</td>
	  <td style="width:1.6cm; vertical-align:middle;text-align:center;background-color:#BDBDBD;">Average Length Fingers</td>
	  <td style="width:1.6cm; vertical-align:middle;text-align:center;background-color:#BDBDBD;">Average Weight per Box</td>
	
	  <td style="width:1.6cm; vertical-align:middle;text-align:center;background-color:#BDBDBD;">Quality %</td>
	</tr>
	
      
      
{/report_header}

{report_detail}
	{assign var=num value=$num+1}
	<tr>
	  <td style="vertical-align:middle;text-align:left;">{$rec.HaciendaCodAnterior}</td>
	  <td style="vertical-align:middle;text-align:center;">{$rec.FechaTarja}</td>
	  <td style="vertical-align:middle;text-align:left;">{$rec.HaciendaNombre}</td>
	  <td style="vertical-align:middle;text-align:left;">{$rec.MarcaAbrv}</td>
	  <td style="vertical-align:middle;text-align:left;">{$rec.EmpqAbrv}</td>
	  <td style="vertical-align:middle;text-align:right;">{$rec.Embarcadas|number_format:0}</td>
	  
	  <td style="vertical-align:middle;text-align:right;">{$rec.Calibre|number_format:2}</td>
	  <td style="vertical-align:middle;text-align:right;">{$rec.Dedos|number_format:2}</td>
	  <td style="vertical-align:middle;text-align:right;">{$rec.PesoTotal|number_format:2}</td>
	
	  <td style="vertical-align:middle;text-align:right;">{$rec.Calidad|number_format:2}</td>
	</tr>
        
	
{/report_detail}

{report_footer}
	
	<tr>
	  <td style="vertical-align:middle; text-align:left;font-weight:bold;background-color:#BDBDBD;"colspan="5">Total:</td>
	  <td style="vertical-align:middle; text-align:right;font-weight:bold;background-color:#BDBDBD;">{$sum.Embarcadas|number_format:0}</td>
	  
	  <td style="vertical-align:middle; text-align:right;font-weight:bold;background-color:#BDBDBD;">{$avg.Calibre|number_format:2}</td>
	  <td style="vertical-align:middle; text-align:right;font-weight:bold;background-color:#BDBDBD;">{$avg.Dedos|number_format:2}</td>
	  <td style="vertical-align:middle; text-align:right;font-weight:bold;background-color:#BDBDBD;">{$avg.PesoTotal|number_format:2}</td>
	
	  <td style="vertical-align:middle; text-align:right;font-weight:bold;background-color:#BDBDBD;">{$avg.Calidad|number_format:2}</td>
	</tr>
      </table>
      <br><br>
      <table border="0" style="font-size:1.4em;">
	<tr>
	  <td style="vertical-align:middle; font-weight:bold;" colspan=2>{$rec.Sec4Tt1}</td>
	</tr>
	<!-- <tr>
	  <td style="vertical-align:middle; text-align:justify;" colspan=2>The evaluation of the fruit and control of weight is carried out on 1% to 3% of
	  cartons chosen at random from each truck, and the 100% of trucks are checked.
	  <br><br>The crown of the fruit is treated with chemical products, free of diseases, harmful plagues, free of fungal infections and colepterous
	  damage and broken finger.
	  <br><br>Average quality of the fruit loaded: {$avg.Calidad}%, {$sum.Embarcadas} boxes (Minimum quality: 80,00%)
	  <br><br>Average mm. in transversal diameter measured at middle finger of each hand:<br>
	  </td>
	</tr>-->
	<tr>
	  <td style="width:8cm; vertical-align:middle;">{$rec.Sec4Tx1}</td>
	  <td style="width:1cm; vertical-align:middle;">&nbsp;</td>
	  <td style="width:8cm; vertical-align:middle;">{$rec.Sec4Tx2}</td>
	</tr>
      </table><br><br>
      
      <!-- 
      <table style="font-size:1.4em;">
	<tr>
	  <td style="vertical-align:middle; font-weight:bold;" colspan=2><strong>V. VISUAL CONDITION OF THE CARGO:</strong></td>
	</tr>
	<tr>
	  <td style="vertical-align:middle; text-align:justify;" colspan=2>The loading cartons were chosen at randon for visual inspection. In general, we found the bananas fresh,
	  green, clean and with normal appearance</td>
	</tr>
      </table>
      -->
      
      <!-- <br><br>
       <table border="0" style="font-size:1.4em; /*border-style:solid; border-color:#BDBDBD; border-width:1px;*/">
	<tr>
	  <td style="vertical-align:middle; text-align:justify;"><br><br>This report is based on the facts observed and reported by our surveyors in attendance
	  and is submitted without prejudice. The right to amend or supplement this report on the basis of additional information is reserved.
	  <br><br>This inspection was carried out to the best of our knowledge and ability and our report reflects our findings at the time and place of our
	  inspection and is believed to be correct at time of issuing the certificate. This Report do not realese the contractual parties from their contactual
	  obligations and our responsibility is limited to the exercise of due care.<br>
	  </td>
	</tr>
       </table>
       -->
       
       <br><br><br><br>
       <table border="0" style="font-size:1.4em;">
	<tr>
	  <td style="width:4cm; vertical-align:middle; font-weight:bold;">&nbsp;</td>
	  <td style="width:8cm; vertical-align:middle; text-align:center; font-weight:bold;"><hr></td>
	  <td style="vertical-align:middle;font-weight:bold;">&nbsp;</td>
	</tr>
        <tr>
	  <td style="width:4cm; vertical-align:middle; font-weight:bold;">&nbsp;</td>
	  <td style="width:8cm; vertical-align:middle; text-align:center; font-weight:bold;">Supervisor<!--{$rec.Shipper}--></td>
	  <td style="vertical-align:middle;font-weight:bold;">&nbsp;</td>
	</tr>
       </table>
{/report_footer}

{/report}
</body>
