<?php

namespace App\Http\Controllers;

use App\Model\Image;
use App\Model\EventImage;
use App\Helpers\AuthHelper;
use App\Http\Requests\ImageRequests\UploadImageRequest;
use App\Http\Requests\ImageRequests\PutEventImageRequest;
use App\Http\Requests\ImageRequests\PostEventImageRequest;

class EventImageController extends Controller
{
    protected $_model; 
    public function __construct(EventImage $_model) {
        $this->_model = $_model;
    }
    public function get(Request $request) {
        return response()->json([
            'event_images' => $this->_model->get()
        ]);
    }
    public function create($data, $evenId){
        $data['event_id'] = $evenId;
       return $this->_model->create($data);
    }
    public function update($data, $imageId){
        $image = $this->_model->find($imageId);
        if(!empty($image)){
            return $this->create($data, $data['event_id']);
        }   
       return $image->update($data);
    }
    public function post(PostEventImageRequest $request) {
        $data = $request->validated();
        $img = Image::findOrFail($data['image_id']);
        $data['src'] = $img->src;
        
        return response()->json([
            'event_image' => $this->_model->create($data)
        ]);
    }
    public function postImageController($eventId, PostEventImageRequest $request){
        $data = $request->validated();
        $data['event_id'] = $eventId;
        $data['user_id'] = AuthHelper::getUserId();
        return response()->json([
            'event_image' => $this->_model->create($data)
        ]);
    }
    public function put(PutEventImageRequest $request, $id) {
        $data = $request->validated();
        return response()->json([
            'event_image' => $this->_model->create($data)
        ]);
    }

    public function delete(Request $request, $id){
        $image = $this->model->findOrFail($id);
        return response()->json([
            'massage' => $img->delete()
        ]);
    }
}