<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category')->truncate();

        DB::table('category')->insert([
            [
                'name' => 'Category A'
            ],
            [
                'name' => 'Category B'
            ],
            [
                'name' => 'Category C'
            ],
        ]);
    }
}
