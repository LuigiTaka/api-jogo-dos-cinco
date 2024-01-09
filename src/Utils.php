<?php

abstract class Utils
{

    static function checkPergunta(string $pergunta): bool
    {
        if (empty($pergunta)) {
            throw new Exception('Pergunta não informada.');
        }

        if (mb_strlen($pergunta) > self::getMaxLengthPergunta()) {
            throw new Exception("Pergunta informada maior que o limite máximo de caracteres.");
        }

        return true;
    }

    private static function getMaxLengthPergunta(): int
    {
        return 255;
    }

    public static function sanitizeString(string $string): string
    {
        return trim(htmlspecialchars($string));
    }


    public static function response(array $data, array $headers, int $status = 200): bool|string
    {


        ob_start();
        foreach ($headers as $header) {
            header($header);
        }
        self::responseStatus($status);
        echo json_encode($data);
        return ob_get_clean();
    }

    /**
     * Get header Authorization
     * */
    static public function getAuthorizationHeader(): ?string
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * get access token from header
     * */
    static public function getBearerToken()
    {
        $headers = self::getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public static function isValidToken(string $bearerToken): bool
    {
        //@todo Melhorar essa palhaçada
        //  lembrar de time attack
        return $bearerToken === self::getValidBearerToken();
    }

    private static function getValidBearerToken(): string
    {
        //@todo Melhorar essa palhaçada
        return "97a150fa8476669a43100b0e37ed55f6bad86cf49ca74e5b90e2caa233ae6821bc947e97ba8183097e6a059a9d7fbdcc4ec5cf100cc8c998165d1398c27579be";
    }

    private static function responseStatus(int $status)
    {
        header("HTTP/1.0 " . $status);
    }


}