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
        Device::factory(10)->create();

        // Actualités
        News::create([
            'title' => 'Menu de la semaine', 'slug' => Str::slug('Menu de la semaine'),
            'content' => 'Voici notre délicieux menu...', 'category' => 'menu',
            'is_published' => true, 'published_at' => now(), 'author_id' => $admin->id
        ]);
    }
}