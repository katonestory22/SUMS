<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('first_name')->after('id');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->after('middle_name');

            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('national_id')->nullable()->unique();


            $table->string('passport_photo')->nullable()->after('national_id');


            $table->string('name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'phone',
                'address',
                'date_of_birth',
                'national_id',
                'passport_photo',
            ]);
        });
    }
};
