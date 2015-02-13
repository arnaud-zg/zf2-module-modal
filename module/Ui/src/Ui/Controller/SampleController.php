<?php

/**
 * Sample Controller
 *
 * @author arnaud
 */

namespace Ui\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Json\Json;
use Zend\View\Model\ViewModel;

class SampleController extends AbstractActionController {

    protected $request;
    private $route;
    private $db;

    public function onDispatch(\Zend\Mvc\MvcEvent $e) {
        $this->route = $this->getRequest()->getUri()->getPath();
        $this->db = $this->getServiceLocator()->get("db");
        return parent::onDispatch($e);
    }

    public function indexAction() {
         return new ViewModel();
    }

    public function confirmAction() {
        $modal = new ModalController($this, '/ui/sample/confirm');
        $modal->setFlashMessenger("L'action a bien été éffectuée");
        $modal->setTitle("Title");
        return $modal->render();
    }

    public function infoAction() {
        $modal = new ModalController($this, '/ui/sample/info');
        $data = array(
            "state_message" => 0,
        );
        $modal->setTitle("Title");
        $modal->setData($data);
        $modal->setButton($modal->data->btn);
        return $modal->render();
    }

    public function modalAction() {
        $form = new SampleForm();

        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
        $renderer->inlineScript()->appendFile('/js/core_modal.js', 'text/javascript');

        // Default Modal
        $modal_sample = array(
            "id" => "sample",
            'url_redir' => $this->getRequest()->getUri()->getPath(),
            'flash' => array("Sentence 1"),
        );

        // Override Modal
        $modal_override = array(
            "id" => "sample",
            'url_redir' => $this->getRequest()->getUri()->getPath(),
            'title' => "'Title Override'",
            'content' => "'Display some sentence'",
            'btn' => array("confirm" => "Ok", "noconfirm" => "Fermer"),
        );

        // Custom Modal
        $modal_custom = array(
            "id" => "sample",
            'url_redir' => $this->getRequest()->getUri()->getPath(),
            'url_info' => '/ui/sample/info',
            'url_confirm' => '/ui/sample/confirm',
            'btn' => array("confirm" => "Ok", "noconfirm" => "Fermer"),
        );


        return array(
            'modals' => array(
                'sample' => $modal_sample,
                'override' => $modal_override,
                'custom' => $modal_custom,
            ),
            'form' => $form,
        );
    }
}
