<?php /* Smarty version 2.6.18, created on 2010-10-21 15:04:34
         compiled from CoTrTr_libroventas.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoTrTr_libroventas.tpl', 12, false),array('block', 'report_header', 'CoTrTr_libroventas.tpl', 13, false),array('block', 'report_detail', 'CoTrTr_libroventas.tpl', 54, false),array('block', 'report_footer', 'CoTrTr_libroventas.tpl', 86, false),array('modifier', 'number_format', 'CoTrTr_libroventas.tpl', 78, false),array('modifier', 'count', 'CoTrTr_libroventas.tpl', 88, false),array('modifier', 'date_format', 'CoTrTr_libroventas.tpl', 89, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>VENTAS</title>
        <link rel="stylesheet" type="text/css" href="../css/AAA_basico.css">
       <link rel="stylesheet" type="text/css" href="../css/general_print.css">
        <link rel="stylesheet" type="text/css" media="screen, print" href="http:../css/report.css" title="CSS para pantalla" />
    </head>
    <body align:"center" id="top" style="font-family:'Arial'; ">
    <?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
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
			  <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">REPORTE DE CONTABILIZACION</td>
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
	 			<td align="center"><fsize="2" face="Verdana, Arial, Helvetica,sans-serif"></td>
                 <td align="center"><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">COMPROBANTE</td>
                        <td align="center""><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">NUMERO</td>
                        <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">F/EMISION</td>
			            <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">F/CONTAB</td>
                      <td align="center"><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">F/VENC</td>
                     <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">LIBRO</td>
                      <td align="center"><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">USUARIO</td>
                     <td ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">CLIENTE</td>
                      <td ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">RUC</td>
                <?php $_from = $this->_tpl_vars['gsPivot']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['Pivot']):
?>
             		<?php $_from = $this->_tpl_vars['Pivot']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>           
		        		<td align=""><fsize="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo $this->_tpl_vars['item']; ?>
</td>
					<?php endforeach; endif; unset($_from); ?>
				<?php endforeach; endif; unset($_from); ?>
			    <td align="center" ><fsize="2" face="Verdana, Arial, Helvetica,sans-serif">CONCEPTO</td>
     </thead>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<tr >
	       <td align="center" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif" ><A HREF="<?php echo $this->_tpl_vars['rec']['url']; ?>
" TARGET="_new">***</A></td>
			<td align="center" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif" ><?php echo $this->_tpl_vars['rec']['TIP']; ?>
</td>
                        <td align="left" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif"><?php echo $this->_tpl_vars['rec']['NUM']; ?>
</td>
			<td align="left" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif"><?php echo $this->_tpl_vars['rec']['FTR']; ?>
</td>
			<td align="right" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif"><?php echo $this->_tpl_vars['rec']['FCT']; ?>
</td>
			<td align="right" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif"><?php echo $this->_tpl_vars['rec']['FCV']; ?>
</td>
                         <td align="right" class="nowrap"><size="2" face="Verdana, Arial, Helvetica,
                sans-serif"><?php echo $this->_tpl_vars['rec']['LIB']; ?>
</td>
			<td align="right" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif"><?php echo $this->_tpl_vars['rec']['USU']; ?>
</td>
                        <td align="right" class="nowrap"><size="2" face="Verdana, Arial, Helvetica,
                sans-serif"><?php echo $this->_tpl_vars['rec']['CLI']; ?>
</td>
			<td align="right" ><size="2" face="Verdana, Arial, Helvetica,
                sans-serif"><?php echo $this->_tpl_vars['rec']['RUC']; ?>
</td>
                <?php $_from = $this->_tpl_vars['gsPivot']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['Pivot']):
?>
				    <?php $_from = $this->_tpl_vars['Pivot']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>		                
		        	   <td align="center" class="colnum"><fsize="2" face="Verdana, Arial, Helvetica,sans-serif"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec'][$this->_tpl_vars['item']])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
					<?php endforeach; endif; unset($_from); ?>
				<?php endforeach; endif; unset($_from); ?>
                    <td align="center" class="nowrap"><size="2" face="Verdana, Arial, Helvetica,
                sans-serif"><?php echo $this->_tpl_vars['rec']['CON']; ?>
</td>                  
	      </tr>
 <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

 <?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
             <tr> 
             <?php $this->assign('cnt', count($this->_tpl_vars['gsPivot'])); ?>
			<td colspan="<?php echo $this->_tpl_vars['cnt']+10; ?>
" style="text-align:left"><?php echo $_SESSION['g_user']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</td>
			<td><?php echo $this->_tpl_vars['PiePagina']; ?>
</td>
             </tr>
 <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
         </table>    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
   </div>
</div>
</body>
</html>