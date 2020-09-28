<?php

use Illuminate\Database\Seeder;

use App\Models\MemberClass;
use App\Models\User;

class MembersClassesSeeder extends Seeder
{
    /**
     * @var string
     */
    private $club_code;

    /**
     * @var array
     */
    private $classes = array(
        [
            'label' => 'silver',
            'name' => 'Silver',
            'default' => true,
        ],
        [
            'label' => 'gold',
            'name' => 'Gold',
        ],
        [
            'label' => 'vip',
            'name' => 'Vip',
        ],
    );

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->club_code = DatabaseSeeder::$club_code;
        
        foreach($this->classes as $class)
        {
            if (! MemberClass::select()->where('club_code', $this->club_code)->where('label', $class['label'])->first())
            {
                $add_class = new MemberClass();
                $add_class->club_code = $this->club_code;
                $add_class->name = $class['name'];
                $add_class->label = $class['label'];

                if (array_key_exists('default', $class) && $class['default'] === true)
                    $add_class->default = true;

                $add_class->save();
            }
        }
        
        $users_without_class = User::select()
            ->where('club_code', $this->club_code)
            ->whereNull('member_class_id')
            ->get();

        if ($users_without_class->count() > 0)
        {
            $default_class = MemberClass::select()
                ->where('club_code', $this->club_code)
                ->where('default', true)
                ->first();

            foreach($users_without_class as $user)
            {
                $user->member_class_id = $default_class->id;
                $user->save();
            }
        }

    }
}
