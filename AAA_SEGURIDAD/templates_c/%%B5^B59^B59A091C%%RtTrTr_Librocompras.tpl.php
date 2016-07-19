<?php /* Smarty version 2.6.18, created on 2014-03-05 16:11:26
         compiled from RtTrTr_Librocompras.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'RtTrTr_Librocompras.tpl', 17, false),array('block', 'report_header', 'RtTrTr_Librocompras.tpl', 18, false),array('block', 'report_detail', 'RtTrTr_Librocompras.tpl', 103, false),array('block', 'report_footer', 'RtTrTr_Librocompras.tpl', 154, false),array('modifier', 'date_format', 'RtTrTr_Librocompras.tpl', 20, false),array('modifier', 'str_pad', 'RtTrTr_Librocompras.tpl', 110, false),array('modifier', 'number_format', 'RtTrTr_Librocompras.tpl', 120, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<!-- @rev	18/08/2010	esl	agregar columna para retención del 10%-->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="root" />

  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Libro de Compras</title>

</head>
<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "FECHA_D_CONT,Proveedor,RUC",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
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
	  <td rowspan=2 class="headerrow"><strong>Codigo</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Codigo de Sustento</strong></td>
	  <td rowspan=2 class="headerrow"><strong>T/D</strong></td>
	  <td class="headerrow" colspan=3 style="text-align:center;"><strong>Comp Venta</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Tipo Comp</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Num Comp</strong></td>
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
	  <td colspan=2 class="headerrow"><strong>Ret. Fte. 1%</strong></td>
	  <!--<td rowspan=2 class="headerrow"><strong>Cod. Ret. 1%</strong></td>-->
	  <td colspan=2 class="headerrow"><strong>Ret. Fte. 2%</strong></td>
	  <!--<td rowspan=2 class="headerrow"><strong>Cod. Ret. 2%</strong></td>-->
	  <td colspan=2 class="headerrow"><strong>Ret. Fte. 5%</strong></td>
	  <!--<td rowspan=2 class="headerrow"><strong>Cod. Ret. 5%</strong></td>-->
	  <td colspan=2 class="headerrow"><strong>Ret. Fte. 8%</strong></td>
	  <!--<td rowspan=2 class="headerrow"><strong>Cod. Ret. 8%</strong></td>-->
	  <td colspan=2 class="headerrow"><strong>Ret. Fte. 25%</strong></td>
	  <!--<td rowspan=2 class="headerrow"><strong>Cod. Ret. 25%</strong></td>-->
	  <td colspan=2 class="headerrow"><strong>Ret. Fte. 10%</strong></td>
	  <!--<td rowspan=2 class="headerrow"><strong>Cod. Ret. 25%</strong></td>-->
	  <td rowspan=2 class="headerrow"><strong>Total a Pagar</strong></td>
	  <td colspan=4 class="headerrow" style="text-align:center;"><strong>No.Comp.Ret.</strong></td>
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

	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>

	    <td class="headerrow"><strong>Esta blecim</strong></td>
            <td class="headerrow"><strong>Punto Emision</strong></td>
            <td class="headerrow"><strong>Secuen cial</strong></td>
			<td class="headerrow"><strong>Autori zacion</strong></td>
        </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr><td class="colnum "><?php echo $this->_tpl_vars['rec']['ID']; ?>
</td>
	<td nowrap><?php echo $this->_tpl_vars['rec']['Proveedor']; ?>
</td>
        <td class="colnum ">&nbsp;<?php echo $this->_tpl_vars['rec']['RUC']; ?>
</td>
	<td class="coldata" align="center"><?php echo $this->_tpl_vars['rec']['codSustento']; ?>
</td>
	<td class="colnum"><?php echo $this->_tpl_vars['rec']['sustento']; ?>
</td>
       <td class="colnum "><?php echo $this->_tpl_vars['rec']['TIPO_DOC']; ?>
</td>
       <td class="colnum ">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['establecimiento'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', @STR_PAD_LEFT) : str_pad($_tmp, 3, '0', @STR_PAD_LEFT)); ?>
</td>
		 <td class="colnum ">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['puntoEmision'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', @STR_PAD_LEFT) : str_pad($_tmp, 3, '0', @STR_PAD_LEFT)); ?>
</td>
	    <td class="colnum ">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['secuencial'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 9, '0', @STR_PAD_LEFT) : str_pad($_tmp, 9, '0', @STR_PAD_LEFT)); ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['TC']; ?>
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

	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RET_1'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo $this->_tpl_vars['rec']['CRT_1']; ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RET_2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo $this->_tpl_vars['rec']['CRT_2']; ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RET_5'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo $this->_tpl_vars['rec']['CRT_5']; ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RET_8'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo $this->_tpl_vars['rec']['CRT_8']; ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RET_25'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo $this->_tpl_vars['rec']['CRT_25']; ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RET_10'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum " style="text-align:right;"><?php echo $this->_tpl_vars['rec']['CRT_10']; ?>
</td>

	<?php $this->assign('ret', $this->_tpl_vars['rec']['valorRetBienes']+$this->_tpl_vars['rec']['valorRetServicios']+$this->_tpl_vars['rec']['RET_1']+$this->_tpl_vars['rec']['RET_2']+$this->_tpl_vars['rec']['RET_5']+$this->_tpl_vars['rec']['RET_8']+$this->_tpl_vars['rec']['RET_25']); ?>
	<?php $this->assign('tot', $this->_tpl_vars['rec']['TOTAL_COMPRA']-$this->_tpl_vars['ret']); ?>
        <td class="colnum " style="text-align:right;"><?php echo ((is_array($_tmp=$this->_tpl_vars['tot'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td class="colnum ">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['estabRetencion1'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', @STR_PAD_LEFT) : str_pad($_tmp, 3, '0', @STR_PAD_LEFT)); ?>
</td>
	<td class="colnum ">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['puntoEmiRetencion1'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 3, '0', @STR_PAD_LEFT) : str_pad($_tmp, 3, '0', @STR_PAD_LEFT)); ?>
</td>
	<td class="colnum ">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['secRetencion1'])) ? $this->_run_mod_handler('str_pad', true, $_tmp, 9, '0', @STR_PAD_LEFT) : str_pad($_tmp, 9, '0', @STR_PAD_LEFT)); ?>
</td>
	<td class="colnum "><?php echo $this->_tpl_vars['rec']['AUTRET']; ?>
</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr><td colspan=35>&nbsp</td></tr>
    <tr>
        <!--<td colspan=13>&nbsp</td>-->
        <td colspan=18 class="colnum"><strong>TOTALES:</strong></td>
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
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['RET_1'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum " style="text-align:right;"></td>
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['RET_2'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum " style="text-align:right;"></td>
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['RET_5'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum " style="text-align:right;"></td>
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['RET_8'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum " style="text-align:right;"></td>
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['RET_25'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
	<td class="colnum " style="text-align:right;"></td>
	<?php $this->assign('retGen', $this->_tpl_vars['sum']['valorRetBienes']+$this->_tpl_vars['sum']['valorRetServicios']+$this->_tpl_vars['sum']['RET_1']+$this->_tpl_vars['sum']['RET_2']+$this->_tpl_vars['sum']['RET_5']+$this->_tpl_vars['sum']['RET_8']+$this->_tpl_vars['sum']['RET_25']); ?>
	<?php $this->assign('totGen', $this->_tpl_vars['sum']['TOTAL_COMPRA']-$this->_tpl_vars['retGen']); ?>
	<td class="colnum headerrow"><?php echo ((is_array($_tmp=$this->_tpl_vars['totGen'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
        <td></td>
	<td></td>
	<td></td>
	<td></td>
    </tr>
    </table>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>