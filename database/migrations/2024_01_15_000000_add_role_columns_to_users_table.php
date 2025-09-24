<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'manager', 'client'])->default('client')->after('email');
            $table->string('trainer_code', 20)->nullable()->unique()->after('role');
            $table->unsignedBigInteger('assigned_trainer_id')->nullable()->after('trainer_code');
            $table->boolean('is_active')->default(true)->after('assigned_trainer_id');
            $table->json('permissions')->nullable()->after('is_active');
            
            // Foreign key constraint
            $table->foreign('assigned_trainer_id')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('role');
            $table->index('trainer_code');
            $table->index('assigned_trainer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['assigned_trainer_id']);
            $table->dropIndex(['role']);
            $table->dropIndex(['trainer_code']);
            $table->dropIndex(['assigned_trainer_id']);
            
            $table->dropColumn([
                'role',
                'trainer_code', 
                'assigned_trainer_id',
                'is_active',
                'permissions'
            ]);
        });
    }
}
