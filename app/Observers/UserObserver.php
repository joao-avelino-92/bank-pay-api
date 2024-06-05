<?php


    namespace App\Observers;

    use App\Models\User as User;
    use Ramsey\Uuid\Uuid;

    class UserObserver
    {
        public function created(User $user) {
            $user->wallet()->create([
                'id' => Uuid::uuid1()->toString(),
                'balance' => 0
            ]);
        }
    }
