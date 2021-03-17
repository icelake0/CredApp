<?php

namespace App\Services;

class ServiceResponse
{
    public $success;

    public $message;

    public $data;

    /**
     * Class constructor
     * 
     * @param bool|null $success
     * @param string|null $error
     * @param mixed|null $data
     */
    protected function __construct(
        bool $success = true,
        string $message = null,
        $data = []
    ) {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Make an instance of ServiceResponse
     * 
     * @param bool|null $success
     * @param string|null $error
     * @param mixed|null $data
     * @return ServiceResponse
     */
    public static function make(
        bool $success = true,
        string $message = null,
        $data = []
    ): ServiceResponse {
        return new static(
            $success,
            $message,
            $data
        );
    }
}
