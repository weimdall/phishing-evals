<?php
namespace Dev\Command;

use Illuminate\Filesystem\Filesystem;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Boris\Boris;

class Tinker extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'tinker';
    protected static $defaultDescription = 'PHP Intactive shell';
    protected function configure(): void
    {
        // $this->addOption('app' , null, InputOption::VALUE_REQUIRED, 'Value for App name' );
        // $this->addOption('set-ver' , null,InputOption::VALUE_REQUIRED , 'Value for Version');
        // $this->addOption('default' , null,InputOption::VALUE_REQUIRED , 'Value for default page');
        // $this->addOption('test-mode' , null,InputOption::VALUE_REQUIRED , 'Value for test mode true or false');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
       
        $this->tink();

       return Command::SUCCESS;
    }
    protected function tink()
    {
        $fp = fopen("php://stdin", "r");
        $in = '';
        while($in != "quit") {
            echo "Ryu [".basename(dirname(dirname(__DIR__)))."] [$in] >>";
            $in=trim(fgets($fp));
            @eval($in);
            echo "\n";
            }
    }
   
}
