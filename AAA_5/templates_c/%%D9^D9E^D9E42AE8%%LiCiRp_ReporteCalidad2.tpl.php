<?php /* Smarty version 2.6.18, created on 2011-12-26 18:22:19
         compiled from ../Li_Files/LiCiRp_ReporteCalidad2.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', '../Li_Files/LiCiRp_ReporteCalidad2.tpl', 13, false),array('block', 'report_header', '../Li_Files/LiCiRp_ReporteCalidad2.tpl', 14, false),array('block', 'report_detail', '../Li_Files/LiCiRp_ReporteCalidad2.tpl', 190, false),array('block', 'report_footer', '../Li_Files/LiCiRp_ReporteCalidad2.tpl', 210, false),array('modifier', 'number_format', '../Li_Files/LiCiRp_ReporteCalidad2.tpl', 92, false),)), $this); ?>
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
  <?php $this->assign('num', 0); ?>
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<!-- 
      <table cellspacing=0 style="font-size:1.0em; width:19cm;height:auto;">
	<tr style="vertical-align:middle; text-align: center;">
            <td style=" width:7cm; vertical-align:middle;"><img src="../Images/InformeCliente/Logo_84x72.jpg"></td>
	    <td valign="middle" style="width:7cm; vertical-align:middle; "><?php echo $this->_tpl_vars['rec']['EDir']; ?>
<br><?php echo $this->_tpl_vars['rec']['ECiu']; ?>
, <?php echo $this->_tpl_vars['rec']['EPai']; ?>
<br>Phone <?php echo $this->_tpl_vars['rec']['ETel']; ?>
</td>
	
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
	  <td style="width:14cm; vertical-align:middle; text-align:center;font-size:0.9em; font-family:cursive;"><i><?php echo $this->_tpl_vars['rec']['Sec1Tx1']; ?>
<b><?php echo $this->_tpl_vars['rec']['Shipper']; ?>
</b>.</i></td>
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
	  <td style="width:12cm; vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['Shipper']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> To: </td>
	  <td style=" vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['Cliente']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Vessel: </td>
	  <td style=" vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['Vapor']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Port of loading: </td>
	  <td style=" vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['PtoEmbarque']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Port of destination: </td>
	  <td style=" vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['PtoDestino']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Date of sailing: </td>
	  <td style=" vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['FechaSalida']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Our reference: </td>
	  <td style=" vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['Semana']; ?>
-Viaje:<?php echo $this->_tpl_vars['rec']['Viaje']; ?>
</td>
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
	  <td style="width:12cm; vertical-align:middle;"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['CajasTotales'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Described as: </td>
	  <td style=" vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['Producto']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Weight: </td>
	  <td style=" vertical-align:middle;"><?php $this->assign('total1', $this->_tpl_vars['sum']['Embarcadas']*$this->_tpl_vars['avg']['PesoNeto']); ?><?php $this->assign('total2', $this->_tpl_vars['sum']['Embarcadas']*$this->_tpl_vars['avg']['PesoTotal']); ?><?php echo ((is_array($_tmp=$this->_tpl_vars['avg']['PesoNeto'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
 Net per box<br><?php echo ((is_array($_tmp=$this->_tpl_vars['avg']['PesoTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
 Gross per box<br><?php echo ((is_array($_tmp=$this->_tpl_vars['total1'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
 Net loaded<br><?php echo ((is_array($_tmp=$this->_tpl_vars['total2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
 Gross loaded</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Type of cartons: </td>
	  <td style=" vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['TipoCaja']; ?>
</td>
	</tr>
	<tr>
	  <td style="width:4cm; vertical-align:middle;font-weight:bold;"> Brands: </td>
	  <td style=" vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['TodasMarcas']; ?>
</td>
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
	  <td style="vertical-align:middle;"><?php echo $this->_tpl_vars['sum']['Embarcadas']; ?>
 Bxs of <?php echo $this->_tpl_vars['avg']['PesoNeto']; ?>
 Kg. Net</td>
	  <?php $this->assign('total1', $this->_tpl_vars['sum']['Embarcadas']*$this->_tpl_vars['avg']['PesoNeto']); ?>    
	  <td style="vertical-align:middle;"><?php echo $this->_tpl_vars['total1']; ?>
</td>
	</tr>
	<tr>
	  <td style="vertical-align:middle;font-weight:bold;">Gross Weight:</td>
	  <td style="vertical-align:middle;"><?php echo $this->_tpl_vars['sum']['Embarcadas']; ?>
 Bxs of <?php echo $this->_tpl_vars['avg']['PesoTotal']; ?>
 Kg. Gross</td>
	  <?php $this->assign('total2', $this->_tpl_vars['sum']['Embarcadas']*$this->_tpl_vars['avg']['PesoTotal']); ?>
	  <td style="vertical-align:middle;"><?php echo $this->_tpl_vars['total2']; ?>
</td>
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
	  <td style="width:6cm; vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['Evaluador']; ?>
</td>
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
	
      
      
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<?php $this->assign('num', $this->_tpl_vars['num']+1); ?>
	<tr>
	  <td style="vertical-align:middle;text-align:left;"><?php echo $this->_tpl_vars['rec']['HaciendaCodAnterior']; ?>
</td>
	  <td style="vertical-align:middle;text-align:center;"><?php echo $this->_tpl_vars['rec']['FechaTarja']; ?>
</td>
	  <td style="vertical-align:middle;text-align:left;"><?php echo $this->_tpl_vars['rec']['HaciendaNombre']; ?>
</td>
	  <td style="vertical-align:middle;text-align:left;"><?php echo $this->_tpl_vars['rec']['MarcaAbrv']; ?>
</td>
	  <td style="vertical-align:middle;text-align:left;"><?php echo $this->_tpl_vars['rec']['EmpqAbrv']; ?>
</td>
	  <td style="vertical-align:middle;text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['Embarcadas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
	  
	  <td style="vertical-align:middle;text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['Calibre'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <td style="vertical-align:middle;text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['Dedos'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <td style="vertical-align:middle;text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PesoTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	
	  <td style="vertical-align:middle;text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['Calidad'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	</tr>
        
	
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	
	<tr>
	  <td style="vertical-align:middle; text-align:left;font-weight:bold;background-color:#BDBDBD;"colspan="5">Total:</td>
	  <td style="vertical-align:middle; text-align:right;font-weight:bold;background-color:#BDBDBD;"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['Embarcadas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
	  
	  <td style="vertical-align:middle; text-align:right;font-weight:bold;background-color:#BDBDBD;"><?php echo ((is_array($_tmp=$this->_tpl_vars['avg']['Calibre'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <td style="vertical-align:middle; text-align:right;font-weight:bold;background-color:#BDBDBD;"><?php echo ((is_array($_tmp=$this->_tpl_vars['avg']['Dedos'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	  <td style="vertical-align:middle; text-align:right;font-weight:bold;background-color:#BDBDBD;"><?php echo ((is_array($_tmp=$this->_tpl_vars['avg']['PesoTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	
	  <td style="vertical-align:middle; text-align:right;font-weight:bold;background-color:#BDBDBD;"><?php echo ((is_array($_tmp=$this->_tpl_vars['avg']['Calidad'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	</tr>
      </table>
      <br><br>
      <table border="0" style="font-size:1.4em;">
	<tr>
	  <td style="vertical-align:middle; font-weight:bold;" colspan=2><?php echo $this->_tpl_vars['rec']['Sec4Tt1']; ?>
</td>
	</tr>
	<!-- <tr>
	  <td style="vertical-align:middle; text-align:justify;" colspan=2>The evaluation of the fruit and control of weight is carried out on 1% to 3% of
	  cartons chosen at random from each truck, and the 100% of trucks are checked.
	  <br><br>The crown of the fruit is treated with chemical products, free of diseases, harmful plagues, free of fungal infections and colepterous
	  damage and broken finger.
	  <br><br>Average quality of the fruit loaded: <?php echo $this->_tpl_vars['avg']['Calidad']; ?>
%, <?php echo $this->_tpl_vars['sum']['Embarcadas']; ?>
 boxes (Minimum quality: 80,00%)
	  <br><br>Average mm. in transversal diameter measured at middle finger of each hand:<br>
	  </td>
	</tr>-->
	<tr>
	  <td style="width:8cm; vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['Sec4Tx1']; ?>
</td>
	  <td style="width:1cm; vertical-align:middle;">&nbsp;</td>
	  <td style="width:8cm; vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['Sec4Tx2']; ?>
</td>
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
	  <td style="width:8cm; vertical-align:middle; text-align:center; font-weight:bold;">Supervisor<!--<?php echo $this->_tpl_vars['rec']['Shipper']; ?>
--></td>
	  <td style="vertical-align:middle;font-weight:bold;">&nbsp;</td>
	</tr>
       </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>