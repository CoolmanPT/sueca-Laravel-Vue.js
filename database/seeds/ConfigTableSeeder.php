<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $platform_email = 'recurso.sueca@gmail.com';
        $driver = 'smtp';
        $host = 'smtp.gmail.com';
        $port = 587;
        $password = 'projetodad';
        $encryption = 'tls';
        $filesPath = 'tiles';
        $createdAt = Carbon\Carbon::now()->subMonths(2);

        $configInfo = [
            'platform_email' => $platform_email,
            'platform_email_properties' => "{\"driver\": \"$driver\", \"host\": \"$host\", \"port\": $port, \"password\": \"$password\", \"encryption\": \"$encryption\" }",
            'img_base_path' => 'img/decks/',
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];

        DB::table('config')->insert($configInfo);

        $passportData = [
            'name' => "Laravel Personal Access Client",
            'secret' => "ZF2nTD3YMqSiK6dmJGLlmQtSlFINzNZRWICSyOXh",
            'redirect' => "http://localhost",
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
            'created_at'=> '2018-02-2 21:14:43',
            'updated_at' => '2018-02-2 21:14:43'
        ];

        DB::table('oauth_clients')->insert($passportData);

        $passportData = [
            'name' => "Laravel Personal Access Client",
            'secret' => "5sEoB7ZQi8tqvX3gH38hEPQ00CZdJkqD51eJh3k7",
            'redirect' => "http://localhost",
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
            'created_at'=> '2018-02-2 21:14:43',
            'updated_at' => '2018-02-2 21:14:43'
        ];

        DB::table('oauth_clients')->insert($passportData);

        $user = [
            'name' => "bruno",
            'email' => "brunocaspereira@gmail.com",
            'password' => bcrypt('secret'),
            'nickname' => "bruno",
            'admin' => 1,
            'blocked' => false,
            'reason_blocked' => null,
            'reason_reactivated' => null,
            'activated' => true,
            'created_at'=> '2018-02-2 21:14:43',
            'updated_at' => '2018-02-2 21:14:43'
        ];

        DB::table('users')->insert($user);


	    $deck = [
		    'name' => "default",
		    'hidden_face_image_path' => "img/cards/semFace.png",
		    'active' => 1,
		    'complete' => '1',
		    'created_at' => Carbon\Carbon::now()->subMonths(2)
	    ];

	    DB::table('decks')->insert($deck);

	    $path = 'resources/cartas.sql';
	    DB::unprepared(file_get_contents($path));

    }


}
