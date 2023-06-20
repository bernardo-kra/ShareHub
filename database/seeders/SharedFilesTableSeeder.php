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
        $user = User::first();
        $recipient = User::find(2);
        $file = File::first();

        $sharedFilesCount = 5;

        for ($i = 0; $i < $sharedFilesCount; $i++) {
            DB::table('shared_files')->insert([
                'user_id' => $user->id,
                'recipient_id' => $recipient->id,
                'file_id' => $file->id,
            ]);
        }
    }
}