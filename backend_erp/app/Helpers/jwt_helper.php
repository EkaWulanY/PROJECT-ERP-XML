<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generateJWT($payload)
{
    $key = getenv('JWT_SECRET');
    $exp = time() + getenv('JWT_EXP');

    $payload['iat'] = time();
    $payload['exp'] = $exp;

    return JWT::encode($payload, $key, 'HS256');
}

function verifyJWT($token)
{
    try {
        $key = getenv('JWT_SECRET');
        return JWT::decode($token, new Key($key, 'HS256'));
    } catch (\Exception $e) {
        return null;
    }
}
