<?php

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

        // Create default privilege groups
        $this->createAdminPrivilegeGroup();
        $this->createMemberPrivilegeGroup();

        // Create default users
        $this->createAdminUser();
        $this->createMemberUser();
        $this->createMemberUserWaitingApproval();

        // Create default vehicle data
        $this->createCarBrand();
        $this->createCarColor();
        $this->createCarModel();
        $this->readCarModelPictureFiles();

        // Create privileges
        $this->createPrivileges();
        $this->addPrivilegesAdmin();
        $this->addPrivilegesMember();

        // Create members classes
        $this->createMembersClases();
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
        if (App\Models\Club::where('code', self::$club_code)->count() < 1)
            return $this->call(CreateClub::class);
    }

    ////////////////////////////////////////

    /**
     * Create admin privilege group
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    private function createAdminPrivilegeGroup()
    {
        if (! App\Models\PrivilegeGroup::select('id')->where('type', 'admin')->where('club_code', self::$club_code)->first())
            return $this->call(CreatePrivilegeGroupAdmin::class);
    }

    /**
     * Create member privilege group
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    private function createMemberPrivilegeGroup()
    {
        if (! App\Models\PrivilegeGroup::select('id')->where('type', 'member')->where('club_code', self::$club_code)->first())
            return $this->call(CreatePrivilegeGroupMember::class);
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
        if (App\Models\CarBrand::select('id')->where('club_code', self::$club_code)->count() < 1)
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
        if (App\Models\CarColor::select('id')->where('club_code', self::$club_code)->count() < 1)
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
        if (App\Models\CarModel::select('id')->where('club_code', self::$club_code)->count() < 1)
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
        if (App\Models\User::select('id')->where('club_code', self::$club_code)->where('type', 'admin')->where('approval_status', 'approved')->count() < 1)
            return $this->call(CreateAdminUser::class);
    }

    /**
     * Create default member user
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    private function createMemberUser()
    {
        if (App\Models\User::select('id')->where('club_code', self::$club_code)->where('type', 'member')->where('approval_status', 'approved')->count() < 1)
            return $this->call(CreateMemberUser::class);
    }

/**
     * Create member user waiting approval
     *
     * @author Davi Souto
     * @since 18/06/2020
     */
    private function createMemberUserWaitingApproval()
    {
        if (App\Models\User::select('id')->where('club_code', self::$club_code)->where('type', 'member')->where('approval_status', 'waiting')->count() < 1)
            return $this->call(CreateMemberUserWaitingApproval::class);
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

    /**
     * Add privilege actions to member privilege group
     *
     * @author Davi Souto
     * @since 16/06/2020
     */
    public function addPrivilegesMember()
    {
        return $this->call(AddPrivilegesMember::class);
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
}
