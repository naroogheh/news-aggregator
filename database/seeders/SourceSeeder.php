<?php

namespace Database\Seeders;

use App\Enum\Status;
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
            'title' => 'guardian',
            'base_url' => 'https://content.guardianapis.com',
            'reader_class' => 'GuardianReader',
            'api_token' => 'c9a2e89a-4dff-460e-97b6-616a87062611',
            'last_sync_time' => null,
            'status' => Status::Active->value,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $this->sourceService->insertItem($params);
//        $params = [
//            'title' => 'newsorg',
//            'base_url' => 'https://www.google.com',
//            'api_token' => 1,
//            'last_sync_time' => null,
//            'status' => Status::Active->value,
//            'created_at' => now(),
//            'updated_at' => now(),
//        ];
//        $this->sourceService->insertItem($params);
    }
}
