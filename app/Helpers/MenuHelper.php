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

    public static function getIconSvg(string $iconName): string
    {
        $icons = [
            'dashboard' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V9C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 9V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V9C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 9V5.5ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15Z" fill="currentColor"></path></svg>',
            'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C8.41421 2 8.75 2.33579 8.75 2.75V3.75H15.25V2.75C15.25 2.33579 15.5858 2 16 2C16.4142 2 16.75 2.33579 16.75 2.75V3.75H18.5C19.7426 3.75 20.75 4.75736 20.75 6V19C20.75 20.2426 19.7426 21.25 18.5 21.25H5.5C4.25736 21.25 3.25 20.2426 3.25 19V6C3.25 4.75736 4.25736 3.75 5.5 3.75H7.25V2.75C7.25 2.33579 7.58579 2 8 2ZM8 5.25H5.5C5.08579 5.25 4.75 5.58579 4.75 6V8.25H19.25V6C19.25 5.58579 18.9142 5.25 18.5 5.25H16H8ZM19.25 9.75H4.75V19C4.75 19.4142 5.08579 19.75 5.5 19.75H18.5C18.9142 19.75 19.25 19.4142 19.25 19V9.75Z" fill="currentColor"></path></svg>',
            'booking' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H18.5C19.7426 20.75 20.75 19.7426 20.75 18.5V5.5C20.75 4.25736 19.7426 3.25 18.5 3.25H5.5ZM6.25 9.75C6.25 9.33579 6.58579 9 7 9H17C17.4142 9 17.75 9.33579 17.75 9.75C17.75 10.1642 17.4142 10.5 17 10.5H7C6.58579 10.5 6.25 10.1642 6.25 9.75ZM6.25 14.25C6.25 13.8358 6.58579 13.5 7 13.5H17C17.4142 13.5 17.75 13.8358 17.75 14.25C17.75 14.6642 17.4142 15 17 15H7C6.58579 15 6.25 14.6642 6.25 14.25Z" fill="currentColor"></path></svg>',
            'room' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M3 21V7l9-4 9 4v14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 21v-6h6v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'organization' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M23 21v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'user' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/></svg>',
        ];

        return $icons[$iconName] ?? '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/></svg>';
    }

    public static function isActive($path): bool
    {
        return request()->is(ltrim($path, '/'));
    }
}
