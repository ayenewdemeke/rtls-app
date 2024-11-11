<?php

namespace App\Utils;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageUploader
{
    // public static function upload_user_image($image)
    // {
    //     $extention = $image->getClientOriginalExtension();
    //     $image_name_to_save = uniqid('user_') . time() . '.' . $extention;

    //     $img = Image::make($image)->orientate(); // Handle orientation once
    //     $size = min(1000, $img->width());

    //     $img->resize($size, null, function ($constraint) {
    //         $constraint->aspectRatio();
    //     });

    //     $csize = min($img->width(), $img->height());
    //     $img->crop($csize, $csize)->save(storage_path('app/public/user/image/' . $image_name_to_save));

    //     return $image_name_to_save;
    // }

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
