<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomPhoto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RoomPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = Room::all();

        foreach ($rooms as $room) {
            $this->createPlaceholderForRoom($room);
        }
    }

    private function createPlaceholderForRoom(Room $room): void
    {
        $labels = [
            'Tampak Depan',
            'Area Interior',
            'Fasilitas Utama',
        ];

        $colors = [
            [59, 130, 246],
            [99, 102, 241],
            [139, 92, 246],
        ];

        foreach ($labels as $index => $label) {
            $width = 1200;
            $height = 800;
            $image = imagecreatetruecolor($width, $height);

            [$r, $g, $b] = $colors[$index];
            $bgColor = imagecolorallocate($image, $r, $g, $b);
            $textColor = imagecolorallocate($image, 255, 255, 255);

            imagefill($image, 0, 0, $bgColor);

            $fontSize = 5;
            $text = $room->name . ' - ' . $label;
            $textWidth = imagefontwidth($fontSize) * strlen($text);
            $textHeight = imagefontheight($fontSize);
            $x = (int)(($width - $textWidth) / 2);
            $y = (int)(($height - $textHeight) / 2);

            imagestring($image, $fontSize, $x, $y, $text, $textColor);

            $filename = 'rooms/' . $room->code . '_photo_' . ($index + 1) . '.png';
            $fullPath = Storage::disk('public')->path($filename);

            $dir = dirname($fullPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            imagepng($image, $fullPath);
            imagedestroy($image);

            RoomPhoto::create([
                'room_id' => $room->id,
                'photo_path' => $filename,
                'sort_order' => $index,
            ]);
        }
    }
}
