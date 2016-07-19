<?php /* Smarty version 2.6.18, created on 2009-06-16 14:19:40
         compiled from InTrTr_factura.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_factura.tpl', 42, false),array('block', 'report_header', 'InTrTr_factura.tpl', 43, false),array('block', 'report_detail', 'InTrTr_factura.tpl', 85, false),array('block', 'report_footer', 'InTrTr_factura.tpl', 94, false),array('modifier', 'truncate', 'InTrTr_factura.tpl', 61, false),array('modifier', 'number_format', 'InTrTr_factura.tpl', 87, false),array('modifier', 'date_format', 'InTrTr_factura.tpl', 134, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  <?php echo '
  <style type="text/css">
        .padding10{padding:10px;}
        .empresa{height:50px;font-size:20px;font-weight:bold;text-align:center;vertical-align:middle;}
        .titulo{font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;}
        .parrafo{text-align:justify; vertical-align:middle; text-transform:uppercase;}
        .bordeCompleto {border: #FFFFFF solid 1px;}
        .bordeDer {border-right: #FFFFFF solid 1px;}
        .bordeIzq {border-left: #FFFFFF solid 1px;}
        .bordeSup {border-top: #FFFFFF solid 1px;}
        .bordeInf {border-bottom: #FFFFFF solid 1px;}
        .izq{text-align:left;}
        .der{text-align:right;}
        .cen{text-align:center;}
        .espacioCol1{padding-left:70px;}
        .cabTabla{height:40px;}
        .espacioDer{padding-right:20px !important;}
        .espacioIzq{padding-left:10px !important;}
        @media print {
            body { font-size: 10pt; }

          }

    </style>
  '; ?>

  
  <!--<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
    <title>Factura Comercial</title>
  
</head>
<body id="top" style="font-family:'Arial,Tahoma,Helvetica,sans-serif' !important; font-size:13px;">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "TIPO,COMPR",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <!--<hr/>-->
    <?php $this->assign('numCol', 7); ?>
    
    
        <table width="850" border=0 cellspacing=0 style="border:#FFFFFF solid 1px; margin-top:70px;">
        <!--<tr>
            <td colspan=<?php echo $this->_tpl_vars['numCol']; ?>
 style="height:50px;font-size:16px;font-weight:bold;text-align:center;vertical-align:middle;">
                <?php echo $_SESSION['g_empr']; ?>
</td>            
        </tr>-->
        <tr>
            <td colspan=2 class="espacioCol1" nowrap><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['RECEP'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 32) : smarty_modifier_truncate($_tmp, 32)); ?>
</td>
            <td colspan=2 class="espacioCol1"><?php echo $this->_tpl_vars['rec']['FECHA']; ?>
</td>
        </tr>
        <tr>
            <td colspan=2 class="espacioCol1" nowrap><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['direccion'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 35) : smarty_modifier_truncate($_tmp, 35)); ?>
</td>
            <td colspan=2>&nbsp;</td>
        </tr>
        <tr>
            <td colspan=2 class="espacioCol1"><?php echo $this->_tpl_vars['rec']['ciudad']; ?>
</td>
            <td colspan=2 class="espacioCol1"><?php echo $this->_tpl_vars['rec']['telefono']; ?>
</td>
        </tr>
        <tr>
            <td colspan=2 class="espacioCol1"><?php echo $this->_tpl_vars['rec']['ruc']; ?>
</td>
            <td colspan=2>&nbsp;</td>
        </tr>
        <tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow cabTabla" width="30">&nbsp;<!--<strong>CANTIDAD</strong>--></td>
	  <td class="headerrow cabTabla" width="560">&nbsp;<!--<strong>DESCRIPCION</strong>--></td>
	  <td class="headerrow cabTabla" width="110">&nbsp;<!--<strong>V.UNITARIO</strong>--></td>
	  <td class="headerrow cabTabla" width="170">&nbsp;<!--<strong>TOTAL</strong>--></td>
	</tr>
        
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr><!-- style="border:#000000 solid 1px;">-->
        <td class="colnum der" ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['CANTI'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>  
	<td nowrap class="coldata espacioIzq"><?php echo $this->_tpl_vars['rec']['ITEM']; ?>
</td>
        <td class="colnum espacioDer der" ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['vunit'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 4) : smarty_modifier_number_format($_tmp, 4)); ?>
</td>        
        <td class="colnum espacioDer der" ><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['VALOR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <?php $this->assign('cuenta', $this->_tpl_vars['count']['CANTI']); ?>
    <?php $_from = $this->_tpl_vars['agFilas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?>
        <?php if (( $this->_tpl_vars['value'] > $this->_tpl_vars['cuenta'] )): ?>
            <tr>
                <td class="der bordeIzq bordeDer" >&nbsp</td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>               
            </tr>
        <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>

    <tr style="padding-top:50px;">
        <td colspan=3 rowspan=6 class="espacioCol1" style="vertical-align:top;"><!--<strong>SON:</strong>--> <?php echo $this->_tpl_vars['letras']; ?>
</td>
        <!--<td><strong>SUBTOTAL:</strong></td>-->
        <td class="colnum der"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['VALOR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
    <tr>
        <!--<td><strong>IVA:</strong></td>-->
        <td>&nbsp;</td>
    </tr>
     <tr>
        <!--<td><strong>IVA:</strong></td>-->
        <td>&nbsp;</td>
    </tr>
    <tr>
        <!--<td><strong>IVA:</strong></td>-->
        <td>&nbsp;</td>
    </tr>
    <tr>
        <!--<td><strong>IVA:</strong></td>-->
        <td>&nbsp;</td>
    </tr>
    <tr>
        <!--<td><strong>TOTAL A PAGAR:</strong></td>-->
        <td class="colnum der" style="padding-top:10px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['VALOR'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
    </table>
    </br>
    <div style="font-size:0.8em; text-align:left;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>