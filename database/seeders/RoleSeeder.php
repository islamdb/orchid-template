<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Orchid\Platform\Models\Role;
use Orchid\Support\Facades\Dashboard;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'superadmin',
            'admin'
        ];

        foreach ($roles as $key => $role) {
            $attr = [
                'name' => ucwords($role),
                'slug' => Str::slug($role),
                'permissions' => [
                    'platform.index' => true,
                    'platform.systems.attachment' => true
                ]
            ];

            switch ($role)
            {
                case 'superadmin':
                    $attr['permissions'] = Dashboard::getAllowAllPermission();
                    break;
            }

            Role::create($attr);
        }
    }
}
