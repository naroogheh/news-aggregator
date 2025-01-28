<?php

namespace Database\Seeders;

use App\Service\SourceService;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    private $sourceService;
    function __construct( SourceService $sourceService )
    {
        $this->sourceService = $sourceService;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $params = [
            'name' => 'test',
            'url' => 'https://www.google.com',
            'status' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ];
        $this->sourceService->insertItem($params);
    }
}
