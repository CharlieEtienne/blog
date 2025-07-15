<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and configure the blog application';

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->info('Installing your Blog...');

        // Step 1: Copy .env file if it doesn't exist
        $this->info('Step 1: Creating the environment file...');
        if (!File::exists('.env')) {
            File::copy('.env.example', '.env');
            $this->info('Environment file created.');
        }
        else {
            $this->warn('Environment file already exists.');
        }

        $appName = $this->ask('What is the name of your application?', 'My Blog');
        $envContent = File::get('.env');
        $envContent = preg_replace('/^APP_NAME=.*$/m', 'APP_NAME="' . $appName . '"', $envContent);
        File::put('.env', $envContent);
        $this->info('Application name set to: ' . $appName);

        // Step 2: Generate the application key
        $this->info('Step 2: Generating the application key...');
        $this->call('key:generate', ['--ansi' => true]);

        // Step 3: Creating the SQLite database
        $this->info('Step 3: Creating the SQLite database...');
        if (!File::exists(database_path('database.sqlite'))) {
            File::put(database_path('database.sqlite'), '');
            $this->info('SQLite database created.');
        }
        else {
            $this->fail('SQLite database already exists.');
        }

        // Step 4: Run migrations
        $this->info('Step 4: Running migrations...');
        $seedData = $this->confirm('Would you like to seed some dummy data?', false);
        $this->call('migrate', $seedData ? ['--seed' => true] : []);

        // Step 5: Install NPM dependencies and build assets
        $this->info('Step 5: Installing NPM dependencies...');
        $process = Process::fromShellCommandline('npm install && npm run build');
        $process->setTimeout(null);
        $process->run(function ($type, $line) {
            $this->output->write($line);
        });

        // Step 6: Create Admin User
        $this->info('Step 6: Creating Admin User...');
        $this->call('make:filament-user');

        $this->info('Blog installation complete! You can now start using your blog.');
    }
}
