<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'surname' => 'Business',
            'name' => 'Owner',
            'email' => 'business.owner@gmail.com',
            'password' => Hash::make('e2KZ75xTcYZKy5o8'),
            'role' => UserRole::OWNER,
            'img_path' => '',
        ]);

        User::create([
            'surname' => 'System',
            'name' => 'Admin',
            'email' => 'system.admin@gmail.com',
            'password' => Hash::make('e2KZ75xTcYZKy5o8'),
            'role' => UserRole::ADMIN,
            'img_path' => '',
        ]);

        User::create([
            'surname' => 'Green',
            'name' => 'Rachel',
            'email' => 'rachel.green@gmail.com',
            'password' => Hash::make('e2KZ75xTcYZKy5o8'),
            'role' => UserRole::MANAGER,
            'img_path' => 'https://yuichiroyamaji-todo.s3.ap-northeast-1.amazonaws.com/assets/img/rachel.png',
        ]);

        User::create([
            'surname' => 'Tribbiani',
            'name' => 'Joey',
            'email' => 'joey.tribbiani@gmail.com',
            'password' => Hash::make('e2KZ75xTcYZKy5o8'),
            'role' => UserRole::MEMBER,
            'img_path' => 'https://yuichiroyamaji-todo.s3.ap-northeast-1.amazonaws.com/assets/img/joey.png',
        ]);

        User::create([
            'surname' => 'Bing',
            'name' => 'Chandler',
            'email' => 'chandler.bing@gmail.com',
            'password' => Hash::make('e2KZ75xTcYZKy5o8'),
            'role' => UserRole::MEMBER,
            'img_path' => 'https://yuichiroyamaji-todo.s3.ap-northeast-1.amazonaws.com/assets/img/chandler.png',
        ]);

        User::create([
            'surname' => 'Geller',
            'name' => 'Monica',
            'email' => 'monica.geller@gmail.com',
            'password' => Hash::make('e2KZ75xTcYZKy5o8'),
            'role' => UserRole::MEMBER,
            'img_path' => 'https://yuichiroyamaji-todo.s3.ap-northeast-1.amazonaws.com/assets/img/monica.png',
        ]);

        User::create([
            'surname' => 'Buffay',
            'name' => 'Phoebe',
            'email' => 'phoebe.buffay@gmail.com',
            'password' => Hash::make('e2KZ75xTcYZKy5o8'),
            'role' => UserRole::MEMBER,
            'img_path' => 'https://yuichiroyamaji-todo.s3.ap-northeast-1.amazonaws.com/assets/img/phoebe.png',
        ]);

        User::create([
            'surname' => 'Geller',
            'name' => 'Ross',
            'email' => 'ross.geller@gmail.com',
            'password' => Hash::make('e2KZ75xTcYZKy5o8'),
            'role' => UserRole::MEMBER,
            'img_path' => 'https://yuichiroyamaji-todo.s3.ap-northeast-1.amazonaws.com/assets/img/ross.png',
        ]);
    }
}
