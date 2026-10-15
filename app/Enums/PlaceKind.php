<?php

namespace App\Enums;

enum PlaceKind: string
{
    case Hall = 'hall';
    case Platform = 'platform';
    case Train = 'train';
    case TicketOffice = 'ticket_office';
    case WaitingRoom = 'waiting_room';
    case StationEntrance = 'station_entrance';
    case Underpass = 'underpass';
    case Parking = 'parking';
    case BicycleParking = 'bicycle_parking';
    case Toilets = 'toilets';

    public function label(): string
    {
        return match ($this) {
            self::Hall => 'Hall',
            self::Platform => 'Quai',
            self::Train => 'Train',
            self::TicketOffice => 'Guichet',
            self::WaitingRoom => 'Salle d’attente',
            self::StationEntrance => 'Entrée de gare',
            self::Underpass => 'Passage souterrain',
            self::Parking => 'Parking',
            self::BicycleParking => 'Parking vélo',
            self::Toilets => 'Toilettes',
        };
    }
}
