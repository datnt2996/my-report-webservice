<?php

namespace App\Http\Controllers;

use App\Model\Image;
use App\Helpers\FileHelper;
use App\Http\Requests\ImageRequests\UploadImageRequest;

class ImageController extends Controller
{
    protected $_model; 
    public function __construct(Image $_model) {
        $this->_model = $_model;
    }

    public function upload(UploadImageRequest $request) {
        
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $img = $this->_model->create([
                'src' => 'images/'.$name,
                'height' => null,
                'width' => null
            ]);
            return response()->json([
                'massage' => $img
            ]);
        }
    }

    public function delete(Request $request, $id){
        $image = $this->model->findOrFail($id);
        FileHelper::delete($image->src);
        return response()->json([
            'massage' => $img->delete()
        ]);
    }
    
}