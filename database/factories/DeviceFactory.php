<?php
namespace Database\Factories;

use App\Models\{Device, DeviceCategory, Zone, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    protected $model = Device::class;

    private static array $devices = [
        ['name' => 'Four convection', 'model' => 'Rational'],
        ['name' => 'Frigo pro', 'model' => 'Liebherr'],
        ['name' => 'Thermostat', 'model' => 'Honeywell'],
    ];

    public function definition(): array {
        $device = $this->faker->randomElement(self::$devices);
        return [
            'name' => $device['name'] . ' #' . $this->faker->numerify('##'),
            'serial_number' => strtoupper($this->faker->bothify('CL-####-??##')),
            'model' => $device['model'],
            'category_id' => DeviceCategory::inRandomOrder()->first()?->id ?? 1,
            'zone_id' => Zone::inRandomOrder()->first()?->id,
            'status' => $this->faker->randomElement(['online', 'online', 'offline']),
            'is_active' => true,
            'current_data' => ['temperature' => $this->faker->randomFloat(1, -5, 250)],
            'created_by' => User::where('role', 'complex')->inRandomOrder()->first()?->id,
        ];
    }

    public function online(): static { return $this->state(['status' => 'online', 'last_seen_at' => now()]); }
}