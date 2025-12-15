<?php
namespace src\Service;

use Firebase\JWT\JWT;

class JwtService{
    public static String $secretKey = "cesi";
    public static function createToken(array $datas){
        $issuedAt = new \DateTimeImmutable();
        $expireAt = $issuedAt->modify("+3 minutes")->getTimestamp();
        $serverName = "cesi.local";
        $data = [
            'iat' => $issuedAt->getTimestamp(), //Date de génération du token
            'iss' => $serverName,
            'nbf' => $issuedAt->getTimestamp(),//Inutilisable avant le ....
            'exp' => $expireAt, //Date expiration
            'datas' => $datas
        ];
        $jwt = JWT::encode($data, self::$secretKey,'HS512');
        return $jwt;
    }
}