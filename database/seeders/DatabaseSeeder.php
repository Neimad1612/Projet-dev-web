<?php
namespace Database\Seeders;

use App\Models\{DeviceCategory, Device, User, Zone, News};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void {
        // Zones
        $zones = [['name' => 'Cuisine', 'slug' => 'cuisine'], ['name' => 'Salle', 'slug' => 'salle']];
        foreach ($zones as $zone) Zone::firstOrCreate(['slug' => $zone['slug']], $zone);

        // Catégories
        $categories = [
        ['name' => 'Four', 'slug' => 'four', 'is_active' => 1],
        ['name' => 'Réfrigérateur', 'slug' => 'refrigerateur', 'is_active' => 1],
        ['name' => 'Thermostat', 'slug' => 'thermostat', 'is_active' => 1],
        ['name' => 'Borne commande', 'slug' => 'borne-commande', 'is_active' => 1],
        ['name' => 'Cave à vin', 'slug' => 'cave-a-vin', 'is_active' => 1],
        ['name' => 'Plonge', 'slug' => 'plonge', 'is_active' => 1],];
        foreach ($categories as $cat) DeviceCategory::firstOrCreate(['slug' => $cat['slug']], $cat);

        // Utilisateurs
        $admin = User::firstOrCreate(['email' => 'admin@chezleon.fr'], [
            'name' => 'Léon Admin', 'pseudo' => 'Admin', 'password' => Hash::make('password'),
            'role' => 'admin', 'level' => 'expert', 'experience_points' => 1500, 'is_approved' => true,
        ]);
        User::factory(5)->create(['role' => 'simple', 'is_approved' => true, 'level' => 'beginner']);

        // Appareils
        Device::factory()->count(10)->create()->each(function ($device) {
            $category = DeviceCategory::inRandomOrder()->first();

            $names = [
                'four' => 'Four connecté',
                'refrigerateur' => 'Réfrigérateur intelligent',
                'thermostat' => 'Thermostat IoT',
                'borne-commande' => 'Borne de commande tactile',
                'cave-a-vin' => 'Cave à vin connectée',
                'plonge' => 'Système de plonge automatisé',
            ];

            $baseName = $names[$category->slug] ?? 'Appareil connecté';

            $data = match ($category->slug) {
                'four' => [
                    'temperature' => fake()->randomFloat(1, 150, 250),
                    'power' => fake()->numberBetween(1000, 3000),
                ],
                'refrigerateur' => [
                    'temperature' => fake()->randomFloat(1, 0, 5),
                    'door_open' => fake()->boolean(10),
                ],
                'thermostat' => [
                    'temperature' => fake()->randomFloat(1, 18, 25),
                    'target_temperature' => fake()->randomFloat(1, 19, 23),
                ],
                'cave-a-vin' => [
                    'temperature' => fake()->randomFloat(1, 10, 14),
                    'humidity' => fake()->randomFloat(1, 60, 80),
                ],
                'borne-commande' => [
                    'temperature' => fake()->randomFloat(1, 20, 30),
                    'active_sessions' => fake()->numberBetween(0, 5),
                ],
                'plonge' => [
                    'temperature' => fake()->randomFloat(1, 30, 60),
                    'cycle_active' => fake()->boolean(50),
                ],
                default => [
                    'temperature' => fake()->randomFloat(1, 18, 25),
                    'status' => fake()->randomElement(['ok', 'warning']),
                ],
            };

            $device->update([
                'name' => $baseName . ' #' . fake()->numberBetween(1, 100),
                'status' => fake()->randomElement(['online', 'offline', 'maintenance']),
                'is_active' => fake()->boolean(80),
                'category_id' => $category->id,
                'zone_id' => Zone::inRandomOrder()->first()->id,
                'current_data' => $data,
            ]);
        });

        
        // Actualités
        $news = [
            [
                'title' => 'Nouveau four connecté en cuisine',
                'slug' => Str::slug('Nouveau four connecté en cuisine'),
                'excerpt' => 'Chez Léon modernise sa cuisine avec un four connecté permettant un meilleur suivi des températures.',
                'content' => 'Notre restaurant intègre un nouveau four connecté capable de suivre précisément la température, le temps de cuisson et l’état de fonctionnement. Cette amélioration permet une meilleure régularité en cuisine et une gestion plus efficace de l’énergie.',
                'category' => 'announcement',
                'is_published' => true,
                'published_at' => now(),
                'author_id' => $admin->id,
            ],
            [
                'title' => 'Optimisation de la cave à vin',
                'slug' => Str::slug('Optimisation de la cave à vin'),
                'excerpt' => 'La cave à vin connectée permet de surveiller la température et l’humidité en temps réel.',
                'content' => 'La cave à vin de Chez Léon est désormais suivie par des capteurs permettant de contrôler les conditions de conservation. Les données de température et d’humidité permettent d’assurer une meilleure stabilité pour les bouteilles sensibles.',
                'category' => 'maintenance',
                'is_published' => true,
                'published_at' => now(),
                'author_id' => $admin->id,
            ],
            [
                'title' => 'Menu de la semaine',
                'slug' => Str::slug('Menu de la semaine'),
                'excerpt' => 'Découvrez le menu proposé cette semaine par Chez Léon.',
                'content' => 'Voici notre délicieux menu de la semaine, élaboré à partir de produits frais et de saison.',
                'category' => 'menu',
                'is_published' => true,
                'published_at' => now(),
                'author_id' => $admin->id,
            ],
        ];

        foreach ($news as $item) {
            News::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}