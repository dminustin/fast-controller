<?php

declare(strict_types=1);

namespace FastController\Console;

use Illuminate\Console\Command;

class FastControllerGenerateCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fast-controller:create {path}';

    public function handle()
    {
        die("Works");
    }
}
