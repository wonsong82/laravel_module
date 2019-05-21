<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreeFunctions extends Migration
{
    public function up()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS `getDescendants`');
        DB::unprepared('DROP FUNCTION IF EXISTS `getAscendants`');

        DB::unprepared('
                        CREATE FUNCTION `getDescendants`(rootId BIGINT) RETURNS varchar(1000)
                        BEGIN 
                        
                            DECLARE ptemp varchar(1000);
                            DECLARE ctemp varchar(1000);
                            
                            SET ptemp = "#";
                            SET ctemp = cast(rootId as CHAR);
                            
                            WHILE ctemp is not null DO
                                SET ptemp = concat(ptemp, ",", ctemp);
                                SELECT group_concat(item_id) INTO ctemp 
                                FROM boms
                                WHERE FIND_IN_SET(parent_item_id, ctemp) > 0;
                            END WHILE;
                            
                            RETURN ptemp;
                              
                        END');


        DB::unprepared('
                        CREATE FUNCTION `getAscendants`(rootId BIGINT) RETURNS varchar(1000)
                        BEGIN 
                        
                            DECLARE ptemp varchar(1000);
                            DECLARE ctemp varchar(1000);
                            
                            SET ptemp = "#";
                            SET ctemp = cast(rootId as CHAR);
                            
                            WHILE ctemp is not null DO
                                SET ptemp = concat(ptemp, ",", ctemp);
                                SELECT group_concat(parent_item_id) INTO ctemp
                                FROM boms
                                WHERE FIND_IN_SET(item_id, ctemp) > 0;
                            END WHILE;
                            
                            RETURN ptemp;
                            
                        END');



    }


    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS `getDescendants`');
        DB::unprepared('DROP FUNCTION IF EXISTS `getAscendants`');
    }
}
