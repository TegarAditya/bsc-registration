<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_city","view_any_city","create_city","update_city","restore_city","restore_any_city","replicate_city","reorder_city","delete_city","delete_any_city","force_delete_city","force_delete_any_city","view_participant","view_any_participant","create_participant","update_participant","restore_participant","restore_any_participant","replicate_participant","reorder_participant","delete_participant","delete_any_participant","force_delete_participant","force_delete_any_participant","view_province","view_any_province","create_province","update_province","restore_province","restore_any_province","replicate_province","reorder_province","delete_province","delete_any_province","force_delete_province","force_delete_any_province","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role"]},{"name":"panel_user","guard_name":"web","permissions":[]},{"name":"admin","guard_name":"web","permissions":["view_participant","view_any_participant","create_participant","update_participant","restore_participant","restore_any_participant","replicate_participant","reorder_participant","delete_participant","delete_any_participant","force_delete_participant","force_delete_any_participant"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
