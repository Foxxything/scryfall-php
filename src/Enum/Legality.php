<?php

namespace Foxxything\Scryfall\Enum;

enum Legality: string
{
    case Legal = 'legal';
    case NotLegal = 'not_legal';
    case Restricted = 'restricted';
    case Banned = 'banned';
}