<?php

namespace LimeSoda\Environment\Configure\Ess\M2ePro;

use N98\Magento\Command\AbstractMagentoCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetChannelStatus extends AbstractMagentoCommand
{
    const EXTENSION_NAME = 'Ess_M2ePro';
    
    protected function configure()
    {
      $this
          ->setName('ls:env:configure:ess:m2epro:set-channel-status')
          ->addArgument('name', InputArgument::REQUIRED, 'Channel name')
          ->addArgument('status', InputArgument::REQUIRED, 'Channel status')
          ->setDescription('Sets the channel sattus')
          ->setHelp(
              <<<EOT
              Sets the channel status for Ess_M2ePro. The extension has to be installed and enabled.
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
            
           $name = strip_tags($input->getArgument('name'));
           $status = intval(strip_tags($input->getArgument('status')));
           
           \Mage::helper('M2ePro/Module')->getConfig()->setGroupValue('/component/' . $name . '/', 'mode', $status);
           
           $output->writeln("Set '" . $name . "' channel status."); 
        }
    }
} 