<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DocumentCatalogSeeder::class,
            DepartmentSeeder::class,
            RoleBasedUserSeeder::class,
            MunicipalStaffUserSeeder::class,
            DashboardContentSeeder::class,
            VacancySeeder::class,
        ]);
    }
}
