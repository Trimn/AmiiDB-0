<?php

namespace Database\Seeders;

use App\Models\Navigation;
use App\Models\NavCategories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Payroll',
            'Financial',
            'Reporting',
            'Community',
            'Fellows',
            'Admin',
        ];

        $payroll = [
            ['Students', 'students'],
            ['Staff', 'staff'],
            ['CFS', 'cfs'],
            ['SBA', 'sba'],
            ['Awards', 'awards'],
            ['Budgets', 'budgets'],
            ['Award Calc', NULL],
        ];

        $financial = [
            ['Speedcodes', 'speedcodes'],
            ['Projects', 'projects'],
            ['Accounts in O/E', NULL],
            ['SBA Request', NULL],
            ['CCC Request', NULL],
            ['Future Adjust.', NULL],
        ];

        $reporting = [
            ['Metrics', NULL],
            ['Student Appointments', 'student_appts'],
            ['Staff Appointments', 'staff_appts'],
            ['Student/Staff Metrics', 'metrics.appts'],
            ['Commit by SC', NULL],
            ['Amii Claimed Students', 'claimed_students'],
            ['New Amii People', NULL],
            ['Custom', NULL],
        ];

        $community = [
            ['Fellows', 'fellows'],
            ['Affiliate Chairs', 'affiliates'],
            ['Affiliate Staff and Students', 'affiliate_staff'],
            ['Visitors', 'visitor'],
            ['Contact Preferences', 'contact'],
        ];

        $fellows = [
            ['Active Staff', NULL],
            ['Active Students', NULL],
            ['My Speedcodes', NULL],
            ['Budget Report', 'fellowsView.budget'],
        ];

        $admin = [
            ['eTRAC Upload', NULL],
            ['FGSR Upload', NULL],
            ['References', 'reference'],
            ['View as Fellow', 'admin.view_as_fellow', 'admin'],
        ];

        $pages = [
            'Payroll' => $payroll, 
            'Financial' => $financial, 
            'Reporting' => $reporting, 
            'Community' => $community, 
            'Fellows' => $fellows, 
            'Admin' => $admin
        ];

        foreach($categories as $key => $cat) {
            NavCategories::create([
                'category' => $cat, 
                'order' => $key
            ]);
        }

        foreach($pages as $key => $page) {
            foreach($page as $idx => $cat) {
                Navigation::create([
                    'name' => $cat[0], 
                    'route' => $cat[1], 
                    'category' => $key, 
                    'order' => $idx, 
                    'permission' => $cat[2] ?? 'view',
                ]);
            }
        }
    }
}
