<?php

namespace LimeSoda\Database;

use N98\Magento\Command\Database\AbstractDatabaseCommand;
use N98\Util\Console\Helper\DatabaseHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TruncateCommand extends AbstractDatabaseCommand
{
    protected function configure()
    {
        $this
            ->setName('db:truncate')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force')
            ->setDescription('Truncate database tables');

        $help = <<<HELP
The command prompts before truncating the tables in the database. If --force option is specified it
directly truncates the tables.
The configured user in app/etc/local.xml must have "TRUNCATE" privileges.
HELP;
        $this->setHelp($help);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->detectDbSettings($output);
        $dialog = $this->getHelperSet()->get('dialog');
        $dbHelper = $this->getHelper('database');

        if ($input->getOption('force')) {
            $shouldDrop = true;
        } else {
            $question = '<question>Really truncate tables in ' . $this->dbSettings['dbname'] . ' ?</question> ' .
                        '<comment>[n]</comment>: ';
            $shouldDrop = $dialog->askConfirmation($output, $question, false);
        }

        if ($shouldDrop) {
            $result = $dbHelper->getTables();
            $query = 'SET FOREIGN_KEY_CHECKS = 0; ';
            $count = 0;
            foreach ($result as $tableName) {
                $query .= 'TRUNCATE TABLE `'.$tableName.'`; ';
                $count++;
            }
            $query .= 'SET FOREIGN_KEY_CHECKS = 1;';
            $dbHelper->getConnection()->query($query);
            $output->writeln('<info>Truncated database tables</info> <comment>' . $count . ' tables truncated</comment>');
        }
    }
}
