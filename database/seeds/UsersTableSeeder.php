<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class)->create([
        	'name' => 'Admin',
			'email' => 'admin@admin.com'
		]);

		factory(\App\Models\User::class)->create([
			'name' => 'Atacadista',
			'email' => 'atacadista@scsgame.tk'
		]);

		factory(\App\Models\User::class)->create([
			'name' => 'Varejista',
			'email' => 'varejista@scsgame.tk'
		]);

		factory(\App\Models\User::class)->create([
			'name' => 'Distribuidor',
			'email' => 'distribuidor@scsgame.tk'
		]);

		factory(\App\Models\User::class)->create([
			'name' => 'Fabricante',
			'email' => 'fabricante@scsgame.tk'
		]);
    }
}
