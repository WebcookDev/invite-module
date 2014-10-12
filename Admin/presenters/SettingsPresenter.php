<?php

    namespace AdminModule\InviteModule;

    /**
     * Description of SettingsPresenter
     * @author Jakub Šanda <sanda at webcook.cz>
     */
    class SettingsPresenter extends BasePresenter {



	protected function startup() {
	    parent::startup();
	}

	protected function beforeRender() {
	    parent::beforeRender();
	}

	public function actionDefault($idPage) {
	    
	}

	public function createComponentSettingsForm() {

	    $settings = array();
	    
	    $settings[] = $this->settings->get('Email subject', 'inviteModule', 'text');

	    return $this->createSettingsForm($settings);
	}


	
    }
    
