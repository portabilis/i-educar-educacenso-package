<?php

namespace iEducar\Packages\Educacenso\Database\Factories;

use Database\Factories\LegacyUserFactory;
use iEducar\Packages\Educacenso\Models\EducacensoInepImport;
use Illuminate\Database\Eloquent\Factories\Factory;

class EducacensoInepImportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EducacensoInepImport::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'year' => $this->faker->randomElement([2022, 2023]),
            'school_name' => $this->faker->name,
            'user_id' => fn () => LegacyUserFactory::new()->current(),
        ];
    }
}
