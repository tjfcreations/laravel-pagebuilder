<?php

namespace Tjall\Users\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class DeleteUser extends Command {
    protected $signature = 'user:delete {email}';
    protected $description = 'Delete a user by email address';

    public function handle() {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("No user found with email '{$email}'.");
            return 1;
        }

        $user->delete();
        $this->info("User '{$user->name}' ({$email}) has been deleted.");

        return 0;
    }
}
