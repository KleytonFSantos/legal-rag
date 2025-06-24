<?php

namespace App\Providers;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Output\OutputInterface;

class AppServiceProvider extends ServiceProvider
{
    public float $starting_time;

    public float $finished_time;

    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected array $listen = [];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(CommandStarting::class, function (CommandStarting $event) {
            $this->starting_time = microtime(true);
        });

        Event::listen(CommandFinished::class, function (CommandFinished $event) {
            $this->finished_time = microtime(true);
            $time = (($this->finished_time - $this->starting_time) / 60); // time in minutes
            $event->output->writeln("Command [$event->command] takes $time minutes.");
        });
    }
}
