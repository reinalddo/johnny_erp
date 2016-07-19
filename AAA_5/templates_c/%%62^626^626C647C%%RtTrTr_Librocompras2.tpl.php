<?php /* Smarty version 2.6.18, created on 2009-04-09 14:49:04
         compiled from RtTrTr_Librocompras2.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'RtTrTr_Librocompras2.tpl', 15, false),array('block', 'report_header', 'RtTrTr_Librocompras2.tpl', 16, false),array('block', 'report_detail', 'RtTrTr_Librocompras2.tpl', 130, false),array('block', 'report_footer', 'RtTrTr_Librocompras2.tpl', 181, false),array('modifier', 'date_format', 'RtTrTr_Librocompras2.tpl', 18, false),array('modifier', 'number_format', 'RtTrTr_Librocompras2.tpl', 109, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <!--<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />-->
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Libro de Compras</title>
  
</head>
<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "FECHA_D_CONT,ID,Proveedor,RUC",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong><?php echo $_SESSION['g_empr']; ?>
</strong><br>
        LIBRO DE COMPRAS<br>
        <?php echo $this->_tpl_vars['subtitulo']; ?>

    </p>
    </div>
    <table border=1 cellspacing=0 >
	<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	  <td rowspan=2 class="headerrow"><strong>ID</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Proveedor</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Ruc</strong></td>
	  <td rowspan=2 class="headerrow"><strong>T/D</strong></td>
	  <td class="headerrow" colspan=3 style="text-align:center;"><strong>Comp Venta</strong></td>
	  <td rowspan=2 class="headerrow"><strong>CC</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Fecha Imp.</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Fecha Cont.</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Fecha Validez</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Aut. SRI</strong></td>
	  <td rowspan=2 class="headerrow"><strong>N/D Aut.</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Base Imp. 12%</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Base Imp. 0%</strong></td>
	  <td rowspan=2 class="headerrow"><strong>IVA</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Total Compra</strong></td>
	  <td colspan=6 class="headerrow"><strong>Retencion IVA</strong></td>
	  <!--<td rowspan=2 class="headerrow"><strong>Monto IVA Bienes</strong></td>
	  <td rowspan=2 class="headerrow"><strong>% Ret. Bienes</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Valor Ret. Bienes</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Monto IVA Serv.</strong></td>
	  <td rowspan=2 class="headerrow"><strong>% Ret. Serv.</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Valor Ret. Serv.</strong></td>-->
	  <td colspan=3 class="headerrow"><strong>Ret. Fte.</strong></td>
	  <!--
	  <td colspan=2 class="headerrow"><strong>Ret. Fte. 2%</strong></td>
	  
	  <td colspan=2 class="headerrow"><strong>Ret. Fte. 5%</strong></td>
	  
	  <td colspan=2 class="headerrow"><strong>Ret. Fte. 8%</strong></td>
	  
	  <td colspan=2 class="headerrow"><strong>Ret. Fte. 25%</strong></td>-->
	  
	  <td rowspan=2 class="headerrow"><strong>Total a Pagar</strong></td>
	  <td colspan=3 class="headerrow" style="text-align:center;"><strong>No.Comp.Ret.</strong></td>
	</tr>
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	    
            <td class="headerrow"><strong>Esta blecim</strong></td>
            <td class="headerrow"><strong>Punto Emision</strong></td>
            <td class="headerrow"><strong>Secuen cial</strong></td>
	    
            <td class="headerrow"><strong>Monto IVA Bienes</strong></td>
	    <td class="headerrow"><strong>% Ret. Bienes</strong></td>
	    <td class="headerrow"><strong>Valor Ret. Bienes</strong></td>
	    <td class="headerrow"><strong>Monto IVA Serv.</strong></td>
	    <td class="headerrow"><strong>% Ret. Serv.</strong></td>
	    <td class="headerrow"><strong>Valor Ret. Serv.</strong></td>
	
            <td class="headerrow"><strong>Porcentaje</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>
	    <!--<td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>-->
	
	    <td class="headerrow"><strong>Esta blecim</strong></td>
            <td class="headerrow"><strong>Punto Emision</strong></td>
            <td class="headerrow"><strong>Secuen cial</strong></td>
        </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_header', array('group' => 'ID')); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['ID']; ?>
</td>
        <td nowrap><?php echo $this->_tpl_vars['rec']['Proveedor']; ?>
</td>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['RUC']; ?>
</td>        
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['TIPO_DOC']; ?>
</td>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['establecimiento']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['puntoEmision']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['secuencial']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['CC']; ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['FECHA_IMP']; ?>
</td>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['FECHA_D_CONT']; ?>
</td>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['FECHA_VALIDEZ']; ?>
</td>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['AUT_SRI']; ?>
</td>
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['N_D_AUT']; ?>
</td>
        <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['BASE_12'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['BASE_0'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['IVA'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['TOTAL_COMPRA'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['montoIvaBienes'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PIVAB'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['valorRetBienes'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['montoIvaServicios'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['PIVAS'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['valorRetServicios'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td></td><td></td><td></td>
		
	<?php $this->assign('ret', $this->_tpl_vars['rec']['valorRetBienes']+$this->_tpl_vars['rec']['valorRetServicios']+$this->_tpl_vars['rec']['RET_1']+$this->_tpl_vars['rec']['RET_2']+$this->_tpl_vars['rec']['RET_5']+$this->_tpl_vars['rec']['RET_8']+$this->_tpl_vars['rec']['RET_25']); ?>
	<?php $this->assign('tot', $this->_tpl_vars['rec']['TOTAL_COMPRA']-$this->_tpl_vars['ret']); ?>
        <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['tot'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['estabRetencion1'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
	<td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['puntoEmiRetencion1'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
	<td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['secRetencion1'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0, "", "") : smarty_modifier_number_format($_tmp, 0, "", "")); ?>
</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    
        	
        <?php if (( $this->_tpl_vars['rec']['RET_1'] != 0 )): ?>
            <tr><td colspan=23></td>
                <td class="colnum " style="text-align:right;">1%</td>
                <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RET_1'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
                <td class="colnum " style="text-align:right;"><?php echo $this->_tpl_vars['rec']['CRT_1']; ?>
</td>
                <td colspan=4></td>
            </tr>
        <?php endif; ?>
        <?php if (( $this->_tpl_vars['rec']['RET_2'] != 0 )): ?>
            <tr><td colspan=23></td>
                <td class="colnum " style="text-align:right;">2%</td>
                <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RET_2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
                <td class="colnum " style="text-align:right;"><?php echo $this->_tpl_vars['rec']['CRT_2']; ?>
</td>
                <td colspan=4></td>
            </tr>
        <?php endif; ?>
	
        <?php if (( $this->_tpl_vars['rec']['RET_5'] != 0 )): ?>
            <tr><td colspan=23></td>
                <td class="colnum " style="text-align:right;">5%</td>
                <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RET_5'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
                <td class="colnum " style="text-align:right;"><?php echo $this->_tpl_vars['rec']['CRT_5']; ?>
</td>
                <td colspan=4></td>
            </tr>
        <?php endif; ?>
        
        <?php if (( $this->_tpl_vars['rec']['RET_8'] != 0 )): ?>
            <tr><td colspan=23></td>
                <td class="colnum " style="text-align:right;">8%</td>
                <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RET_8'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
                <td class="colnum " style="text-align:right;"><?php echo $this->_tpl_vars['rec']['CRT_8']; ?>
</td>
                <td colspan=4></td>
            </tr>
        <?php endif; ?>
	
        <?php if (( $this->_tpl_vars['rec']['RET_25'] != 0 )): ?>
            <tr><td colspan=23></td>
                <td class="colnum " style="text-align:right;">25%</td>
                <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RET_25'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
                <td class="colnum " style="text-align:right;"><?php echo $this->_tpl_vars['rec']['CRT_25']; ?>
</td>
                <td colspan=4></td>
            </tr>
        <?php endif; ?>
        
	
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr><td colspan=32>&nbsp</td></tr>
    <tr>
        <!--<td colspan=13>&nbsp</td>-->
        <td colspan=13 class="colnum"><strong>TOTALES:</strong></td>
        <td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['BASE_12'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['BASE_0'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['IVA'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['TOTAL_COMPRA'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['montoIvaBienes'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td>&nbsp</td>
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['valorRetBienes'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['montoIvaServicios'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td>&nbsp</td>
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['valorRetServicios'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td>&nbsp</td>
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['RET_1']+$this->_tpl_vars['sum']['RET_2']+$this->_tpl_vars['sum']['RET_5']+$this->_tpl_vars['sum']['RET_8']+$this->_tpl_vars['sum']['RET_25'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	
        <td class="colnum " style="text-align:right;"></td>
	<?php $this->assign('retGen', $this->_tpl_vars['sum']['valorRetBienes']+$this->_tpl_vars['sum']['valorRetServicios']+$this->_tpl_vars['sum']['RET_1']+$this->_tpl_vars['sum']['RET_2']+$this->_tpl_vars['sum']['RET_5']+$this->_tpl_vars['sum']['RET_8']+$this->_tpl_vars['sum']['RET_25']); ?>
	<?php $this->assign('totGen', $this->_tpl_vars['sum']['TOTAL_COMPRA']-$this->_tpl_vars['retGen']); ?>
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['totGen'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td></td>
	<td></td>
	<td></td>
    </tr>
    </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>