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
     * @event adminhtml_controller_action_predispatch_start
     * @param Varien_Event_Observer $observer
     * @return LimeSoda_EnvironmentConfiguration_Model_Observer
     */
    public function adminhtmlControllerActionPredispatchStart(Varien_Event_Observer $observer)
    {
        /** @var LimeSoda_EnvironmentConfiguration_Helper_Current $helper */
        $helper = Mage::helper('limesoda_environmentconfiguration/current');
        try {
            $environment = $helper->getEnvironmentName();
            $this->getCoreCookieModel()->set('limesoda_environment', $helper->escapeHtml($environment), 60*60*24*30, '/', null, null, false);
            $this->getCoreCookieModel()->set('limesoda_environment_background_color', $helper->escapeHtml($helper->getSetting('background_color')), 60*60*24*30, '/', null, null, false);
            $this->getCoreCookieModel()->set('limesoda_environment_color', $helper->escapeHtml($helper->getSetting('color')), 60*60*24*30, '/', null, null, false);
        } catch (Exception $e) {
            $this->getCoreCookieModel()->set('limesoda_environment', $e->getMessage(), 60*60*24*30, '/', null, null, false);
        }

        return $this;
    }
}