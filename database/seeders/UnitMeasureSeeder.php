<?php

namespace Database\Seeders;

use App\Models\UnitMeasure;
use Illuminate\Database\Seeder;

class UnitMeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $measures = collect([
            '0' => ['Kw/Hora', 'Medida para Valores elétricos'],
            '1' => ['M³ ', 'Medidas em metros cubicos'],
        ]);

        foreach ($measures as $m) {
            $this->save($m);
        }
    }

    public function save($data)
    {

        $measure = new UnitMeasure();
        $measure->name = $data[0];
        $measure->description = $data[1];
        $measure->save();

    }
}
