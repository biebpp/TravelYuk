<?php

namespace Database\Seeders;

class SuperadminSeeder extends EnvironmentAccountSeeder
{
    public function run(): void
    {
        $this->seedConfiguredAccount('superadmin', 'superadmin');
    }
}
