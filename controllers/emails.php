<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * The public controller for the Pages module.
 *
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Pages\Controllers
 */
class Emails extends Public_Controller
{

	/**
	 * Constructor method
	 */
	public function __construct()
	{
		parent::__construct();
        $this->load->model(array('solicitud_email_m','files/file_folders_m'));
        $this->lang->load('email');
        $this->load->library(array('files/files'));
        $this->load->library('GService');


    $this->validation_rules = array(
          'motivo'=>array(
        'field' => 'alumno',
        'label' => 'Alumno',
        'rules' => 'trim|required'
        ),

        );

     if($this->current_user == false)
        {
            $this->session->set_userdata('redirect_to', current_url());
            redirect('users/login');
        }
        
        $this->director = $this->db->where('user_id',$this->current_user->id)->get('directores')->row();
        
        $this->template->set('director',$this->director)
                       ->append_js('handlebars.js')
                    //   ->append_js('bloodhound.min.js')
                       ->append_js('typeahead.bundle.min.js')
                       ->append_js('typeahead.jquery.min.js')
                      
                       ->append_css('typehead.css')
                        ->set_breadcrumb('Solicitud de Emails','emails');  
  
  }
    public function index()
    {
         $email = null;


        $total_asignados =  $this->db->select('org_path, COUNT(*) AS numrows')
          ->join('chromebooks','chromebooks.email=emails.email')
          ->group_by('org_path')
          ->get('emails')
          ->result();
      //  print_r($total_asignados);

                    


    $asignado =  $this->db                                             
                         ->join('chromebooks','chromebooks.email=emails.email')
                         ->count_all_results('emails');

    $chromebook =  $this->db->count_all_results('chromebooks');

    $disponibles = $chromebook - $asignado;


           $query1 =  $this->db->select('org_path, COUNT(*) AS numrows')
          ->group_by('org_path')
          ->get('emails')
          ->result();
          // print_r($query1);

            
          $data;


          foreach($query1 as $elemento)
          {
            
            $org_path =  $elemento->org_path;
            $total = $elemento->numrows;
           

            foreach ($total_asignados as $alumnos) 
            {

              if ($org_path == $alumnos->org_path) 
              {

                  $alumnos->alumnos=   $total;
              
              } 
             
            
              

            }



              
 
          }



 $this->template->set_layout(false)
            ->enable_parser(true)
            ->set('total_asignados',$total_asignados)
            //->set('asignados',$asignados)
           // ->set('data',$data)
            ->set('disponibles',$disponibles)
            ->build('admin/index_mail');   
    }

    public function remover()
    {
        $result   = false;
        $message  = '';      
        $asignacion   = false; 
      
         $message = '<div class="alert alert-warning">01</div>';
        if($_POST)
        {
        

           $asignacion =  $this->db->where(array(
                    'id_chromebook' => $this->input->post('serial'),
                    'removido IS NULL'      => null
           ))->get('chromebook_asignacion')->row();
           

           if(!$asignacion)
           {
               $message = '<div class="alert alert-warning">No hay registro de la CHROMEBOOK o aún no ha sido asignada</div>';
           }
           else{
                
              
               $this->db->where(array('id'=>$asignacion->id))->set(array('removido'=>date('Y-m-d H:i:s')))->update('chromebook_asignacion');
               $message = '<div class="alert alert-success">La CHROMEBOOK con No. de Serie '.$asignacion->id_chromebook.' ha sido removida</div>';    
              
               /*if($chromebook->email)
               {
                 
                    $asignado = $this->db->where('emails.email',$chromebook->email)
                                    ->join('chromebooks','chromebooks.email=emails.email')
                                    ->get('emails')->row();

                  
                   $baja = array('email' => null,'updated_on'=>now());
                   
                   $this->db->where('serial',$chromebook->serial)
                                            ->set($baja)
                                            ->update('chromebooks');
                            
                    $message = '<div class="alert alert-success">La CHROMEBOOK con No. de Serie '.$chromebook->serial.' ha sido removida</div>';        
                   

               }
               else
               {
                     $message = '<div class="alert alert-info">La CHROMEBOOK con No. de Serie '.$chromebook->serial.' anteriormente ya habia sido removida.</div>';        
               }*/

           }
           
            
        
                     
           
        }


        $this->template->set_layout(false)
            ->enable_parser(true)
            ->set('asignado',$asignacion)
            ->set('message',$message)
            ->build('admin/form_remover');   
    }

