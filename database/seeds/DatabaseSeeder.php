<?php

use App\User;
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
        if (!User::whereEmail('admin@mail.com')->first()){
            $user = User::create([
                    'name' => "admin",
                    'email' => "admin@mail.com",
                    'password' => Hash::make("123456789"),
                    'is_superuser' => 1,
                ]
            );
            $permissions = [
                ["name" => "show-post", "label" => "نمایش پست"],
                ["name" => "create-post", "label" => "ایجاد پست"],
                ["name" => "update-post", "label" => "ویرایش پست"],
                ["name" => "delete-post", "label" => "حذف پست"]
            ];


            \App\Permission::insert(
                $permissions
            );

        }

    }
}
