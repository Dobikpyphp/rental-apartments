<?php

namespace App\Exceptions;

use Exception;

class BookingFailedException extends Exception
{
    protected $message = 'Не удалось создать бронирование. Попробуйте позже.';
}
