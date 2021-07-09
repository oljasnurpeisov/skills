<?php

namespace App\Console\Commands;

use App\Imports\OkedImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ParseOKED extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:oked';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Excel::import(new OkedImport(), public_path('oked.xlsx'));

    }
}
