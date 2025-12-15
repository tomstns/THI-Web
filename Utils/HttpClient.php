<?php
namespace Utils;

class HttpClient {
    public static function post($url, $data, $token = null) {
        $ch = curl_init();

        $jsonDataEncoded = json_encode($data);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

        $headers = array('Content-Type: application/json');
        if($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 

        $result = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if($status == 200 && !empty($result)) {
            return json_decode($result);
        } else if($status == 204) {
            return true;
        }
        throw new \Exception('Http status is ' . $status . ': ' . $result);
    }

    public static function put($url, $data, $token = null) {
        $ch = curl_init();

        $jsonDataEncoded = json_encode($data);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

        $headers = array('Content-Type: application/json');
        if($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 

        $result = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if($status == 200 && !empty($result)) {
            return json_decode($result);
        } else if($status == 204) {
            return true;
        }
        throw new \Exception('Http status is ' . $status);
    }

    public static function get($url, $token = null) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        if($token) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token)); 
        }

        $result = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if($status == 200 && !empty($result)) {
            return json_decode($result);
        } else if($status == 204) {
            return true;
        }
        throw new \Exception('Http status is ' . $status . ': ' . $result);
    }

    public static function delete($url, $token = null) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        if($token) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token)); 
        }

        $result = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if($status == 200 && !empty($result)) {
            return json_decode($result);
        } else if($status == 204) {
            return true;
        }
        throw new \Exception('Http status is ' . $status . ': ' . $result);
    }
}