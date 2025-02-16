<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LocationService;

class ImportLocations extends Command
{
    protected $signature = 'import:locations';
    protected $description = 'Lấy dữ liệu tỉnh, huyện, xã từ API công cộng và lưu vào database';

    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        parent::__construct();
        $this->locationService = $locationService;
    }

    public function handle()
    {
        $this->info('Bắt đầu lấy dữ liệu...');
        $this->locationService->fetchAndStoreLocations();
        $this->info('Dữ liệu đã được cập nhật thành công!');
    }
}