    public function agregar ()
    {


        //$this->load->helper('cookie');
        
        $result   = false;
        $message  = '';
        $org_path = $this->input->post('org_path');
        
        $asignado   = false; 
        $total_asignados = 0;
        
        
        
        
        if($_POST && $org_path)
        {
            
            
            
            
                
                
             
            
           
           $chromebook =  $this->db->where('serial',$this->input->post('serial'))->get('chromebooks')->row();
           
           $list =  $this->db->select('serial,full_name,org_path,emails.email AS email')
                    ->where(array(
                         'org_path'=>$this->input->post('org_path')
                       
                     ))->order_by('grado,grupo')
                     ->join('alumnos','alumnos.idalum=emails.table_id')
                     ->join('chromebooks','chromebooks.email=emails.email','LEFT')
                     ->get('emails')->result();
                     
            
           
           if(!$chromebook)
           {
               $message = '<div class="alert alert-warning">No hay registro de la CHROMEBOOK</div>';
           }
           else{
               $inc = 0;
               
               if($chromebook->email)
               {
                    $asignado = $this->db->where('emails.email',$chromebook->email)
                                    ->join('chromebooks','chromebooks.email=emails.email')
                                    ->get('emails')->row();
                                    
                    if(!$asignado)
                    {
                        $message = '<div class="alert alert-danger"> CHROMEBOOK asignada pero no existe el beneficiado: '.$chromebook->email.'</div>';
                    }
                    else
                    {
                        $message = '<div class="alert alert-info">CHROMEBOOK asignada : '.$chromebook->serial.'  / '.$chromebook->email.'</div>';
                    }
                   
               }
               else{
                   while(!$asignado)
                   {
                        
                        foreach($list as $alum)
                        {
                            
                            if(!$alum->serial)
                            {
                                
                               
                                
                                
                                $asignado = array('email' => $alum->email,'updated_on'=>now());
                                
                                if($this->db->where('serial',$chromebook->serial)
                                            ->set($asignado)
                                            ->update('chromebooks'))
                                {
                                   
                                    $asignado['serial']    = $chromebook->serial;
                                    $asignado['full_name'] = $alum->full_name;
                                    $asignado['org_path']  = $alum->org_path;
                                    $asignado = (Object)$asignado;
                                    
                                    break;
                                }
                            }
                        }
                        if(!$asignado)
                        {
                             $message = '<div class="alert alert-danger"> Se hay llegado al limite de registros</div>';
                             $asignado = true;
                        }
                        
                       
                        
                        
                   }
               }
           }
           
            
              $total_asignados =  $this->db
                        ->where(array(
                             'org_path'=>$this->input->post('org_path')
                           
                         ))
                         
                         ->join('chromebooks','chromebooks.email=emails.email')
                         ->count_all_results('emails');
           
                     
           
        }
        
         $orgs = $this->db->group_by('org_path')->get('emails')->result();
         $this->template->set_layout(false)
            ->enable_parser(true)
            ->set('orgs',array_for_select($orgs,'org_path','org_path'))
            ->set('total_alumnos',isset($list)?count($list):0)
            ->set('total_asignados',$total_asignados)
            ->set('asignado',$asignado)
            ->set('message',$message)
            ->build('admin/form_lectura');        
    
    

    }
     public function consulta()
    {
        $result   = false;
        $message  = '';      
        $asignado   = false; 
      
         $message = '<div class="alert alert-success">01</div>';
        if($_POST)
        {
            if(strlen($this->input->post('serial'))!= 10)
            {
               $message = '<div class="alert alert-danger">Numero de Serie Incorrecto</div>';
            }
            else{
          $chromebook_levantamiento =  $this->db->where('chromebook',$this->input->post('serial'))->get('chromebook_levantamiento')->row();
          if($chromebook_levantamiento)
          {
            $message = '<div class="alert alert-warning">La CHROMEBOOK con No. de Serie '.$chromebook_levantamiento->chromebook.' ya habia sido escaneada</div>';
          }
          else{
           $chromebook =  $this->db->where('serial',$this->input->post('serial'))->get('chromebooks')->row();
           
           if(!$chromebook)
           {
                $data = array( 'chromebook' => $this->input->post('serial'),
                                'status' =>   0  );
                if($this->db->insert('default_chromebook_levantamiento',$data))
                {
                   $message = '<div class="alert alert-info">La CHROMEBOOK con No. de Serie '.$this->input->post('serial').' No se encontraba en la Base de Datos</div>';
                }
           }
           else
           {
            $data = array( 'chromebook' => $this->input->post('serial'),
                                'status' =>   1 );
                if($this->db->insert('default_chromebook_levantamiento',$data))
                {
                   $message = '<div class="alert alert-success">La CHROMEBOOK con No. de Serie '.$chromebook->serial.' Se encontraba en la Base de Datos</div>';
                }
                    
               
           }   
           } 
           }      
          
         }
        $this->template->set_layout(false)
            ->enable_parser(true)
            ->set('message',$message)
            ->build('form_consulta');   
    }

