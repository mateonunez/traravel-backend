<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class MakeUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user
                            {--R|role= : The role of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Creating a new user');

        // Ask for role
        $role = $this->option('role');
        $roles = Role::all();
        if (!$role) {
            $role = $this->choice(
                'Choiche the role',
                [...array_map(fn ($role) => $role['name'], $roles->toArray()), "None"]
            );
        }

        $roleKey = array_search(
            $role,
            array_map(fn ($role) => $role['name'], $roles->toArray())
        );

        if ($roleKey === false) {
            $this->info('Creating a new user without role');
            $role = null;
        } else {
            $role = $roles[$roleKey];
        }

        // Ask for name
        $name = $this->ask('Name');
        $validator = Validator::make(
            ['name' => $name],
            ['name' => 'required|string|max:255']
        );

        // Ask for email
        $email = $this->ask('Email');
        $validator = Validator::make(
            ['email' => $email],
            ['email' => 'required|email']
        );

        if ($validator->fails()) {
            $this->error('Email is not valid');
            return 1;
        }

        // Ask for password
        $password = $this->secret('Password');
        $validator = Validator::make(
            ['password' => $password],
            ['password' => 'required|string|min:6']
        );
        if ($validator->fails()) {
            $this->error('Password is not valid');
            return 1;
        }

        // Ask for password confirmation
        $passwordConfirmation = $this->secret('Password confirmation');
        $validator = Validator::make(
            ['password' => $passwordConfirmation],
            ['password' => 'required|same:password']
        );
        if ($validator->fails()) {
            $this->error('Password confirmation is not valid');
            return 1;
        }

        $payload = [
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'emailVerifiedAt' => now(),
        ];

        $user = User::create($payload);

        if ($role) {
            $user->roles()->attach($role);
        }

        $this->info('User created');

        return 0;
    }
}
