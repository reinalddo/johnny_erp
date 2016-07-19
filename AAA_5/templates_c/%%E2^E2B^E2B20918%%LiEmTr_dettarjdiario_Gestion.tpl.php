<?php /* Smarty version 2.6.18, created on 2009-05-14 13:52:59
         compiled from LiEmTr_dettarjdiario_Gestion.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'LiEmTr_dettarjdiario_Gestion.tpl', 12, false),array('block', 'report_header', 'LiEmTr_dettarjdiario_Gestion.tpl', 14, false),array('block', 'report_detail', 'LiEmTr_dettarjdiario_Gestion.tpl', 38, false),array('modifier', 'number_format', 'LiEmTr_dettarjdiario_Gestion.tpl', 46, false),)), $this); ?>
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DETALLE DE TARJAS <?php echo $this->_tpl_vars['gsSubTitul']; ?>
</title>
<link rel="stylesheet" type="text/css" href="../css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="../css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
</head>
<body align:"center" id="top" style="font-family:'Arial'; ">

<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'groups' => "txp_NombZona,txp_Shipper,txp_Productor,txp_Producto",'record' => 'rec')); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<table width="98%" border="1" cellpadding="0" cellspacing="0">
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	  
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  
	  <thead>
	    <tr>
	      <td style="text-align:center" colspan=9>PARA GESTION DE FACTURACION<?php echo $this->_tpl_vars['gsSubTitul']; ?>
</td>
	  </tr>
			<tr>
			<td>ZONA</td>
			<td>COMPAÑIA</td>
			<td>CODIGO</td>
			<td>PRODUCTOR</td>
			<td>MARCA</td>
			<td>TIPO</td>
			<td>CANT</td>
			<td>PRECIO</td>
			<td>TOTAL_FAC</td>	
			</tr>
         </thead>	 
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<tr style="white-space:nowrap">
			<td><?php echo $this->_tpl_vars['rec']['txp_NombZona']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Shipper']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Embarcador']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Productor']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Marca']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['txp_Producto']; ?>
</td>		
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_CantPagar'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_PrecUnit'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 4) : smarty_modifier_number_format($_tmp, 4)); ?>
</td>
			<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['txp_ValTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			
         </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


</table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>
</html>