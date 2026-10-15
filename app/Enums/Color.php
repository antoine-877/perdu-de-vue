<?php

namespace App\Enums;

enum Color: string
{
    case Black = 'black';
    case White = 'white';
    case Grey = 'grey';
    case Red = 'red';
    case Blue = 'blue';
    case Green = 'green';
    case Yellow = 'yellow';
    case Orange = 'orange';
    case Brown = 'brown';
    case Pink = 'pink';
    case Purple = 'purple';
    case Multicolor = 'multicolor';

    public function label(): string
    {
        return match ($this) {
            self::Black => 'Noir',
            self::White => 'Blanc',
            self::Grey => 'Gris',
            self::Red => 'Rouge',
            self::Blue => 'Bleu',
            self::Green => 'Vert',
            self::Yellow => 'Jaune',
            self::Orange => 'Orange',
            self::Brown => 'Brun',
            self::Pink => 'Rose',
            self::Purple => 'Mauve',
            self::Multicolor => 'Multicolore',
        };
    }
}
