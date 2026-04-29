<?php
namespace Database\Factories;

use App\Models\{Device, DeviceCategory, Zone, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    protected $model = Device::class;

    public function definition(): array
    {
        $category = DeviceCategory::inRandomOrder()->first();

        $namePool = match ($category?->slug) {
            'four' => ['Four convection', 'Four vapeur', 'Four professionnel'],
            'refrigerateur' => ['Frigo pro', 'Réfrigérateur industriel', 'Chambre froide'],
            'thermostat' => ['Thermostat mural', 'Thermostat connecté', 'Régulateur température'],
            'borne-commande' => ['Borne commande', 'Terminal tactile', 'Interface cuisine'],
            'cave-a-vin' => ['Cave à vin', 'Cellier contrôlé', 'Armoire vin'],
            default => ['Capteur', 'Appareil connecté', 'Module IoT'],
        };

        $baseName = fake()->randomElement($namePool);

        return [
            'name' => $baseName . ' #' . fake()->numerify('##'),
            'serial_number' => strtoupper(fake()->bothify('CL-####-??##')),
            'model' => fake()->company(),

            'category_id' => $category?->id ?? 1,
            'zone_id' => Zone::inRandomOrder()->first()?->id,

            'status' => fake()->randomElement(['online', 'offline', 'maintenance']),
            'is_active' => true,

            'current_data' => [
                'temperature' => match ($category?->slug) {
                    'refrigerateur' => fake()->randomFloat(1, -5, 6),
                    'four' => fake()->randomFloat(1, 150, 250),
                    'thermostat' => fake()->randomFloat(1, 18, 25),
                    default => fake()->randomFloat(1, 10, 30),
                },
            ],

            'created_by' => User::where('role', 'complex')->inRandomOrder()->first()?->id,
        ];
    }
}