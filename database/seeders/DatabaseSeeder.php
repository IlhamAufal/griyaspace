<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Organization;
use App\Models\Room;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrator sistem',
        ]);

        $orgRole = Role::create([
            'name' => 'Organisasi',
            'slug' => 'organization',
            'description' => 'Pengguna dari organisasi/ormawa',
        ]);

        // Admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@griyaspace.test',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'is_active' => true,
            'must_change_password' => false,
        ]);

        // Organization
        Organization::create([
            'name' => 'Himpunan Mahasiswa Informatika',
            'abbreviation' => 'HMI',
            'leader_name' => 'Budi Santoso',
            'phone' => '081234567890',
        ]);

        // Organization user
        User::create([
            'name' => 'Ormawa HMI',
            'email' => 'ormawa@griyaspace.test',
            'username' => 'ormawa',
            'password' => bcrypt('password'),
            'role_id' => $orgRole->id,
            'organization_id' => 1,
            'is_active' => true,
            'must_change_password' => false,
        ]);

        // Rooms
        Room::create([
            'code' => 'RGB-01',
            'name' => 'Ruang Meeting Besar',
            'capacity' => 30,
            'location' => 'Gedung A Lt.2',
            'status' => 'active',
        ]);

        Room::create([
            'code' => 'RMK-01',
            'name' => 'Ruang Meeting Kecil 1',
            'capacity' => 10,
            'location' => 'Gedung A Lt.1',
            'status' => 'active',
        ]);

        Room::create([
            'code' => 'RMK-02',
            'name' => 'Ruang Meeting Kecil 2',
            'capacity' => 10,
            'location' => 'Gedung A Lt.1',
            'status' => 'active',
        ]);
    }
}
