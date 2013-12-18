<?php

class LimeSoda_EnvironmentConfiguration_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @var string 
     */
    const XML_PATH_ENVIRONMENTS = 'global/limesoda/environments';
    
    /**
     * Cache for getVariables() calls.
     * 
     * @var array
     */
    protected $_variablesCache = array();
    
    /**
     * Returns the commands from Magento configuration XML.
     * 
     * @param string $environment
     * @return array
     */
    public function getCommands($environment)
    {
        $config = $this->getEnvironmentConfig($environment);
        
        // get parent commands (if they exist)
        if ($parent = $config->getAttribute('parent')) {
            $result = $this->getCommands($parent);
        } else {
            $result = array();
        }
        
        $commands = $config->descend('commands');
        
        if ($commands === false) {
            return $result;
        }

        // get commands
        foreach ($commands->children() as $key => $value) {
            $result[$key] = $value;
        }
        
        return $result;
    }
    
    /**
     * Returns the environment configuration
     * 
     * @param string $environment
     * @return Mage_Core_Model_Config_Element
     */
    public function getEnvironmentConfig($environment)
    {
        $config = Mage::getConfig()->getNode(self::XML_PATH_ENVIRONMENTS . '/' . $environment);
        
        if ($config === false) {
            throw new InvalidArgumentException('Environment ' . $environment . ' isn\'t specified in XML.');
        }
        
        return $config;
    }
    
    /**
     * Returns the variables from Magento configuration XML.
     * 
     * @param string $environment
     * @return array
     */
    public function getVariables($environment)
    {
        if (!array_key_exists($environment, $this->_variablesCache)) {
            $config = $this->getEnvironmentConfig($environment);
            
            // get parent variables (if they exist)
            if ($parent = $config->getAttribute('parent')) {
                $result = $this->getVariables($parent);
            } else {
                $result = array();
            }
            
            $variables = $config->descend('variables');
            
            if ($variables !== false) {
                // get commands
                foreach ($variables->children() as $variable) {
                    $result['${' . $variable->getName() . '}'] = strval($variable);
                }
            }
            
            $this->_variablesCache[$environment] = $result;
        }

        return $this->_variablesCache[$environment];
    }
    
    /**
     * Returns the value of the variable in the given environment.
     * 
     * @param string $environment
     * @param string $variable
     * @return string|null
     */
    public function getValue($environment, $variable)
    {
        $key = '${' . $variable . '}';
        $variables = $this->getVariables($environment);
        
        if (array_key_exists($key, $variables)) {
            return $variables[$key];
        }
        
        return null;
    }
    
}
