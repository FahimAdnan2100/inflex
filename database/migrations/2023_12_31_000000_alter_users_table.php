<?php

use App\Enums\UserType;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('user_type')->default(UserType::WebsiteUser);
        });
        DB::table('users')->insert([
            ['name' => 'Admin', 'password' => Hash::make('11111111'), 'email' => 'admin@example.com', 'email_verified_at' => Carbon::now(), 'user_type' => UserType::SystemUser],
            ['name' => 'User1',  'password' => Hash::make('11111111'), 'email' => 'user1@example.com', 'email_verified_at' => Carbon::now(), 'user_type' => UserType::WebsiteUser],
            ['name' => 'User2',  'password' => Hash::make('11111111'), 'email' => 'user2@example.com', 'email_verified_at' => Carbon::now(), 'user_type' => UserType::WebsiteUser]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
    }
};
