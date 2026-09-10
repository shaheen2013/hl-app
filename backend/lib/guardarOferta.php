<?php //Miramos si esta definida la variable de control de index.php
if(!defined('INDEXCONTROLVAL')){echo 'No direct access allowed.';exit;}

include_once 'fecha.php';
include_once 'obtenerdatosHotel.php';
include_once RUTA_DIR.MODEL.'guardarOfertaModel.php';

//Campos obligatorios lang
//Para guardar :nombre
//Para publicar: nombre, descripcion, condiciones
// $publicar: 1 o 6 (publicar), 0 (borrador)
function camposObligatoriosLangOk($nombre, $description, $conditions)
{
	if($nombre!='' && $description!='' && $conditions!='' ){
		return true;
	}else{
		return false;
	}
}

//Array datos $_SESSION para: publicar, guardar, editar. De hotel_ofert
$datos = array ('offertype', 'category', 'subCategory', 'offerMethod', 'inicio', 'fin', 'requerimientos', 'cupo', 'descuento', 'coste', 'puntos', 'foto', 'moneda', 'bookingEngineCode');

//Array datos $_SESSION para: publicar, guardar, editar. De hotel_oferta_langs
$datosLang = array('descripcion', 'condiciones', 'nombre');
?>