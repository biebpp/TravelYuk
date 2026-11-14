<?php

namespace Database\Seeders;

class AdminSeeder extends EnvironmentAccountSeeder
{
    public function run(): void
    {
        $this->seedConfiguredAccount('admin', 'admin');
    }
}
