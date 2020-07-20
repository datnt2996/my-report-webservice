<?php

namespace App\Contracts;


interface EventRepositoryInterface
{
    /**
     * Get 5 posts hot in a month the last.
     * @return mixed
     */
    public function getEvents($request);
    public function getEvent($id);
    public function createEvent($request);
    public function updateEvent($data, $id);
    public function deleteEvent($id);
}