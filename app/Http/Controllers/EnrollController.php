<?php

namespace App\Http\Controllers;

use App\Model\Enroll;
use App\Helpers\AuthHelper;
use App\Model\Notification;
use App\Http\Controllers\NotificationController;
use App\Http\Requests\EnrollRequests\PostEnrollRequest;


class EnrollController extends Controller
{
    protected $_model; 
    public function __construct(Enroll $_model) {
        $this->_model = $_model;
    }
    public function create(PostEnrollRequest $request, $eventId){
        $data = $request->validated();
        $data['user_id']= AuthHelper::getUserId();
        $data['event_id'] = $eventId;
        $enroll = $this->_model->create($data);

        $notiControl = new NotificationController(new Notification());
        $notiControl->create([
            'user_id' => $data['user_id'],
            'body' => [ 'event_id' => $data['event_id'], 'enroll' => $enroll->id],
            'content' => 'sự kiện của bạn có 1 lượt tham gia mới!!',
            'type' => 'NEW_ENROLL',
            'status' => 'active'
        ]);
        return response()->json([
            'enroll' => $enroll
        ]);
    }
    public function cancel($enrollId){
        $enroll = $this->_model->find($enroll);

        return response()->json([
            'status' => $enroll->delete()
        ]);
    }
}
