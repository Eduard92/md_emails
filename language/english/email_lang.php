<?php defined('BASEPATH') OR exit('No direct script access allowed');
$lang['email:title']		 =	'Correos';
$lang['email:orgs']		 =	'Organizaciones';
$lang['email:orgs_edit']		 =	'Modificar organización';

$lang['email:create']		 =	'New email';
$lang['email:syncron']		 =	'Sincronizar';
$lang['email:csv']		 =	'Subir archivo CSV';
$lang['email:delete_success']		 =	'Se han eliminado %s correos de manera satisfactoria.';
$lang['email:save_success']		 =	'Los cambios a esta cuenta han sido aplicados y guardados correctamente';
$lang['email:download_success']		 =	'Se han agregados %s  y actualizado %s correos electronicos de manera local.';

$lang['email:upload']		 =	'<i class="fa fa-upload"></i> Subir';
$lang['email:download']		 =	'Descargar';
$lang['email:orgs_add']		 =	'Asignar usuarios a organizacion';
$lang['email:duplicate']		 =	'El correo <strong>%s</strong> ya se encuentra registrado.';
$lang['email:org_notfound']		 =	'No tienes organizaciones asignadas. Comunicate con el administrador del sitio a través de este correo <a href="mailto:%s">%s</a>  para asignarte una.';
$lang['email:not_found_template']	 		 =	'No se establecio la plantilla.';
//eduard
$lang['email:title_solicitud']		 	 =	'Solicitud de Emails';
$lang['email:not_found_alum']	 		 =	'No existen alumnos con los criterios de busqueda.';
$lang['email:exist']	 				 =	' ya cuenta con correo electronico. Cualquier aclaracion comunicate a <a href="mailto:informacion@cobacam.edu.mx">informacion@cobacam.edu.mx</a> reportando este problema.' ;
$lang['email:not_found_solicitudes']	 =	'No existen solicitudes pendientes.';

$lang['email:error_access'] 		 	 =  'La cuenta de usuario no esta completamente vinculada a una cuenta de director, te recomendamos que nos envies un mensaje al correo <a href="mailto:'.Settings::get('contact_email').'">'.Settings::get('contact_email').'</a> reportando este problema.';
$lang['email:error_insert']	 			 =	'Ocurrio un error al intentar guardar el registro comuniquese con el administrador.';
$lang['email:solicitud_title']		 		 =	'Solicitudes';

$lang['email:solicitud']		 		 =	'Administración de Solicitudes de Emails';
$lang['email:save_success_solicitud']	 =	'Se aprobo la solicitud y se creo correctamente el correo <strong>%s</strong>';
$lang['email:reconmedaciones']		 	 =	'Seleccione un correo de los recomendamos en la pestaña Correo';
$lang['email:delete_solicitud']	 		 =	'Se han eliminado la solicitud de manera satisfactoria.';
$lang['email:delete_error']	 		     =	'Se han eliminado la solicitud de manera satisfactoria.';
$lang['email:solicitud_duplicada']		 =	'Ya existe una solicitud en proceso';
//End eduard

?>