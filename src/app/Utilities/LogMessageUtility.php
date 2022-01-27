<?php

namespace App\Utilities;
use Log;

/**
 * ログのメッセージを形成し、ログを書き込むUtilityクラスです。
 *
 * @access public
 * @author Yuichiro.Yamaji <yuichiroyamaji@hotmail.com>
 * @package Utility
 */
class LogMessageUtility
{
    /**
     * 成功時のResponseBodyを形成します。
     * @param $message: message
     * @param $statusCd: status code
     * @param $data: data
     * @return array
     */
    public function logApiMessage($message, $statusCd, $data)
    {
        $logContents = [
            'message' => $message,
            'statusCode' => $statusCd,
            'data' => $data,
        ];
        $statusCd < 300 ? Log::info($logContents) : Log::error($logContents);
    }
}
