<?php /* Smarty version 2.6.18, created on 2015-03-26 14:44:42
         compiled from CoAdEf_balmultiperhtml.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoAdEf_balmultiperhtml.tpl', 11, false),array('block', 'report_header', 'CoAdEf_balmultiperhtml.tpl', 13, false),array('block', 'report_detail', 'CoAdEf_balmultiperhtml.tpl', 45, false),array('modifier', 'number_format', 'CoAdEf_balmultiperhtml.tpl', 53, false),)), $this); ?>
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $this->_tpl_vars['gsSubTitul']; ?>
</title>
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/report.css" title="CSS para pantalla" />
</head>
<body align:"center" id="top" style="font-family:'Arial'; ">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<table width="98%" border="1" cellpadding="0" cellspacing="0">
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr >
			  <td class="colhead" colspan=<?php echo $this->_tpl_vars['gsNumCols']; ?>
 align="center" style="text-align: center; font-weight:bold; "><?php echo $this->_tpl_vars['gsEmpresa']; ?>
</td>
			</tr>
			<tr>
			  <td class="colhead" colspan=<?php echo $this->_tpl_vars['gsNumCols']; ?>
 align="center" style="text-align: center; font-weight:bold; ">BALANCE GENERAL</td>
			</tr>
			<tr>
			  <!-- <td class="colhead"  colspan=<?php echo $this->_tpl_vars['gsNumCols']; ?>
 align="center" style="text-align: center; font-weight:bold; ">Acumulado a: <?php echo $this->_tpl_vars['rec']['PERI']; ?>
 </td> !-->
			</tr>
	      		 
			   <tr>
				  <td>CUENTA</td>
				  <td>AUXILIAR</td>
				  <td>DESCRIPCION</td>
				  <?php $_from = $this->_tpl_vars['gaColumnas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
					<td  align="right"><?php echo $this->_tpl_vars['col']; ?>
</td>
   				  <?php endforeach; endif; unset($_from); ?>

			 </tr>
			   
			 
         </thead>	 
         <tbody>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        
		              <tr style="white-space:nowrap;">
						  <td><?php echo $this->_tpl_vars['rec']['CUENTA']; ?>
</td>
						  <td><?php echo $this->_tpl_vars['rec']['AUXILIAR']; ?>
</td>
						  <td><?php echo $this->_tpl_vars['rec']['DESCRIPCION']; ?>
</td>
		                     <?php $_from = $this->_tpl_vars['gaColumnas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
		    
		                     <td  align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec'][$this->_tpl_vars['col']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, '.', ',') : smarty_modifier_number_format($_tmp, 2, '.', ',')); ?>
</td>
		      
		                    <?php endforeach; endif; unset($_from); ?>
		    
	                      </tr>
	             
  <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

</table>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>
</html>