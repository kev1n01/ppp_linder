<?php

function generate_email_from_name(string $fullName): string
{
    $firstName = explode(' ', trim($fullName))[0];
    $firstName = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $firstName));
    $randomPart = substr(md5(uniqid()), 0, 5);

    return "{$firstName}.{$randomPart}@example.com";
}
