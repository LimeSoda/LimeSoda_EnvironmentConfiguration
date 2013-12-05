<?php

namespace LimeSoda\Database;

use N98\Magento\Command\Database\AbstractDatabaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExecuteCommand extends AbstractDatabaseCommand
{
    protected function configure()
    {
        $this
            ->setName('db:execute')
            ->addArgument('statement', InputArgument::REQUIRED, 'SQL query')
            ->addOption('only-command', null, InputOption::VALUE_NONE, 'Print only mysql command. Do not execute')
            ->setDescription('Executes query on database defined in local.xml');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->detectDbSettings($output);

        $sql = $input->getArgument('statement');

        // dump data for all other tables
        $exec = 'mysql ' . $this->getMysqlClientToolConnectionString() . " -e '" . $sql . "'";

        if ($input->getOption('only-command')) {
            $output->writeln($exec);
        } else {
            exec($exec, $commandOutput, $returnValue);
            if ($returnValue > 0) {
                $output->writeln('<error>' . implode(PHP_EOL, $commandOutput) . '</error>');
            }
        }
    }

}