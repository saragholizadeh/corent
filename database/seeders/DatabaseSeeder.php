<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Regulation;
use App\Models\Sub_Category;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\DB;
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

        User::factory()
        ->count(20)
        ->create();


        // Regulation::factory()
        // ->count(20)
        // ->create();



        // Post::factory()
        // ->count(20)
        // ->create();


    }
}
