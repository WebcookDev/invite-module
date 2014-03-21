<?php

namespace AdminModule\InviteModule;

/**
 * Description of BasePresenter
 *
 * @author Jakub Šanda <sanda at webcook.cz>
 */
class BasePresenter extends \AdminModule\BasePresenter {
	
	
	protected function startup() {
		parent::startup();
	}

	protected function beforeRender() {
		parent::beforeRender();
		
	}
	
	public function actionDefault($idPage){

	}
	
	public function renderDefault($idPage){
		$this->reloadContent();
		
		$this->template->idPage = $idPage;
	}
}