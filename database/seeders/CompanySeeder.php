<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Company::create([
                'title' => '제목' . $i,
                'content' => '내용' . $i,
                'filter' => '조달인증',
                'file_path' => "uploads/우수조달컨설팅 로고.png",
            ]);
        }
    }
}
