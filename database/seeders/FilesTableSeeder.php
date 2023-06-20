<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;


class FilesTableSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();

        $filePaths = ['upload/to/file1.txt', 'upload/to/file2.pdf', 'upload/to/file3.pdf'];

        foreach ($filePaths as $filePath) {
            DB::table('files')->insert([
                'user_id' => $user->id,
                'file_path' => $filePath,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}