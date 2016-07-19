<?php /* Smarty version 2.6.18, created on 2011-10-06 16:30:11
         compiled from CoRtTr_comprob_blanco.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoRtTr_comprob_blanco.tpl', 12, false),array('block', 'report_header', 'CoRtTr_comprob_blanco.tpl', 14, false),array('block', 'report_detail', 'CoRtTr_comprob_blanco.tpl', 46, false),array('block', 'report_footer', 'CoRtTr_comprob_blanco.tpl', 105, false),array('function', 'math', 'CoRtTr_comprob_blanco.tpl', 108, false),)), $this); ?>
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>prueba</title>
<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />



<body id="top" style="font-family:'Arial'; font size:5;" onload="window.print()">

<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'groups' => 'TIB','record' => 'rec')); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<table width="98%" border="1" cellpadding="0">
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	  
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    
	<table  style="margin-top:150px; font-family:'Arial'; font-size:13px;" cellspacing="0" >
	  
	  <thead>
	    
	    
	    <tr style="margin-top:200px;">
	      <td style="text-align:left" colspan=3>Sr.(es): <?php echo $this->_tpl_vars['rec']['NOM']; ?>
</td>
	      <td style="text-align:left" colspan=3>FECHA DE EMISION:<?php echo $this->_tpl_vars['rec']['FEC']; ?>
</td>
	    </tr>
	     <tr>
	      <td style="text-align:left" colspan=3>RUC/CI:<?php echo $this->_tpl_vars['rec']['RUC']; ?>
</td>
	      <td style="text-align:left" colspan=3>TIPO DE COMPROBANTE DE VENTA:<?php echo $this->_tpl_vars['rec']['TIP']; ?>
</td>
	    </tr>
	     <tr>
	      <td style="text-align:left" colspan=3>DIRECCION:<?php echo $this->_tpl_vars['rec']['Direcc']; ?>
</td>
	      <td style="text-align:left" colspan=3>Nº DE COMPROBANTE DE VENTA:<?php echo $this->_tpl_vars['rec']['FAC']; ?>
</td>
	    </tr>
			<tr>
			<td>EJERCICIO FISCAL</td>
			<td>BASE IMPONIBLE</td>
			<td>IMPUESTO</td>
			<td>CODIGO DEL IMPUESTO</td>
			<td>% DE RETENCION</td>
			<td>VALOR RETENIDO</td>
			</tr>
         </thead>	 
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		      <?php if (( $this->_tpl_vars['rec']['MIB'] > 0 )): ?>
		      <tr style="white-space:nowrap">
			<td><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['BIB']; ?>
</td>
			<td>IVA</td>
			<td><?php echo $this->_tpl_vars['rec']['TIB']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['PIB']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['MIB']; ?>
</td>
			</tr>
		      
		      <?php endif; ?>
			
		      <?php if (( $this->_tpl_vars['rec']['MIS'] > 0 )): ?>
		      <tr style="white-space:nowrap">
		      <td><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['BIS']; ?>
</td>
			<td>IVA</td>
			<td><?php echo $this->_tpl_vars['rec']['TIS']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['PIS']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['MIS']; ?>
</td>
			</tr>
		      <?php endif; ?>
			
		      <?php if (( $this->_tpl_vars['rec']['MIR'] > 0 )): ?>
		      <tr style="white-space:nowrap">
		      <td><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['BIR']; ?>
</td>
			<td>RENTA</td>
			<td><?php echo $this->_tpl_vars['rec']['TIR']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['PIR']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['MIR']; ?>
</td>
			</tr>
		      <?php endif; ?>
			
		      <?php if (( $this->_tpl_vars['rec']['MIR2'] > 0 )): ?>
		      <tr style="white-space:nowrap">
		      <td><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['BIR2']; ?>
</td>
			<td>RENTA</td>
			<td><?php echo $this->_tpl_vars['rec']['TIR2']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['PIR2']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['MIR2']; ?>
</td>
			</tr>
		      <?php endif; ?>
		      <?php if (( $this->_tpl_vars['rec']['MIR3'] > 0 )): ?>
		      <tr style="white-space:nowrap">
		      <td><?php echo $this->_tpl_vars['rec']['PER']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['BIR3']; ?>
</td>
			<td>RENTA</td>
			<td><?php echo $this->_tpl_vars['rec']['TIR3']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['PIR3']; ?>
</td>
			<td><?php echo $this->_tpl_vars['rec']['MIR3']; ?>
</td>
		      </tr>
		      <?php endif; ?>
					
       
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		  <tr> 
			<td colspan=5>&nbsp;</td>
			<td>Total <?php echo smarty_function_math(array('equation' => "x + y + z + a + b",'x' => $this->_tpl_vars['rec']['MIR3'],'y' => $this->_tpl_vars['rec']['MIR2'],'z' => $this->_tpl_vars['rec']['MIR'],'a' => $this->_tpl_vars['rec']['MIS'],'b' => $this->_tpl_vars['rec']['MIB']), $this);?>
</td>
		  </tr>
		  
		   <tr> 
			<td style="height:100px; vertical-align:bottom; text-align:center;" colspan=3>_____________________</td>
			<td style="height:100px; vertical-align:bottom; text-align:center;" colspan=3>_____________________</td>
		  </tr>
		   <tr> 
			<td style="text-align:center" colspan=3>FIRMA DEL AGENTE DE RETENCION</td>
			<td style="text-align:center" colspan=3>FIRMA DEL SUJETO PASIVO RETENIDO</td>
		  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>
</html>