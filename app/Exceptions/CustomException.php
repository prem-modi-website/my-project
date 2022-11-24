<?php

namespace App\Exceptions;
    use Exception;
use Illuminate\Http\Request;

class CustomException extends Exception
{
    protected $vehicle;

    public function __construct(string $message = 'Error while processing your request', int $code = 500)
    {
        parent::__construct($message, $code);

        $this->vehicle = $message;
       \Log::info($this->vehicle);
    }
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        // 
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render(Request $request)
    {
       \Log::info($this->vehicle);
        return response()->view('login');
    }
}
