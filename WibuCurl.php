<?php
/*
 * @Wibuheker | Curl Class
 */
class Curl {
    public static $URL = null;
    public static $ch;
    public static function MakeRequests()
    {
        self::$ch = curl_init();
        curl_setopt (self::$ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt (self::$ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt (self::$ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt (self::$ch, CURLOPT_HEADER, 1);
    }
    public static function SetHeaders($header)
    {
        curl_setopt (self::$ch, CURLOPT_HTTPHEADER, $header);
    }
    public static function setTimeout($timeout)
    {
        curl_setopt (self::$ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt (self::$ch, CURLOPT_CONNECTTIMEOUT,$timeout);
    }
    public static function Cookies($file_path)
    {
        $fp = fopen($file_path, 'wb');
        fclose($fp);
        curl_setopt (self::$ch, CURLOPT_COOKIEJAR, $file_path);
        curl_setopt (self::$ch, CURLOPT_COOKIEFILE, $file_path);
    }
    public static function Follow()
    {
        curl_setopt (self::$ch, CURLOPT_FOLLOWLOCATION, 1);
    }
    public static function Post($data) 
    {
        curl_setopt (self::$ch, CURLOPT_URL, self::$URL);
        curl_setopt (self::$ch, CURLOPT_POST, 1);	
		curl_setopt (self::$ch, CURLOPT_POSTFIELDS, $data);
    }
    public static function Get()
    {
        curl_setopt (self::$ch, CURLOPT_URL, self::$URL);
        curl_setopt (self::$ch, CURLOPT_POST, 0);
    }
    public static function Response()
    {
        $data = curl_exec (self::$ch);
        $header_size = curl_getinfo(self::$ch, CURLINFO_HEADER_SIZE);
		$status_code = curl_getinfo(self::$ch, CURLINFO_HTTP_CODE);
		$head = substr($data, 0, $header_size);
		$body = substr($data, $header_size);
        return json_decode(json_encode(
            array(
                'status_code' => $status_code,
                'headers' => self::HeadersToArray($head),
                'body' => $body
            )
            ));
    }
    public static function HeadersToArray($str) {
        $str = explode("\r\n", $str);
        $str = array_splice($str, 0, count($str) - 1);
        $output = [];
        foreach($str as $item) {
            if ($item === '' || empty($item)) continue;
            $index = stripos($item, ": ");
            $key = substr($item, 0, $index);
            $key = strtolower(str_replace('-', '_', $key));
            $value = substr($item, $index + 2);
            if (@$output[$key]) {
                if (strtolower($key) === 'set_cookie') {
                    $output[$key] = $output[$key] . "; " . $value; 
                } else {
                    $output[$key] = $output[$key];
                }
            } else {
                $output[$key] = $value;
            }
        }
        return $output;
    }
}
