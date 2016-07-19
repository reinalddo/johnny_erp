<?php /* Smarty version 2.6.18, created on 2009-11-19 10:58:47
         compiled from InTrTr_saldbodhtml.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_saldbodhtml.tpl', 12, false),array('block', 'report_header', 'InTrTr_saldbodhtml.tpl', 13, false),array('block', 'report_detail', 'InTrTr_saldbodhtml.tpl', 72, false),array('block', 'report_footer', 'InTrTr_saldbodhtml.tpl', 78, false),array('modifier', 'date_format', 'InTrTr_saldbodhtml.tpl', 67, false),array('modifier', 'number_format', 'InTrTr_saldbodhtml.tpl', 85, false),array('function', 'addto', 'InTrTr_saldbodhtml.tpl', 91, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>SALDOS DE INVENTARIO</title>
        <link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_basico.css">
        <link rel="stylesheet" type="text/css" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/AAA_tablas_print.css">
        <link rel="stylesheet" type="text/css" media="screen, print" href="http://190.95.249.171/AAA/AAA_SEGURIDAD/css/report.css" title="CSS para pantalla" />
    </head>
    <body align:"center" id="top" style="font-family:'Arial'; ">
    <?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => false,'groups' => "BOD,DES")); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
     <div id="container"  style="height: 98% !important">
        <div class="tableContainer">
    
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<p></p>
	<table  align="center" cellspacing="3" BORDERCOLOR="black" >
	  <thead >
			<tr >
			  <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo $this->_tpl_vars['gsEmpresa']; ?>
</td>
			</tr>
			<tr>
			  <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif"> SALDOS DE BODEGA</td>
			</tr>
                        <tr>
			  <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">PERIODO DE <?php echo $this->_tpl_vars['gsDesde']; ?>
 A <?php echo $this->_tpl_vars['gsHasta']; ?>
</td>
			</tr>
	</thead>

      </table >
<p></p>
<tbody>
      <table align="center" cellspacing="3" BORDERCOLOR="black">
	 <thead>
	 <tr>

			<td align="center" COLSPAN="5" width="50%" style="background-color:white !important; border-top-color: transparent; border-left-color: transparent"><fsize="2" face="Verdana, Arial, Helvetica,
                sans-serif"></td>
                         <td align="center" COLSPAN="3" width="50%" ><fsize="2" face="Verdana, Arial, Helvetica,
                sans-serif">INGRESOS</td>
                         <td align="center" COLSPAN="3" width="50%"><fsize="2" face="Verdana, Arial, Helvetica,
                sans-serif">EGRESOS</td>
                        <td align="center" COLSPAN="3" width="50%"><fsize="2" face="Verdana, Arial, Helvetica,
                sans-serif">SALDOS</td>
	  </tr>
	 <tr>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">BODEGA</td>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">GRUPO</td>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">NOMBRE GRUPO</td>
	    <td align="center"><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">CODIGO ITEM</td>
	    <td align="center" width="50%"><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">NOMBRE ITEM</td>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">CANT<br/>INGRESOS</td>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">COSTO<br/>UNITARIO</td>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">COSTOS<br/>INGRESOS</td>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">CANT EGRESOS</td>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">COSTO<br/>UNITARIO</td>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">COSTOS<br/>EGRESOS</td>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">SALDO FINAL</td>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">COSTO FINAL</td>
	    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">C. UNI</td>
	 </tr>
	 </thead>
	 <tfoot>
	  <tr> 
			<td colspan="13" style="text-align:left"><?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</td>
			<td><?php echo $this->_tpl_vars['PiePagina']; ?>
</td>
             </tr>
	 </tfoot>	 
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
  <?php if ($this->_tpl_vars['fgBandera'] != $this->_tpl_vars['rec']['DES']): ?><?php $this->_tpl_vars['fgSumCant'] = 0; ?><?php endif; ?>
    <?php if ($this->_tpl_vars['fgBandera'] != $this->_tpl_vars['rec']['DES']): ?><?php $this->_tpl_vars['fgSumCant1'] = 0; ?><?php endif; ?> 
 
	
 <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
 <?php $this->_tag_stack[] = array('report_footer', array('group' => 'DES')); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
      <tr >	
	 <td align="center"><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo $this->_tpl_vars['rec']['BOD']; ?>
</td>
	 <td align="center"><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo $this->_tpl_vars['rec']['GRU']; ?>
</td>
	 <td align="center"><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo $this->_tpl_vars['rec']['GRD']; ?>
</td>
	 <td align="center" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo $this->_tpl_vars['rec']['ITE']; ?>
</td>
	 <td align="left" ><size="2" face="Verdana, Arial, Helvetica,sans-serif" STYLE="white-space: nowrap"><?php echo $this->_tpl_vars['rec']['DES']; ?>
</td>
	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['CIN'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['SAN'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 6) : smarty_modifier_number_format($_tmp, 6)); ?>
</td>
	  <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['VIN'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['CEG'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['VEG']/$this->_tpl_vars['sum']['CEG'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 6) : smarty_modifier_number_format($_tmp, 6)); ?>
</td>
	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['VEG'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	 <?php echo smarty_function_addto(array('var' => 'fgSumCant','value' => $this->_tpl_vars['rec']['SAC']), $this);?>

	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo $this->_tpl_vars['sum']['CIN']; ?>
</td>
	 <?php $this->assign('fgCant', $this->_tpl_vars['rec']['CIN']+$this->_tpl_vars['rec']['CEG']); ?>
	 <?php $this->assign('fgValr', $this->_tpl_vars['rec']['VIN']+$this->_tpl_vars['rec']['VEG']); ?>
	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo $this->_tpl_vars['sum']['VIN']; ?>
</td>
	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo ((is_array($_tmp=$this->_tpl_vars['fgValr']/$this->_tpl_vars['fgCant'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 6) : smarty_modifier_number_format($_tmp, 6)); ?>
</td>
	 
	      </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
      <tr >	
	 <td colspan=5> SUMA  GENERAL:</td>
	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['CIN'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"></td>
	  <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['VIN'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['CEG'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"></td>
	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['VEG'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	 <?php echo smarty_function_addto(array('var' => 'fgSumCant','value' => $this->_tpl_vars['rec']['SAC']), $this);?>

	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo $this->_tpl_vars['sum']['CIN']; ?>
</td>
	 <?php $this->assign('fgCant', $this->_tpl_vars['rec']['CIN']+$this->_tpl_vars['rec']['CEG']); ?>
	 <?php $this->assign('fgValr', $this->_tpl_vars['rec']['VIN']+$this->_tpl_vars['rec']['VEG']); ?>
	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo $this->_tpl_vars['sum']['VIN']; ?>
</td>
	 <td align="right" ><size="2" face="Verdana, Arial, Helvetica,sans-serif"></td>
	 
	      </tr>
 <?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
             
 <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
         </table>    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
   </div>
</div>
</body>
</html>