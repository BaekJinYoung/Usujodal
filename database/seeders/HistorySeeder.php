<?php

namespace Database\Seeders;

use App\Models\History;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startYear = 2001;
        $endYear = 2024;

        for ($year = $startYear; $year <= $endYear; $year++) {
            $date = Carbon::create($year, 1, 1);

            History::create([
                'date' => $date->toDateString(),
                'content' => "내용 {$year}년",
            ]);
        }
    }
}
