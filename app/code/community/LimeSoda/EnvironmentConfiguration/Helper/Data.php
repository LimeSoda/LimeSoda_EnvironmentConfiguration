<?php

class LimeSoda_EnvironmentConfiguration_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @var string
     */
    const XML_PATH_ENVIRONMENTS = 'global/limesoda/environments';

    /**
     * Command stages/phases.
     *
     * @var array
     */
    protected $_commandStages = array('pre_configure', 'commands', 'post_configure');

    /**
     * Cache for getVariables() calls.
     *
     * @var array
     */
    protected $_variablesCache = array();

    /**
     * Processes the node 'command'.
     *
     * @param Mage_Core_Model_Config_Element $config
     * @param array $result
     * @param string $stage Command stage/phase
     * @return array
     */
    protected function _getCustomCommands(Mage_Core_Model_Config_Element $config, array $result, $stage = 'commands')
    {
        $commands = $config->descend($stage);

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
     * Returns the commands from Magento configuration XML.
     *
     * @param string $environment
     * @param string $stage Command stage/phase
     * @return array
     */
    public function getCommands($environment, $stage = 'commands')
    {
        $config = $this->getEnvironmentConfig($environment);

        // get parent commands (if they exist)
        if ($parent = $config->getAttribute('parent')) {
            $result = $this->getCommands($parent, $stage);
        } else {
            $result = array();
        }

        $result = $this->_getCustomCommands($config, $result, $stage);

        if ($stage === 'commands') {
            $result = Mage::getModel('limesoda_environmentconfiguration/systemConfiguration')->getCommands($config, $result);
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
     * Returns the specified environments
     *
     * @return array
     */
    public function getEnvironments()
    {
        $result = array();
        $environments = Mage::getConfig()->getNode(self::XML_PATH_ENVIRONMENTS);

        if ($environments === false) {
            return $result;
        }

        foreach ($environments->children() as $name => $config) {
            $result[] = $name;
        }

        return $result;
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
     * Returns the commands from Magento configuration XML parsed ready for execution.
     *
     * @param string $environment
     * @return array
     */
    public function getParsedCommands($environment)
    {
        $result = array();

        $variables = $this->getVariables($environment);
        $search = array_keys($variables);
        $replace = array_values($variables);

        foreach($this->_commandStages as $stage) {
            foreach ($this->getCommands($environment, $stage) as $command) {
                $result[] = str_replace($search, $replace, strval($command));
            }
        }

        return $result;
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

    /**
     * Returns the operations from Magento configuration XML.
     *
     * @param string $environment
     * @return array
     */
    public function getOperations($environment, $operation, $data)
    {
        $config = $this->getEnvironmentConfig($environment);
        // get parent commands (if they exist)
        if ($parent = $config->getAttribute('parent')) {
            $result = $this->getOperations($parent, $operation, $data);
        } else {
            $result = array();
        }

        $operations = $config->descend('operations/'.$operation.'/'.$data);

        if ($operations === false) {
            return $result;
        }

        // get commands
        foreach ($operations->children() as $key => $value) {
            $result[$key] = $value;
        }

        return $result;
    }

}
