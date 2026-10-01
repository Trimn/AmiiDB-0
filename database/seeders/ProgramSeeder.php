<?php

namespace Database\Seeders;

use App\Models\Projects;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rap_speedcodes = [
            'SSHRC' => '36054',
            '36PDF' => '36056',
            'ASSPG' => '36064',
            'ADWHI' => '36067',
            '36BOW' => '36031',
            '36BUR' => '36058',
            '36CHO' => '36057',
            '36FYS' => '36038',
            '36GOE' => '36032',
            '36GRE' => '36033',
            '36GUZ' => '36055',
            '36HEG' => '36051',
            '36JAR' => '36059',
            '36JIA' => '36063',
            '36KAC' => '36070',
            '36KON' => '36047',
            '36KNG' => '36062',
            '36LEI' => '36053',
            '36LIX' => '36053',
            '36MAH' => '36043',
            '36MIT' => '36060',
            '36MOU' => '36042',
            '36MUE' => '36044',
            '36PIL' => '36034',
            '36ROC' => '36069',
            '36SCH' => '36035',
            '36SFR' => '36061',
            '36STU' => '36046',
            '36SUT' => '36041',
            '36SZE' => '36040',
            '36TAN' => '36065',
            '36TAY' => '36052',
            '36WHA' => '36045',
            '36WHI' => '36036',
            '36WRI' => '36039',
            '36YAS' => '36037',
            '36ZAI' => '36030',
            '37HOL' => '36049',
            '37LIL' => '36050',
            '37STE' => '36048',
        ];

        foreach($rap_speedcodes as $sc => $prog) {
            $proj = Projects::where('code', $sc)->first();
            if($proj) {
                $proj->program = $prog;
                $proj->save();
            }
        }
    }
}
