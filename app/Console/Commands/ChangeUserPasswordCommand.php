<?php

namespace App\Console\Commands;

use App\Exceptions\User\ChangeUserPasswordException;
use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class ChangeUserPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'candydate:user:password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user password';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->addOption('username', 'u', InputOption::VALUE_REQUIRED);
    }

    public function handle()
    {
        try {
            $username = $this->input->getOption('username');
            $user = User::query()->where('username', '=', $username)->firstOrFail();
            $password = $this->ask('Type in the new plain password:');
            User::changeUserPassword(['id' => $user->id, 'password' => $password]);
            $this->output->success("Password for user {$username} changed sucessfully");
        } catch (ChangeUserPasswordException $e) {
            $this->output->error($e->getMessage());
        }
    }
}
