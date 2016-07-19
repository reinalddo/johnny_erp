<?php /* Smarty version 2.6.18, created on 2016-01-07 15:28:27
         compiled from LiEmTr_dettarjdiario_total.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'LiEmTr_dettarjdiario_total.tpl', 10, false),array('block', 'report_header', 'LiEmTr_dettarjdiario_total.tpl', 12, false),array('block', 'report_detail', 'LiEmTr_dettarjdiario_total.tpl', 63, false),array('block', 'report_footer', 'LiEmTr_dettarjdiario_total.tpl', 119, false),array('modifier', 'number_format', 'LiEmTr_dettarjdiario_total.tpl', 87, false),)), $this); ?>
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DETALLE DE TARJAS <?php echo $this->_tpl_vars['gsSubTitul']; ?>
</title>
</head>
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
<body align:"center" id="top" style="font-family:'Arial'; ">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec')); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<table width="100%" border="1" cellpadding="0" cellspacing="0">
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr>
			<td>Fecha</td>
			<td>Semana</td>
			<td>Codigo</td>
			<td>Grupo/Productor</td>
			<td>Productor</td>
			
			<td>Empresa</td>
			<td>Zona</td>
			<td>Producto</td>
			<td>Marca</td>
			<td>Cliente</td>
			<td>Vapor</td>
			<td>Puerto</td>
			<td>Tarja #</td>
			
			<td>Hora Inicio</td>
			<td>Hora Cierre</td>
			<td>Placa</td>
			<td>Contrato</td>
			
			<td>Codigo Palet</td>
			<td>Declaradas</td>
			<td>Rechazadas</td>
			<td>Caidas</td>
			<td>Embarcadas</td>
			<td>Faltantes/Sobrantes</td>
			<td>A Pagar</td>
			<td>Contenedor</td>
			
			<td>Destino</td>
		 	<td>Destino Final</td>
		        <td>Cod. Empacador</td>
			<td>Calidad</td>
			<td>Peso</td>
			<td>Calibre</td>
			<td>Dedo. x Clus.</td>
			<td>Clus. Caja</td>
			<td>Cod. Evaluador</td>
			<td>Evaluador</td>
			<td>Cod. Alterno</td>
			</tr>
         </thead>	 
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<tr style="white-space:nowrap">
			<td><?php echo $this->_tpl_vars['rec']['txp_Fecha']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Semana']; ?>
</td>
			
			<td><?php echo $this->_tpl_vars['rec']['txp_Embarcador']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Productor']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txt_Grupo']; ?>
</td>
			
			<td><?php echo $this->_tpl_vars['rec']['txp_Shipper']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['Zona']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Producto']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Marca']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txt_Cliente']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['vapor']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_PtoDescripcion']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_NumTarja']; ?>
</td>
			
			<td><?php echo $this->_tpl_vars['rec']['txp_Hora']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_HoraFin']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Transporte']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_RefTranspor']; ?>
</td>
			
			<td><?php echo $this->_tpl_vars['rec']['txp_PaletInfo']; ?>
</td>
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['despa'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['rechazo'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['caidas'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_CantNeta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_Faltante'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_CantPagar'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Contenedor']; ?>
</td>
			
			<td><?php echo $this->_tpl_vars['rec']['txt_Destino']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txt_DestiFinal']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_CodOrigen']; ?>
</td>
	                <td><?php echo $this->_tpl_vars['rec']['txp_Calidad']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Peso']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Largo']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_NumDedos']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_ClusCaja']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_CodEvaluador']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txt_Evaluador']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Observaciones']; ?>
</td>
	
	
			<?php $this->assign('tdespa', $this->_tpl_vars['tdespa']+$this->_tpl_vars['rec']['despa']); ?>
			<?php $this->assign('trechazo', $this->_tpl_vars['trechazo']+$this->_tpl_vars['rec']['rechazo']); ?>
			<?php $this->assign('tcaidas', $this->_tpl_vars['tcaidas']+$this->_tpl_vars['rec']['caidas']); ?>
			<?php $this->assign('tCantNeta', $this->_tpl_vars['tCantNeta']+$this->_tpl_vars['rec']['txp_CantNeta']); ?>
			<?php $this->assign('tfaltante', $this->_tpl_vars['tfaltante']+$this->_tpl_vars['rec']['txp_Faltante']); ?>
			<?php $this->assign('tCantPagar', $this->_tpl_vars['tCantPagar']+$this->_tpl_vars['rec']['txp_CantPagar']); ?>
         </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>



<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr style="font-weight:bold;text-align:right; vertical-align:middle;">
      <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
      <td></td><td></td><td></td><td></td>
      <td></td><td></td><td></td><td></td><td></td>
       <td> Totales: </td>
       <td><?php echo $this->_tpl_vars['tdespa']; ?>
</td>
       <td><?php echo $this->_tpl_vars['trechazo']; ?>
</td>
       <td><?php echo $this->_tpl_vars['tcaidas']; ?>
</td>
       <td><?php echo $this->_tpl_vars['tCantNeta']; ?>
</td>
       <td><?php echo $this->_tpl_vars['tfaltante']; ?>
</td>
       <td><?php echo $this->_tpl_vars['tCantPagar']; ?>
</td>
       <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
       <td></td><td></td><td></td><td></td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>



</table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>
</html>