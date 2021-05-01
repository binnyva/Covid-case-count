<?php
// namespace App\Libraries;

class JSend
{
    public static function success($data = array(), $message = '', $response_code = 200)
    {
        return JSend::situation('success', $message, $data, $response_code);
    }

    public static function fail($data = array(), $message = '', $response_code = 404)
    {
        return JSend::situation('fail', $message, $data, $response_code);
    }

    public static function error($data = array(), $message = '', $response_code = 500)
    {
        return JSend::situation('error', $message, $data, $response_code);
    }

    public static function situation($status, $message, $data, $response_code = 200)
    {
        $template = array(
            'status'	=> 'success',
            'data'		=> null
        );

        if ($status == 'error') {
            $template['message'] = $message;
        } elseif ($status == 'fail') {
            $template['data'] = array($message);
        }

        $template['status'] = $status;

        if (is_string($message) and $message) {
            $template[$status] = $message;
        } elseif (is_array($message)) {
            $template = array_merge($template, $message);
        }

        if ($data) {
            $template['data'] = $data;
        }

        return json_encode($template);
    }
}
