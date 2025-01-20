<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);



        $arrayTransports = [
            ['name' => 'ШКБ С308', 'type' => 55],
            ['name' => 'ШКБ С309', 'type' => 55],
            ['name' => 'ШКБ С310', 'type' => 55],
            ['name' => 'ШКБ С311', 'type' => 55],

            ['name' => 'ШКБ С312', 'type' => 130],
            ['name' => 'ШКБ С314', 'type' => 130],
            ['name' => 'ШКБ С315', 'type' => 130],
            ['name' => 'ШКБ С316', 'type' => 130],

            ['name' => 'ШКБ С317', 'type' => 55],
            ['name' => 'ШКБ С318', 'type' => 55],
            ['name' => 'ШКБ С333', 'type' => 55],

            ['name' => 'ШКБ С345', 'type' => 55],
            ['name' => 'ШКБ С348', 'type' => 55],
            ['name' => 'ШКБ С353', 'type' => 55],
            ['name' => 'ШКБ С357', 'type' => 55],
            ['name' => 'ШКБ С358', 'type' => 55],
            ['name' => 'ШКБ С359', 'type' => 55],

            ['name' => 'ШКБ С360', 'type' => 90],
            ['name' => 'ШКБ С361', 'type' => 90],
            ['name' => 'ШКБ С362', 'type' => 90],
            ['name' => 'ШКБ С363', 'type' => 90],
            ['name' => 'ШКБ С364', 'type' => 90],
            ['name' => 'ШКБ С365', 'type' => 90],
            ['name' => 'ШКБ С366', 'type' => 90],
            ['name' => 'ШКБ С367', 'type' => 90],
            ['name' => 'ШКБ С368', 'type' => 90],
            ['name' => 'ШКБ С369', 'type' => 90],

            ['name' => 'ШКБ С370', 'type' => 130],
            ['name' => 'ШКБ С371', 'type' => 130],
            ['name' => 'ШКБ С373', 'type' => 130],


            ['name' => 'ШКБ С374', 'type' => 90],
            ['name' => 'ШКБ С375', 'type' => 90],
            ['name' => 'ШКБ С376', 'type' => 90],
            ['name' => 'ШКБ С377', 'type' => 90],


            ['name' => 'ШКБ С380', 'type' => 91],
            ['name' => 'ШКБ С381', 'type' => 91],
            ['name' => 'ШКБ С382', 'type' => 91],
            ['name' => 'ШКБ С383', 'type' => 91],
            ['name' => 'ШКБ С384', 'type' => 91],
            ['name' => 'ШКБ С385', 'type' => 91],
            ['name' => 'ШКБ С386', 'type' => 91],
            ['name' => 'ШКБ С387', 'type' => 91],
            ['name' => 'ШКБ С388', 'type' => 91],
            ['name' => 'ШКБ С389', 'type' => 91],
            ['name' => 'ШКБ С390', 'type' => 91],
            ['name' => 'ШКБ С391', 'type' => 91],
            ['name' => 'ШКБ С392', 'type' => 91],
            ['name' => 'ШКБ С393', 'type' => 91],
            ['name' => 'ШКБ С394', 'type' => 91],
            ['name' => 'ШКБ С395', 'type' => 91],
            ['name' => 'ШКБ С396', 'type' => 91],
            ['name' => 'ШКБ С397', 'type' => 91],

            ['name' => 'ШКБ С501', 'type' => 55],
            ['name' => 'ШКБ С503', 'type' => 55],
            ['name' => 'ШКБ С504', 'type' => 55],
            ['name' => 'ШКБ С506', 'type' => 55],
            ['name' => 'ШКБ С507', 'type' => 55],
            ['name' => 'ШКБ С510', 'type' => 55],
            ['name' => 'ШКБ С540', 'type' => 55],
            ['name' => 'ШКБ С544', 'type' => 55],
            ['name' => 'ШКБ С552', 'type' => 55],
            ['name' => 'ШКБ С560', 'type' => 55],
            ['name' => 'ШКБ С561', 'type' => 55],
            ['name' => 'ШКБ С562', 'type' => 55],
            ['name' => 'ШКБ С563', 'type' => 55],
            ['name' => 'ШКБ С564', 'type' => 55],


            ['name' => 'ШКБ С567', 'type' => 90],
            ['name' => 'ШКБ С568', 'type' => 90],
            ['name' => 'ШКБ С569', 'type' => 90],
            ['name' => 'ШКБ С570', 'type' => 90],
            ['name' => 'ШКБ С571', 'type' => 90],
            ['name' => 'ШКБ С572', 'type' => 90],
            ['name' => 'ШКБ С573', 'type' => 90],
            ['name' => 'ШКБ С574', 'type' => 90],
            ['name' => 'ШКБ С575', 'type' => 90],
            ['name' => 'ШКБ С576', 'type' => 90],

            ['name' => 'ШКБ С577', 'type' => 92],
            ['name' => 'ШКБ С578', 'type' => 92],
            ['name' => 'ШКБ С579', 'type' => 92],


            ['name' => 'ШКБ С580', 'type' => 91],
            ['name' => 'ШКБ С581', 'type' => 91],
            ['name' => 'ШКБ С582', 'type' => 91],
            ['name' => 'ШКБ С583', 'type' => 91],
            ['name' => 'ШКБ С584', 'type' => 91],
            ['name' => 'ШКБ С585', 'type' => 91],
            ['name' => 'ШКБ С586', 'type' => 91],
            ['name' => 'ШКБ С587', 'type' => 91],
            ['name' => 'ШКБ С588', 'type' => 91],
            ['name' => 'ШКБ С589', 'type' => 91],
            ['name' => 'ШКБ С590', 'type' => 91],

            ['name' => 'ШКБ С602', 'type' => 55],
            ['name' => 'ШКБ С608', 'type' => 55],
            ['name' => 'ШКБ С610', 'type' => 55],
            ['name' => 'ШКБ С611', 'type' => 55],
            ['name' => 'ШКБ С612', 'type' => 55],
            ['name' => 'ШКБ С614', 'type' => 55],
            ['name' => 'ШКБ С615', 'type' => 55],
            ['name' => 'ШКБ С616', 'type' => 55],
            ['name' => 'ШКБ С659', 'type' => 55],
            ['name' => 'ШКБ С661', 'type' => 55],
            ['name' => 'ШКБ С662', 'type' => 55],
            ['name' => 'ШКБ С664', 'type' => 55],


            ['name' => 'ШКБ С667', 'type' => 90],
            ['name' => 'ШКБ С668', 'type' => 90],
            ['name' => 'ШКБ С669', 'type' => 90],
            ['name' => 'ШКБ С670', 'type' => 90],
            ['name' => 'ШКБ С671', 'type' => 90],
            ['name' => 'ШКБ С672', 'type' => 90],
            ['name' => 'ШКБ С673', 'type' => 90],
            ['name' => 'ШКБ С674', 'type' => 90],
            ['name' => 'ШКБ С675', 'type' => 90],
            ['name' => 'ШКБ С676', 'type' => 90],
            ['name' => 'ШКБ С677', 'type' => 90],

            ['name' => 'ШКБ С678', 'type' => 92],
            ['name' => 'ШКБ С679', 'type' => 92],
            ['name' => 'ШКБ С680', 'type' => 92],

            ['name' => 'ШКБ С681', 'type' => 91],
            ['name' => 'ШКБ С682', 'type' => 91],
            ['name' => 'ШКБ С683', 'type' => 91],
            ['name' => 'ШКБ С684', 'type' => 91],
            ['name' => 'ШКБ С685', 'type' => 91],
            ['name' => 'ШКБ С686', 'type' => 91],
            ['name' => 'ШКБ С687', 'type' => 91],
            ['name' => 'ШКБ С688', 'type' => 91],
            ['name' => 'ШКБ С689', 'type' => 91],
            ['name' => 'ШКБ С690', 'type' => 91],


        ];


        // DB::table('cars')->insert($arrayTransports);



    }
}
