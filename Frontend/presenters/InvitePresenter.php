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
		
		$form->addSubmit('send', 'Send')->setAttribute('class', 'btn btn-primary btn-lg');
		$form->onSuccess[] = callback($this, 'formSubmitted');
		
		return $form;
	}
	
	public function formSubmitted($form){
		
		$values = $form->getValues();

		if (\WebCMS\Helpers\SystemHelper::rpHash($_POST['real']) == $_POST['realHash']) {
			
			/*$data = array();

			$redirect = $values->redirect;
			unset($values->redirect);

			$emailContent = '';
			foreach($values as $key => $val){
				$element = $this->elementRepository->findOneByName($key);

				if($element->getType() === 'checkbox'){
					$value = $val ? $this->translation['Yes'] : $this->translation['No'];
				}else{
					$value = $val;
				}

				$data[$element->getLabel()] = $value;

				$emailContent .= $element->getLabel() . ': ' . $value . '<br />';
			}

			$entry = new \WebCMS\FormModule\Doctrine\Entry;
			$entry->setDate(new \DateTime);
			$entry->setPage($this->actualPage);
			$entry->setData($data);

			$this->em->persist($entry);
			$this->em->flush();

			// info email
			$infoMail = $this->settings->get('Info email', 'basic', 'text')->getValue();
			$parsed = explode('@', $infoMail);

			$mailBody = $this->settings->get('Info email', 'formModule' . $this->actualPage->getId(), 'textarea')->getValue();
			$mailBody = \WebCMS\Helpers\SystemHelper::replaceStatic($mailBody, array('[FORM_CONTENT]'), array($emailContent));

			$mail = new \Nette\Mail\Message;
			$mail->addTo($infoMail);

			if($this->getHttpRequest()->url->host !== 'localhost') $mail->setFrom('no-reply@' . $this->getHttpRequest()->url->host);
			else $mail->setFrom('no-reply@test.cz'); // TODO move to settings

			$mail->setSubject($this->settings->get('Info email subject', 'formModule' . $this->actualPage->getId(), 'text')->getValue());
			$mail->setHtmlBody($mailBody);
			$mail->send();*/

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

	public function formBox($context, $fromPage){
		
		$template = $context->createTemplate();
		$template->form = $this->createComponentForm('form',$context, $fromPage);
		$template->setFile('../app/templates/invite-module/Invite/boxes/form.latte');
	
		return $template;
	}
}
