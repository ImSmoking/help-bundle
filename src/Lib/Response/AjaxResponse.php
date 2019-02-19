<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 2/8/19
 * Time: 3:39 PM
 */

namespace App\Lib\Response;


use App\Lib\Constants;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxResponse extends JsonResponse
{

    protected $raw_data = [
        'status' => '',
        'data' => ''
    ];

    /**
     * AjaxResponse constructor.
     * @param string $raw_data
     * @param int $status
     * @param array $headers
     */
    public function __construct($raw_data = '', $status = 200, $headers = array())
    {
        parent::__construct('', $status, $headers);
        if (!is_array($raw_data)) {
            $raw_data = [$raw_data];
        }
        $this->raw_data['data'] = $raw_data;
        $this->setData($this->raw_data);
    }

    /**
     * Sets status to Error for the response
     * @param $additional_info - adds everything from this array into first level of response
     * @return AjaxResponse
     */
    public function error($additional_info = [])
    {

        $this->raw_data['status'] = Constants::RESPONSE_ERROR;
        if (!empty($additional_info)) {
            $this->raw_data = $this->raw_data + $additional_info;
        }
        $this->setData($this->raw_data);
        return $this;

    }

    /**
     * Sets status success for the response
     * @param $additional_info - adds everything from this array into first level of response
     * @return AjaxResponse
     */
    public function success($additional_info = [])
    {
        $this->raw_data['status'] = Constants::RESPONSE_SUCCESS;
        if (!empty($additional_info)) {
            $this->raw_data = $this->raw_data + $additional_info;
        }
        $this->setData($this->raw_data);
        return $this;

    }

    /**
     * Sets status success for the response
     * @param $additional_info - adds everything from this array into first level of response
     * @return AjaxResponse
     */
    public function info($additional_info = [])
    {
        $this->raw_data['status'] = Constants::RESPONSE_INFO;
        if (!empty($additional_info)) {
            $this->raw_data = $this->raw_data + $additional_info;
        }
        $this->setData($this->raw_data);
        return $this;

    }


}