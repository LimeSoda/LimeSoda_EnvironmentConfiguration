<?php

class LimeSoda_EnvironmentConfiguration_Helper_Current extends Mage_Core_Helper_Abstract
{
    /**
     * @var string
     */
    const ENVIRONMENT_NAME_XML_PATH = 'global/limesoda/environment/name';
    const ENVIRONMENT_LABEL_XML_PATH = 'global/limesoda/environments/%s/label';

    const EXCEPTION_ENVIRONMENT_NOT_SET = 'Please specify an environment';

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
            $node = Mage::getConfig()->getNode(self::ENVIRONMENT_NAME_XML_PATH);

            if (!$node OR ($environment = trim($node->__toString())) == '') {
                throw new InvalidArgumentException(self::EXCEPTION_ENVIRONMENT_NOT_SET) ;
            }

            $this->_environment = $environment;
        }
        return $this->_environment;
    }
    
    public function getEnvironmentLabel()
    {
        $environmentName = $this->getEnvironmentName();
        $labelNode = Mage::getConfig()->getNode(sprintf(self::ENVIRONMENT_LABEL_XML_PATH, $environmentName));

        if ($labelNode AND ($label = trim($labelNode->__toString())) != '') {
            return sprintf('%s [%s]', $label, $environmentName);
        }

        return $environmentName;
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