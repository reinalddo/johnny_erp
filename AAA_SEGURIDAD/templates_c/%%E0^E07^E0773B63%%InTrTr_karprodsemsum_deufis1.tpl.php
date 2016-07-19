<?php /* Smarty version 2.6.18, created on 2009-11-23 11:05:11
         compiled from InTrTr_karprodsemsum_deufis1.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_karprodsemsum_deufis1.tpl', 14, false),array('block', 'report_header', 'InTrTr_karprodsemsum_deufis1.tpl', 16, false),array('block', 'report_detail', 'InTrTr_karprodsemsum_deufis1.tpl', 60, false),array('block', 'report_footer', 'InTrTr_karprodsemsum_deufis1.tpl', 79, false),array('modifier', 'number_format', 'InTrTr_karprodsemsum_deufis1.tpl', 68, false),)), $this); ?>
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $this->_tpl_vars['title1']; ?>
</title>
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/report.css" title="CSS para pantalla" />
</head>
<body align:"center" id="top" style="font-family:'Arial'; ">
  
 <center style="font-size: 16px"><?php echo $this->_tpl_vars['title1']; ?>
 <br> <br> <?php echo $this->_tpl_vars['subTitle']; ?>
 <br> </center>
 
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "RECEP,ITEM",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<table width="98%" border="1" cellpadding="0" cellspacing="0">
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
				      		 
			   <tr>
			      <?php $_from = $this->_tpl_vars['rec']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['rec_item']):
?>
			 
			       <td  align="center"><?php echo $this->_tpl_vars['key']; ?>
</td>
			    
			     <?php endforeach; endif; unset($_from); ?>
			 </tr>
			   
			 
         </thead>	 
         <tbody>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'RECEP')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>			
  		<tr>
			
			  
			    <td height="40" class="colhead" style="vertical-align: bottom;  text-align:center" colspan=10 >
		                     <?php echo $this->_tpl_vars['rec']['RECEP']; ?>

			      </td>
	                    
       		</tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'ITEM')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>			
  		<tr>
		          
			    <td height="40"  align="left" colspan=10 style="vertical-align: bottom">
		                     <?php echo $this->_tpl_vars['rec']['ITEM']; ?>

			      </td>
	                 
       		</tr>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        
		              <tr style="white-space:nowrap;">
						    
		                     <?php $_from = $this->_tpl_vars['rec']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rec_item']):
?>
				       <?php if (( $this->_tpl_vars['rec_item'] == $this->_tpl_vars['rec']['COMPR'] ) || ( $this->_tpl_vars['rec_item'] == $this->_tpl_vars['rec']['RECEP'] ) || ( $this->_tpl_vars['rec_item'] == $this->_tpl_vars['rec']['ITEM'] )): ?>
		                         <td  align="left"><?php echo $this->_tpl_vars['rec_item']; ?>
</td>
				       <?php elseif (( $this->_tpl_vars['rec_item'] == $this->_tpl_vars['rec']['CANTID'] ) || ( $this->_tpl_vars['rec_item'] == $this->_tpl_vars['rec']['COSTO'] )): ?>
				         <td  align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec_item'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
				       <?php else: ?>
				         <td  align="center"><?php echo $this->_tpl_vars['rec_item']; ?>
</td>
		                       <?php endif; ?>
		                    <?php endforeach; endif; unset($_from); ?>
		    
	                      </tr>
	             
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php $this->_tag_stack[] = array('report_footer', array('group' => 'RECEP')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		  <tr> 
			<td colspan="4">TOTAL PRODUCTOR <?php echo $this->_tpl_vars['rec']['RECEP']; ?>
: </td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['CANTID'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
			<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['COSTO'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
		  </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


</table>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>
</html>