<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\Marriage;

class FamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generation 1: Grandparents
        $grandpa = Person::create([
            'name' => 'Buyut Ahmad',
            'gender' => 'male',
            'birth_date' => '1940-05-15',
            'generation' => 1,
        ]);

        $grandma = Person::create([
            'name' => 'Buyut Siti',
            'gender' => 'female',
            'birth_date' => '1943-08-20',
            'generation' => 1,
        ]);

        // Marriage between grandparents
        Marriage::create([
            'person1_id' => $grandpa->id,
            'person2_id' => $grandma->id,
            'marriage_date' => '1962-06-10',
        ]);

        // Generation 2: Parents - First family
        $father1 = Person::create([
            'name' => 'Pak Budi',
            'gender' => 'male',
            'birth_date' => '1965-03-20',
            'parent_id' => $grandpa->id,
            'generation' => 2,
        ]);

        $mother1 = Person::create([
            'name' => 'Ibu Ani',
            'gender' => 'female',
            'birth_date' => '1968-07-15',
            'generation' => 2,
        ]);

        Marriage::create([
            'person1_id' => $father1->id,
            'person2_id' => $mother1->id,
            'marriage_date' => '1990-12-01',
        ]);

        // Generation 3: Children of first family
        Person::create([
            'name' => 'Riza',
            'gender' => 'male',
            'birth_date' => '1992-01-15',
            'parent_id' => $father1->id,
            'generation' => 3,
        ]);

        Person::create([
            'name' => 'Sinta',
            'gender' => 'female',
            'birth_date' => '1995-06-20',
            'parent_id' => $father1->id,
            'generation' => 3,
        ]);

        Person::create([
            'name' => 'Doni',
            'gender' => 'male',
            'birth_date' => '1997-11-10',
            'parent_id' => $father1->id,
            'generation' => 3,
        ]);

        // Generation 2: Parents - Second family
        $father2 = Person::create([
            'name' => 'Pak Rifki',
            'gender' => 'male',
            'birth_date' => '1970-09-25',
            'parent_id' => $grandpa->id,
            'generation' => 2,
        ]);

        $mother2 = Person::create([
            'name' => 'Ibu Dewi',
            'gender' => 'female',
            'birth_date' => '1972-12-08',
            'generation' => 2,
        ]);

        Marriage::create([
            'person1_id' => $father2->id,
            'person2_id' => $mother2->id,
            'marriage_date' => '1995-05-20',
        ]);

        // Generation 3: Children of second family
        Person::create([
            'name' => 'Farah',
            'gender' => 'female',
            'birth_date' => '1996-04-12',
            'parent_id' => $father2->id,
            'generation' => 3,
        ]);

        Person::create([
            'name' => 'Ahmad',
            'gender' => 'male',
            'birth_date' => '1999-08-30',
            'parent_id' => $father2->id,
            'generation' => 3,
        ]);
    }
}

