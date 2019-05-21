<?php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Module\Application\Constants\UserStatus;

class CreateUserTables extends Migration
{
    public function up()
    {
        /**
         * Locale
         */
        Schema::create('locales', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('code')->index();
            $table->string('locale')->unique();
            $table->string('language_code'); // ISO 639
            $table->string('country_code'); // SO 3166
            $table->string('encoding');
            $table->string('country_name');
            $table->string('language_name');
            $table->timestamps();
        });


        /**
         * Users
         */
        Schema::table('users', function(Blueprint $table){
            $table->bigIncrements('id')->change();
            $table->integer('status_code')->default(UserStatus::ACTIVE)->index();
            $table->unsignedBigInteger('locale_id')->nullable();
            $table->foreign('locale_id')->references('id')->on('locales')->onDelete('set null');
            $table->string('timezone')->default('America/New_York')->index();
        });


        /**
         * Permissions & Roles
         */
        $tableNames = config('permission.table_names');

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('permission_id');
            $table->morphs('model');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('role_id');
            $table->morphs('model');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);

            Cache::forget('spatie.permission.cache');
        });
    }


    public function down()
    {
        $tableNames = config('permission.table_names');

        Schema::dropIfExists($tableNames['role_has_permissions']);
        Schema::dropIfExists($tableNames['model_has_roles']);
        Schema::dropIfExists($tableNames['model_has_permissions']);
        Schema::dropIfExists($tableNames['roles']);
        Schema::dropIfExists($tableNames['permissions']);

        Schema::table('users', function(Blueprint $table){
            $table->increments('id')->change();
            $table->dropColumn('status_code');
            $table->dropForeign('users_locale_id_foreign');
            $table->dropColumn('locale_id');
            $table->dropColumn('timezone');
        });

        Schema::dropIfExists('locales');
    }
}
