<?php

namespace App\Constante;

class UserRoleConstante
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ROLE_USER_LABEL = 'Utilisateur';
    public const ROLE_ADMIN_LABEL = 'Admin';

    public const MAP = [
        self::ROLE_ADMIN_LABEL => self::ROLE_ADMIN,
        self::ROLE_USER_LABEL => self::ROLE_USER
    ];

    public const MAP_INVERSE = [
        self::ROLE_ADMIN => self::ROLE_ADMIN_LABEL,
        self::ROLE_USER => self::ROLE_USER_LABEL
    ];
}
