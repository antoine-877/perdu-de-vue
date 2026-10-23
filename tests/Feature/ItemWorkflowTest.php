<?php

use App\Enums\Color;
use App\Models\Claim;
use App\Models\Item;
use App\Models\Place;
use App\Models\Tag;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

function makeItem(array $attributes = []): Item
{
    return Item::factory()->create(array_merge([
        'color' => Color::Red,
        'found_on' => today()->toDateString(),
    ], $attributes));
}

test('visitors can switch between French and English and keep their choice', function () {
    $this->get(route('items.index'))
        ->assertOk()
        ->assertSee('Objets trouvés');

    $this->from(route('items.index'))
        ->post(route('locale.update'), ['locale' => 'en'])
        ->assertRedirect(route('items.index'));

    app('translator')->setLoaded([
        'fr' => [],
        'en' => [],
    ]);

    $this->get(route('items.index'))
        ->assertOk()
        ->assertSee('Found items')
        ->assertSee('Add an item')
        ->assertSee('Switch to French');

    $this->get(route('items.create'))
        ->assertOk()
        ->assertSee('Add a found item')
        ->assertSee('Main photo')
        ->assertSee('Location found *');

    $this->get(route('places.index'))
        ->assertOk()
        ->assertSee('Locations');

    $this->get(route('tags.index'))
        ->assertOk()
        ->assertSee('Tags');

    $item = makeItem(['name' => 'Blue suitcase']);
    $this->get(route('items.show', $item))
        ->assertOk()
        ->assertSee('Item details')
        ->assertSee('Date found:')
        ->assertSee('Status:');

    $this->from(route('items.index'))
        ->post(route('locale.update'), ['locale' => 'fr'])
        ->assertRedirect(route('items.index'));

    $this->get(route('items.index'))
        ->assertOk()
        ->assertSee('Objets trouvés')
        ->assertSee('Passer en anglais');
});

test('the database seeder assigns editable enum colors to example tags', function () {
    Tag::factory()->create(['name' => 'Électronique', 'color' => '#0D9488']);
    Tag::factory()->create(['name' => 'Vêtement', 'color' => '#0D9488']);

    $this->seed(DatabaseSeeder::class);

    expect(Tag::where('name', 'Électronique')->value('color'))->toBe(Color::Blue->value)
        ->and(Tag::where('name', 'Vêtement')->value('color'))->toBe(Color::Green->value)
        ->and(Tag::where('name', 'Insolite')->value('color'))->toBe(Color::Multicolor->value)
        ->and(Tag::whereIn('name', ['Électronique', 'Vêtement'])->distinct('color')->count('color'))->toBe(2);
});

test('tag badges apply enum and legacy hex colors as their background', function () {
    $enumTag = Tag::factory()->create(['name' => 'Tag bleu', 'color' => Color::Blue->value]);
    $legacyTag = Tag::factory()->create(['name' => 'Tag ancien', 'color' => '#0D9488']);

    $this->blade('<x-tag-badge :tag="$tag" />', ['tag' => $enumTag])
        ->assertSee('background-color: #2563eb; color: #ffffff;', false)
        ->assertSee('Tag bleu');

    $this->blade('<x-tag-badge :tag="$tag" />', ['tag' => $legacyTag])
        ->assertSee('background-color: #0D9488; color: #ffffff;', false);
});

