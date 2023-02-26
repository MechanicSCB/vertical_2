<?php


namespace App\Dev;

use GdImage;
use Illuminate\Support\Facades\Storage;

class ImageHandler
{
    public function test()
    {
        dd('test');
        set_time_limit(300);
        $srcDir = storage_path('app/public/images/products/orig/');
        $storeDir = storage_path('app/public/images/products/s220/');
        $filenames = array_values(array_filter(scandir($srcDir), fn($v) => str_ends_with($v, '.jpg')));
        $filenames = array_slice($filenames,7060);

        foreach ($filenames as $filename){
            $srcPath = $srcDir.$filename;
            $storePath = $storeDir .$filename;
            //$this->copyResizeJpg($srcPath, 220, 220,$storePath);
        }

        df(tmr(@$this->start), scandir($storeDir));
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
