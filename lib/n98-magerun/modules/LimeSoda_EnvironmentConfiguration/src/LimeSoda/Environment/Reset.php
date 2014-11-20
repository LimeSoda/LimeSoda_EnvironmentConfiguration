<?php

namespace LimeSoda\Environment;

use N98\Magento\Command\AbstractMagentoCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class Reset extends AbstractMagentoCommand
{
    protected $_operation = "reset";
    protected $_data = array('customers','reports','sales');

    protected function configure()
    {
      $this
          ->setName('ls:env:'.$this->_operation)
          ->addArgument('environment', InputArgument::REQUIRED, 'Identifier of the environment')
          ->addArgument('data', InputArgument::OPTIONAL, 'Data to remove: customers, reports, sales')
          ->setDescription('Reset (empty) customer, sales, reporting and logging tables in database');
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
          $toReset = $input->getArgument('data');

          if (isset($toReset)) {
              if (array_search($toReset, $this->_data)===false) {
                throw new \Exception("No data to reset '" . $toReset ."' provided.");
              }
            $this->_data = array($toReset);
          }

          $helper = \Mage::helper('limesoda_environmentconfiguration');
          
          // Deactivating auto-exiting after command execution
          $this->getApplication()->setAutoExit(false);

          foreach ($this->_data as $data) {
              foreach ($helper->getOperations($environment, $this->_operation, $data) as $operation) {
                  $input = new StringInput($operation);
                  $this->getApplication()->run($input, $output);
              }
          }
          
          // Reactivating auto-exiting after command execution
          $this->getApplication()->setAutoExit(true);
        }
    }

}
