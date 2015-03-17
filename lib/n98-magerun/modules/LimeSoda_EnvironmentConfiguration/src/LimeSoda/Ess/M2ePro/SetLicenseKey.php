<?php

namespace LimeSoda\Environment\Configure\Ess\M2ePro;

use N98\Magento\Command\AbstractMagentoCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetLicenseKey extends AbstractMagentoCommand
{
    const EXTENSION_NAME = 'Ess_M2ePro';
    
    protected function configure()
    {
      $this
          ->setName('ls:env:configure:ess:m2epro:set-license-key')
          ->addArgument('key', InputArgument::REQUIRED, 'Ess_M2ePro license key')
          ->setDescription('Set the Ess_M2ePro license key')
          ->setHelp(
              <<<EOT
              Sets the license key for Ess_M2ePro. The extension has to be installed and enabled.
EOT
              );
          
      ;
    }

   /**
    * @param \Symfony\Component\Console\Input\InputInterface $input
    * @param \Symfony\Component\Console\Output\OutputInterface $output
    * @return int|void
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->detectMagento($output);
        if ($this->initMagento()) {
            if (\Mage::helper('core')->isModuleEnabled(self::EXTENSION_NAME) === false) {
                throw new \Exception("Extension '" . self::EXTENSION_NAME ."' is not installed.");
            }

            $key = strip_tags($input->getArgument('key'));

            \Mage::helper('M2ePro/Primary')->getConfig()->setGroupValue(
                '/'.\Mage::helper('M2ePro/Module')->getName().'/license/', 'key',(string)$key
            );

            \Mage::getModel('M2ePro/Servicing_Dispatcher')->processTasks(
                array(\Mage::getModel('M2ePro/Servicing_Task_License')->getPublicNick())
            );

            \Mage::helper('M2ePro/data_cache')->removeValue('M2ePro/Config_Primary_data');

            $output->writeln("Set license key '" . $key . "'.");
        }
    }
}
