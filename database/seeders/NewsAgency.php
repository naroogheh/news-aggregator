<?php

namespace Database\Seeders;

use App\Service\NewsAgencyService;
use Illuminate\Database\Seeder;

class NewsAgency extends Seeder
{
    private $newsAgencyService;
    function __construct( NewsAgencyService $newsAgencyService )
    {
        $this->newsAgencyService = $newsAgencyService;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $params = [
            'title' => 'guardian',
            'slug' => 'guardian',
            'category' => 'general',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $this->newsAgencyService->insertItem($params);
    }
}
