<?php


namespace App\Dev;

use App\Models\Category;
use App\Models\Product;
use Exception;
use GdImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHandler
{
    public function test()
    {
        dd(tmr(),'ImageHandler');

        set_time_limit(60000);
        $srcDir = storage_path('app/public/images/products/cropped/add/');
        $storeDir = storage_path('app/public/images/products/s220/add/');
        $filenames = array_values(array_filter(scandir($srcDir), fn($v) => str_ends_with($v, '.jpg')));
        // $s220 = array_values(array_filter(scandir($storeDir), fn($v) => str_ends_with($v, '.jpg')));
        // $filenames = array_diff($filenames, $s220);
        //df(tmr(@$this->start), count($filenames), count($s220));
        // $filenames = array_slice($filenames,0,100);
        // df(tmr(@$this->start), $filenames);

        foreach ($filenames as $filename){
            $srcPath = $srcDir.$filename;
            $storePath = $storeDir .$filename;
            // $this->cropImage($srcPath,$storePath);
            $this->copyResizeJpgAspectRatio($srcPath, 220, 220,$storePath);
        }
        //
        df(tmr(@$this->start), 'done');
    }

    public function copyResizeJpgAspectRatio(string $srcPath, int $newWidth, int $newHeight = null, string $storePath = null)
    {
        try {
            $srcImg = imagecreatefromjpeg($srcPath);
        }catch (\Exception $e){
            // log
            return;
        }

        $srcSize = getimagesize($srcPath);
        $srcWidth = $srcSize[0];
        $srcHeight = $srcSize[1];

        $ratio_orig = $srcWidth/$srcHeight;
        if ($newWidth/$newHeight > $ratio_orig) {
            $newWidth = $newHeight*$ratio_orig;
        } else {
            $newHeight = $newWidth/$ratio_orig;
        }

        $newImg = imagecreatetruecolor($newWidth, $newHeight); //Создаем полноцветное изображение
        //imagealphablending($newImg, false); //Отключаем режим сопряжения цветов
        //imagesavealpha($newImg, true); //Включаем сохранение альфа канала

        //Ресайз
        imagecopyresampled($newImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);

        //Сохранение
        //imagepng($newImg, $storePath);
        imagejpeg($newImg, $storePath);
    }

    public function cropImage(string $srcPath, string $storePath = null)
    {
        try {
            $srcImg = imagecreatefromjpeg($srcPath);
        }catch (\Exception $e){
            // log
            return;
        }

        $croppedImg = imagecropauto($srcImg, IMG_CROP_THRESHOLD, 0.5, 16777215);

        if ($croppedImg !== false) {
            //Сохранение
            imagejpeg($croppedImg, $storePath);
        }

    }



    public function copyResizeJpg(string $srcPath, int $newWidth, int $newHeight = null, string $storePath = null)
    {
        $newHeight ??= $newWidth;

        if (is_null($storePath)) {
            $storePath = explode('.', $srcPath);
            $storePath = "$storePath[0]({$newWidth}x$newHeight).$storePath[1]";
        }

        try {
            $srcImg = imagecreatefromjpeg($srcPath);
        }catch (\Exception $e){
            // log
            return;
        }

        $srcSize = getimagesize($srcPath);
        $srcWidth = $srcSize[0];
        $srcHeight = $srcSize[1];

        $newImg = imagecreatetruecolor($newWidth, $newHeight); //Создаем полноцветное изображение
        imagealphablending($newImg, false); //Отключаем режим сопряжения цветов
        imagesavealpha($newImg, true); //Включаем сохранение альфа канала

        //Ресайз
        imagecopyresampled($newImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);

        //Сохранение
        //imagepng($newImg, $storePath);
        imagejpeg($newImg, $storePath);
    }

    public function copyResize(string $srcPath, int $newWidth, int $newHeight = null, string $storePath = null)
    {
        $newHeight ??= $newWidth;

        if (is_null($storePath)) {
            $storePath = explode('.', $srcPath);
            $storePath = "$storePath[0]({$newWidth}x$newHeight).$storePath[1]";
        }

        $srcImg = imagecreatefrompng($srcPath);
        $srcSize = getimagesize($srcPath);
        $srcWidth = $srcSize[0];
        $srcHeight = $srcSize[1];

        $newImg = imagecreatetruecolor($newWidth, $newHeight); //Создаем полноцветное изображение
        imagealphablending($newImg, false); //Отключаем режим сопряжения цветов
        imagesavealpha($newImg, true); //Включаем сохранение альфа канала

        //Ресайз
        imagecopyresampled($newImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);

        //Сохранение
        imagepng($newImg, $storePath);

        df(tmr(@$this->start), 777);

    }

}
