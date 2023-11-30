<?php

namespace App\Constante;

class AdresseTypeConstante
{
    public const LIVRAISON = 'livraison';
    public const FACTURATION = 'facturation';

    public const MAP = [
        self::LIVRAISON => self::LIVRAISON,
        self::FACTURATION => self::FACTURATION
    ];
}
