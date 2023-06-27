<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\File;

class SharedFilesTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $files = File::all();

        foreach ($users as $user) {
            foreach ($files as $file) {
                if ($user->id !== $file->user_id) {
                    DB::table('shared_files')->insert([
                        'user_id' => $file->user_id,
                        'recipient_id' => $user->id,
                        'file_id' => $file->id,
                        'can_view' => rand(0, 1),
                        'can_edit' => rand(0, 1),
                        'can_delete' => rand(0, 1),
                    ]);
                }
            }
        }
    }
}