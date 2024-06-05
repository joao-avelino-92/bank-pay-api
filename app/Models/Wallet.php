<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
    use App\Models\User as User;

    class Wallet extends Model {

        public $incrementing = false;
        protected $fillable = [
           'id', 'user_id', 'balance'
        ];

        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function transfer()
        {
            return $this->hasMany(Transfer::class);
        }

        public function debitWallet($value)
        {
            $this->update([
                'balance' => $this->attributes['balance'] - $value
            ]);
        }

        public function depositWallet($value)
        {
            $this->update([
                'balance' => $this->attributes['balance'] + $value
            ]);
        }

	}
