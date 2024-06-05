<?php

	namespace App\Http\Controllers;

    use App\Models\User;
    use Illuminate\Http\Response;
    use Illuminate\Support\Facades\Auth;

    class UserController extends Controller {
        public function __constructor(){
            $this->middleware('auth:user');
        }
        public function getUsers()
        {
            return User::All();
        }

        /**
         * Retrieve the user for the given ID.
         *
         * @param  int  $id
         * @return Response
         */
        public function getUserById($id) {
            return User::findOrFail($id);
        }

        public function getUserByToken(){
            return response()->json(Auth::guard('user')->user());
        }

	}
