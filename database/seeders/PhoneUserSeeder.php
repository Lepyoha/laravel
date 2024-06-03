<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PhoneNumber;
use App\Models\User;

class PhoneUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $phoneNumbers = PhoneNumber::all();
        $users = User::all();

        foreach ($users as $user) {
            foreach ($phoneNumbers as $index => $phoneNumber) {
                $user->phoneNumbers()->attach($phoneNumber->id, ['is_primary' => $index === 0]);
            }
        }
    }
}