test('item photos can be uploaded replaced and removed from public storage', function () {
    Storage::fake('public');
    $place = Place::factory()->create();
    $payload = [
        'name' => 'Parapluie photo',
        'color' => Color::Blue->value,
        'found_on' => today()->toDateString(),
        'place_id' => $place->id,
    ];

    $this->post(route('items.store'), $payload + ['photo' => UploadedFile::fake()->image('first.jpg')])
        ->assertRedirect();

    $item = Item::where('name', 'Parapluie photo')->firstOrFail();
    $firstPath = $item->photo_path;
    expect(Storage::disk('public')->exists($firstPath))->toBeTrue();
    $this->get(route('items.show', $item))->assertSee('/storage/'.$firstPath);
    $this->get(route('items.index'))->assertSee('/storage/'.$firstPath);

    $this->put(route('items.update', $item), $payload + ['photo' => UploadedFile::fake()->image('second.jpg')])
        ->assertRedirect();

    $item->refresh();
    $secondPath = $item->photo_path;
    expect($secondPath)->not->toBe($firstPath);
    expect(Storage::disk('public')->exists($firstPath))->toBeFalse()
        ->and(Storage::disk('public')->exists($secondPath))->toBeTrue();

    $this->put(route('items.update', $item), $payload + ['remove_photo' => '1'])
        ->assertRedirect();

    expect($item->fresh()->photo_path)->toBeNull();
    expect(Storage::disk('public')->exists($secondPath))->toBeFalse();

    $this->put(route('items.update', $item), $payload + ['photo' => UploadedFile::fake()->image('final.jpg')]);
    $finalPath = $item->fresh()->photo_path;
    $this->delete(route('items.destroy', $item));
    expect(Storage::disk('public')->exists($finalPath))->toBeFalse();
    $this->assertDatabaseMissing('photos', ['item_id' => $item->id]);
});

test('place photos are agent managed replaced removed and shown on cards', function () {
    Storage::fake('public');
    config(['services.agent_action_token' => 'place-agent-token']);
    $place = Place::factory()->create(['name' => 'Gare photo']);

    $this->post(route('places.photo.update', $place), [
        'photo' => UploadedFile::fake()->image('station.jpg'),
    ])->assertForbidden();

    $this->post(route('places.photo.update', $place), [
        'photo' => UploadedFile::fake()->image('station.jpg'),
        'agent_token' => 'place-agent-token',
    ])->assertRedirect(route('places.show', $place));

    $place->refresh();
    $firstPath = $place->photo_path;
    expect(Storage::disk('public')->exists($firstPath))->toBeTrue();
    $this->get(route('places.index'))->assertSee('/storage/'.$firstPath);
    $this->get(route('places.show', $place))->assertSee('/storage/'.$firstPath);

    $this->post(route('places.photo.update', $place), [
        'photo' => UploadedFile::fake()->image('station-new.jpg'),
        'agent_token' => 'place-agent-token',
    ])->assertRedirect(route('places.show', $place));

    $place->refresh();
    $secondPath = $place->photo_path;
    expect($secondPath)->not->toBe($firstPath)
        ->and(Storage::disk('public')->exists($firstPath))->toBeFalse()
        ->and(Storage::disk('public')->exists($secondPath))->toBeTrue();

    $this->post(route('places.photo.update', $place), [
        'remove_photo' => '1',
        'agent_token' => 'place-agent-token',
    ])->assertRedirect(route('places.show', $place));

    expect($place->fresh()->photo_path)->toBeNull()
        ->and(Storage::disk('public')->exists($secondPath))->toBeFalse();
});

test('item photo uploads reject non-image files', function () {
    $place = Place::factory()->create();

    $this->from(route('items.create'))
        ->post(route('items.store'), [
            'name' => 'Objet sans photo invalide',
            'color' => Color::Blue->value,
            'found_on' => today()->toDateString(),
            'place_id' => $place->id,
            'photo' => UploadedFile::fake()->create('notes.txt', 2, 'text/plain'),
        ])
        ->assertRedirect(route('items.create'))
        ->assertSessionHasErrors('photo');

    $this->assertDatabaseMissing('items', ['name' => 'Objet sans photo invalide']);
});

