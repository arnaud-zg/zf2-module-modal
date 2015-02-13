<?php

/**
 * Modal Controller, Generate some modal by a simple way
 *
 * Works with Semantic UI
 * 
 * @author arnaud
 */

namespace Ui\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;

final class ModalController extends AbstractActionController {
    /*
     * Collect data from Ajax
     * @var array
     * @author arnaud
     */

    public $data = null;

    /*
     * Path of the overriden view
     * @var string
     * @author arnaud
     */
    public $pathView = '/ui/modal/info';

    /*
     * Current service translator
     * @var Translator
     * @author arnaud
     */
    private $translator = null;

    /*
     * Object origin called
     * @var object
     * @author arnaud
     */
    private $objectHandle = null;

    /*
     * Path of the main view
     * @var string
     * @author arnaud
     */
    private $layoutPath = 'layout/modal';

    /*
     * Title of the modal
     * @var strng
     * @author arnaud
     */
    private $title = "Erreur";

    /*
     * Buttons of the modal
     * @var array
     * @author arnaud
     */
    private $button = array('noconfirm' => 'Fermer');

    /*
     * This array will be bind with the child view
     * @var array
     * @author arnaud
     */
    private $dataChild = array();

    const ERROR = -1;
    const VALID = 0;

    function __construct($objectHandle = null, $pathView = null) {
        if ($objectHandle != null) {
            $this->pathView = $pathView;
            $this->setHandle($objectHandle);
            $this->collectdata();
        }
    }

    /*
     * Dispatch, setting the translator
     *
     * @param MvcEvent Zf2 $e
     * @return MvcEvent onDispatch
     * @author arnaud
     */

    public function onDispatch(\Zend\Mvc\MvcEvent $e) {
        $this->translator = $this->getServiceLocator()->get('translator');
        return parent::onDispatch($e);
    }

    /*
     * Set title private variable
     *
     * @param string $title
     * @return nothing
     * @author arnaud
     */

    public function setTitle($title) {
        if ($this->title != $title)
            $this->title = $title;
    }

    /*
     * Set button private variable
     *
     * @param string $button
     * @return nothing
     * @author arnaud
     */

    public function setButton($button) {
        if ($this->button != $button)
            $this->button = $button;
    }

    /*
     * Set message private variable
     *
     * @param string $message
     * @return nothing
     * @author arnaud
     */

    public function setData($data) {
        if ($this->dataChild != $data) {
            if (is_string($data))
                $this->dataChild['custom_message'] = $this->translator->translate($data);
            else if (is_array($data))
                $this->dataChild = array_merge($this->dataChild, $data);
        }
    }

    /*
     * Add a string in a array
     *
     * @param string $string Content of flashMessenger
     * @param string $color Color of flashMessenger background
     * @return nothing
     * @author arnaud
     */

    public function setFlashMessenger($string = null, $color = "green") {
        $this->setHandle($this);
        $this->objectHandle->flashMessenger()->setNamespace($color)->addMessage($this->translator->translate($string));
    }

    /*
     * Get object of main process
     *
     * @param object $objectHandle
     * @return Nothing
     * @author arnaud
     */

    public function setHandle(&$objectHandle) {
        if ($this->objectHandle == null)
            if ($this->objectHandle != $objectHandle) {
                $this->objectHandle = &$objectHandle;
                $this->translator = $this->objectHandle->getServiceLocator()->get('translator');
            }
    }

    /*
     * Change the layout
     *
     * @param string $layoutPath
     * @return Nothing
     * @author arnaud
     */

    private function setLayoutPath($layoutPath) {
        $this->layoutPath = $layoutPath;
    }

    /*
     * Get Default and Custom Title
     *
     * @param array data
     * @param string icon
     * @param string title for customization
     * @return array for title
     * @author arnaud
     */

    public function getTitle($icon) {
        return array(
            "icon" => $icon,
            "string" => $this->translator->translate($this->title),
        );
    }

    /*
     * Get Default Value for error modal
     *
     * @param Nothing
     * @return array for default error message
     * @author arnaud
     */

    public function getErrorMessage() {
        return array(
            'custom_message' => $this->translator->translate("Une erreur s'est produite."),
        );
    }

    /*
     * Get Post Value
     *
     * @param Nothing
     * @return bool state
     * @author arnaud
     */

