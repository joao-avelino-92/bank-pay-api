<?php

	namespace App\Enums;

	class UserAccountTypeEnum {
        const USER = 1;
        const TRADER = 2;

        public static function map() : array
        {
            return [
                static::USER => 'user',
                static::TRADER => 'trader'
            ];
        }
	}
