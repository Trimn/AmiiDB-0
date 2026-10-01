<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FellowsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Michael Bowling',
            'Neil Burch',
            'Alona Fyshe',
            'Randy Goebel',
            'Russ Greiner',
            'Matthew Guzdial',
            'Nidhi Hegde',
            'Robert Holte',
            'Jacob Jaremko',
            'Bei Jiang',
            'Grzegorz Kondrak',
            'Linglong Kong',
            'Levi Lelis',
            'Lei Ma',
            'Marlos Machado',
            'Rupam Mahmood',
            'Joseph Ross Mitchell',
            'Lili Mou',
            'Martin Müller',
            'Patrick Pilarski',
            'Jonathan Schaeffer',
            'Dale Schuurmans',
            'Martha Steenstrup',
            'Nathan Sturtevant',
            'Richard Sutton',
            'Csaba Szepesvári',
            'Xiaoqi Tan',
            'Matt Taylor',
            'Adam White',
            'Martha White',
            'James Wright',
            'Yutaka Yasui',
            'Osmar Zaïane',
            'Jun Jin',
            'Bailey Kacsmar',
            'Xingyu Li',
            'Geoffrey Rockwell',
        ];
        $ccids = [
            'mbowling',
            'nburch',
            'alona',
            'rgoebel',
            'rgreiner',
            'guzdial',
            'nidhih',
            'rholte',
            'jjaremko',
            'bei1',
            'gkondrak',
            'lkong',
            'santanad',
            'lma7',
            'machado',
            'ashique',
            'jmitche2',
            'lmou',
            'mmueller',
            'pilarski',
            'jonathan',
            'daes',
            'steenstr',
            'nathanst',
            'rsutton',
            'szepesva',
            'xt7',
            'mtaylor3',
            'amw8',
            'whitem',
            'jwright4',
            'yyasui',
            'zaiane',
            'jjin5',
            'kacsmar',
            'xingyu',
            'grockwel',
        ];
        $passwords = [
            'jwEp4HrT6sVN',
            'Q7AbmaBMt5T4',
            'Xt933ZddNGtt',
            'eWuPMvqfhPnD',
            'dqhgstb5dv56',
            '9KZxGNJepVFR',
            'xz2nwaAMTVxh',
            'HKwSZaa4JX2B',
            'fRyE7ZXDb53R',
            'PjSqLkvZZjqf',
            'kWnExAwxP6mB',
            'mW6mZca8fDGm',
            'y2fgkgLFwRDA',
            '42FGJAJejyhQ',
            'b5mr5edGTwmn',
            'eB8rM75eaEwM',
            'yUCp43gKuJQV',
            'uPHmxLx2B832',
            'yMMVMQtYpVzd',
            'egPL8MHuVvX2',
            'QTJPXmKRnyqs',
            '9PYbSYDDQuhf',
            'ArMdfewMEBrG',
            'LecDaPdLqZe6',
            'N5Cdy24CbQUu',
            'jr37EgDCfqdg',
            'kd4DJxa2uUn7',
            '8eaCVHYxFPVM',
            'zMMPjJSAHELp',
            'KttmvWVXKMKV',
            'fPMxbZWXLrEd',
            'PAaKxytMeMqe',
            'qmAc52bFyW2q',
            'v2U9MUVB6Tnk',
            'SjxECdEpT66F',
            'GUKDNHvYUFpn',
            'DQ5hcFQnEHdd',
        ];

        for($i = 0; $i < count($ccids); $i++) {
            $user = User::create([
                'name' => $names[$i],
                'email' => $ccids[$i] . '@ualberta.ca',
                'password' => Hash::make($passwords[$i]),
            ]);
            $user->save();
            $user->assignRole('fellow');
        }
    }
}
