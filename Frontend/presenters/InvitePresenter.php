<?php

namespace FrontendModule\InviteModule;

use Nette\Application\UI;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;

/**
 * Description of InvitePresenter
 *
 * @author Jakub Šanda <sanda at webcook.cz>
 */
class InvitePresenter extends \FrontendModule\BasePresenter{
		
	private $elements;
	
	protected function startup() {
		parent::startup();

	}

	protected function beforeRender() {
		parent::beforeRender();
		
	}
	
	public function actionDefault($id){

	}
	
	public function createComponentForm($name, $context = null, $fromPage = null){
		
		if($context != null){
			
			$form = new UI\Form();
		
			$form->getElementPrototype()->action = $context->link('default', array(
				'path' => $fromPage->getPath(),
				'abbr' => $context->abbr,
				'do' => 'form-submit'
			));

			$form->setTranslator($context->translator);
			$form->setRenderer(new BootstrapRenderer);
			
			$form->getElementPrototype()->class = 'form-horizontal contact-agent-form';
			
			$form->addHidden('redirect')->setDefaultValue(true);
		}else{
			$form = $this->createForm('form-submit', 'default', $context);
			$form->addHidden('redirect')->setDefaultValue(false);
		}
		
		$form->addText('emailFrom', 'Váš email')
				->setRequired()
				->addRule(UI\Form::EMAIL);
		$form->addText('emailTo', 'Email příjemce')
				->setRequired()
				->addRule(UI\Form::EMAIL);
		
		$form['emailFrom']->getControlPrototype()->addClass('form-control');
		$form['emailTo']->getControlPrototype()->addClass('form-control');
		
		$form->addSubmit('send', 'Send')->setAttribute('class', 'btn btn-primary btn-lg');
		$form->onSuccess[] = callback($this, 'formSubmitted');
		
		return $form;
	}
	
	public function formSubmitted($form){
		
		$values = $form->getValues();

		if (!array_key_exists('realHash', $_POST) || \WebCMS\Helpers\SystemHelper::rpHash($_POST['real']) == $_POST['realHash']) {
			
			$redirect = $values->redirect;
			unset($values->redirect);
			
			$template = $this->createTemplate();
			$template->values = $values;
			$template->setFile('../app/templates/invite-module/Invite/email.latte');
			
			$data = array();
			
			$mail = new \Nette\Mail\Message;
			$mail->addTo($values->emailTo);

			if($this->getHttpRequest()->url->host !== 'localhost') $mail->setFrom($this->settings->get('Info email', 'basic')->getValue());
			else $mail->setFrom('no-reply@test.cz');

			$mail->setSubject('Pozvánka na pivofest 2014');
			$mail->setHtmlBody($template);
			$mail->send();

			$this->flashMessage('Data has been sent.', 'success');

			if(!$redirect){
				$this->redirect('default', array(
					'path' => $this->actualPage->getPath(),
					'abbr' => $this->abbr
				));
			}else{
				$httpRequest = $this->getContext()->getService('httpRequest');

				$url = $httpRequest->getReferer();
				$url->appendQuery(array(self::FLASH_KEY => $this->getParam(self::FLASH_KEY)));

				$this->redirectUrl($url->absoluteUrl);
			}
		
		} else {
			
			$this->flashMessage('Wrong protection code.', 'danger');	
			$httpRequest = $this->getContext()->getService('httpRequest');

			$url = $httpRequest->getReferer();
			$url->appendQuery(array(self::FLASH_KEY => $this->getParam(self::FLASH_KEY)));

			$this->redirectUrl($url->absoluteUrl);
			
	    }
	}
	
	public function renderDefault($id){
		
		$this->template->form = $this->createComponentForm('form', $this, $this->actualPage);
		$this->template->id = $id;
	}

	public function inviteBox($context, $fromPage){
		
		$template = $context->createTemplate();
		$template->form = $this->createComponentForm('form', $context, $fromPage);
		$template->setFile('../app/templates/invite-module/Invite/boxes/form.latte');
	
		return $template;
	}
}
