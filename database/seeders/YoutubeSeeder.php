<?php

namespace Database\Seeders;

use App\Models\Youtube;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class YoutubeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() {
        // 반복문을 사용하여 여러 개의 더미 데이터 생성
        for ($i = 1; $i <= 10; $i++) {
            Youtube::create([
                'title' => '제목 ' . $i,
                'content' => '시딩 ' . $i . '번',
                'link' => 'https://www.youtube.com/watch?v=' . $i,
            ]);
        }
    }
}
