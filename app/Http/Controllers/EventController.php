<?php

namespace App\Http\Controllers;

use App\Contracts\UserRepositoryInterface;
use App\Contracts\EventRepositoryInterface;
use App\Http\Requests\EventRequests\GetEventRequest;
use App\Http\Requests\EventRequests\PutEventRequest;
use App\Http\Requests\EventRequests\PostEventRequest;

class EventController extends Controller
{
    protected $eventRepository;

    public function __construct(
        EventRepositoryInterface $eventRepository
    ) {
        $this->eventRepository = $eventRepository;
    }
    public function getEventsController(GetEventRequest $request)
    {
        $event = $this->eventRepository->getEvents($request);
        $event_data = [];
        foreach($event as $data){
            $data->event_times = $data->event_times;
            $data->event_image = $data->event_image;
            $data->event_images = $data->event_images;
            $event_data[] = $data;
        }
        $event->data = $event_data;
        return response()->json([
            'events' => $event
        ], 200);
    }
    public function getEventController($id)
    {
        $event = $this->eventRepository->getEvent($id);
        
        return response()->json([
            'event' => $event
        ], 200);
    }
    public function postEventController(PostEventRequest $request)
    {
        $data = $request->validated();
        $data['organization_name'] = 
        $event = $this->eventRepository->createEvent($data);
        $event->event_times = $event->event_times;
        $event->event_image = $event->event_image;
        $event->event_images = $event->event_images;
        return response()->json([
            'event' => $event
        ], 201);
    }
    public function putEventController(PutEventRequest $request, $id)
    {
        $event = $this->eventRepository->updateEvent($request->validated(), $id);
        return response()->json([
            'event' => $event
        ], 200);
    }
    public function deleteEventController($id)
    {
        $event = $this->eventRepository->deleteEvent($id);
        return response()->json([
            'event' => $event
        ], 204);
    }
}