<?php

namespace Database\Seeders;

use App\Enum\Status;
use App\Service\SourceService;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    private $sourceService;

    public function __construct(SourceService $sourceService)
    {
        $this->sourceService = $sourceService;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();  // Store current timestamp to reuse

        // Load sources from the external file
        $sources = require base_path('database/seeders/data/sources.php');

        // Insert each source using repository pattern
        foreach ($sources as $source) {
            $this->sourceService->insertItem(array_merge($source, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
