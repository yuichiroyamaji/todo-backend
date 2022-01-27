<?php

namespace App\Utilities;

/**
 * ApiResponseのdataを形成するUtilityクラスです。
 *
 * @access public
 * @author Yuichiro.Yamaji <yuichiroyamaji@hotmail.com>
 * @package Utility
 */
class ApiResponseUtility
{
    /**
     * 成功時のResponseBodyを形成します。
     * @param $data: Response data
     * @return array
     */
    public function formSuccessResponseData($data)
    {
        return [
            'status' => 'successed',
            'data' => $data,
            'errors' => '',
        ];
    }
    /**
     * 失敗時のResponseBodyを形成します。
     * @param $data: Response data
     * @param $errors: Error contents
     * @return array
     */
    public function formFailResponseData($errors)
    {
        return [
            'status' => 'failed',
            'data' => [],
            'errors' => $errors,
        ];
        
    }
}
