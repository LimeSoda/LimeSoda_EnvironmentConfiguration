<?php

class LimeSoda_EnvironmentConfiguration_Helper_Current extends Mage_Core_Helper_Abstract
{
	/**
	 * @var string
	 */
	const ENVIRONMENT_NAME_XML_PATH = 'global/limesoda/environment/name';

	/**
	 * @var string
	 */
	protected $_environment = null;

	/**
	 * Returns the environment the shop runs in.
	 *
	 * @return string
	 */
	public function getEnvironmentName()
	{
		if ($this->_environment === null) {
			$environment = trim(Mage::getConfig()->getNode(self::ENVIRONMENT_NAME_XML_PATH)->__toString());

			if ($environment === '') {
				throw new InvalidArgumentException('Please specify an environment');
			}

			$this->_environment = $environment;
		}
		return $this->_environment;
	}

	/**
	 * Returns the setting for the current environment.
	 *
	 * @param string $name
	 * @return string|null
	 */
	public function getSetting($name)
	{
		return Mage::helper('limesoda_environmentconfiguration')->getSetting($this->getEnvironmentName(), $name);
	}

	/**
	 * Returns the value defined in the environment configuration XML for the current environment.
	 *
	 * @param string $variable
	 * @return string|null
	 */
	public function getValue($variable)
	{
		return Mage::helper('limesoda_environmentconfiguration')->getValue($this->getEnvironmentName(), $variable);
	}
}
