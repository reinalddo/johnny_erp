/**
 *  Cajas de seleccion de clientes / proveedores, Comisionistas, etc
 *  @package utilitarios
 *  @rev 1.01
 **/

/**
	Habilita el proceso de generacion de un combo box de clientes - comisionista
	@param		oCtrl	objeto	Referencia del Objeto que recibe enfoque
	@pSalida	string	Lista de elementos que reciben datos del combo
	@return	void
**/
function fSelectCliCom(oCtrl, pSalida) {
	slSql = escape("SELECT CONCAT(per_Apellidos, ' ', per_Nombres) AS txt_Nombre , per_CodAuxiliar " +
				   		"FROM conpersonas JOIN concategorias ON cat_codauxiliar = per_Codauxiliar  " +
						   		" WHERE CONCAT(per_Apellidos, ' ', per_Nombres) LIKE ");
	slCond = escape("");
	fSelectBox(oCtrl, slSql, slCond, pSalida);
}
/**
	Habilita el proceso de generacion de un combo box de clientes
	@param		oCtrl	objeto	Referencia del Objeto que recibe enfoque
	@pSalida	string	Lista de elementos que reciben datos del combo
	@return	void
**/
function fSelectCli(oCtrl, pSalida, pCate) {
	slSql = escape("SELECT CONCAT(per_Apellidos, ' ', per_Nombres) AS txt_Nombre , per_CodAuxiliar " +
				   		"FROM conpersonas JOIN concategorias ON cat_codauxiliar = per_Codauxiliar  " +
						   		" WHERE CONCAT(per_Apellidos, ' ', per_Nombres) LIKE ");
	if (pCate)
		slCond = escape(" AND cat_categoria in (" + pCate + ") ");
	else slCond=""
	fSelectBox(oCtrl, slSql, slCond, pSalida);
}
