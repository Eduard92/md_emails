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
    function edit($id=0)
    {
        $org = $this->org_m->get($id) OR show_404();
        
        
        if($_POST)
        {
            $data = array(                
                'name'     => $this->input->post('name'), 
                'template' => $this->input->post('template'),                 
            );
            if($this->org_m->update($id,$data))
            {
                
                Centro::AddUsers($id,'orgs',$this->input->post('users'));
               	$this->session->set_flashdata('success',lang('global:save_success'));
            }
            else
            {
                $this->session->set_flashdata('error',lang('global:save_error'));
            }
             
             redirect('admin/emails/organizaciones');
        }
        
        $users = $this->user_m->where_in('name',$this->config->item('groups'))->get_all();
        $user_actives = Centro::GetList($id,'orgs');
        $templates = $this->db->get('email_templates')->result();
        
        $this->template->title($this->module_details['name'],lang('email:edit_org'))
                ->set('users',$users)
                ->set('users_active',$user_actives?$user_actives:array())
                ->set('templates',array_for_select($templates,'slug','name'))
                ->set('org',$org)
                ->build('admin/orgs/form');
    }
 }
?>