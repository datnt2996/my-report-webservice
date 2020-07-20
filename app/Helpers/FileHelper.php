<?php

namespace App\Helpers;

use Illuminate\Http\File;

class FileHelper{
    static public function delete($path){
        if(File::exists($path)) {
            File::delete($path);
        }
    }
}

