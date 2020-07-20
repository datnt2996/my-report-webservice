<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Model\EventTime;
use App\Helpers\AuthHelper;
use Illuminate\Http\Request;
use App\Http\Requests\EventTimeRequest\PutEventTimeRequest;
use App\Http\Requests\EventTimeRequest\PostEventTimeRequest;


class EventTimeController extends Controller
{
    protected $_model; 
    public function __construct(EventTime $_model) {
        $this->_model = $_model;
    }
    public function post(PostEventTimeRequest $request, $evenId){
        $request['event_id'] = $evenId;
       return $this->_model->create($request);
    }
    public function create($request, $evenId){
        $request['event_id'] = $evenId;
        $request['time_start'] = Carbon::parse($request['time_start'])->toDateTimeString();
        $request['time_end'] = Carbon::parse($request['time_end'])->toDateTimeString();
       return $this->_model->create($request);
    }
    public function postEventTimeController(PostEventTimeRequest $request, $evenId){
        $data= $request->validated();
        $data['event_id'] = $evenId;
        $data['user_id'] = AuthHelper::getUserId();
        return response()->json([
            'event_time' => $this->_model->create($data)
        ]);
    }
    public function getEventTimeController(Request $request, $eventId){
        return response()->json([
            'event_times' => $this->_model->where('event_id', $eventId)->simplePaginate(5)
        ]);
    }
    public function update($data, $TimeId){
        if(!$TimeId){
            return $this->create($data, $data['event_id']);
        }
        $time = $this->_model->find($TimeId);
        if(!empty($time)){
            return $this->create($data, $data['event_id']);
        }   
       return $time->time($data);
    }
    public function updateByData(PutEventTimeRequest $request, $eventTimeId){
       $evenTime= $this->_model->findOrFail($eventTimeId);
       return $eventTime->save($request);
    }
    public function delete($eventTimeId){
        $evenTime= $this->_model->findOrFail($eventTimeId);
       return $eventTime->delete();
    }
}
