
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_solicitudes extends Admin_Controller {
	protected $section='solicitudes';
	public function __construct()
	{
		parent::__construct();
        
        $this->load->model(array('solicitud_email_m','email_m'));
        $this->lang->load('email');
        $this->load->library('GService');

   
        //$this->config->load('files/files');
        //$this->_path = FCPATH.rtrim($this->config->item('files:path'), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }

    function index()
    {
          $data = array(
         
            'Recibidos'  => array(),
            'Rechazados'  => array(),
            'Validados'  => array()
         );
          $f_centro = $this->input->get('f_centro');
                  
         if($f_centro)
         {
            $base_where['id_centro'] = $f_centro;
            $solicitudes = $this->solicitud_email_m->where($base_where)->get_all();
         }
         else
         {
          $solicitudes = $this->solicitud_email_m->get_all();
         }

         foreach ($solicitudes as $solicitud) {
            if($solicitud->estatus == 'enviado')
            {
                $solicitud->extra = json_decode($solicitud->extra);
                $data['Recibidos'][] = $solicitud;
            }
            if ($solicitud->estatus == 'validado') {
                 $solicitud->extra = json_decode($solicitud->extra);
                 $data['Validados'][] = $solicitud;
            }
            if ($solicitud->estatus == 'rechazado') {
                 $solicitud->extra = json_decode($solicitud->extra);
                 $data['Rechazados'][] = $solicitud;
            }

         }

         $centros = $this->db->get('centros')->result();

        $this->template->title($this->module_details['name'])
                ->append_metadata('<script type="text/javascript">  var emails,recibidos='.json_encode($data['Recibidos']).', rechazados='.json_encode($data['Rechazados']).', validados='.json_encode($data['Validados']).'</script>')
                ->append_js('module::email.controller.js')
                ->set('centros',array_for_select($centros,'id','nombre'))
                ->set('data',$data)
                ->build('admin/solicitudes/index');
    }

    function update()
    {
        
        $result = array(
            'status' => false,
            'data'   => array(),            
            'message' => ''
        
        );

        if($this->input->post('estatus') == 'enviado')
        {
                $result['message'] = 'Se requiere cambiar el estatus';
        }
        else
        {
                $data = array(
                  'estatus'  =>  $this->input->post('estatus')
                );

            if($this->solicitud_email_m->update($this->input->post('id'),$data))
            {            
                 $solicitud = $this->solicitud_email_m->get($this->input->post('id'));

                        $result['message'] = $solicitud->estatus;
                        $result['status'] = true;
            }
            else
            {
                $result['message'] = 'Ocurrio un error';
            }

        }
     
        return $this->template->build_json($result);
       
    }

    function validar()
    {
      $result = array(
      'status' => false,
      'data'   => array(),            
      'message' => ''   );

        $nombres = $this->input->post('given_name');
        $apellidos = $this->input->post('family_name');

            $nombres = str_replace('Ñ','N',$nombres);
            $nombres = str_replace('Ó','O',$nombres);
            $nombres = str_replace('Á','A',$nombres);
            $nombres = str_replace('Í','I',$nombres);
            $nombres = str_replace('É','E',$nombres);
            $nombres = str_replace('Ü','U',$nombres);
            $nombres = str_replace('Ú','U',$nombres);

            $apellidos = str_replace('Ñ','N',$apellidos);
            $apellidos = str_replace('Ó','O',$apellidos);
            $apellidos = str_replace('Á','A',$apellidos);
            $apellidos = str_replace('Í','I',$apellidos);
            $apellidos = str_replace('É','E',$apellidos);
            $apellidos = str_replace('Ü','U',$apellidos);
            $apellidos = str_replace('Ú','U',$apellidos);

        $nombres = explode(" ", $nombres);
        $apellidos = explode(" ", $apellidos);

            $data_apellidos = array();

            $special_tokens = array('da', 'de', 'del', 'la', 'las', 'los', 'y', 'i', 'san', 'santa');
            $previo = "";

                  foreach($apellidos as $apellido) 
                  {
                      $_apellido = strtolower($apellido);
                          if(in_array($_apellido, $special_tokens)) {
                              $previo .= "$apellido";
                              $previo = trim($previo);

                          } else {
                              $data_apellidos[] = $previo. $apellido;
                              $previo = "";
                          }
                  }

        $partial_email = strtolower(replace_string($nombres[0]).'_'. replace_string($data_apellidos[0]));
        $domain = '@cobacam.edu.mx';
        $email = $partial_email.$domain;

        $data_emails = array();



        $user = $this->gservice->get_user($email);

             if($user['status']== true)
             {
                    $count= 1;
                         while($user['status']== true)
                         {

                            $partial_email_tmp=$partial_email;
                            $partial_email_tmp=$partial_email.(string)$count;

                            $email = $partial_email_tmp.$domain;
                            $user = $this->gservice->get_user($email);

                            $count= $count+1;

                         }
                    $data_emails[]=$email;
                                              
                if($user['status']== false)
                {
                    $partial_email = strtolower(replace_string($nombres[0]).'_'. replace_string($data_apellidos[1]));
                    $email = $partial_email.$domain;
                    $user = $this->gservice->get_user($email);

                     $count= 1;
                         while($user['status']== true)
                         {

                            $partial_email_tmp=$partial_email;
                            $partial_email_tmp=$partial_email.(string)$count;

                            $email = $partial_email_tmp.$domain;
                            $user = $this->gservice->get_user($email);

                            $count= $count+1;

                         }
                    $data_emails[]=$email;


                    $result['data'] =$data_emails;
                    $result['status'] =  $result['message'] = lang('email:reconmedaciones');

                }
             }
             else
             {
                $data_emails[] = $email;
                $result['data'] = $data_emails;
                $result['status']= true;

             }


            return $this->template->build_json($result);
        //}
       
    }
    function created()
    {
        $result = array(
        'status' => false,
        'data'   => array(),            
        'message' => ''
            );

         $verify_user = $this->gservice->get_user($this->input->post('email'));
        
            if($verify_user['status'])
            {
                $result['message'] = sprintf($this->input->post('email'));
            }
            else
            {

               $add_user = $this->gservice->add_user($this->input->post('email'),$this->input->post('given_name'),$this->input->post('family_name'),$this->input->post('full_name'),$this->input->post('org'));

               if($add_user['status'])
              {
                    $insert = array(
                                'email'       => $this->input->post('email'),
                                'given_name'  => $this->input->post('given_name'),
                                'family_name' => $this->input->post('family_name'),
                                'full_name'   => $this->input->post('full_name'),
                                'org_path'    => $this->input->post('org_path'),
                                'syncronize'  => 1,
                                'created_on'  => now(),
                                'updated_on'  => now(),
                                'table_id'    =>$this->input->post('module_id'),
                                'table'       => 'alumnos'
                            );               
                            
                    $data = array(
                         'estatus'  =>  'validado'
                                );

                    $solicitud = $this->solicitud_email_m->update($this->input->post('id_solicitud'),$data);
                    $email = $this->email_m->insert($insert);



                       $insert['email']   = $email;
                       $insert['solicitud'] = $solicitud;
                       $result['data']    = $insert;
                       $result['status']  = true;
                }
               else
                {
                    $result = $add_user;
                }
                
            }
        return $this->template->build_json($result);
        

    }

    function rechazar()
    {
        $result = array(
        'status' => false,
        'data'   => array(),            
        'message' => ''
            );

        $data = array(
         'estatus'  =>  'rechazado');

                   if($this->solicitud_email_m->update($this->input->post('id_solicitud'),$data))
                    {
                      $result['status']  = true;
                    }
                  else
                    {
                       $result['message'] = lang('email:error_insert');
                    }
                
            
        return $this->template->build_json($result);
        

    }

    function delete($id=0)
    {
        if($this->solicitud_email_m->delete($id))
        {
        $this->session->set_flashdata('success',lang('email:delete_solicitud'));
        }
        else
        {
            $this->session->set_flashdata('error',lang('email:delete_error'));
        }
        redirect('admin/emails/solicitudes');
    }



    
}
?>