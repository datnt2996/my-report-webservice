<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Model\Event;
use App\Model\Category;
use App\Model\EventTime;
use App\Model\EventImage;
use App\Helpers\AuthHelper;
use App\Policies\UserPolicy;
use App\Exceptions\NotFoundException;
use App\Contracts\EventRepositoryInterface;
use App\Http\Controllers\EventTimeController;
use App\Http\Controllers\EventImageController;

class EventEloquentRepository extends EloquentRepository implements EventRepositoryInterface
{
    public function getModel()
    {
        return Event::class;
    }

    public function getEvents($request)
    {
        $userId = AuthHelper::getUserID();

        $event = $this->_model->orderBy('created_at', 'desc')
        ->queryCategory($request->input('category'))
        ->queryEventTimeStatus($request->input('status'))
        ->queryTextSearch($request->input('search'))
        ->queryByEnroll($request->input('enroll'), $request->input('user_id') ?? $userId)
        ->queryByUserId($request->input('user_id')) 
        ->paginate((int) $request->input('limit') ?? 10);
    
        return $event;
    }

    public function getEvent($id)
    {
        $event = $this->_model->find($id);
        if (empty($event)) {
            throw new NotFoundException('event not found', 'event.not_found');
        }
        return $event;
    }

    public function createEvent($data)
    {
        $data['user_id'] = AuthHelper::getUserID();
        $data['total_rate'] = 0;
        $data['total_star'] = 0;
        $data['price'] = $data['price'] ?? 0;
        $data['time_start'] = Carbon::parse($data['time_start'])->toDateTimeString();
        $data['time_end'] = Carbon::parse($data['time_start'])->toDateTimeString();
        $event = $this->_model->create($data);
        if(!empty($data['event_times'])){
            $modelEventTime = new EventTimeController(new EventTime());
            foreach ($data['event_times'] as $eventTime){
                $modelEventTime->create($eventTime, $event->id);
            }
        }
        if(!empty($data['event_images'])){
            $modelEventImage = new EventImageController(new EventImage());
            foreach ($data['event_images'] as $eventImage){
                $modelEventImage->create($eventImage, $event->id);
            }
        }

        return $event;
    }

    public function addColumn($arr, $id){
        foreach($arr as $value){
            $value['event_id'] = $id;
        }
        return $arr;
    }
    public function updateEvent($data, $id)
    {
        $event = $this->checkUserId($id);
        if(!empty($data['event_times'])){
            $modelEventTime = new EventTimeController(new EventTime());
            foreach ($data['event_times'] as $eventTime){
                $eventTime['event_id'] = $id;

                $modelEventTime->update($eventTime, $eventTime['id']);
            }
        }
        if(!empty($data['event_images'])){
            $modelEventImage = new EventImageController(new EventImage());
            foreach ($data['event_images'] as $eventImage){
                $eventImage['event_id'] = $id;
                $modelEventImage->update($eventImage, $eventImage['id']);
            }
        }
        $event->update($data);
     return $event;
    }


    public function deleteEvent($id)
    {
        $event = $this->checkUserId($id);
        return $event->delete();
    }
    public function checkUserId($eventId)
    {
        $event = $this->getEvent($eventId);
        if(AuthHelper::isAdmin()){
            return $event;
        }
        UserPolicy::checkUser($event->user_id);
        return $event;
    }
}