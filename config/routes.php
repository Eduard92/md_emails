<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
$route['emails/admin/organizaciones(/:any)?']			= 'admin_organizaciones$1';

$route['emails/solicitudes']		        = 'emails/load';
$route['emails/solicitudes/(:any)?']		= 'emails/load';
$route['emails/nueva']					    = 'emails/create';

$route['emails/admin/solicitudes(/:any)?']			= 'admin_solicitudes$1';
$route['emails/admin/solicitudes/download']			= 'admin_solicitudes/download';
?>