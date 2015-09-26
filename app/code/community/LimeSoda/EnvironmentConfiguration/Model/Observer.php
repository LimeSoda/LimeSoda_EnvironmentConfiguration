<?php

class LimeSoda_EnvironmentConfiguration_Model_Observer
{
    /**
     * @return Mage_Core_Model_Cookie
     */
    protected function getCoreCookieModel()
    {
        return Mage::getSingleton('core/cookie');
    }

    /**
     * Sets a cookie containing the current configured environment name
     *
     * @event controller_action_predispatch
     * @param Varien_Event_Observer $observer
     * @return LimeSoda_EnvironmentConfiguration_Model_Observer
     */
    public function controllerActionPredispatch(Varien_Event_Observer $observer)
    {
        if ($observer->getControllerAction() instanceof Mage_Core_Controller_Front_Action && !Mage::getStoreConfigFlag('admin/limesoda_environmentconfiguration/notice_bar_frontend')) {
            return $this;
        }
        
        /** @var LimeSoda_EnvironmentConfiguration_Helper_Current $helper */
        $helper = Mage::helper('limesoda_environmentconfiguration/current');
        try {
            $environmentLabel = $helper->getEnvironmentLabel();
            $this->getCoreCookieModel()->set('limesoda_environment', $helper->escapeHtml($environmentLabel), 60*60*24*30, '/', null, null, false);
            $this->getCoreCookieModel()->set('limesoda_environment_background_color', $helper->escapeHtml($helper->getSetting('background_color')), 60*60*24*30, '/', null, null, false);
            $this->getCoreCookieModel()->set('limesoda_environment_color', $helper->escapeHtml($helper->getSetting('color')), 60*60*24*30, '/', null, null, false);
        } catch (Exception $e) {
            $this->getCoreCookieModel()->set('limesoda_environment', $e->getMessage(), 60*60*24*30, '/', null, null, false);
        }

        return $this;
    }
}