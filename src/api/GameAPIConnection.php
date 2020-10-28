<?php


namespace drflvirtual\src\api;

class GameAPIConnection {

    public static function sendLoginRequest(int $player_id, string $password) {
        $response = static::sendPostRequest("login.php", array(
            "player_id" => $player_id,
            "password" => $password
        ));

        return $response;
    }

    static function generateHeaders(string $dataString) {
        $token = ($_SESSION && array_key_exists("token", $_SESSION)) ? $_SESSION["token"] : false;

        $headers = array(
            "Content-Type: application/json",
            'Content-Length: ' . strlen($dataString)
        );

        if ($token) $headers[] = "Token: $token";

        return $headers;
    }

    static function sendGetRequest($extension, $data=array()) {
        $curl = curl_init();

        // Parse data.
        $dataString = json_encode($data);

        // Generate headers.
        $headers = GameAPIConnection::generateHeaders($dataString);

        curl_setopt($curl, CURLOPT_URL, API_HOST . "/" . $extension . $dataString);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $exec = curl_exec($curl);

        $result = json_decode($exec, true);

        return $result;
    }

    static function sendPostRequest($extension, $data=array()) {
        $curl = curl_init();

        // Parse data.
        $dataString = json_encode($data);

        // Generate headers.
        $headers = GameAPIConnection::generateHeaders($dataString);

        curl_setopt($curl, CURLOPT_URL, API_HOST . "/" . $extension);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $exec = curl_exec($curl);

        $result = json_decode($exec, true);

        return $result;
    }


    /*function sendRequest($extension, string $method, $data=array()) {
        $curl = curl_init();

        // Parse data.
        $dataString = http_build_query($data);
        //$dataLength = strlen($dataString);

        // Generate headers.
        $headers = array(
            "Content-Type: application/json",
            'Content-Length: ' . strlen($dataString)
        );

        curl_setopt($curl, CURLOPT_URL, API_HOST . "/" . $extension . ((strtolower($method) == "post") ? "" : ($data ? "?" . $dataString : "")));

        curl_setopt($curl, CURLOPT_POST, $post);

        $token = ($_SESSION && array_key_exists("token", $_SESSION)) ? $_SESSION["token"] : false;

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $exec = curl_exec($curl);

        $result = json_decode($exec, true);

        return $result;
    }*/
}