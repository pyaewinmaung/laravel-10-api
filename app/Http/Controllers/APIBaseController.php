<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as LaravelResponse;

class APIBaseController extends Controller
{
    /**
     * A flag for understanding the process was success or not
     * 
     * @var int 1: success / 0: fail
     */
    protected $success = 1;

    /**
     * HTTP status code
     * 
     * @var int
     */
    protected $code = 200;

    /**
     * Accessed HTTP method
     */
    protected $method;

    /**
     * Accessed Endpoint
     */
    protected $endpoint;

    /**
     * Starting position of retrieves a resource
     */
    protected $offset;

    /**
     * End position of retrieves a resource
     */
    protected $limit = 30;

    /**
     * A total count of retrieved result
     */
    protected $total;

    /**
     * Response data
     * 
     * @var array
     */
    protected $data = [];

    /**
     * Response errors
     * 
     * @var array
     */
    protected $errors = [];

    /**
     * Execution time
     * 
     * @var int
     */
    protected $duration = 0;

    /**
     * Start time of executed
     * 
     * @var int
     */
    protected $startTime = 0;

    public function make()
    {
        return [
            'success' => $this->success,
            'code' => $this->getStatusCode(),
            'meta' => $this->getMeta(),
            'data' => $this->data,
            'errors' => $this->getErrors(),
            'duration' => $this->calcDuration()
        ];
    }

    public function response($code = null)
    {
        if (count($this->errors) > 0):
            $this->success = 0;
        endif;

        $res = $this->make();
        $response = LaravelResponse::json($res, $code);
        $response->header('Content-Type', 'application/json');
        $response->header('Access-Control-Allow-Origin', '*');

        return $response;
    }

    public function success($success)
    {
        $this->success = $success;
        return $this;
    }

    public function code($code)
    {
        $this->code = (int) $code;
        return $this;
    }

    public function method($method)
    {
        $this->method = $method;
        return $this;
    }

    public function endpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function total($total)
    {
        $this->total = $total;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function data($data)
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function errors($errors)
    {
        $this->errors = array_merge($this->errors, $errors);
        return $this;
    }

    public function getStatusCode()
    {
        if ($this->code > 100000) {
            return intval($this->code / 1000);
        } else {
            return $this->code;
        }
    }

    public function getMeta()
    {
        $meta = [
            'method' => $this->method,
            'endpoint' => $this->endpoint
        ];

        if ($this->limit !== null) {
            $meta['limit'] = (int) $this->limit;
        }

        if ($this->offset !== null) {
            $meta['offset'] = (int) $this->offset;
        }

        if ($this->total !== null) {
            $meta['total'] = (int) $this->total;
        }

        return $meta;
    }

    public function getErrors()
    {
        $errors = $this->errors;
        if (empty($errors)) {
            $errors = (object) $errors;
        }

        return $errors;
    }

    public function calcDuration()
    {
        $now = microtime(true);
        return $this->duration = (float) sprintf("%.3f", ($now - $this->startTime));
    }

    public function setErrors($errorCode, $id = 0)
    {
        switch ($errorCode) {
            case '400': 
                $error = [
                    'message' => 'The request parameters are incorrect, please make sure to follow the documentation.',
                    'code' => 400002
                ];
                $this->errors($error);
                break;
            case '404':
                $error = [
                    'message' => 'The resource that match ID : ({$id}) does not found.',
                    'code' => 404001
                ];
                $this->errors($error);
                break;
            case '500':
                $error = [
                    'message' => 'A fatal error has occurred while creating the files to store, please try again.',
                    'code' => 500100
                ];
                $this->errors($error);
                break;
        }
    }
}