<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use App\Models\Room;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@griyaspace.test',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'admin',
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
            'role' => 'organization',
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
            'facilities' => json_encode(['Proyektor', 'Whiteboard', 'AC', 'Mic']),
            'status' => 'active',
        ]);

        Room::create([
            'code' => 'RMK-01',
            'name' => 'Ruang Meeting Kecil 1',
            'capacity' => 10,
            'location' => 'Gedung A Lt.1',
            'facilities' => json_encode(['Proyektor', 'Whiteboard', 'AC']),
            'status' => 'active',
        ]);

        Room::create([
            'code' => 'RMK-02',
            'name' => 'Ruang Meeting Kecil 2',
            'capacity' => 10,
            'location' => 'Gedung A Lt.1',
            'facilities' => json_encode(['Whiteboard', 'AC']),
            'status' => 'active',
        ]);
    }
}