     function load()    
    {
       $director = $this->director->id;

       $keywords = $this->input->get('keywords');
       $status = $this->input->get('tab')?$this->input->get('tab'):'enviados';


        $base_where = array(
              'id_director' => $director);

        switch($status)
        {
            case 'enviados':
                //$base_where['id'] = null;
                $base_where['estatus'] = 'enviado';
                break;
            case 'validados':
                $base_where['estatus'] = 'validado';
                break;
            case 'rechazados':
                $base_where['estatus'] = 'rechazado';
                break;
        }

        $solicitudes = false;
        $nombre_director = $this->director->nombre;

        $plantel='';

        if($director)
        {            
            $total_rows = $this->solicitud_email_m
                            ->order_by('id','DESC')
                            ->count_by($base_where);
                            
         $pagination = create_pagination('/emails/solicitudes/'.$status, $total_rows,NULL);
            
            $solicitudes = $this->solicitud_email_m->where($base_where)
                            ->order_by('id','DESC')
                            ->limit($pagination['limit'],$pagination['offset'])
                            ->get_all();
              foreach ($solicitudes as &$solicitud) 
              {
                     $extra = json_decode($solicitud->extra);
                     $solicitud->matricula = $extra->matricula;
                     $solicitud->grupo = $extra->grupo;
                     $solicitud->plantel = $extra->plantel;
                     $solicitud->motivo = $extra->motivo;
              }
            if($status=='enviados')
                       $enviados = $solicitudes;
            
            
               
        }
        //print_r($solicitudes);
        switch ($this->director->id_centro) 
        {
            case 3:
                $plantel = 'HECELCHAKAN';
                $org_path = '/Alumnos/Plantel 01 - Hecelchakán';
                break;
            case 4:
                $plantel = 'CANDELARIA';
                $org_path = '/Alumnos/Plantel 02 - Candelaria';
                break;
            case 5:
                $plantel = 'ESCARCEGA';
                $org_path = '/Alumnos/Plantel 03 - Escárcega';
                break;
            case 6:
                $plantel = 'SEYBAPLAYA';
                $org_path = '/Alumnos/Plantel 04 - Seybaplaya';
                break;
            case 7:
                $plantel = 'ATASTA';
                $org_path = '/Alumnos/Plantel 05 - Atasta';
                break;
            case 8:
                $plantel = 'MAMANTEL';
                $org_path = '/Alumnos/Plantel 06 - Mamantel';
                break;
            case 9:
                 $plantel = 'TENABO';
                 $org_path = '/Alumnos/Plantel 07 - Tenabo';
                break;
            case 10:
                 $plantel = 'NUNKINI';
                 $org_path = '/Alumnos/Plantel 08 - Nunkiní';
                break;
            case 11:
                 $plantel = 'CHAMPOTON';
                 $org_path = '/Alumnos/Plantel 09 - Champotón';
                break;
            case 12:
                 $plantel = 'CHICBUL';
                 $org_path = '/Alumnos/Plantel 10 - Chicbul';
                break;
            case 13:
                 $plantel = 'BECAL';
                 $org_path = '/Alumnos/Plantel 11 - Bécal';
                break;
            case 14:
                 $plantel = 'CALKINI';
                 $org_path = '/Alumnos/Plantel 13 - Calkiní';
                break;
            case 15:
                 $plantel = 'XPUJIL';
                 $org_path = '/Alumnos/Plantel 14 - Xpujil';
                break;
            case 16:
                 $plantel = 'LEY FEDERAL DE REFORMA AGRARIA';
                 $org_path = '/Alumnos/Plantel 15 - Ley Federal de Reforma Agraria';
                break;
            case 17:
                 $plantel = 'ADOLFO LOPEZ MATEOS';
                 $org_path = '/Alumnos/Plantel 16 - Adolfo López Mateos';
                break;
            case 18:
                 $plantel = 'NUEVO PROGRESO';
                 $org_path = '/Alumnos/Plantel 17 - Nuevo Progreso';
                break;
            case 19:
                 $plantel = 'XBACAB';
                 $org_path = '/Alumnos/Plantel 18 - Xbacab';
                break;
            case 20:
                 $plantel = 'LERMA';
                 $org_path = '/Alumnos/Plantel 19 - Lerma';
                break;
            case 21:
                 $plantel = 'DON SAMUEL';
                 $org_path = '/Alumnos/Plantel 20 - Don Samuel';
                break;
            case 22:
                 $plantel = 'LIBERTAD';
                 $org_path = '/Alumnos/Plantel 21 - Libertad';
                break;
            case 23:
                 $plantel = 'UKUM';
                 $org_path = '/Alumnos/EMSaD 01 - Ukúm';
                break;
            case 24:
                 $plantel = 'ISLA AGUADA';
                 $org_path = '/Alumnos/EMSaD 03 - Isla Aguada';
                break;
            case 25:
                 $plantel = 'LA ESMERALDA';
                 $org_path = '/Alumnos/EMSaD 04 - La Esmeralda';
                break;
            case 26:
                 $plantel = 'BOLONCHEN DE REJON';
                 $org_path = '/Alumnos/EMSaD 05 - Bolonchén de Rejón';
                break;
            case 27:
                 $plantel = 'SIHO-CHAC';
                 $org_path = '/Alumnos/EMSaD 06 - Sihochac';
                break;
            case 28:
                 $plantel = 'EL DESENGAÑO';
                 $org_path = '/Alumnos/EMSaD 07 - El Desengaño';
                break;
            case 29:
                 $plantel = 'JOSE MARIA MORELOS, EL CIVALITO';
                 $org_path = '/Alumnos/EMSaD 08 - El Civalito';
                break;
            case 30:
                 $plantel = 'EL AGUACATAL';
                 $org_path = '/Alumnos/EMSaD 09 - El Aguacatal';
                break;
            case 31:
                 $plantel = 'DZIBALCHEN';
                 $org_path = '/Alumnos/EMSaD 11 - Dzitbalchén';
                break;
            case 32:
                 $plantel = 'EL JUNCAL';
                 $org_path = '/Alumnos/EMSaD 12 - El Juncal';
                break;
            case 33:
                 $plantel = 'EL CARMEN II';
                 $org_path = '/Alumnos/EMSaD 13 - El Carmen II';
                break;
            case 34:
                 $plantel = 'EL TESORO';
                 $org_path = '/Alumnos/EMSaD 14 - El Tesoro';
                break;
            case 35:
                 $plantel = 'CHINA';
                 $org_path = '/Alumnos/EMSaD 18 - Chiná';
                break;
            case 36:
                 $plantel = 'CONQUISTA CAMPESINA';
                 $org_path = '/Alumnos/EMSaD 19 - Conquista Campesina';
                break;
            case 37:
                 $plantel = 'PICH';
                 $org_path = '/Alumnos/EMSaD 20 - Pich';
                break;
            case 38:
                 $plantel = 'EL NARANJO';
                 $org_path = '/Alumnos/EMSaD 21 - El Naranjo';
                break;
            case 39:
                 $plantel = 'CONSTITUCION';
                 $org_path = '/Alumnos/EMSaD 22 - Constitución';
                break;

      }

        $data_autocomplete     = array();

        $alumnos = $this->db->where('escuela',$plantel)
                            ->get('alumnos')->result();

        foreach ($alumnos as $alumno) 
        {
           $data_autocomplete[] = array(
                    'id_alumno' => $alumno->idalum, 
                    'full_name'    => $alumno->nombre.' '.$alumno->apellido_paterno.' '.$alumno->apellido_materno,
                    'given_name'    => $alumno->nombre,
                    'family_name'   => $alumno->apellido_paterno.' '.$alumno->apellido_materno,
                    'org_path' => $org_path,
                    'plantel' => $alumno->escuela,
                    'grupo' => $alumno->grupo,
                    'matricula' => $alumno->matricula,
                    'id_director'=> $director,
                );
        }



        $this->template->title($this->module_details['name'],lang('email:title_solicitud'))
                    ->enable_parser(true)
                   // ->append_js('module::front/index.js')
                    ->set('pagination',$pagination)
                    ->set('base_where',$base_where)               
                    ->set('solicitudes',$solicitudes)
                    ->set('keywords',$keywords)
                    ->set('status',$status)
                    ->set('total',$total_rows)
                    ->append_js('module::front.js')
                   ->append_metadata('<script type="text/javascript">var ids=[],display_autocomplete=false, text_empty=\''.lang('email:not_found_alum').'\', url_current=\''.base_url($this->uri->uri_string()).'\', data ='.json_encode($data_autocomplete).', SITE_URL=\''.base_url().'\',enviados='.($enviados?json_encode($enviados):'[]').';</script>')
                    ->build('front/index');
    }

