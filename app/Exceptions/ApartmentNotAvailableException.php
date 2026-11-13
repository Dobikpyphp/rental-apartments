<?php

namespace App\Exceptions;

use Exception;

class ApartmentNotAvailableException extends Exception
{
    protected $message = 'Квартира занята на выбранные даты.';
}
