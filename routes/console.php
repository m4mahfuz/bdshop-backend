<?php

use App\Models\Admin;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('create:superadmin', function () {
    if (Admin::count()) {
        $this->error(' Warning ');
        $this->info(' Super Admin already exists! ');
        return;
    }

    $bar = $this->output->createProgressBar(5);
    $bar->start();

    $name = $this->ask('1. What is your name?');
    $bar->advance();
    $this->newLine();
    $phone = $this->ask('2. What is your phone?');
    $bar->advance();
    $this->newLine();
    $email = $this->ask('3. What is your email?');
    $bar->advance();
    $this->newLine();
    $password = $this->secret('4. What is the password?');
    $bar->advance();
    $this->newLine();
    if ($this->confirm('Do you wish to continue?')) {

        try {
            $user = Admin::create(
                [
                    'name' => $name,
                    'phone' => $phone,
                    'email' => $email,
                    'email_verified_at' => \Carbon\Carbon::now(),
                    'active' => true,
                    'type_id' => 1,
                    'password' => Hash::make($password),
                ]
            );
            $bar->advance();
            $this->newLine(2);
            $this->question(" Success! ");
            $this->info(" Super Admin created successfully! ");
            $this->newLine();
            $bar->finish();
        }
        catch(\Exception $e) {
          echo "Message:  {$e->getMessage()}";
        }    
    }
})->describe('Create Super Admin if not exists');


Artisan::command('log:clear', function() {
    if ($this->confirm('Do you wish to continue?')) {
        exec('echo "" > ' . storage_path('logs/laravel.log'));
        $this->info('Logs have been cleared');
        // $this->comment('Logs have been cleared!');
    }
})->describe('Clear Laravel log');
