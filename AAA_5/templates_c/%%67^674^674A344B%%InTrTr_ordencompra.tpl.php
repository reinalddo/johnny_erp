<?php /* Smarty version 2.6.18, created on 2009-05-12 12:12:42
         compiled from InTrTr_ordencompra.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'report', 'InTrTr_ordencompra.tpl', 17, false),array('block', 'report_header', 'InTrTr_ordencompra.tpl', 18, false),array('block', 'report_detail', 'InTrTr_ordencompra.tpl', 69, false),array('block', 'report_footer', 'InTrTr_ordencompra.tpl', 81, false),array('modifier', 'number_format', 'InTrTr_ordencompra.tpl', 74, false),array('modifier', 'date_format', 'InTrTr_ordencompra.tpl', 112, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
    <title>Orden de Compra</title>
  
</head>
<body id="top" style="font-family:'Arial'">
<?php $this->_tag_stack[] = array('report', array('recordset' => $this->_tpl_vars['agData'],'record' => 'rec','groups' => "com_NumComp,det_Secuencia",'resort' => true)); $_block_repeat=true;smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php $this->_tag_stack[] = array('report_header', array()); $_block_repeat=true;smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <hr/>
    <?php $this->assign('numCol', 7); ?>
    
    
        <table border=1 cellspacing=0 style="border:#000000 solid 1px;">
        <tr>
            <td colspan=<?php echo $this->_tpl_vars['numCol']; ?>
 style="height:50px;font-size:16px;font-weight:bold;text-align:center;vertical-align:middle;">
                <?php echo $_SESSION['g_empr']; ?>
</td>            
        </tr>
        <tr>
            <td colspan=3><strong>ORDEN DE COMPRA N°:</strong>   <?php echo $this->_tpl_vars['rec']['com_NumComp']; ?>
</td>
            <td colspan=4><strong>EMISION:</strong>   <?php echo $this->_tpl_vars['rec']['com_FecTrans']; ?>
</td>
        </tr>
        <tr>
            <td colspan=5><strong>PROVEEDOR:</strong>   <?php echo $this->_tpl_vars['rec']['com_Receptor']; ?>
</td>
            <td colspan=2><strong>RUC:</strong>   <?php echo $this->_tpl_vars['rec']['ruc']; ?>
</td>
        </tr>
        <tr>
            <td colspan=7><strong>FORMA DE PAGO:</strong>   </td>
        </tr>
        <tr>
            <td colspan=5><strong>USO:</strong><br>   <?php echo $this->_tpl_vars['rec']['com_Concepto']; ?>
</td>
            <td colspan=2><strong>FECHA DE ENTREGA:</strong>   <?php echo $this->_tpl_vars['rec']['com_FecContab']; ?>
</td>
        </tr>
        <tr>
            <td colspan=5><strong>SOLICITA:</strong>    </td>
            <td colspan=2><strong>TIPO DE FORMA:</strong>   </td>
        </tr>
        <tr>
            <td colspan=7><strong>CON CARGO A NUESTRA CUENTA SIRVASE DESPACHAR LO SIGUIENTE DE ACUERDO A COTIZACION PACTADA CON:</strong></td>
        </tr>
	<tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow"><strong>Item</strong></td>
	  <td class="headerrow"><strong>Codigos</strong></td>
	  <td class="headerrow"><strong>Unidad de Medida</strong></td>
	  <td class="headerrow"><strong>Cantidad</strong></td>
	  <td class="headerrow"><strong>Descripcion</strong></td>
	  <td class="headerrow"><strong>Precio Unitario</strong></td>
	  <td class="headerrow"><strong>Importe Total</strong></td>
	</tr>
        
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_header($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_detail', array()); $_block_repeat=true;smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr><!-- style="border:#000000 solid 1px;">-->
        <td class="colnum "><?php echo $this->_tpl_vars['rec']['det_Secuencia']; ?>
</td>  
	<td nowrap class="colnum "><?php echo $this->_tpl_vars['rec']['det_CodItem']; ?>
</td>
        <td class="coldata "><?php echo $this->_tpl_vars['rec']['uni_Abreviatura']; ?>
</td>        
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_CanDespachada'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 0) : smarty_modifier_number_format($_tmp, 0)); ?>
</td>
        <td class="coldata "><?php echo $this->_tpl_vars['rec']['descripcion']; ?>
</td>
	<td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_CosUnitario'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 4) : smarty_modifier_number_format($_tmp, 4)); ?>
</td>
	<td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['rec']['det_CosTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_detail($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('report_footer', array()); $_block_repeat=true;smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <tr>
        <td colspan=5 rowspan=3 ><strong>SON:</strong>  <?php echo $this->_tpl_vars['letras']; ?>
</td>
        <td><strong>SUBTOTAL:</strong></td>
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['det_CosTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
    <tr>
        <td><strong>IVA:</strong></td>
        <td></td>
    </tr>
    <tr>
        <td><strong>TOTAL A PAGAR:</strong></td>
        <td class="colnum "><?php echo ((is_array($_tmp=$this->_tpl_vars['sum']['det_CosTotal'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : smarty_modifier_number_format($_tmp, 2)); ?>
</td>
    </tr>
    <tr style="text-align:center;">
        <td colspan=3 ><strong>ELABORADO</strong></td>
        <td colspan=2 ><strong>REVISADO</strong></td>
        <td colspan=2 ><strong>APROBADO</strong></td>
    </tr>
    <tr style="text-align:center; height:60px;">
        <td colspan=3><?php echo $this->_tpl_vars['rec']['com_Usuario']; ?>
, <?php echo $_SESSION['g_user']; ?>
</td>
        <td colspan=2></td>
        <td colspan=2></td>
    </tr>
    <tr style="text-align:center;">
        <td colspan=3><strong>DEPTO. COMPRAS</strong></td>
        <td colspan=2><strong>CONTRALORIA</strong></td>
        <td colspan=2><strong>GERENCIA GENERAL</strong></td>
    </tr>
    </table>
    </br>
    <div style="font-size:0.8em; text-align:left;">Fecha Imp.: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</div>
    
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report_footer($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_report($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</body>