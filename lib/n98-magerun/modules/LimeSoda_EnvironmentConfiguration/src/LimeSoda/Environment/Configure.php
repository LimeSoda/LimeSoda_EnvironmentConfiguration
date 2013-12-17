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
          $helper = \Mage::helper('limesoda_environmentconfiguration');
          
          // Deactivating auto-exiting after command execution
          $this->getApplication()->setAutoExit(false);
          
          $variables = $helper->getVariables($environment);
          $search = array_keys($variables);
          $replace = array_values($variables);
          
          foreach ($helper->getCommands($environment) as $command) {
              $value = str_replace($search, $replace, strval($command));
              $input = new StringInput($value);
              $this->getApplication()->run($input, $output);
          }
          
          // Reactivating auto-exiting after command execution
          $this->getApplication()->setAutoExit(true);
        }
    }

}