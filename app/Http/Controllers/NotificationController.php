<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Model\Notification;
use Illuminate\Http\Request;
use App\Http\Requests\NotificationRequests\CreateNotificationRequest;


class NotificationController extends Controller
{
    protected $_model; 
    public function __construct(Notification $_model) {
        $this->_model = $_model;
    }
    //CreateNotificationRequest
    public function create( $data){
        return $this->_model->create($data);
    }
    public function getNotification(Request $request){
        return $this->_model->where('user_id', AuthHelper::getUserId())->simplePaginate(20);
    }

}
