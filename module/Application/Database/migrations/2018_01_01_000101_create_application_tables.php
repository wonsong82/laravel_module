<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationTables extends Migration
{
    public function up()
    {
        /**
         * Lookup
         */
        Schema::create('constant_headers', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name');

        });

        Schema::create('constants', function(Blueprint $table){
            $table->unsignedBigInteger('constant_header_id');
            $table->unsignedInteger('code')->unique();
            $table->string('name');
            $table->string('display_name');
            $table->string('type')->nullable();
            $table->integer('order')->index();
            $table->string('key');

            $table->foreign('constant_header_id')->references('id')->on('constant_headers')->onDelete('cascade');
            $table->primary('code');
        });


        /***
         * Serial
         */
        Schema::create('serial_codes', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('unique_key')->index();
            $table->string('prefix')->index();
            $table->integer('last_seq')->index();
            $table->integer('length');
            $table->timestamps();

            $table->unique(['unique_key', 'prefix']);
        });


        /**
         * ActivityLog
         */
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->integer('type_code')->index();
            $table->string('loggable_type')->index();
            $table->unsignedBigInteger('loggable_id')->index();
            $table->string('title')->nullable();
            $table->string('text')->nullable();
            $table->longText('detail')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });


        /**
         * Snapshot
         */
        Schema::create('snapshots', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->longText('content');
            $table->unsignedBigInteger('activity_log_id')->nullable();
            $table->timestamps();

            $table->foreign('activity_log_id')->references('id')->on('activity_logs')->onDelete('set null');
        });


        /**
         * Notification
         */
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type_code')->index();
            $table->string('notifyable_type')->index();
            $table->unsignedBigInteger('notifyable_id')->index();
            $table->string('title')->nullable();
            $table->string('detail')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_role', function (Blueprint $table) {
            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('role_id');
            $table->boolean('is_archived')->default(false)->index();
            $table->dateTime('archived_at')->nullable();
            $table->unsignedBigInteger('archived_by')->nullable();

            $table->primary(['notification_id', 'role_id']);
            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('archived_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('notification_user', function (Blueprint $table) {
            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_read')->default(false)->index();
            $table->dateTime('read_at')->nullable();

            $table->primary(['notification_id', 'user_id']);
            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });


        /**
         * Revision
         */
        Schema::create('revisions', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('revisionable_type')->index();
            $table->unsignedBigInteger('revisionable_id')->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('old_value')->nullable();
            $table->longText('new_value')->nullable();
            $table->unsignedBigInteger('activity_log_id')->nullable();
            $table->foreign('activity_log_id')->references('id')->on('activity_logs')->onDelete('set null');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('revision_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('revision_id');
            $table->string('field_name');
            $table->longText('old_value')->nullable();
            $table->longText('new_value')->nullable();
            $table->boolean('is_dirty');
            $table->boolean('is_related')->default(false);
            $table->string('model')->nullable();
            $table->string('entity')->nullable();
            $table->integer('entity_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('revision_id')->references('id')->on('revisions')->onDelete('cascade');
        });


        /**
         * Translation
         */
        Schema::create('translations', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('translatable_type')->index();
            $table->unsignedBigInteger('translatable_id')->index();
            $table->unsignedBigInteger('locale_id');
            $table->string('name')->index();
            $table->longText('value')->nullable();
            $table->timestamps();

            $table->foreign('locale_id')->references('id')->on('locales')->onDelete('cascade');
            $table->unique(['translatable_type', 'translatable_id', 'locale_id', 'name'], 'translations_type_id_locale_name');
        });




    }


    public function down()
    {
        Schema::dropIfExists('translations');
        Schema::dropIfExists('revision_fields');
        Schema::dropIfExists('revisions');
        Schema::dropIfExists('notification_user');
        Schema::dropIfExists('notification_role');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('snapshots');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('serial_codes');
        Schema::dropIfExists('constants');
        Schema::dropIfExists('constant_headers');
    }
}
