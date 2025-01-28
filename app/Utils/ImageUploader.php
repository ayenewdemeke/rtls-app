<?php

namespace App\Utils;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageUploader
{
    public static function uploadUserImage($image)
    {
        $extension = $image->getClientOriginalExtension();
        $imageNameToSave = uniqid('user_') . time() . '.' . $extension;

        $manager = new ImageManager(new Driver());
        $img = $manager->read($image->getPathname())->orient();

        $img->scale(width: 1000);

        $cropSize = min($img->width(), $img->height());
        $img->crop($cropSize, $cropSize);

        $img->save(storage_path('app/public/user/image/' . $imageNameToSave));

        return $imageNameToSave;
    }

    public static function uploadWorkZoneImage($image)
    {
        $extension = $image->getClientOriginalExtension();
        $uniqueId = uniqid('work_zone_') . time();
        $imageNameToSave = $uniqueId . '.' . $extension;
        $thumbnailNameToSave = $uniqueId . '_thumb.' . $extension;

        // Initialize ImageManager with the GD driver
        $manager = new ImageManager(new Driver());

        // Load and orientate the image
        $img = $manager->read($image->getPathname())->orient();

        // Create and save the square thumbnail (500x500 or the minimum dimension)
        $thumbnailSize = min(500, $img->width(), $img->height());
        $squareImg = clone $img;
        $squareImg->crop($thumbnailSize, $thumbnailSize);
        $squareImg->save(storage_path('app/public/work_zone/thumbnail/' . $thumbnailNameToSave));

        // Create and save the image with a width-to-height ratio of 2.4
        $maxHeight = 1000;
        $height = min($img->height(), $maxHeight);
        $width = intval($height * 2); // Calculate width based on 2 ratio
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(storage_path('app/public/work_zone/image/' . $imageNameToSave));

        return ['image' => $imageNameToSave, 'thumbnail' => $thumbnailNameToSave];
    }
}
