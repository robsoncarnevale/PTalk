<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seder;
use DB;

class DatabaseSeeder extends Seeder
{
    public static $club_code = 'porsche_talk';
    public static $fn;
    public static $tableSeeds = [];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);

        echo "Running seeder to \033[36m" . self::$club_code . "\033[0m " . PHP_EOL . PHP_EOL;

        self::$tableSeeds = DB::table('seeders')->select('name','executed')->get()->toArray();

        $this->generateFnVar();
        if (!$this->executed('clubs')) {
            // Create club
            $this->createClub();
            // Create default users
            $this->createAdminUser();
        }

        // Create default vehicle data
        if (!$this->executed('car_brands')) $this->createCarBrand();
        if (!$this->executed('car_colors')) $this->createCarColor();
        if (!$this->executed('car_models')) {
            $this->readCarModelPictureFiles();
            $this->createCarModel();
        }

        // Create privileges
        if (!$this->executed('privileges')) { 
            $this->createPrivileges();
            $this->addPrivilegesAdmin();
        }

        // Create members classes
        if (!$this->executed('members_classes')) $this->createMembersClases();

        //Create Bank Account Types
        if (!$this->executed('bank_account_types')) $this->createBankAccountTypes();

        //Create Statuses
        if (!$this->executed('statuses')) $this->createStatuses();

        //Create Club Bank Account
        if (!$this->executed('bank_accounts')) $this->createClubBankAccount();

        /* Create Transactions */

        if (!$this->executed('transaction_types')) $this->call(CreateTransactionTypesTableSeeder::class);
        if (!$this->executed('transaction_statuses')) $this->call(CreateTransactionStatusesTableSeeder::class);
        if (!$this->executed('payment_methods')) $this->call(CreatePaymentMethodsTableSeeder::class);
        if (!$this->executed('brands')) $this->call(CreateBrandsTableSeeder::class);
        if (!$this->executed('user_demonstrative')) $this->call(CreateUserDemonstrativeSeeder::class);
        if (!$this->executed('config')) $this->call(CreateConfigTableSeeder::class);

        if (!$this->executed('user_privileges')) $this->call(UserPrivilegesSeeder::class);
        if (!$this->executed('product_privileges')) $this->call(ProductPrivilegesSeeder::class);
    }

    private function executed($table) {
        foreach(self::$tableSeeds as $t) {
            if ($t->name == $table) {
                if (!$t->executed) {
                    $this->updateSeed($table);
                    return false;
                }
                return true;
            }
        }
        $this->createSeed($table);
        return false;
    }

    private function createSeed($table) {
        DB::table('seeders')->insert(
            ['created_at' => date('Y-m-d H:i:s'),
             'updated_at' => date('Y-m-d H:i:s'),
             'name' => $table, 
             'time' => date('Y-m-d H:i:s'),
             'executed' => 1]
        );
    }

    private function updateSeed($table) {
        DB::table('seeders')
            ->where('name',$table)
            ->update(
                [
                    'updated_at' => date('Y-m-d H:i:s'),
                    'executed' => 1
                ]
        );
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
