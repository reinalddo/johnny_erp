/**
*   Devuelve un valor numerico redondeao
*   @param  valor   float       Valor a redondear
*   @param  precision   int     Número de posiciones decimales
**/
function fredondear(valor, precision)
{
        valor = "" + valor //convierte valor a string
        precision = parseInt(precision);
        var total = "" + Math.round(valor * Math.pow(10, precision));
        if (precision > total.length) return valor;
        var puntoDec = total.length - precision;
        if(puntoDec != 0)  {
                resultado = total.substring(0, puntoDec);
                resultado += ".";
                resultado += total.substring(puntoDec, total.length);
        }
        else {
                resultado = 0;
                resultado += ".";
                resultado += total.substring(puntoDec, total.length);
        }
        return resultado;
}
