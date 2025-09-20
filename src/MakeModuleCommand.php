<?php

namespace AbrarPatel\Moduler;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module {name}';
    protected $description = 'Create a full CRUD module';

    public function handle()
    {

        // Test comment
        $name = $this->argument('name');
        $path = base_path("modules/{$name}");

        if (File::exists($path)) {
            $this->error("Module {$name} already exists.");
            return;
        }

        File::makeDirectory($path, 0755, true);

        // Example: create files
        File::put("{$path}/{$name}Model.php", $this->getStub('model', $name));
        File::put("{$path}/{$name}Controller.php", $this->getStub('controller', $name));

        $this->info("✅ Module {$name} created at {$path}");
    }

    protected function getStub($type, $name)
    {
        $stubPath = __DIR__."/stubs/{$type}.stub";
        $content = File::get($stubPath);
        return str_replace('{{name}}', $name, $content);
    }
}
