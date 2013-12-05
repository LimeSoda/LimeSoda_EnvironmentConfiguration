<?php

namespace LimeSoda\Environment;

use N98\Magento\Command\AbstractMagentoCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class Configure extends AbstractMagentoCommand
{
    protected function configure()
    {
      $this
          ->setName('ls:env:configure')
          ->addArgument('environment', InputArgument::REQUIRED, 'Identifier of the environment')
          ->setDescription('Update settings and data for environment')
      ;
    }

   /**
    * @param \Symfony\Component\Console\Input\InputInterface $input
    * @param \Symfony\Component\Console\Output\OutputInterface $output
    * @return int|void
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->detectMagento($output, true);
        if ($this->initMagento()) {
          
          $environment = $input->getArgument('environment');
          
          // Deactivating auto-exiting after command execution
          $this->getApplication()->setAutoExit(false);
          
          $variables = $this->getVariables($environment);
          $search = array_keys($variables);
          $replace = array_values($variables);
          
          foreach ($this->getCommands($environment) as $command) {
              $value = str_replace($search, $replace, strval($command));
              $input = new StringInput($value);
              $this->getApplication()->run($input, $output);
          }
          
          // Reactivating auto-exiting after command execution
          $this->getApplication()->setAutoExit(true);
        }
    }

    /**
     * Returns the commands from Magento configuration XML.
     * 
     * @param string $environment
     * @return array
     */
    protected function getCommands($environment)
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
    protected function getEnvironmentConfig($environment)
    {
        $config = \Mage::getConfig()->getNode('global/build/environments/' . $environment);
        
        if ($config === false) {
            throw new \InvalidArgumentException('Environment ' . $environment . ' isn\'t specified in XML.');
        }
        
        return $config;
    }
    
    /**
     * Returns the variables from Magento configuration XML.
     * 
     * @param string $environment
     * @return array
     */
    protected function getVariables($environment)
    {
        $config = $this->getEnvironmentConfig($environment);
        
        // get parent variables (if they exist)
        if ($parent = $config->getAttribute('parent')) {
            $result = $this->getVariables($parent);
        } else {
            $result = array();
        }
        
        $variables = $config->descend('variables');
        
        if ($variables === false) {
            return $result;
        }

        // get commands
        foreach ($variables->children() as $variable) {
            $result['${' . $variable->getName() . '}'] = strval($variable);
        }
        
        return $result;
    }
}