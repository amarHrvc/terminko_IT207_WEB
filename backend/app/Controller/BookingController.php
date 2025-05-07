<?php

namespace App\Controller;

use Flight;

class BookingController extends BaseController
{
    public function __construct()
    {
        $dao = Flight::BookingDao();
        parent::__construct($dao);
    }

    public function index()
    {

        $bookings = $this->dao->findAll();
        return (Flight::getArrayFromModels($bookings));

    }

    public function show(string $id): array
    {
        $bookingById = $this->dao->findById($id);
        return ($bookingById ? $bookingById->toArray() : []);

    }

    public function update(array $data)
    {
        return $this->dao->update($data['id'], $data);
    }

}
