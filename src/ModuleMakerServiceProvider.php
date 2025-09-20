<?php

namespace AbrarPatel\Moduler;

use Illuminate\Support\ServiceProvider;

class ModuleMakerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            MakeModuleCommand::class,
        ]);
    }
}
