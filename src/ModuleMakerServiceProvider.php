<?php

namespace AbrarPatel\Moduler;

use Illuminate\Support\ServiceProvider;
use AbrarPatel\Moduler\MakeModuleCommand;

class ModuleMakerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }
    
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModuleCommand::class,
            ]);
        }
    }
}
