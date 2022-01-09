<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public static $club_code = 'porsche_talk';
    public static $fn;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);

        echo "Running seeder to \033[36m" . self::$club_code . "\033[0m " . PHP_EOL . PHP_EOL;

        $this->generateFnVar();

        // Create club
        $this->createClub();

        // Create default users
        $this->createAdminUser();

        // Create default vehicle data
        $this->createCarBrand();
        $this->createCarColor();
        $this->createCarModel();
        $this->readCarModelPictureFiles();

        // Create privileges
        $this->createPrivileges();
        $this->addPrivilegesAdmin();

        // Create members classes
        $this->createMembersClases();

        //Create Bank Account Types
        $this->createBankAccountTypes();

        //Create Statuses
        $this->createStatuses();

        //Create Club Bank Account
        $this->createClubBankAccount();

        /* Create Transactions */

        $this->call(CreateTransactionTypesTableSeeder::class);
        $this->call(CreateTransactionStatusesTableSeeder::class);
        $this->call(CreatePaymentMethodsTableSeeder::class);
        $this->call(CreateBrandsTableSeeder::class);
    }

    ////////////////////////////////////////

    /**
     * Generate function variable
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    private function generateFnVar()
    {
        self::$fn = str_replace([ '_', '-', "\n", " " ], '', ucwords(self::$club_code, "_-\n "));
    }

    /**
     * Run club function in seeder class
     *
     * @example
     *  If club code is "porsche_talk", when run this function you call the function $class->PorscheTalk()
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    public static function runClubFunction($class, $params = null)
    {
        $fn = self::$fn;

        return call_user_func([ $class, $fn ], $params);
        
        // return $class->$fn($params);
    }

    ////////////////////////////////////////

    /**
     * Create club
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    private function createClub()
    {
        if (\App\Models\Club::where('code', self::$club_code)->count() < 1)
            return $this->call(CreateClub::class);
    }

    ////////////////////////////////////////

    /**
     * Create default car brand
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    private function createCarBrand()
    {
        if (\App\Models\CarBrand::select('id')->where('club_code', self::$club_code)->count() < 1)
            return $this->call(CreateCarBrand::class);
    }

    /**
     * Create default car colors
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    private function createCarColor()
    {
        if (\App\Models\CarColor::select('id')->where('club_code', self::$club_code)->count() < 1)
            return $this->call(CreateCarColor::class);
    }

    /**
     * Create default car models
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    private function createCarModel()
    {
        if (\App\Models\CarModel::select('id')->where('club_code', self::$club_code)->count() < 1)
            return $this->call(CreateCarModel::class);
    }

    /**
     * Read car model pictures from disk and saves on database
     *
     * @author Davi Souto
     * @since 24/06/2020
     */
    private function readCarModelPictureFiles()
    {
        return $this->call(AddCarModelPicture::class);
    }

    ////////////////////////////////////////

    /**
     * Create default admin user
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    private function createAdminUser()
    {
        if (\App\Models\User::select('id')->where('club_code', self::$club_code)->where('type', 'admin')->where('approval_status', 'approved')->count() < 1)
            return $this->call(CreateAdminUser::class);
    }

    ////////////////////////////////////////

    /**
     * Create privileges actions
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    public function createPrivileges()
    {
        return $this->call(CreatePrivileges::class);
    }

    /**
     * Add privilege actions to admin privilege group
     *
     * @author Davi Souto
     * @since 16/06/2020
     */
    public function addPrivilegesAdmin()
    {
        return $this->call(AddPrivilegesAdmin::class);
    }

    /////////////////////////

     /**
     * Create default car brand
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    private function createMembersClases()
    {
        return $this->call(MembersClassesSeeder::class);
    }

    private function createBankAccountTypes()
    {
        return $this->call(CreateBankAccountTypesSeeder::class);
    }

    private function createStatuses()
    {
        return $this->call(CreateStatusesTableSeeder::class);
    }

    private function createClubBankAccount()
    {
        return $this->call(CreateClubBankAccountTableSeeder::class);
    }
}
