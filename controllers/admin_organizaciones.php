<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Blog Fields
 *
 * Manage custom blogs fields for
 * your blog.
 *
 * @author 		PyroCMS Dev Team
 * @package 	PyroCMS\Core\Modules\Users\Controllers
 */
class Admin_organizaciones extends Admin_Controller {

	protected $section = 'organizaciones';

	// --------------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();
        $this->lang->load('email');
        $this->load->model(array('org_m','users/user_m'));
        $this->load->config('groups');
        
        $this->load->library('centros/centro');
        $this->validation_rules = array(

            array(
                'field' => 'table',
                'label' => 'Tabla o módulo',
                'rules' => 'trim'
            ),
            array(
                'field' => 'table_id',
                'label' => 'Columna primaria',
                'rules' => 'trim'
            ),

            array(
                'field' => 'auth_by',
                'label' => 'Auntenticado',
                'rules' => 'trim'
            ),

        );
    }
    function index()
    {
        $orgs = $this->org_m->get_all();
        
        foreach($orgs as &$org)
        {
            $org->users = Centro::GetList($org->id,'orgs');
            
           
            
        }
        
        
        $this->template->title($this->module_details['name'])
                ->set('orgs',$orgs)
                ->build('admin/orgs/index');
    }
    function add($id)
    {
        $org   = $this->org_m->get($id) OR redirect('admin');
        
        if($_POST)
        {
             Centro::AddUsers($id,'orgs',$this->input->post('users'));
             
             redirect('admin/emails/organizaciones');
        }
        
        
        $users = $this->user_m->where_in('name',$this->config->item('groups'))->get_all();
        
        $user_actives = Centro::GetList($id,'orgs');
        $this->template->title($this->module_details['name'],lang('email:add_org'))
                ->set('users',$users)
                ->set('users_active',$user_actives?$user_actives:array())
                ->set('org',$org)
                ->build('admin/orgs/form');
    }
    public function config($id=0)
    {
        $org   = $this->org_m->get($id) OR redirect('admin');
        print_r($org);

        $modules = array(

            array(
                'slug' => 'alumnos',
                'name' => 'Alumnos',
                'rows' => array()
            ),
            array(
                'slug' => 'empleados',
                'name' => 'Empleados',
                'rows' => array()
            )
        );

        foreach($modules as &$module)
        {
            $module['rows'] = (array)$this->db->list_fields($module['slug']);
            //print_r($module);
            foreach($module['rows'] as &$row)
            {
                ///$row->table = $module['slug'];
                //$row['visible'] = true;
            }

        }
        $this->form_validation->set_rules($this->validation_rules);

        if($this->form_validation->run())
        {
            if($this->org_m->edit($id,$this->input->post()))
            {

                Equipo::addAsignacion($equipo->asignacion,$id,$this->input->post('asignacion'));

                $this->session->set_flashdata('success',sprintf(lang('marcas:save_success'),$this->input->post('nombre')));

            }
            else
            {
                $this->session->set_flashdata('error',lang('global:save_error'));

            }


            redirect('admin/emails/organizaciones');

        }
        $this->template->title($this->module_details['name'],lang('email:add_org'))
            ->set('org',$org)
            ->append_metadata('<script type="text/javascript">var  modules ='.json_encode($modules).'; </script>')
            ->append_js('module::email.controller.js')
            ->build('admin/orgs/form_configuracion');
    }
 }
?>