test('gallery photos require an agent token and maintain a replacement cover', function () {
    Storage::fake('public');
    config(['services.agent_action_token' => 'gallery-agent-token']);
    $item = makeItem(['name' => 'Objet galerie']);

    $this->post(route('photos.store', $item), [
        'photos' => [UploadedFile::fake()->image('one.jpg'), UploadedFile::fake()->image('two.jpg')],
    ])->assertForbidden();

    $this->post(route('photos.store', $item), [
        'photos' => [UploadedFile::fake()->image('one.jpg'), UploadedFile::fake()->image('two.jpg')],
        'agent_token' => 'gallery-agent-token',
    ])->assertRedirect(route('items.show', $item));

    $photos = $item->photos()->orderBy('id')->get();
    expect($photos)->toHaveCount(2)
        ->and($photos[0]->is_cover)->toBeTrue()
        ->and($photos[1]->is_cover)->toBeFalse();
    expect(Storage::disk('public')->exists($photos[0]->path))->toBeTrue()
        ->and(Storage::disk('public')->exists($photos[1]->path))->toBeTrue();

    $this->patch(route('photos.cover', [$item, $photos[1]]), ['agent_token' => 'gallery-agent-token'])
        ->assertRedirect(route('items.show', $item));
    expect($item->fresh()->photo_path)->toBe($photos[1]->path)
        ->and($photos[1]->fresh()->is_cover)->toBeTrue()
        ->and($photos[0]->fresh()->is_cover)->toBeFalse();

    $this->delete(route('photos.destroy', [$item, $photos[1]]), ['agent_token' => 'gallery-agent-token'])
        ->assertRedirect(route('items.show', $item));
    expect($item->fresh()->photo_path)->toBe($photos[0]->path)
        ->and($photos[0]->fresh()->is_cover)->toBeTrue();
    expect(Storage::disk('public')->exists($photos[1]->path))->toBeFalse()
        ->and(Storage::disk('public')->exists($photos[0]->path))->toBeTrue();
});

test('item search sorting pagination and query parameters work together', function () {
    $place = Place::factory()->create();
    foreach (range(1, 11) as $number) {
        makeItem([
            'place_id' => $place->id,
            'name' => sprintf('Objet recherché %02d', 12 - $number),
            'found_on' => today()->subDays($number)->toDateString(),
        ]);
    }
    makeItem(['name' => 'Autre objet']);

    $response = $this->get(route('items.index', [
        'q' => 'recherché',
        'lieu' => $place->id,
        'couleur' => Color::Red->value,
        'tri' => 'nom',
    ]));

    $response->assertOk()
        ->assertSee('Objet recherché 01')
        ->assertSee('Objet recherché 09')
        ->assertDontSee('Objet recherché 10')
        ->assertDontSee('Autre objet')
        ->assertSee('value="recherché"', false)
        ->assertSeeInOrder(['Objet recherché 01', 'Objet recherché 02', 'Objet recherché 03'])
        ->assertSee('q=recherch%C3%A9');

    $paginationText = preg_replace('/\s+/', ' ', strip_tags($response->getContent()));
    expect($paginationText)->toContain('Affiche les résultats 1 à 9 sur 11');
});

test('travelers can create claims without an item match', function () {
    $this->get(route('claims.create'))->assertOk()->assertSee('Déclarer un objet perdu');
    $payload = [
        'claimant_name' => 'Camille Martin',
        'email' => 'camille@example.test',
        'description' => 'Sac à dos bleu oublié dans le train.',
    ];

    $this->post(route('claims.store'), $payload)->assertRedirect(route('claims.create'));
    $this->assertDatabaseHas('claims', $payload + ['item_id' => null]);

    $this->post(route('claims.store'), $payload)->assertSessionHasNoErrors();
    expect(Claim::where('email', $payload['email'])->count())->toBe(2);
});

test('only a configured agent token can associate a claim with an item', function () {
    $claim = Claim::create([
        'claimant_name' => 'Alex',
        'email' => 'alex@example.test',
        'description' => 'Un livre perdu.',
    ]);
    $item = makeItem();
    config(['services.agent_action_token' => 'agent-secret-test']);

    $this->post(route('claims.link', $claim), ['item_id' => $item->id])
        ->assertForbidden();
    $this->post(route('claims.link', $claim), ['item_id' => $item->id, 'agent_token' => 'agent-secret-test'])
        ->assertOk();

    $this->assertDatabaseHas('claims', ['id' => $claim->id, 'item_id' => $item->id]);
});

test('statistics show empty state and grouped object counts', function () {
    $this->get(route('statistics.index'))->assertOk()->assertSee('Aucun objet enregistré.');
    $place = Place::factory()->create(['name' => 'Gare centrale']);
    makeItem(['place_id' => $place->id, 'found_on' => '2026-10-02']);
    makeItem(['place_id' => $place->id, 'found_on' => '2026-10-20', 'returned_at' => today()]);

    $this->get(route('statistics.index'))
        ->assertOk()
        ->assertSee('Gare centrale')
        ->assertSee('octobre 2026')
        ->assertSee('2');
});

