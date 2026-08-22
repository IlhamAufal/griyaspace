<?php

namespace App\Helpers;

class MenuHelper
{
    public static function getMenuGroups(): array
    {
        $user = auth()->user();

        if (!$user) {
            return [];
        }

        $items = [
            [
                'icon' => 'dashboard',
                'name' => 'Dashboard',
                'path' => '/',
            ],
            [
                'icon' => 'calendar',
                'name' => 'Kalender',
                'path' => '/kalender',
            ],
            [
                'icon' => 'booking',
                'name' => 'Pengajuan',
                'path' => '/pengajuan',
            ],
        ];

        if ($user->isAdmin()) {
            $items[] = [
                'icon' => 'room',
                'name' => 'Ruangan',
                'path' => '/ruangan',
            ];
            $items[] = [
                'icon' => 'organization',
                'name' => 'Organisasi',
                'path' => '/organisasi',
            ];
            $items[] = [
                'icon' => 'role',
                'name' => 'Role',
                'path' => '/role',
            ];
            $items[] = [
                'icon' => 'user',
                'name' => 'Pengguna',
                'path' => '/pengguna',
            ];
        }

        return [
            [
                'title' => 'Menu',
                'items' => $items,
            ],
        ];
    }

    public static function getIconClass(string $iconName): string
    {
        $icons = [
            'dashboard'    => 'fa-solid fa-table-cells-large',
            'calendar'     => 'fa-solid fa-calendar-days',
            'booking'      => 'fa-solid fa-file-lines',
            'room'         => 'fa-solid fa-door-open',
            'organization' => 'fa-solid fa-building',
            'user'         => 'fa-solid fa-users',
            'role'         => 'fa-solid fa-shield-halved',
        ];

        return $icons[$iconName] ?? 'fa-solid fa-circle-dot';
    }

    public static function isActive($path): bool
    {
        return request()->is(ltrim($path, '/'));
    }
}

