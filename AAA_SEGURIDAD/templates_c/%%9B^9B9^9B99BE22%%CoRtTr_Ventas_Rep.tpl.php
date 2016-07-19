<?php /* Smarty version 2.6.18, created on 2016-06-27 12:51:08
         compiled from CoRtTr_Ventas_Rep.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'CoRtTr_Ventas_Rep.tpl', 15, false),array('block', 'report_header', 'CoRtTr_Ventas_Rep.tpl', 16, false),array('block', 'report_detail', 'CoRtTr_Ventas_Rep.tpl', 55, false),array('block', 'report_footer', 'CoRtTr_Ventas_Rep.tpl', 82, false),array('modifier', 'date_format', 'CoRtTr_Ventas_Rep.tpl', 18, false),array('modifier', 'number_format', 'CoRtTr_Ventas_Rep.tpl', 67, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para el reporte de Ventas - Asisbane -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Alejandro" />
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="print" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />
  
    <title>CUADRO DE VENTAS</title>
  
</head>
<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','resort' => false)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br>
        CUADRO DE VENTAS<br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>

    </p>
    </div>
    <table border=1 cellspacing=0 >
	<tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow"><strong>FECHA</strong></td>
	  <td class="headerrow"><strong>MES</strong></td>
	  <td class="headerrow"><strong>RUC</strong></td>
	  <td class="headerrow"><strong>CLIENTE</strong></td>
	  <td class="headerrow"><strong>TIPO DE VENTA</strong></td>
	  <td class="headerrow"><strong>REF.</strong></td>
	  <td class="headerrow"><strong>No FACTURA</strong></td>
	  <td class="headerrow"><strong>COD ITEM</strong></td>
	  <td class="headerrow"><strong>ITEM</strong></td>
	  <td class="headerrow"><strong>U. MEDIDA</strong></td>
	  <td class="headerrow"><strong>CANTIDAD</strong></td>
	  <td class="headerrow"><strong>P. UNIT</strong></td>
	  <td class="headerrow"><strong>TOTAL</strong></td>
	  <td class="headerrow"><strong>% DSCTO</strong></td>
	  <td class="headerrow"><strong>DESCUENTO</strong></td>
	  <td class="headerrow"><strong>TARIFA 0%</strong></td>
	  <td class="headerrow"><strong>% IVA</strong></td>
	  <td class="headerrow"><strong>TARIFA IVA</strong></td>
	  <td class="headerrow"><strong>IVA</strong></td>
	  <td class="headerrow"><strong>TOTAL FACTURA</strong></td>
	  <td class="headerrow"><strong>RET IR</strong></td>
	  <td class="headerrow"><strong>RET IVA</strong></td>
	  
	</tr>
        
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['com_FecContab']; ?>
</td>  
	<td class="coldata"><?php echo $this->_tpl_vars['rec']['mes']; ?>
</td>
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['RUC']; ?>
</td>        
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['cliente']; ?>
</td>        
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['libro']; ?>
</td>
        <td class="coldata"><?php echo $this->_tpl_vars['rec']['com_RefOperat']; ?>
</td>
	<td class="coldata"><?php echo $this->_tpl_vars['rec']['com_NumComp']; ?>
</td>
	<td class="colnum"><?php echo $this->_tpl_vars['rec']['det_CodItem']; ?>
</td>
	<td class="coldata"><?php echo $this->_tpl_vars['rec']['item']; ?>
</td>
	<td class="coldata"><?php echo $this->_tpl_vars['rec']['uniMedida']; ?>
</td>  
	<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_CanDespachada'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_valunitario'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 4) : smarty_modifier_number_format($_tmp, 4)); ?>
</td>        
        <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_ValTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 4) : smarty_modifier_number_format($_tmp, 4)); ?>
</td>
        <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_porceDesc'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['Dscto'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['base0'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['pIVA']; ?>
</td>
	<td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['baseIva'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['montoIva'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>  
	<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['totalFac'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['valorRetRenta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>        
        <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['valorRetIva'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
        <td class="coldata">&nbsp;</td>  
	<td class="coldata">&nbsp;</td>
        <td class="coldata" colspan=9> TOTAL GENERAL</td>
        <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['det_valunitario'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 4) : smarty_modifier_number_format($_tmp, 4)); ?>
</td>        
        <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['det_ValTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 4) : smarty_modifier_number_format($_tmp, 4)); ?>
</td>
	<td class="colnum "></td>
        <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['Dscto'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['base0'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum "></td>
	<td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['baseIva'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['montoIva'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>  
	<td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['totalFac'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['valorRetRenta'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>        
        <td class="colnum"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['valorRetIva'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
    </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>