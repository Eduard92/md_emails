<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * create sliders with swipe.js
 *
 * @author 		James Doyle (james2doyle)
 * @website		http://ohdoylerules.com/
 * @package 	PyroCMS
 * @subpackage 	Sliders
 * @copyright 	MIT
 */
class Solicitud_email_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'default_email_solicitudes';
		
	}
		function create($input,$id_centro)
	{
		$extra = array(
			'grupo'=>$input['grupo'],
			'matricula'=>$input['matricula'],
			'plantel'=>$input['plantel'],
			'motivo'=>$input['motivo']?strtoupper($input['motivo']):null
              
        );

        $data = array(
        	'id_centro'  =>$id_centro,
			'id_director'  =>$input['id_director'],
			'module_id'    =>$input['id_alumno'],
			'given_name'   =>$input['given_name'],
			'full_name'    =>$input['full_name'],
			'family_name'  =>$input['family_name'],
			'org_path'     =>$input['org_path'],
        	'create_on'	   => date('Y-m-d H:i:s', now()),
            'estatus'	   => 'enviado',
            'extra'		   => json_encode($extra)

        );
      //  print_r($data);
        
      return $this->insert($data);


	}
 }
 ?>