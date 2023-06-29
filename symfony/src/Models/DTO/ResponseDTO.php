<?php

namespace App\Models\DTO;

class ResponseDTO
{
    public string $message;

    public int $status;

    public string $requested_url;

    public $data;
}
