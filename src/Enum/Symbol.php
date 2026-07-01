<?php

declare(strict_types=1);

namespace Foxxything\Scryfall\Enum;

enum Symbol: string
{
    // Special / non-mana
    case Tap = '{T}';
    case Untap = '{Q}';
    case Energy = '{E}';
    case Pawprint = '{P}';
    case Planeswalker = '{PW}';
    case Chaos = '{CHAOS}';
    case Acorn = '{A}';
    case Ticket = '{TK}';
    case LandDrop = '{D}';

    // Variable generic
    case X = '{X}';
    case Y = '{Y}';
    case Z = '{Z}';

    // Generic numeric
    case Zero = '{0}';
    case Half = '{½}';
    case One = '{1}';
    case Two = '{2}';
    case Three = '{3}';
    case Four = '{4}';
    case Five = '{5}';
    case Six = '{6}';
    case Seven = '{7}';
    case Eight = '{8}';
    case Nine = '{9}';
    case Ten = '{10}';
    case Eleven = '{11}';
    case Twelve = '{12}';
    case Thirteen = '{13}';
    case Fourteen = '{14}';
    case Fifteen = '{15}';
    case Sixteen = '{16}';
    case Seventeen = '{17}';
    case Eighteen = '{18}';
    case Nineteen = '{19}';
    case Twenty = '{20}';
    case OneHundred = '{100}';
    case OneMillion = '{1000000}';
    case Infinity = '{∞}';

    // Basic colored mana
    case White = '{W}';
    case Blue = '{U}';
    case Black = '{B}';
    case Red = '{R}';
    case Green = '{G}';
    case Colorless = '{C}';
    case Snow = '{S}';
    case Legendary = '{L}';

    // Two-color hybrid
    case WhiteBlue = '{W/U}';
    case WhiteBlack = '{W/B}';
    case BlackRed = '{B/R}';
    case BlackGreen = '{B/G}';
    case BlueBlack = '{U/B}';
    case BlueRed = '{U/R}';
    case RedGreen = '{R/G}';
    case RedWhite = '{R/W}';
    case GreenWhite = '{G/W}';
    case GreenBlue = '{G/U}';

    // Phyrexian hybrid
    case BlackGreenPhyrexian = '{B/G/P}';
    case BlackRedPhyrexian = '{B/R/P}';
    case GreenBluePhyrexian = '{G/U/P}';
    case GreenWhitePhyrexian = '{G/W/P}';
    case RedGreenPhyrexian = '{R/G/P}';
    case RedWhitePhyrexian = '{R/W/P}';
    case BlueBlackPhyrexian = '{U/B/P}';
    case BlueRedPhyrexian = '{U/R/P}';
    case WhiteBlackPhyrexian = '{W/B/P}';
    case WhiteBluePhyrexian = '{W/U/P}';

    // Colorless hybrid
    case ColorlessWhite = '{C/W}';
    case ColorlessBlue = '{C/U}';
    case ColorlessBlack = '{C/B}';
    case ColorlessRed = '{C/R}';
    case ColorlessGreen = '{C/G}';

    // Generic-or-color hybrid
    case TwoOrWhite = '{2/W}';
    case TwoOrBlue = '{2/U}';
    case TwoOrBlack = '{2/B}';
    case TwoOrRed = '{2/R}';
    case TwoOrGreen = '{2/G}';

    // Phyrexian mono-color
    case AnyPhyrexian = '{H}';
    case WhitePhyrexian = '{W/P}';
    case BluePhyrexian = '{U/P}';
    case BlackPhyrexian = '{B/P}';
    case RedPhyrexian = '{R/P}';
    case GreenPhyrexian = '{G/P}';
    case ColorlessPhyrexian = '{C/P}';

    // Half-mana (funny/Un-set)
    case HalfWhite = '{HW}';
    case HalfRed = '{HR}';

    public function isFunny(): bool
    {
        return in_array($this, [
            self::Acorn, self::Y, self::Z, self::OneHundred, self::OneMillion,
            self::Infinity, self::Legendary, self::ColorlessPhyrexian,
            self::HalfWhite, self::HalfRed, self::LandDrop, self::Half,
        ], true);
    }
}