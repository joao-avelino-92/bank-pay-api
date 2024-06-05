<?php

	namespace App\Models;

    use App\Models\Wallet as Wallet;
    use Illuminate\Database\Eloquent\Model;

    class Transfer extends Model {
        public $incrementing = false;
        protected $table = 'transfers';
        protected $fillable = [
            'id', 'payer_wallet_id', 'payee_wallet_id', 'value'
        ];

        public function payerTransfer()
        {
            return $this->belongsTo(Wallet::class,  'payer_wallet_id');
        }

        public function payeeTransfer()
        {
            return $this->belongsTo(Wallet::class,  'payee_wallet_id');
        }

	}
