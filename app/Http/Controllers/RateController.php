<?php

namespace App\Http\Controllers;

use Exception;
use App\Model\Event;
use App\Model\RateEvent;
use App\Helpers\AuthHelper;
use App\Exceptions\ApiException;
use App\Exceptions\NotFoundException;
use App\Exceptions\PermissionDeniedException;
use App\Http\Requests\RateEventRequests\PutRateEventRequest;
use App\Http\Requests\RateEventRequests\PostRateEventRequest;


class RateController extends Controller
{
    protected $_model; 
    public function __construct(RateEvent $_model) {
        $this->_model = $_model;
    }
    public function create(PostRateEventRequest $request, $eventId){
        $data = $request->validated();
        $data['event_id'] = $eventId;
        $data['user_id'] = AuthHelper::getUserId();

        if($this->_model->where('user_id', $data['user_id'])
                        ->where('event_id', $eventId)->first()){
                            throw new Exception('user rated!');
                        }
        $event = Event::find($eventId);
        if(empty($event)){
             NotFoundException::render();
        }
        $rateEvent = $this->_model->create($data);
        $event->update([
            'total_star' => $event->total_star + $rateEvent->star,
            'total_rate' => $event->total_rate + 1
        ]);
        return response()->json([
            'rate_event' => $rateEvent
        ], 200);
    }
    public function update(PutRateEventRequest $request, $eventId, $rateId){
        $data = $request->validated();
        $event = Event::find($eventId);
        if(empty($event)){
             NotFoundException::render();
        }
        $rateEvent = $this->_model->find($rateId);
        if(empty($rateEvent)){
            NotFoundException::render();
        }
        if($rateEvent->user_id != AuthHelper::getUserId()){
            PermissionDeniedException::render();
        }

        $event->update([
            'total_star' => $event->total_star - $rateEvent->star + $data['star']
        ]);

        $rateEvent->update($data);
        return response()->json([
            'rate_event' => $rateEvent
        ], 200);
    }
}