    public function collectdata() {
        $request = ($this->objectHandle == null) ? $this->getRequest() : $this->objectHandle->getRequest();
        if ($request->isPost() && $request->isXmlHttpRequest()) {
            $this->data = (object) $request->getPost();
            return true;
        }
        $this->setHandle($this);
        $this->objectHandle->response->setStatusCode(403);
        return false;
    }

    /*
     * Set variables in a view
     *
     * @param array $varsLayout
     * @return Nothing
     * @author arnaud
     */

    private function formattingValueModal($varsLayout) {
        $this->setHandle($this);
        $this->objectHandle->layout()->setVariables($varsLayout);
    }

    /*
     * Generate an error modal
     *
     * @param nothing
     * @return JsonModel
     * @author arnaud
     */

    public function errorAction() {
        $content = array();
        $view = "error/404";
        $this->layout("layout/modal");
        $viewModel = new ViewModel();
        $request = $this->getRequest();
        if ($request->isPost() && $request->isXmlHttpRequest()) {
            $content = $request->getPost();
            switch ($content['error']) {
                case 401:
                    $view = "error/401";
                    break;
                case 403:
                    $view = "error/403";
                    break;
                case 404:
                    $view = "error/404";
                    break;
                case 500:
                    $view = "error/500";
                    break;
            }
        } else {
            $this->response->setStatusCode(403);
        }
        $viewModel->setTemplate($view);
        $this->layout()->setVariables(
                array(
                    "id" => $content["id"],
                    "title" => array("icon" => "warning sign", "string" => "Erreur"),
                    "button" => array("noconfirm" => "Fermer"),
                    "content" => $this->getServiceLocator()->get('ViewRenderer')->render($viewModel),
                )
        );
        $htmlOutput = $this->getServiceLocator()->get('ViewRenderer')->render($this->layout());
        return $resultJson = new JsonModel(array(
            'action' => self::VALID,
            'html' => $htmlOutput,
        ));
    }

    /*
     * Default Confirm View
     *
     * @param Nothing
     * @return JSON Data
     * @author arnaud
     */

    public function confirmAction() {
        $this->pathView = '/ui/modal/confirm';
        if ($this->collectdata() == false)
            return false;
        $this->setFlashMessenger("L'action a bien été éffectuée");
        if (isset($this->data->flash))
            foreach ($this->data->flash as $flash)
                $this->setFlashMessenger($flash);
        return $this->render();
    }

    /*
     * Default Info View
     *
     * @param Nothing
     * @return JSON Data
     * @author arnaud
     */

    public function infoAction() {
        $this->pathView = '/ui/modal/info';
        if ($this->collectdata() == false)
            return false;
        if (isset($this->data->title))
            $this->setTitle($this->data->title);
        if (isset($this->data->btn))
            $this->setButton($this->data->btn);
        if (isset($this->data->content))
            $this->setData($this->data->content);
        return $this->render();
    }

    /*
     * Modal Generator
     *
     * @param string $pathLayout Override the layout
     * @return JsonModel
     * @author arnaud
     */

    public function render($pathLayout = "layout/modal", $icon = "warning sign") {
        $action = self::ERROR;
        $this->setHandle($this);
        $this->objectHandle->layout($pathLayout);
        $action = ($this->data != null) ? self::VALID : $action;
        $this->title = $this->getTitle($icon);
        if (!isset($this->dataChild) ||
                (isset($this->data->state_message) && ($this->data->state_message < 0))) {
            $this->dataChild = $this->getErrorMessage();
            $this->button = array('noconfirm' => 'Fermer');
            $this->pathView = '/ui/modal/error';
        }
        $this->dataChild = array_merge((array) $this->data, $this->dataChild);
        $dataParent = array_merge((array) $this->data, array(
            "child_page" => $this->dataChild,
            "title" => $this->title,
            "button" => $this->button,
        ));
        $this->formattingValueModal($dataParent);
        $viewModel = new ViewModel($this->dataChild);
        $viewRender = $this->objectHandle->getServiceLocator()->get('ViewRenderer');
        $viewModel->setTemplate($this->pathView);
        $this->objectHandle->layout()->setVariable("content", $viewRender->render($viewModel));
        $htmlOutput = $this->objectHandle->getServiceLocator()->get('ViewRenderer')->render($this->objectHandle->layout());
        return $resultJson = new JsonModel(array(
            'action' => $action,
            'html' => $htmlOutput,
        ));
    }

}
