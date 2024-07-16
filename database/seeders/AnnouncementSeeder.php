<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Announcement::create([
                'title' => '제목' . $i,
                'content' => '내용' . $i,
                'is_featured' => true,
                'file_path' => "uploads/우수조달컨설팅 로고.png",
            ]);
        }
    }
}
