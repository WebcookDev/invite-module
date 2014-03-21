<?php

namespace AdminModule\InviteModule;

/**
 * Description of BasePresenter
 *
 * @author Jakub Šanda <sanda at webcook.cz>
 */
class BasePresenter extends \AdminModule\BasePresenter {
	
	protected $repository;
	
	protected $elementRepository;
	
	protected function startup() {
		parent::startup();
		
		$this->repository = $this->em->getRepository('WebCMS\FormModule\Doctrine\Entry');
		$this->elementRepository = $this->em->getRepository('WebCMS\FormModule\Doctrine\Element');
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