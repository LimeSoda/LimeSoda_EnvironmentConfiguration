<?php

class LimeSoda_EnvironmentConfiguration_Adminhtml_LimeSoda_EnvironmentConfigurationController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('system');

        $environment = $this->getRequest()->getParam('env', false);

        if ($environment) {
            Mage::app()->getLayout()->getBlock('limesoda_environmentconfiguration.overview')->setEnvironment($environment);
        }

        $this->renderLayout();
    }
}
