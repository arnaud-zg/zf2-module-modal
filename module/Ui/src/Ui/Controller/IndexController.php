<?php

/**
 * Index Controller
 *
 * @author arnaud
 */

namespace Ui\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
         return new ViewModel();
    }

}
