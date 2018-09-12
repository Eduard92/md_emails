<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Groups module
 *
 * @author PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\Groups
 */
 class Module_Emails extends Module
{

	public $version = '1.0';

	public function info()
	{
		$info= array(
			'name' => array(
				'en' => 'Emails',
				
				'es' => 'Emails',
				
			),
			'description' => array(
				'en' => 'Administration, registration and cancellation of emails of the COBACAM domain.',
				
				'es' => 'Administración, alta  y baja de correos electrónicos del dominio COBACAM.',
				
			),
			'frontend' => false,
			'backend' => true,
			'menu' => 'content',
            'roles' => array(
				'create', 'edit', 'delete','admin_organizaciones','syncronize'
			),
            'sections'=>array(
                'emails'=>array(
                    'name'=>'email:title',
                    'uri' => 'admin/emails',
        			'shortcuts' => array(
        				/*array(
        					'name' => 'email:create',
        					'uri' =>  'admin/emails/create',
        					'class' => 'btn btn-default',
                            'open-modal' => 'true',
                            'modal-title' => 'Subir archivo scv'
        				),
                        array(
        					'name' => 'email:download',
        					'uri' => 'admin/emails/download',
        					'class' => 'btn btn-default',
                            'open-modal' => 'true',
                            'modal-title' => 'Descargar datos de Google'
        				),*/
        			)
                )
                /*,
                  'solicitudes'=>array(
                    'name'=>'email:solicitud_title',
                    'uri' => 'admin/emails/solicitudes',
        			
                )*/
           )
		);
        
        if (function_exists('group_has_role'))
		{
			if(group_has_role('emails', 'admin_organizaciones'))
			{
			    
				$info['sections']['organizaciones'] = array(
							'name' 	=> 'email:orgs',
							'uri' 	=> 'admin/emails/organizaciones',
							'shortcuts' => array(
									/*'create' => array(
										'name' 	=> 'streams:add_field',
										'uri' 	=> 'admin/avisos/fields/create',
										'class' => 'add'
										)*/
							)
				);
			}
		}
        
        
        return $info;
	}

	public function install()
	{
	    $this->dbforge->drop_table('emails');
        $this->dbforge->drop_table('chromebooks');
		$tables = array(
		    'emails'=>array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true,),
				'email' => array('type' => 'VARCHAR', 'constraint' => 100,),
                'org_path' => array('type' => 'VARCHAR', 'constraint' => 200,'null'=>true),
                'group' => array('type' => 'VARCHAR', 'constraint' => 200,'null'=>true),
                'table' => array('type' => 'VARCHAR', 'constraint' => 200,'null'=>true),
                'table_id' => array('type' => 'VARCHAR', 'constraint' => 200,'null'=>true),
                
                'given_name' => array('type' => 'VARCHAR', 'constraint' => 250,'null'=>true),
                'family_name' => array('type' => 'VARCHAR', 'constraint' => 250,'null'=>true),
                'full_name' => array('type' => 'VARCHAR', 'constraint' => 250,'null'=>true),
				'created_on' => array('type' => 'INT', 'constraint' => 11, 'null' => true,),
                'updated_on' => array('type' => 'INT', 'constraint' => 11, 'null' => true,),
              
                
                'data' => array('type' => 'TEXT','null'=>true),
				//'active' => array('type' => 'ENUM',  'constraint' => array('aviso'=>'aviso','memorandun'=>'memorandun'),),
				'active' => array('type' => 'INT', 'constraint' => 11, 'null' => true,'default'=>'0'),
                'syncronize' => array('type' => 'INT', 'constraint' => 11, 'null' => true,'default'=>'0'),
            ),
            'chromebooks'=>array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true,),
				'email' => array('type' => 'VARCHAR', 'constraint' => 100,),
                'serial' => array('type' => 'VARCHAR', 'constraint' => 200,'null'=>true),
                'created_on' => array('type' => 'INT', 'constraint' => 11, 'null' => true,),
            )
			
		);

        if ( ! $this->install_tables($tables))
		{
			return false;
		}
        return true;
        
		

		
	}

	public function uninstall()
	{
	  
        $this->dbforge->drop_table('emails');
		return true;
	}

	public function upgrade($old_version)
	{
		return true;
	}

}
?>