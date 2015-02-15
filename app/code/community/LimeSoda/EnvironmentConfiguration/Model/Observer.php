<?php

class LimeSoda_EnvironmentConfiguration_Model_Observer
{
	/**
	 * Sets a cookie containing the current configured environment name
	 *
	 * @event adminhtml_controller_action_predispatch_start
	 * @param Varien_Event_Observer $observer
	 * @return LimeSoda_EnvironmentConfiguration_Model_Observer
	 */
	public function adminhtmlControllerActionPredispatchStart(Varien_Event_Observer $observer)
	{
		$helper = Mage::helper('limesoda_environmentconfiguration/current');
		$environment = $helper->getEnvironmentName();
		Mage::getModel('core/cookie')->set('limesoda_environment', $helper->escapeHtml($environment), 60*60*24*30, '/', null, null, false);
		Mage::getModel('core/cookie')->set('limesoda_environment_background_color', $helper->escapeHtml($helper->getSetting('background_color')), 60*60*24*30, '/', null, null, false);
		Mage::getModel('core/cookie')->set('limesoda_environment_color', $helper->escapeHtml($helper->getSetting('color')), 60*60*24*30, '/', null, null, false);

		return $this;
	}
}
