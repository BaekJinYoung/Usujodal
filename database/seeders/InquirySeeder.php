<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 9; $i++) {
            Inquiry::create([
                'name' => '이름',
                'contact' => '010-' . $i . '234-5678',
                'company' => '회사명' . $i,
                'email' => 'dlapdlf' . $i . 'email.com',
                'message' => '문의사항',
            ]);
        }
    }
}
