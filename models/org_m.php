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
class Org_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'default_email_orgs';
		
	}
 }
 ?>