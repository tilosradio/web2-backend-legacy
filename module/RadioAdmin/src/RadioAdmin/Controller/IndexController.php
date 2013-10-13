<?php
namespace RadioAdmin\Controller;

use Zend\View\Model\ViewModel;

class IndexController extends BaseController
{
    public function homeAction()
    {
        return new ViewModel();
    }
}