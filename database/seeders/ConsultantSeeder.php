<?php

namespace Database\Seeders;

use App\Models\Consultant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsultantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Consultant::create([
                'main_image' => '',
                'name' => '이름 ' . $i,
                'Department' => '컨설팅분야' . $i,
                'rank' => '직급' . $i,
                'content' => '약력 소개' . $i,
            ]);
        }
    }
}
