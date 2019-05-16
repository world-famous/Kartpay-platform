<?php

use Illuminate\Database\Seeder;
use App\User;

class FirstAdminData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
  		if(User::count() == 0)
  		{
  			$user = new User();
  			$user->name = '';
  			$user->first_name = '';
  			$user->last_name = '';
  			$user->country_code = '';
  			$user->contact_no = '';
  			$user->verification_code = '';
  			$user->otp = '';
  			$user->contact_no = '';
  			$user->email = 'admin@admin.com';
  			$user->password = bcrypt('Admin@123');
  			$user->type = 'panel';
  			$user->save();
  		}
    }
}
