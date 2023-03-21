<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $user = new User();
        $user->firstname = "Franca";
        $user->lastname = "Ibifuro";
        $user->username  = "franca";
        $user->mobile   = "07031542570";
        $user->email  = "franca@gmail.com";
        $user->password = Hash::make("22222222");

        $user->save();

        $folder = new Folder();
        $folder->user_id = $user->id;
        $folder->bvn = Hash::make("22222222");
        $folder->save();
    }
}
