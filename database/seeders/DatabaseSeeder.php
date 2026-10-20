<?php

namespace Database\Seeders;

use App\Enums\Color;
use App\Enums\PlaceKind;
use App\Models\Item;
use App\Models\Place;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Les quatre lieux associés aux objets de démonstration
        $hall = Place::create([
            'name' => 'Hall des guichets',
            'kind' => PlaceKind::Hall->value,
        ]);

        $quai1 = Place::create([
            'name' => 'Quai 1',
            'kind' => PlaceKind::Platform->value,
        ]);

        $quai2 = Place::create([
            'name' => 'Quai 2',
            'kind' => PlaceKind::Platform->value,
        ]);

        $train = Place::create([
            'name' => 'Train Bruxelles-Charleroi',
            'kind' => PlaceKind::Train->value,
        ]);

        // 2. Les sept lieux vides
        // Ils existent en base de données, mais n'ont aucun objet associé.
        foreach ([
            PlaceKind::TicketOffice,
            PlaceKind::WaitingRoom,
            PlaceKind::StationEntrance,
            PlaceKind::Underpass,
            PlaceKind::Parking,
            PlaceKind::BicycleParking,
            PlaceKind::Toilets,
        ] as $kind) {
            Place::create([
                'name' => $kind->label(),
                'kind' => $kind->value,
            ]);
        }

        // 3. Les huit tags
        $tags = [];

        foreach ([
            'Électronique',
            'Vêtement',
            'Accessoire',
            'Jouet',
            'Document',
            'Sport',
            'Nourriture',
            'Insolite',
        ] as $name) {
            $tags[$name] = Tag::create([
                'name' => $name,
            ]);
        }

        // 4. Les quinze objets
        $items = [
            [
                'place' => $hall,
                'name' => 'Gérard, le nain de jardin',
                'description' => 'Un nain de jardin barbu avec un bonnet rouge et un air très sérieux.',
                'color' => Color::Green,
                'found_on' => '2026-10-02',
                'returned_at' => null,
                'tags' => ['Jouet', 'Insolite'],
            ],
            [
                'place' => $quai1,
                'name' => 'Chaussure de sport solitaire',
                'description' => 'Une chaussure taille 44, partie sans son propriétaire.',
                'color' => Color::Blue,
                'found_on' => '2026-10-03',
                'returned_at' => '2026-10-04',
                'tags' => ['Vêtement', 'Sport'],
            ],
            [
                'place' => $train,
                'name' => 'Poisson rouge en plastique',
                'description' => 'Un poisson rouge décoratif dans un petit sac transparent.',
                'color' => Color::Orange,
                'found_on' => '2026-10-04',
                'returned_at' => null,
                'tags' => ['Jouet', 'Insolite'],
            ],
            [
                'place' => $hall,
                'name' => 'Parapluie banane',
                'description' => 'Un parapluie jaune vif en forme de banane.',
                'color' => Color::Yellow,
                'found_on' => '2026-10-05',
                'returned_at' => null,
                'tags' => ['Accessoire', 'Insolite'],
            ],
            [
                'place' => $quai2,
                'name' => 'Thermos Papa champion',
                'description' => 'Un thermos cabossé avec une inscription enthousiaste.',
                'color' => Color::Red,
                'found_on' => '2026-10-06',
                'returned_at' => '2026-10-08',
                'tags' => ['Accessoire'],
            ],
            [
                'place' => $train,
                'name' => 'Peluche lama à lunettes',
                'description' => 'Un lama en peluche portant de petites lunettes rondes.',
                'color' => Color::White,
                'found_on' => '2026-10-07',
                'returned_at' => null,
                'tags' => ['Jouet', 'Insolite'],
            ],
            [
                'place' => $quai1,
                'name' => 'Sac de frites vide',
                'description' => 'Un emballage de frites soigneusement plié.',
                'color' => Color::Yellow,
                'found_on' => '2026-10-08',
                'returned_at' => null,
                'tags' => ['Nourriture', 'Insolite'],
            ],
            [
                'place' => $hall,
                'name' => 'Livre de recettes annoté',
                'description' => 'Un livre de recettes rempli de notes manuscrites.',
                'color' => Color::Brown,
                'found_on' => '2026-10-09',
                'returned_at' => null,
                'tags' => ['Document'],
            ],
            [
                'place' => $quai2,
                'name' => 'Casque de vélo à sonnette rose',
                'description' => 'Un casque noir équipé d’une petite sonnette rose.',
                'color' => Color::Black,
                'found_on' => '2026-10-10',
                'returned_at' => null,
                'tags' => ['Sport', 'Accessoire'],
            ],
            [
                'place' => $train,
                'name' => 'Boîte à tartines fusée',
                'description' => 'Une boîte à tartines décorée de fusées et de planètes.',
                'color' => Color::Blue,
                'found_on' => '2026-10-11',
                'returned_at' => null,
                'tags' => ['Nourriture', 'Insolite'],
            ],
            [
                'place' => $quai1,
                'name' => 'Lunettes orange',
                'description' => 'Des lunettes de soleil avec une monture orange voyante.',
                'color' => Color::Orange,
                'found_on' => '2026-10-12',
                'returned_at' => '2026-10-15',
                'tags' => ['Accessoire'],
            ],
            [
                'place' => $hall,
                'name' => 'Écharpe verte',
                'description' => 'Une écharpe verte à laquelle il manque un bouton.',
                'color' => Color::Green,
                'found_on' => '2026-10-13',
                'returned_at' => null,
                'tags' => ['Vêtement'],
            ],
            [
                'place' => $quai2,
                'name' => 'Canard en plastique en costume',
                'description' => 'Un petit canard jaune portant un nœud papillon noir.',
                'color' => Color::Yellow,
                'found_on' => '2026-10-14',
                'returned_at' => null,
                'tags' => ['Jouet', 'Insolite'],
            ],
            [
                'place' => $train,
                'name' => 'Calculatrice autocollante',
                'description' => 'Une calculatrice couverte d’autocollants colorés.',
                'color' => Color::Black,
                'found_on' => '2026-10-16',
                'returned_at' => null,
                'tags' => ['Électronique', 'Document'],
            ],
            [
                'place' => $quai1,
                'name' => 'Manique crocodile',
                'description' => 'Une manique de cuisine verte en forme de crocodile.',
                'color' => Color::Green,
                'found_on' => '2026-10-19',
                'returned_at' => null,
                'tags' => ['Accessoire', 'Insolite'],
            ],
        ];

        foreach ($items as $data) {
            $item = Item::create([
                'place_id' => $data['place']->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'color' => $data['color']->value,
                'found_on' => $data['found_on'],
                'returned_at' => $data['returned_at'],
            ]);

            $tagIds = [];

            foreach ($data['tags'] as $tagName) {
                $tagIds[] = $tags[$tagName]->id;
            }

            $item->tags()->attach($tagIds);
        }
    }
}
