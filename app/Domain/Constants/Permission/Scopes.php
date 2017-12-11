<?php

namespace App\Domain\Constants\Permission;

class Scopes
{
    const ACCOUNT = 0;
    const COMPANY = 10;
    const CLIENT = 20;
    const DOCUMENT = 30;
    const COUNTRY = 40;

    public static function getById($id)
    {
        return [
            self::ACCOUNT => 'account',
            self::COMPANY => 'company',
            self::CLIENT => 'client',
            self::DOCUMENT => 'document',
            self::COUNTRY => 'country'
        ][$id];
    }

    public static function getByName($name)
    {
        return [
            'account'  => self::ACCOUNT,
            'company'  => self::COMPANY,
            'client'   => self::CLIENT,
            'document' => self::DOCUMENT,
            'country' => self::COUNTRY
        ][strtolower($name)];
    }
}