test('the objects index shows only active items and filters by place and color', function () {
    $place = Place::factory()->create();
    $otherPlace = Place::factory()->create();
    $matching = makeItem(['place_id' => $place->id, 'name' => 'Parapluie rouge']);
    makeItem(['place_id' => $otherPlace->id, 'color' => Color::Blue, 'name' => 'Sac bleu']);
    makeItem(['place_id' => $place->id, 'returned_at' => today(), 'name' => 'Objet rendu']);

    $response = $this->get(route('items.index', ['lieu' => $place->id, 'couleur' => Color::Red->value]));

    $response->assertOk()
        ->assertSee($matching->name)
        ->assertDontSee('Sac bleu')
        ->assertDontSee('Objet rendu')
        ->assertSee('Rouge');
});

test('a place page includes returned items and their colored and tag badges', function () {
    $place = Place::factory()->create();
    $tag = Tag::factory()->create(['name' => 'Accessoire']);
    $item = makeItem([
        'place_id' => $place->id,
        'returned_at' => today(),
        'name' => 'Parapluie rendu',
    ]);
    $item->tags()->attach($tag);

    $this->get(route('places.show', $place))
        ->assertOk()
        ->assertSee($item->name)
        ->assertSee('Accessoire')
        ->assertSee('Rouge')
        ->assertSee('Restitué');
});

test('creating an item validates fields and syncs selected tags', function () {
    $place = Place::factory()->create();
    $tag = Tag::factory()->create();

    $this->from(route('items.create'))
        ->post(route('items.store'), [
            'name' => 'X',
            'color' => 'not-a-color',
            'found_on' => today()->addDay()->toDateString(),
            'place_id' => $place->id,
        ])
        ->assertRedirect(route('items.create'))
        ->assertSessionHasErrors(['name', 'color', 'found_on']);

    $this->post(route('items.store'), [
        'name' => 'Clés trouvées',
        'color' => Color::Blue->value,
        'found_on' => today()->toDateString(),
        'place_id' => $place->id,
        'tags' => [$tag->id],
    ])->assertRedirect();

    $item = Item::where('name', 'Clés trouvées')->firstOrFail();
    expect($item->tags->modelKeys())->toBe([$tag->id]);
});

test('updating an item uses the same validation and syncs its tags', function () {
    $item = makeItem(['name' => 'Trousseau']);
    $firstTag = Tag::factory()->create();
    $nextTag = Tag::factory()->create();
    $item->tags()->attach($firstTag);

    $this->from(route('items.edit', $item))
        ->put(route('items.update', $item), [
            'name' => 'T',
            'color' => Color::Green->value,
            'found_on' => today()->toDateString(),
            'place_id' => $item->place_id,
            'tags' => [$nextTag->id],
        ])
        ->assertRedirect(route('items.edit', $item))
        ->assertSessionHasErrors('name');

    $editHtml = $this->get(route('items.edit', $item))->getContent();
    expect(preg_match('/id="tag-'.$nextTag->id.'" value="'.$nextTag->id.'"\s+checked/', $editHtml))->toBe(1)
        ->and(preg_match('/id="tag-'.$firstTag->id.'" value="'.$firstTag->id.'"\s+checked/', $editHtml))->toBe(0);

    $this->put(route('items.update', $item), [
        'name' => 'Trousseau mis à jour',
        'color' => Color::Green->value,
        'found_on' => today()->toDateString(),
        'place_id' => $item->place_id,
        'tags' => [$nextTag->id],
    ])->assertRedirect(route('items.show', $item));

    expect($item->fresh()->tags->modelKeys())->toBe([$nextTag->id]);
});

test('returning an item sets today once and removes it from the active list', function () {
    $item = makeItem(['name' => 'Clé perdue']);

    $this->patch(route('items.return', $item))
        ->assertRedirect(route('items.show', $item))
        ->assertSessionHas('success');

    expect($item->fresh()->returned_at->toDateString())->toBe(today()->toDateString());
    $this->get(route('items.index'))
        ->assertDontSee('<article class="item-card">')
        ->assertSee('Aucun objet trouvé pour le moment.');

    $previousDate = today()->subDays(2)->toDateString();
    $item->update(['returned_at' => $previousDate]);
    $this->patch(route('items.return', $item));
    expect($item->fresh()->returned_at->toDateString())->toBe($previousDate);
});
