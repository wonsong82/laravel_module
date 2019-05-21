<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Module\Company\Constants\CurrencySymbolPosition;


class CreateCompanyTables extends Migration
{
    public function up()
    {
        // COMPANY

        Schema::create('companies', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('code_serial')->unique();
            $table->integer('status_code')->index()->default(\Module\Company\Constants\CompanyStatus::ACTIVE);
            $table->string('name')->index();
            $table->string('legal_name')->nullable()->index();
            $table->string('desc')->nullable();
            $table->string('phone')->nullable()->index();
            $table->string('fax')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('website')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable()->index();
            $table->text('note')->nullable();
            $table->string('timezone')->default('America/New_York');
            $table->unsignedBigInteger('locale_id');
            $table->timestamps();

            $table->foreign('locale_id')->references('id')->on('locales')->onDelete('cascade');
        });

        Schema::create('company_addresses', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->integer('type_code')->index()->default(\Module\Application\Constants\AddressType::PHYSICAL);
            $table->string('attention')->nullable();
            $table->string('line1')->nullable();
            $table->string('line2')->nullable();
            $table->string('line3')->nullable();
            $table->string('line4')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });


        // USER

        Schema::create('company_users', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->string('code')->index();
            $table->string('code_serial')->index();
            $table->integer('status_code')->index()->default(\Module\Company\Constants\CompanyStatus::ACTIVE);
            $table->string('name')->index();
            $table->unsignedBigInteger('user_id')->nullable(); // company user doesn't need user account associated
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->unique(['company_id', 'code']);
            $table->unique(['company_id', 'code_serial']);
        });


        // OFFDAY

        Schema::create('company_offdays', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->integer('year')->index();
            $table->integer('month')->index();
            $table->integer('day')->index();
            $table->date('date')->index();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['company_id', 'date']);
        });



        Schema::table('roles', function(Blueprint $table){
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['name', 'company_id']);
        });



        // CURRENCY

        Schema::create('currencies', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->integer('status_code')->default(\Module\Company\Constants\CurrencyStatus::ACTIVE)->index();
            $table->string('code')->index();
            $table->string('name')->index();
            $table->string('code_n')->nullable();
            $table->string('symbol')->nullable();
            $table->integer('symbol_position_code')->default(CurrencySymbolPosition::BEFORE_WITHOUT_SPACE);
            $table->smallInteger('decimal_count')->default(2);
            $table->char('decimal_separator')->default('.');
            $table->char('thousand_separator')->default(',');
            $table->integer('lft')->index()->nullable();
            $table->integer('rgt')->index()->nullable();
            $table->integer('depth')->index()->nullable();
            $table->integer('parent_id')->index()->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['company_id', 'code']);
        });

        Schema::create('currency_rates', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('base_currency_code')->index();
            $table->string('quote_currency_code')->index();
            $table->string('pair');
            $table->decimal('rate', 22, 8);
            $table->dateTime('recorded_at');
            $table->timestamps();
        });



        // PAYTERM

        Schema::create('payterms', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->integer('status_code')->default(\Module\Company\Constants\PaytermStatus::ACTIVE)->index();
            $table->string('code')->index();
            $table->string('name')->index();
            $table->string('desc')->nullable();
            $table->integer('lft')->index()->nullable();
            $table->integer('rgt')->index()->nullable();
            $table->integer('depth')->index()->nullable();
            $table->integer('parent_id')->index()->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['company_id', 'code']);
        });



        // UOM

        Schema::create('uoms', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->string('code')->index();
            $table->string('isc')->nullable()->index();
            $table->string('desc')->nullable();
            $table->integer('lft')->index()->nullable();
            $table->integer('rgt')->index()->nullable();
            $table->integer('depth')->index()->nullable();
            $table->integer('parent_id')->index()->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['company_id', 'code']);
            $table->unique(['company_id', 'isc']);
        });



        // Unit

        Schema::create('units', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->integer('status_code')->default(\Module\Company\Constants\UnitStatus::ACTIVE)->index();
            $table->integer('type_code')->index();
            $table->string('symbol')->index();
            $table->string('name');
            $table->string('plural_name');
            $table->string('desc')->nullable();
            $table->integer('lft')->index()->nullable();
            $table->integer('rgt')->index()->nullable();
            $table->integer('depth')->index()->nullable();
            $table->integer('parent_id')->index()->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['company_id', 'symbol']);
        });



        // MARGIN RATE

        Schema::create('margin_rates', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->string('rates'); // json
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        }); 
        
        

        // CUSTOMIZATION

        Schema::create('customizations', function(Blueprint $table){
            $table->unsignedBigInteger('company_id');
            $table->integer('type_code')->index();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->primary(['company_id', 'type_code']);
        });         
        
        
        



    }


    public function down()
    {
        Schema::dropIfExists('customizations');
        Schema::dropIfExists('margin_rates');
        Schema::dropIfExists('uoms');
        Schema::dropIfExists('payterms');
        Schema::dropIfExists('currency_rates');
        Schema::dropIfExists('currencies');
        Schema::table('roles', function(Blueprint $table){
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            $table->dropUnique(['name', 'company_id']);
        });
        Schema::dropIfExists('company_offdays');
        Schema::dropIfExists('company_users');
        Schema::dropIfExists('company_addresses');
        Schema::dropIfExists('companies');
    }
}
