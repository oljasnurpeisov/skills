<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveUnconfirmedEmailUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove unconfirmed users';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        User::where('created_at', '<=', Carbon::now()->subHours(72)->toDateTimeString())->where('email_verified_at', '=', null)->delete();

        return '';
    }
}
