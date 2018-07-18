<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends Admin_Controller {
	protected $section='emails';
	public function __construct()
	{
		parent::__construct();
        
        $this->load->library('GService');
        $this->load->model(array('email_m','org_m'));
        $this->lang->load('email');
        $this->config->load('files/files');
        $this->_path = FCPATH.rtrim($this->config->item('files:path'), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }
    
    
    
    public function reset_password()
    {
        $result = $this->gservice->reset_password_user('silvia.caamal@cobacam.edu.mx','cobacam2017','/Personal Docente');
        
        
    }
    public function get_list()
    {
         ini_set('max_execution_time', 0); 
        $docentes = $this->db //->limit(5)
            ->get('docs')->result();
        $inc = 1;
        echo '<table>';
        foreach($docentes as $docente)
        {
            echo '<tr>';
            if(!$docente->nombre) continue;
            
            $docente->nombre = str_replace('Ñ','N',$docente->nombre);
            $docente->nombre = str_replace('Ó','O',$docente->nombre);
            $docente->nombre = str_replace('Á','A',$docente->nombre);
            
            list($apellido_paterno,$apellido_materno,$nombre) = explode(' ',$docente->nombre); 
            
            //Nombre
            $full_name = '';
            $docente->nombre = strtolower($docente->nombre);
            
            $array_names =  explode(' ',trim($docente->nombre));
            //$array_name = rsort($array_name);
            $family_name = '';
            $given_name = '';
            $control = 0;
            foreach($array_names as $name)
            {
                if($control < 2)
                {
                    if(!$family_name)
                        $family_name = ucfirst($name);
                    else
                        $family_name .= ' '. ucfirst($name);
                }
                else
                {
                    if(!$given_name)
                        $given_name = ucfirst($name);
                    else
                        $given_name .= ' '. ucfirst($name);
                }
                
                    
                $control++;
            }
            
            $email  = strtolower($nombre.'.'.$apellido_paterno).'@cobacam.edu.mx';
            $result = $this->gservice->get_user($email);
            //echo $inc.' .-'.$docente->nombre.' - '.$email.'<br/>';
            if($result)
            {
                echo '<td>'.$given_name.' '.$family_name.'<td/>';
                echo '<td>'.$result->getPrimaryEmail().'</td>';
                //$result = $this->gservice->reset_password_user($email,'cobacam2017','/Personal Docente');
                
            }
            else{
                //$this->gservice->add_user($email,$given_name,$family_name,$given_name.' '.$family_name,'/Personal Docente');
                echo $inc.' .-'.$docente->nombre.' -  Not Found<br/>';
            }
            //echo '<br/>';
            $inc++;
            
            echo '</tr>';
        }
        echo '</table>';
    }
    function add($limit='200',$init='0')
    {
        ini_set('max_execution_time', 0); 
        //ini_set('memory_limit','600M');
        /*
         "kind": "admin#directory#orgUnit",
   "etag": "\"MuWcgcolLPkKr8QHxQ1YIz3aV0o/Vj1tJIuIQxJiT4DWvXf0KEEJ0Wo\"",
   "name": "EMSaD 18 - Chiná",
   "description": "",
   "orgUnitPath": "/Alumnos/EMSaD 18 - Chiná",
   "orgUnitId": "id:023walu62hp35in",
   "parentOrgUnitPath": "/Alumnos",
   "parentOrgUnitId": "id:023walu640lb65g",
   "blockInheritance": false
        */
        //$result = $this->gservice->add_user('em03@cobacam.edu.mx','EM03','em03','em03','/Alumnos/EMSaD 18 - Chiná');
        
        //echo $result->getCustomerId();
        //echo $result->getPrimaryEmail();
        
        ///$this->email_m->insert(array(
           // 'email'       => $result->getPrimaryEmail(),
            //'customer_id' => $result->getCustomerId()
        //));
        //exit()
        $alumnos = $this->db->where('escuela','BECAL')
                        ->limit($limit,$init)
                        ->order_by('matricula')
                        ->get('alumnos')->result();
        $index = 1;
        foreach($alumnos as $alumno){
            echo $index.'.-cb'.str_replace('-','_',$alumno->matricula).'@cobacam.edu.mx'.$alumno->matricula.'@cobacam.edu.mx<br/>';
             $index++;
           //continue;
            $result = $this->gservice->add_user('cb'.str_replace('-','_',$alumno->matricula).'@cobacam.edu.mx',$alumno->nombre,$alumno->apellido_paterno.' '.$alumno->apellido_materno,$alumno->nombre.' '.$alumno->apellido_paterno.' '.$alumno->apellido_materno,'/Alumnos/Plantel 11 - Bécal','cb'.str_replace('-','_',$alumno->matricula));
            if($result)
            {
                $this->email_m->insert(array(
                    'email'       => $result->getPrimaryEmail(),
                    'customer_id' => $result->getCustomerId(),
                    'created_on'  => now(),
                    
                ));
            }
            
           
        }
        
        
    }
    
    function create()
    {
        
        $result = array(
            'status' => false,
            'data'   => array(),
            
            'message' => ''
        
        );
        $email = str_replace('@cobacam.edu.mx','',$this->input->post('email')).'@cobacam.edu.mx';
        
        $verify_user = $this->gservice->get_user($email);
        
        if($verify_user['status'])
        {
            $result['message'] = sprintf(lang('email:duplicate'),$email);
        }
        else
        {
            $add_user = $this->gservice->add_user($email,$this->input->post('given_name'),$this->input->post('family_name'),$this->input->post('full_name'),$this->input->post('org_path'));
            
            if($add_user['status'])
            {
                $insert = array(
                            'email'       => $email,
                            'given_name'  => $this->input->post('given_name'),  
                            'family_name' => $this->input->post('family_name'),
                            'full_name'   => $this->input->post('full_name'),
                            'org_path'    => $this->input->post('org_path'),
                            'syncronize'  => 1,
                            'created_on'  => now(),
                            'updated_on'  => now()
                             
                        );
                $result['status'] = true;
                
                $id = $this->email_m->insert($insert);

                $insert['id']      = $id;
                $result['data']    = $insert;
                $result['message'] = lang('email:save_success');
            }
            else
            {
                $result = $add_user;
            }
            
        }
        return $this->template->build_json($result);
        
        ///$this->template->title($this->module_details['name'])
           //         ->build('admin/form');
    }
    function delete()
    {
        role_or_die('emails','delete');
        
        ini_set('max_execution_time', 0); 
        $id = $this->input->get('id');
        
        $ids = ($id) ? array($id) : $this->input->post('action_to');
        $deletes = array();
        $result = array(
            'status'  => false,
            'message' => ''
        );
        
        
        if(count($ids)>0)
        {
            foreach($ids AS $id)
            {
                $user   = $this->gservice->delete_user($id);
               
                $email = $this->email_m->get_by('email',$id);
                
                if($email)
                {
                        $this->email_m->delete($email->id);
                }                
                if($user)
                {
                    
                    $deletes[] = $id;
                }
                
                
            }
        }
        
        if(count($deletes)>0)
        {
            $result['status']  = true;
            $result['message'] = sprintf(lang('email:delete_success'),count($deletes));
            
            $this->session->set_flashdata('success',sprintf(lang('email:delete_success'),count($deletes)));
           
        }
        
        
        $this->input->is_ajax_request()?
            $this->template->build_json($result):
            redirect('admin/emails');
            
        
       
        
    }
    public function action()
	{
		switch ($this->input->post('btnAction'))
		{
			
			case 'delete':
				$this->delete();
				break;

			default:
				redirect('admin/emails');
				break;
		}
	}
    function upload()
    {
        ini_set('max_execution_time', 0); 
        $this->load->model(array(
            'files/file_folders_m'
        ));
        $this->load->library('files/files');
        
        $result = array(
            'message' =>  '',
            'status' => false,
            'data'   => array()
        );
        
        $folder        = $this->file_folders_m->get_by_path('otros') OR show_error('Error al buscar la carpeta');
        $file_result   = Files::upload($folder->id,false,'file',false,false,false,'csv');
        
        
       
        if($file_result['status'])
        {
           
           
            
            $file_path = $this->_path.'/'.$file_result['data']['filename'];
            
           
              
            $file   = fopen($file_path, 'r');
            $max_line_length = defined('MAX_LINE_LENGTH') ? MAX_LINE_LENGTH : 10000;
            $header          = fgetcsv($file,$max_line_length); 
            
            while (($line = fgetcsv($file)) !== FALSE) {
             
              $csv_array[] =  array_combine($header,$line);
            }
           
           
            fclose($file);
            
            //Borramos archivo
            
            Files::delete_file($file_result['data']['id']);
             
            
            foreach($csv_array as &$csv)
            {
                //echo $scv['email'];
                //echo $csv['email'];
                
                $csv['given_name']  = utf8_encode($csv['given_name']);
                $csv['family_name'] = utf8_encode($csv['family_name']);
                $csv['full_name'] = utf8_encode($csv['full_name']);
                
                $csv['org_path']= utf8_encode($csv['org_path']);
                if($csv['active']!=1){
                        $csv['message'] = 'Inactivo en archivo CSV';
                        $csv['icon']    = 'fa fa-warning';
                        continue;
                       
                }
                if($csv['email']){
                    $user = $this->gservice->get_user($csv['email']);
                        //echo $csv['email'];
                    
                    switch($this->input->post('action'))
                    {
                        case 'edit':
                            
                            $org = $this->input->post('org_path');
                            $org = isset($csv['org_path'])?$csv['org_path']:$org;
                            
                            if($user['status'])
                            {
                                if($user['data']->getName()->getFullName())
                                {
                                    $update = $this->gservice->update_user($csv['email'],$csv['given_name']?$csv['given_name']:'',$csv['family_name']?$csv['family_name']:'',$csv['full_name']?$csv['full_name']:'',$org);
                                    
                                    if($update['status'])
                                    {
                                        $csv['status'] = true;
                                    }
                                    else
                                        $csv['message'] = $updated['message']; 
                                }
                                else{
                                    $csv['message'] = 'El nombre no corresponde con el usuario';
                                    $csv['icon']    = 'fa fa-user';
                                }
                            }   
                            else{
                                $csv['message'] = $user['message'];  
                                $csv['icon']    = 'fa fa-ban';
                            } 
                        break;
                        case 'add':
                            
                            if(!$csv['given_name'] || !$csv['family_name'] || !$csv['full_name'])
                            {
                                $csv['message'] = 'Faltan algunos campos en el CSV';
                                $csv['status']  = false;
                                continue;
                            }
                            
                            $org = $this->input->post('org_path');
                            $org = isset($csv['org_path'])?$csv['org_path']:$org;
                            
                            if(!$org)
                            {
                                $csv['status'] = false;
                                $csv['message'] = 'Falta especificar la organización';
                                continue;
                            }
                            
                            //Verificamos que no exista
                            if(!$user['status']){
                                
                                
                                $add = $this->gservice->add_user($csv['email'],$csv['given_name'],$csv['family_name'],$csv['full_name'],$org,'cobacam2018');
                                
                                $csv['status']= $add['status'];
                                
                                if(!$csv['status'])
                                {
                                    $csv['icon']    = 'fa fa-warning';
                                }
                                $csv['message'] = $add['message'];
                            }
                            else
                            {
                                //$csv['icon']    = 'fa fa-ban';
                                if($user['data']->getName()->getFullName()!=$csv['full_name'])
                                {
                                   // $csv['email'] = str_replace(' ','_',$csv['family_name']).'@cobacam.edu.mx';
                                   // $add = $this->gservice->add_user($csv['email'],$csv['given_name'],$csv['family_name'],$csv['full_name'],$org,'cobacam2018');
                                
                                    $inc = 1;
                                    //$add = $this->gservice->add_user($csv['email'],$csv['given_name'],$csv['family_name'],$csv['full_name'],$org,'cobacam2018');
                                    $add = array('status'=>false);
                                    while(!$add['status'])
                                    {
                                        $new_email = explode('@',$csv['email']);
                                    
                                        $csv['email'] = $new_email[0].$inc.'@'.$new_email[1];
                                        
                                       
                                        
                                        $add = $this->gservice->add_user($csv['email'],$csv['given_name'],$csv['family_name'],$csv['full_name'],$org,'cobacam2018');
                                       
                                        $inc++;
                                    }
                                    $csv['status']= $add['status'];
                                    
                                    $csv['message'] = $add['message'];
                                    $csv['icon']    = 'fa fa-warning';
                                }else
                                {
                                    $csv['message'] = 'Correo ya existe';
                                    $csv['icon']    = 'fa fa-user';
                                }
                            }     
                        break;
                        
                        case 'check':
                            if($user['status'])
                            {
                                
                                $csv['status']= true;
                                $csv['org_path'] = $user['data']->getOrgUnitPath();
                                
                                if($user['data']->getName()->getFullName()!= $csv['full_name'])
                                {
                                    $csv['status']= false;
                                    $csv['message'] = 'El nombre no corresponde con el usuario';
                                    $csv['icon']    = 'fa fa-user';
                                }
                            }
                            else{
                            
                                $csv['message'] = $user['message'];
                                $csv['icon']    = 'fa fa-ban';
                            }
                            
                        break;
                        
                        case 'cron':
                            if($user['status'])
                            {
                                
                                $csv['status']= true;
                                $csv['org_path'] = $user['data']->getOrgUnitPath();
                                
                                
                                
                                if($this->db->where('cron_serial',$csv['cron_serial'])->get('emails')->row())
                                {
                                    $csv['status']= false;
                                    $csv['message'] = 'El numero de serie '.$csv['cron_serial'].'  se encuentra asignado';
                                    $csv['icon']    = 'fa fa-user';
                                }
                                else
                                {
                                    $this->db->where('email',$csv['email'])
                                            ->set(array(
                                            'cron_serial'=> $csv['cron_serial']
                                        ))->update('emails');
                                }
                            }
                            else{
                                
                                $csv['message'] = $user['message'];
                                $csv['icon']    = 'fa fa-ban';
                            }
                            
                        break;
                        case 'undel':
                            $org = $this->input->post('org_path');
                            if(!$user['status'])
                            {
                                $undeleted = $this->gservice->undelete_user($csv['email'],$org);
                                
                                
                                
                                $csv['status']= $undeleted['status'];
                                
                                $csv['message'] = $undeleted['message'];
                               
                            }
                            else{
                            
                                $csv['message'] = $user['message'];
                            }
                            
                        break;
                        
                       
                    }
                    
                }
                else
                {
                    $csv['status']= false;
                    $csv['message']='Falta la columna email';
                }
                
               
                
            }
            //print_r($result);
            
            $result['status'] = true;
            $result['data']   = $csv_array;
            return $this->template->build_json($result);
             //echo  json_encode($result);
            //return $this->template->build_json($result);
            
        }
        
         
        return $this->template->build_json($result);
    }
    function download()
    {
        $result = array(
            'status'  => false,
            'message' => '',
            'data'    => array()
        );
        $updates = 0;
        $adds    = array();
                
        if($users = $this->input->post('users')){
        
            foreach($users as $user)
            {
                       
                        $data = array(
                            'updated_on' => now(),
                            'given_name'  => $user['given_name'],
                            'family_name' => $user['family_name'],
                            'full_name'   => $user['full_name'],
                            'org_path'    => $user['org_path'],
                            'syncronize'  => 1
                        );
                        if($user_s = $this->email_m->get_by('email',$user['email']))
                        {
                          
                            
                            $this->email_m->update($user_s->id,$data);
                            $updates++;
                        }
                        else
                        {
                            $data['email']      = $user['email'];
                            $data['created_on'] = now();
                            
                           
                            $this->email_m->insert($data);
                            $adds[] = $data;
                        }
            }
            $result['message'] = sprintf(lang('email:download'),count($adds),$updates);
            
            $result['status'] = true;
            $result['data']   = $adds;
        }
        /*exit();
            $action   = $this->input->post('action');
                     
            $org_path = $this->input->post('org_path');
            
            if($org_path){
            
            
                
                $salt = false;
                $next_page = 'next';
                $updates = 0;
                $adds    = array();
                while($next_page){
                    
                    $next_page = $next_page == 'next'?'':$next_page;
                    
                    $users   = $this->gservice->get_list_users($org_path,$next_page);
                    
                    
                    foreach($users['data'] as $user)
                    {
                       
                        $data = array(
                            'updated_on' => now(),
                            'given_name'  => $user->getName()->getGivenName(),
                            'family_name' => $user->getName()->getFamilyName(),
                            'full_name'   => $user->getName()->getFullName(),
                            'org_path'    => $user->getOrgUnitPath(),
                            'syncronize'  => 1
                        );
                        if($user_s = $this->email_m->get_by('email',$user->getPrimaryEmail()))
                        {
                          
                            
                            $this->email_m->update($user_s->id,$data);
                            $updates++;
                        }
                        else
                        {
                            $data['email']      = $user->getPrimaryEmail();
                            $data['created_on'] = now();
                            
                           
                            $this->email_m->insert($data);
                            $adds[] = $data;
                        }
                    }
                    $next_page = $users['data']->getNextPageToken();  
                } 
                
                
            
               
            }   
            $result['message'] = sprintf(lang('email:download'),count($adds),$updates);
            
            $result['status'] = true;
            $result['data']   = $adds;*/
            return $this->template->build_json($result);  
    }
    function index()
    {
        $this->load->library('centros/centro');
        $result = array(
            'status' => false,
            'message' => '',
            'next_page' => '',
            'data'    => array()
        );
        
        $orgs_path = $this->input->get('org')? array($this->input->get('org')):array();
        
        if(!group_has_role('emails','admin_organizaciones'))
        {
            $orgs_perm = Centro::GetPermissions('orgs');
          
            $orgs_path = $this->org_m->where_in('id',$orgs_perm)->dropdown('id','org_path');
            
            $this->template->set('orgs_local',$orgs_path);
        }
        else
        {
            $orgs_dropdown = $this->org_m->where('active',1)->dropdown('org_path','org_path');
            
            $this->template->set('orgs_local',$orgs_dropdown);
        }
        
        
       
        $list_users  = array();
       
         
         
         
         if($this->input->is_ajax_request()){
            $result_users = $this->gservice->get_list_users($this->input->get('org_path'),$this->input->get('next_page'),$this->input->get('search'));
           // print_r($result_users);
            //print_r($_GET);
            
            if($result_users['status'] && $result_users['data']->getUsers())
            {
                foreach($result_users['data'] as $user)
                {
                    $list_users[] = array(
                       'email'=> $user->getPrimaryEmail(),
                       'full_name' => $user->getName()->getFullName(),
                       'given_name' => $user->getName()->getGivenName(),
                       'family_name' => $user->getName()->getFamilyName(),
                       'org_path'   => $user->getOrgUnitPath(),
                        'checked' => false   
                    );
                }
                $result['next_page'] = $result_users['data']->getNextPageToken();
                $result['data'] = $list_users;
                $result['status'] = true;
                
                return $this->template->build_json($result);
            }
             else
             {
                
                return $this->template->build_json($result_users);
             }
         }
         
         $orgs  = $this->gservice->get_list_orgs();
         
         $list_orgs   = array();
         
         
         $inc=1;
        
         
         foreach($orgs as $org)
         {
            if(!array_key_exists($org->getOrgUnitPath(),$list_orgs))
            {
                $child_orgs = $this->gservice->get_list_orgs($org->getOrgUnitPath());
                
                $list_orgs[$org->getOrgUnitPath()]= array(
                    'orgUnithPath' => $org->getOrgUnitPath(),
                    'collapsed' => true,
                    'name'  => $org->getName(),
                    ///'orgs'  => $this->_append_list(),
                    //'users' => array()
                );
                if(count($child_orgs)>0)
                {
                    
                    $list_orgs[$org->getOrgUnitPath()]['orgs'] = $this->_append_list($child_orgs);
                }
            }
         }
         
         
         if(count($orgs_path)>0)
         {
             $users_local = $this->email_m->where_in('emails.org_path',$orgs_path)
                            //->join('email_orgs','email_orgs.org_path=emails.org_path')
                            ->get_all();
         }
        
                            
        
         
         $this->input->is_ajax_request()?
            $this->template->build_json($result):            
            $this->template->title($this->module_details['name'])
                   // ->set('orgs_local',$orgs_path)
                    ->append_metadata('<script type="text/javascript">var users_local='.json_encode(isset($users_local)?$users_local:array()).',  lista_r='.json_encode($list_orgs).';</script>')
                     ->append_js('module::email.controller.js')
                    ->build('admin/index');
    }
    function _append_list($orgs)
    {
        $list_users=array();
        if($orgs)
        {
            foreach($orgs as $org)
            {
                if(!array_key_exists($org->getOrgUnitPath(),$list_users))
                {
                    $list_users[$org->getOrgUnitPath()]= array(
                        'orgUnithPath' => $org->getOrgUnitPath(),
                        'name'  => $org->getName(),
                        'collapsed' => true,
                        //'orgs'  => $this->_append_list($this->gservice->get_list_orgs($org->getOrgUnitPath())),
                    );
                }
            }
            return $list_users;
        }
        return false;
        
    }
    function edit($id=0)
    {
        $result = array(
            'status'  => false,
            'data'    => array(),
            'message' => ''
        ); 
        $password = $this->input->post('password');
        if($_POST)
        {
            $input = $this->input->post();
          
            $update_user =  $this->gservice->update_user($input['email'],$input['given_name'],$input['family_name'],$input['full_name'],$input['org_path'],$password,isset($input['change'])?$input['change']:false);
            if($update_user['status'])
            {
                
                //Actualizamos localmente
                 $update = array(
                           
                            'given_name'  => $this->input->post('given_name'),
                            'family_name' => $this->input->post('family_name'),
                            'full_name'   => $this->input->post('full_name'),
                            'org_path'    => $this->input->post('org_path'),
                            'syncronize'  => 1,
                            
                            'updated_on'  => now()
                             
                 );
                $this->email_m->update($id,$update);
                
                
                $result['status']  = true;
                $result['message'] = lang('email:save_success');
                $result['data']    = $update;
                if($password &&  $input['email_altern'])
                {
                        
                        $data['email']              = $input['email'];   
                        $data['new_password']       = $input['password'];
                    	$data['slug'] 				= 'new-password';
                   		$data['to'] 				= $input['email_altern'];
                   		$data['from'] 				= Settings::get('server_email');
                   		$data['name']				= Settings::get('site_name');
                   		$data['reply-to']			= Settings::get('server_email');
                        
                         Events::trigger('email', $data, 'array');
                }
            }
            else
            {
                $result = $update_user;
            }
        }
        
        return $this->template->build_json($result);
    }
    function get_user()
    {
        $result = array(
            'status' => false,
            'message' => '',
            'data'    => false
        );
        $data = $this->gservice->get_user($this->input->get('email'));
        
        if($data)
        {
            $result['status'] = true;
            $result['data'] = $data;
        }
        
        return $this->template->build_json($result);
        
    }
    function import()
    {
        
        
    }
    public function search()
    {
        $this->load->library('centros/centro');
        $result = array(
            'status' => false,
            'message' => '',
            'next_page' => '',
            'data'    => array()
        );
        $result_users = $this->gservice->get_list_users($this->input->get('org_path'),$this->input->get('next_page'),$this->input->get('search'),$this->input->get('search_by'));
        if($result_users['status'] && $result_users['data']->getUsers())
            {
                foreach($result_users['data'] as $user)
                {
                    $list_users[] = array(
                       'email'=> $user->getPrimaryEmail(),
                       'full_name' => $user->getName()->getFullName(),
                       'given_name' => $user->getName()->getGivenName(),
                       'family_name' => $user->getName()->getFamilyName(),
                       'org_path'   => $user->getOrgUnitPath(),
                        'checked' => false   
                    );
                }
                $result['next_page'] = $result_users['data']->getNextPageToken();
                $result['data'] = $list_users;
                $result['status'] = true;
                
                return $this->template->build_json($result);
        }
        else
        {
                
                return $this->template->build_json($result_users);
        }
    }
    public function export()
    {
        $this->load->helper('download');
		$this->load->library('format');
        
        $org    = $this->input->get('org');
        $result = $this->db->where('org_path',$org)->order_by('full_name')->get('emails')->result_array();
        
        $file_name = str_replace(' ','',$org);
        $file_name = str_replace('/','_',$file_name);
        
         force_download($file_name.'.csv',$this->format->factory($result)->to_csv());
         exit();
        
    }
    public function alumnos($limit=200,$init=0)
    {
         $centro = 'EL NARANJO';
         $this->load->helper('download');
	 	 $this->load->library('format');
         $alumnos = $this->db->select('nombre ,apellido_paterno,apellido_materno,matricula,idalum,matricula,\'/Alumnos/EMSaD 21 - El Naranjo\' AS org_path')
                        ->where('escuela',$centro)
                        ->limit($limit,$init)
                        ->order_by('nombre,apellido_paterno,apellido_materno')
                        ->get('alumnos')->result_array();
                        
         $inc = 1;
         foreach($alumnos AS &$alumno)
         {
           
            if($alumno['nombre']){
                /*$name  = explode(' ',trim($alumno['nombre'])); */
                $alumno['family_name'] = trim($alumno['apellido_paterno']).' '.trim($alumno['apellido_materno']);
                $alumno['given_name']= trim($alumno['nombre']);
                
                $alumno['full_name']= trim($alumno['nombre']).' '.trim($alumno['apellido_paterno']).' '.trim($alumno['apellido_materno']);
               /// echo $alumno['full_name'].'<br/>';
                $alumno['email'] = 'cb'.str_replace('-','_',$alumno['matricula']).'@cobacam.edu.mx'; ///strtolower(replace_string($name[count($name)-1]).'_'.replace_string($alumno['apellido_paterno']).'@cobacam.edu.mx');
                
                
                //$alumno['email'] = replace_string($alumno['email']);
                $alumno['active'] = 1; 
                //echo $inc.' - '.$alumno['email'].'-'.$alumno['full_name'].'<br/>';
                
               if(!$this->db->where(array('full_name'=>trim($alumno['full_name']),'org_path'=>'/Alumnos/EMSaD 21 - El Naranjo'))
                            ->set(array(
                               'table' => 'alumnos',
                               'table_id' => $alumno['idalum']
                            ))
                            ->update('emails'))
                {
                    echo 'No actualizado: '.$alumno['idalumm'].' - '.$alumno['full_name'].'<br/>';
                }
                $inc++;
            }
            unset($alumno['nombre'],$alumno['apellido_paterno'],$alumno['apellido_materno']);
         }       
         exit();  
         $file_name = str_replace(' ','_',$centro);
         $file_name = str_replace('/','',$file_name);               
         force_download('prep_'.$file_name.'.csv',$this->format->factory($alumnos)->to_csv());
         exit();
    }
    function lectura()
    {
        $this->template->set_layout(false)
			->build('admin/form_lectura');
    }


    public function  acuse($id=0)
    {
        $id = $id?$id:$this->input->get('id');

        $data = $this->email_m->get($id);

        print_r($data);
       // $email = $data->email;
        $email = $data->email;
        $nombre= $data->full_name;
        $anio = strftime("%Y");
        $plantel = explode(" ",$data->org_path);  // devuelve "cde" 

        $plantel  =  str_replace('/','',$plantel[count($plantel)-1]);

        $fecha= 'San Francisco de Campeche, Campeche, '.strftime("%#d").' de '.strftime("%B").' de '.$anio;

       


        $output = '';
        $doc = 'correo_alumno';
        ini_set('max_execution_time', 300);
        $this->load->library(array('pdf'));
        $html2pdf = new HTML2PDF('P', 'A4', 'es');
        ob_clean();

        $output=$this->template->set_layout(false)
            ->enable_parser(true)
            ->build('templates/'.$doc,
                array('fecha'=>$fecha,
                      'nombre'=>$nombre,
                      'email'=>$email,
                      'anio'=>$anio,
                      'plantel'=>$plantel
                ),true);

        $html2pdf->writeHTML($output);
        $html2pdf->Output($doc.'_'.now().'.pdf','I');
    }
    
    
    
 }
 ?>