    function create()
    {
        $result = array(

            'status'  => false,
            'message' => '',
            'data'    => array()
        );

        $full_name = $_POST['full_name'];

        if(empty($full_name)==true)
        {
          $result['message'] = '<div class="alert alert-danger"> Seleccione a un Alumno</div>';
          return $this->template->build_json($result);
        }


            $result_users = $this->gservice->get_list_users('','',$full_name,'name');

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

                $result['message'] = '<div class="alert alert-danger">'.$full_name.lang('email:exist').'</div>';
                #$result['message'] = '<div class="alert alert-danger">'.$_POST['motivo'].'</div>';

                return $this->template->build_json($result);


                    
            }
            else
            {   
                $base_where = array(
                      'module_id' => $this->input->post('id_alumno'),
                      'estatus' => 'enviado');
               $duplicado = $this->solicitud_email_m->where($base_where)->get_all();

                if($duplicado)
                    {
                      $result['message'] = '<div class="alert alert-danger">'.lang('email:solicitud_duplicada').'</div>';
                      return $this->template->build_json($result);
                    }
                    else
                    {

                        if($id = $this->solicitud_email_m->create($this->input->post(),$this->director->id_centro ))
                          {
                             $solicitud_enviada = $this->solicitud_email_m->get($id);
                             $extra = json_decode($solicitud_enviada->extra);
                             $solicitud_enviada->matricula = $extra->matricula;
                             $solicitud_enviada->motivo = $extra->motivo;
                             $solicitud_enviada->grupo = $extra->grupo;
                             $solicitud_enviada->plantel = $extra->plantel;
                             $result['message'] = 'OK';
                             $result['status'] = true;
                             $result['data'] = $solicitud_enviada;

                             return $this->template->build_json($result);
                          }
                              else
                              {
                                $result['message'] = lang('email:error_insert');
                              }
                    }                
            }
    }
  
    public function download()
    { 

      $ids = ($id) ?array(0=>$id) :json_decode($_POST["ids"]);

      if(empty($ids)==true)
      {
        show_404();
      }
      

       $where='';
        foreach ($ids as &$id) 
        {
            $where .= $id->value.',';
        } 
     
  
      $base_where = array(
          'id IN ('. substr($where, 0, -1). ')'=> null);

      $solicitudes= $this->solicitud_email_m->select('*')
                                              ->where($base_where)
                                              ->get_all();


            
      $oficio          = $_POST['oficio'];
      $semestre        = $_POST['semestre'];
      $subdirec        = $_POST['subdirec'];
      $control_escolar = $_POST['control_escolar'];

      $table = '<tbody>';
        foreach ($solicitudes as $solicitud)
        {        
            $extra = json_decode($solicitud->extra);

             $table .= '<tr>';
             $table .='<td style="padding: 3px;vertical-align: middle;font-size: 10px;">'.$solicitud->full_name.'</td>';
             $table .='<td align="center" style="padding: 3px;vertical-align: middle;font-size: 10px;">'.$extra->matricula.'</td>';
             $table .='<td align="center" style="padding: 3px;vertical-align: middle;font-size: 10px;">'.$extra->grupo.'</td>';
            $table .='<td align="center" style="padding: 3px;vertical-align: middle;font-size: 10px;">'.$extra->motivo.'</td>';                       
             $table .= '</tr>';
            
          }

      $query = $this->db->select('localidad , municipio, nombre, clave')
                        ->where('id',$this->director->id_centro)
                        ->get('default_centros')->row(); 

        if($query->localidad == $query->municipio)
        {
           $fecha= $query->municipio.', Campeche, '.strftime("%#d").' de '.strftime("%B").' de '.strftime("%Y");   
        }     
        else 
        {
            $fecha= $query->localidad.', '.$query->municipio.', Campeche, '.strftime("%#d").' de '.strftime("%B").' de '.strftime("%Y");  
        }        

      $plantel = $query->nombre;
      $clave = $query->clave;
      $director = $this->director->nombre;
      $table .='</tbody>';

      print_r($solicitudes);


      $output = ''; 
      $doc = 'solicitud_alumno';
      ini_set('max_execution_time', 300);
      $this->load->library(array('pdf'));
      $html2pdf = new HTML2PDF('P', 'A4', 'es');
      ob_clean();         

      $output=$this->template->set_layout(false)
                             ->enable_parser(true)
                             ->build('templates/'.$doc,
                                array('table'=>$table,
                                    'fecha'=>$fecha,
                                    'plantel'=>$plantel,
                                    'director'=>$director,
                                    'oficio'=>$oficio,
                                    'semestre'=>$semestre,
                                    'subdirec'=>$subdirec,
                                    'control_escolar'=>$control_escolar,
                                    'clave'=>$clave
                                    ),true);
                           
    $html2pdf->writeHTML($output);
    $html2pdf->Output($doc.'_'.now().'.pdf','E');

  

    }

    
}