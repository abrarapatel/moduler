<?php

namespace AbrarPatel\Moduler;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use AbrarPatel\Moduler\Services\StubGenerater;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module {schema}';
    protected $description = 'Create a full CRUD module';

    public function handle()
    {
        $schemaPath = $this->argument('schema');

        $stubGenerater = new StubGenerater();

        // Things pending to do:
        // 1. Check if module already exists
        // 2. Consider softDeletes when creating model and controller
        // 3. Foreign keys in migration

        // Read json schema
        $moduleSchema = File::json(base_path() . "/" . $schemaPath, true);
        
        $name = $moduleSchema['name'] ?? null;

        if (!$name) {
            $this->error("❌ 'name' key is required in schema");
            return;
        }

        $name_lwr = strtolower($name);

        $migrationFileName =  $name_lwr . "s_table.php";
        File::put(base_path() . "/database/migrations/create_" . $migrationFileName, $stubGenerater->getMigrationStub($name, $moduleSchema));
        $this->info("✅ Migration file for `{$name}` created at database/migrations/");
    }
}
