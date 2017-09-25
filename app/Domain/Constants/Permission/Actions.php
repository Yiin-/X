<?php

namespace App\Domain\Constants\Permission;

class Actions
{
    const VIEW = 'view';
    const CREATE = 'create';
    const EDIT = 'edit';
    const ARCHIVE = 'archive';
    const DELETE = 'delete';
    const EXPORT = 'export';
    const IMPORT = 'import';

    const LIST = [
        self::VIEW,
        self::CREATE,
        self::EDIT,
        self::ARCHIVE,
        self::DELETE,
        self::EXPORT,
        self::IMPORT
    ];

    const LIST_DOCUMENT_ACTIONS = [
        self::VIEW,
        self::EDIT,
        self::ARCHIVE,
        self::DELETE,
        self::EXPORT,
        self::IMPORT
    ];
}