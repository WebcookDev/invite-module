<?php

namespace WebCMS\InviteModule;

/**
 * Description of Invite
 *
 * @author Jakub Šanda <sanda at webcook.cz>
 */
class Invite extends \WebCMS\Module {
	
	protected $name = 'Invite';
	
	protected $author = 'Jakub Šanda';
	
	protected $presenters = array(
		array(
			'name' => 'Invite',
			'frontend' => TRUE,
			'parameters' => FALSE
			),
		array(
			'name' => 'Settings',
			'frontend' => FALSE
			)
	);
	
	protected $params = array(
		
	);
	
	public function __construct(){
		$this->addBox('Invite box', 'Invite', 'inviteBox');
	}
	
}