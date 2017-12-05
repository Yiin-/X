<?php

namespace App\Domain\Constants\Permission;

class Actions
{
    const VIEW = 1;
    const CREATE = 2;
    const EDIT = 3;
    const DELETE = 4;
    const MANAGE = 5;

    public static function getAll()
    {
        return [
            self::VIEW,
            self::CREATE,
            self::EDIT,
            self::DELETE,
            self::MANAGE
        ];
    }

    public static function getById($id)
    {
        return [
            '' => '*',
            self::VIEW => 'view',
            self::CREATE => 'create',
            self::EDIT => 'edit',
            self::DELETE => 'delete',
            self::MANAGE => 'manage'
        ][$id];
    }

    public static function getByName($name)
    {
        return [
            'view' => self::VIEW,
            'create' => self::CREATE,
            'edit' => self::EDIT,
            'delete' => self::DELETE,
            'manage' => self::MANAGE
        ][strtolower($name)];
    }
}