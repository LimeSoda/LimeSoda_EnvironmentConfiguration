<?php

namespace LimeSoda\Aoe\Scheduler;

use N98\Magento\Command\AbstractMagentoCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetJobStatus extends AbstractMagentoCommand
{
    protected function configure()
    {
        $this
            ->setName('ls:aoe:scheduler:set-job-status')
            ->addArgument('code', InputArgument::REQUIRED, 'Job code')
            ->addArgument('status', InputArgument::REQUIRED, 'Job status')
            ->setDescription('Sets the job status')
            ->setHelp(
                <<<EOT
                Sets the status of a job as used by Aoe_Scheduler >= 1.0.0.
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
            $name = strip_tags($input->getArgument('code'));
            $status = intval(strip_tags($input->getArgument('status')));

            if ($status !== 0 && $status !== 1) {
                throw new \Exception("Status has to be 0 or 1.");
            }

            $key = 'crontab/jobs/' . $name . '/is_active';
            \Mage::app()->getConfig()->saveConfig($key, $status);
            $output->writeln("Set '" . $name . "' job status.");
        }
    }
} 
