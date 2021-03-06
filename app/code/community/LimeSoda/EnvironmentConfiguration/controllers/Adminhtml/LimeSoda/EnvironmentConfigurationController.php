<?php

class LimeSoda_EnvironmentConfiguration_Adminhtml_LimeSoda_EnvironmentConfigurationController extends Mage_Adminhtml_Controller_Action
{
    /**
     * check against ACL
     *
     * @return bool - access is allowed
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/limesoda_environmentconfiguration');
    }

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
