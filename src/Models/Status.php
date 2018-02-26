<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 12/02/2018
 * Time: 13:12
 */

namespace Irisit\Filestash\Models;

class Status
{
    private $code;
    private $success;
    private $message;

    public function __construct(int $code = 200, bool $success = true, $message = 'ok')
    {
        $this->code = $code;
        $this->success = $success;
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

}