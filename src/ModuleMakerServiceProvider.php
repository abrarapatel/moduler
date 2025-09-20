<?php

namespace Abrar\Moduler;

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
