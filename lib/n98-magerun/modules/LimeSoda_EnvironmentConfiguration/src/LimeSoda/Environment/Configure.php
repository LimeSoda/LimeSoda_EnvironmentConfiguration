<?php

namespace LimeSoda\Environment;

use N98\Magento\Command\AbstractMagentoCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class Configure extends AbstractMagentoCommand
{
    protected function configure()
    {
      $this
          ->setName('ls:env:configure')
          ->addArgument('environment', InputArgument::REQUIRED, 'Identifier of the environment')
          ->addOption('override', null, InputOption::VALUE_REQUIRED  | InputOption::VALUE_IS_ARRAY, 'pair of variable name and value overriding the values in the configuration, e.g. --variable="arg1=value1"')
          ->setDescription('Update settings and data for environment');
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
            $helper = \Mage::helper('limesoda_environmentconfiguration');

            // Deactivating auto-exiting after command execution
            $this->getApplication()->setAutoExit(false);

            foreach ($helper->getParsedCommands($environment, $this->getVariableOverrides($input)) as $command) {
                $input = new StringInput($command);
                $this->getApplication()->run($input, $output);
            }

            // Reactivating auto-exiting after command execution
            $this->getApplication()->setAutoExit(true);
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @return array
     */
    protected function getVariableOverrides(InputInterface $input)
    {
        $result = array();

        foreach ($input->getOption('override') as $keyValueString) {
            $keyValuePair = explode('=', $keyValueString);
            if (count($keyValuePair) === 2) {
                $result[$keyValuePair[0]] = $keyValuePair[1];
            }
        }

        return $result;
    }
}
