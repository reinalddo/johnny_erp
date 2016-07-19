<?php /* Smarty version 2.6.18, created on 2013-06-06 09:38:52
         compiled from ../Li_Files/LiLiRp_ConsolidaContenedor.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', '../Li_Files/LiLiRp_ConsolidaContenedor.tpl', 19, false),array('block', 'report_header', '../Li_Files/LiLiRp_ConsolidaContenedor.tpl', 21, false),array('block', 'report_detail', '../Li_Files/LiLiRp_ConsolidaContenedor.tpl', 107, false),array('block', 'report_footer', '../Li_Files/LiLiRp_ConsolidaContenedor.tpl', 115, false),array('modifier', 'date_format', '../Li_Files/LiLiRp_ConsolidaContenedor.tpl', 45, false),array('modifier', 'number_format', '../Li_Files/LiLiRp_ConsolidaContenedor.tpl', 119, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para Reporte Consolidado de Contenedores -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CONSOLIDACION DE CONTENEDORES</title>
  
</head> 

<body id="top" style="font-family:'Arial'">
<?php $this->assign('acum', 0); ?>
<?php $this->assign('sal', 0); ?>

<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "GrpVapor,GrpCont")); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <table style="width:29.80cm; text-align:center;">
    <tr><td>	    <p style="font-size:12pt;"><strong><?php echo $_SESSION['g_empr']; ?>
</strong></p>
		    <p style="font-size:14pt"><?php echo $this->_tpl_vars['subtitulo']; ?>
</p>
		    <p style="font-size:10pt"><?php echo $this->_tpl_vars['subtitulo2']; ?>
</p>
		    <hr>
    </td>
    </tr>
  </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>



<?php $this->_tag_stack[] = array('report_header', array('group' => 'GrpVapor')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
      <table border="0" style="font-size:12pt; width:29.80cm; background-color:#CEE3F6">
      <tr style="text-align:left;background-color:#FFFFFF">
	  <td colspan=6><br><br><br></td>
      </tr>
      <tr style="text-align:left;">
	  <td style="width:3cm;font-weight:bold;">VAPOR:</td>
	  <td style="width:7cm;"><?php echo $this->_tpl_vars['rec']['Vapor']; ?>
</td>
	  <td style="width:3cm;font-weight:bold;">SEMANA:</td>
	  <td style="width:2.5cm;"><?php echo $this->_tpl_vars['rec']['tac_Semana']; ?>
</td>
	  <td style="width:3cm;font-weight:bold;">FECHA:</td>
	  <td style="width:2.5cm;"><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</td><!-- Dia de consulta del reporte -->
      </tr>
      <tr style="text-align:left;">
	  <td style="font-weight:bold;">AGENCIA:</td>
	  <td><?php echo $this->_tpl_vars['rec']['Agencia']; ?>
</td>
	  <td style="font-weight:bold;">PUERTO:</td>
	  <td><?php echo $this->_tpl_vars['rec']['Puerto']; ?>
</td>
	  <td style="font-weight:bold;">MODULO:</td>
	  <td><?php echo $this->_tpl_vars['rec']['Modulo']; ?>
</td><!-- Dia de consulta del reporte -->
      </tr>
      <tr style="text-align:left;">
	  <td style="font-weight:bold;">CONSIGNATARIO:</td>
	  <td><?php echo $this->_tpl_vars['rec']['Consignatario']; ?>
</td>
	  <td style="font-weight:bold;">DESTINO:</td>
	  <td><?php echo $this->_tpl_vars['rec']['PaisDestino']; ?>
</td>
	  <td style="font-weight:bold;">BOOKING:</td>
	  <td><?php echo $this->_tpl_vars['rec']['Booking']; ?>
</td><!-- Dia de consulta del reporte -->
      </tr>
      </table><br>
      <table border="1" style="width:29.80cm;">
	<tr style="text-align:center;font-weight:bold;font-size:10pt;background-color:#BDBDBD">
	  <td style="width:3cm;">CONTENEDOR NUMERO</td>
	  <td style="width:2cm;">INICIO</td>
	  <td style="width:2cm;">TERMINO</td>
	  <td style="width:2cm;">SELLO<BR>NAVIERA</td>
	  <td style="width:2cm;">SELLO<BR>EMPRESA</td>
	  <td style="width:2cm;">SELLO<BR>ANTINARCOTICO 1</td>
	  <td style="width:2cm;">SELLO<BR>ANTINARCOTICO 2</td>
	  <td style="width:2cm;">PRODUCTO</td>
	  <td style="width:1.5cm;">TIPO<BR>CARGA</td>
	  <td style="width:2cm;">TOTAL<BR>CAJAS</td>
	  <td style="width:2cm;">TIPO<BR>EMPAQUE</td>
	  <td style="width:2cm;">MARCA CAJAS</td>
	  <td style="width:1cm;">PESO</td>
	  <td style="width:2cm;">TERMOGRAFO</td>
	  <td style="width:1cm;">CODIGO<BR>PRODUCTOR</td>
	  <td style="width:3cm;">PRODUCTOR</td>
	  <td style="width:2cm;">CANTIDAD</td>
      </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'GrpCont')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
      <tr style="text-align:left;font-size:10pt;">
	  <td rowspan=<?php echo $this->_tpl_vars['rec']['NVap_Con']; ?>
 style="vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['tac_Contenedor']; ?>
</td>
	  <td rowspan=<?php echo $this->_tpl_vars['rec']['NVap_Con']; ?>
 style="vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['emb_FecInicio']; ?>
</td>
	  <td rowspan=<?php echo $this->_tpl_vars['rec']['NVap_Con']; ?>
 style="vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['emb_FecTermino']; ?>
</td>
	  <td rowspan=<?php echo $this->_tpl_vars['rec']['NVap_Con']; ?>
 style="vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['SelloNaviera']; ?>
</td>
	  <td rowspan=<?php echo $this->_tpl_vars['rec']['NVap_Con']; ?>
 style="vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['SelloEmpresa']; ?>
</td>
	  <td rowspan=<?php echo $this->_tpl_vars['rec']['NVap_Con']; ?>
 style="vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['SelloAntiNarc1']; ?>
</td>
	  <td rowspan=<?php echo $this->_tpl_vars['rec']['NVap_Con']; ?>
 style="vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['SelloAntiNarc2']; ?>
</td>
	  <td rowspan=<?php echo $this->_tpl_vars['rec']['NVap_Con']; ?>
 style="vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['DescProducto']; ?>
</td>
	  <td rowspan=<?php echo $this->_tpl_vars['rec']['NVap_Con']; ?>
 style="vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['TipoCarga']; ?>
</td>
	  <td rowspan=<?php echo $this->_tpl_vars['rec']['NVap_Con']; ?>
 style="vertical-align:middle;text-align:right;"><?php echo $this->_tpl_vars['rec']['TotCjaEmbarcada']; ?>
</td>
	  <td rowspan=<?php echo $this->_tpl_vars['rec']['NVap_Con']; ?>
 style="vertical-align:middle;text-align:center;"><?php echo $this->_tpl_vars['rec']['AbreviaturaCaja']; ?>
</td>
	  <td rowspan=<?php echo $this->_tpl_vars['rec']['NVap_Con']; ?>
 style="vertical-align:middle;"><?php echo $this->_tpl_vars['rec']['Marca']; ?>
</td>
	  <td rowspan=<?php echo $this->_tpl_vars['rec']['NVap_Con']; ?>
 style="vertical-align:middle;text-align:right;"><?php echo $this->_tpl_vars['rec']['Peso']; ?>
</td>
	  <td rowspan=<?php echo $this->_tpl_vars['rec']['NVap_Con']; ?>
 style="vertical-align:middle;text-align:center;"><?php echo $this->_tpl_vars['rec']['Termografo']; ?>
</td>
	  
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>



<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
      <!--<tr style="text-align:left;font-size:10pt;">-->
	  <td><?php echo $this->_tpl_vars['rec']['CodHacienda']; ?>
</td>
	  <td><?php echo $this->_tpl_vars['rec']['Embarcador']; ?>
</td>
	  <td style="text-align:right;"><?php echo $this->_tpl_vars['rec']['CjaEmbarcada']; ?>
</td>      
      </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
   <!--  <tr style="font-weight:bold;vertical-align:middle; text-align:right; background-color: #BDBDBD;">
       <td colspan=3">TOTALES:</td>
           <?php $this->assign('tpPcajLiq', $this->_tpl_vars['tpcajLiq']/$this->_tpl_vars['tcajEmb']); ?>
       <td><?php echo ((is_array($_tmp=$this->_tpl_vars['tpPcajLiq'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ",", ".") : smarty_modifier_number_format($_tmp, 2, ",", ".")); ?>
</td> </tr>
     <tr>
      <td colspan="24" style="text-align:left"> <?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
 </td></tr> -->
  </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>