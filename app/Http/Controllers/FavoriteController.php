<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Medicine;
use App\Models\User;

class FavoriteController extends Controller
{
    public function add_favorite(Request $request)
    {
        $user = User::where('token', $request['token'])->get();
        $find = Medicine::find($request['id']);
        $already_added = Favorite::where('user_id', $user[0]['id'])->where('medicine_name', $find['name'])->get();
        if ($already_added) {
            return response()->json([
                'message' => 'error'
            ]);
        }
        if ($find) {
            $new_fav = [
                'user_id' => $user[0]['id'],
                'medicine_name' => $find['name']
            ];
            Favorite::create($new_fav);
            return response()->json([
                'message' => 'success'
            ]);
        } else {
            return response()->json([
                'message' => 'error'
            ]);
        }
    }

    public function remove_favorite(Request $request, $id)
    {
        $user = User::where('token', $request['token'])->get();
        $favorite = Favorite::find($id);
        if ($favorite['user_id'] != $user[0]['id']) {
            return response()->json([
                'message' => 'error'
            ]);
        } else {
            $favorite->delete();
            return response()->json([
                'message' => 'success'
            ]);
        }
    }

    public function view_favorites(Request $request)
    {
        $user = User::where('token', $request['token'])->get();
        $favorites = Favorite::where('user_id', $user[0]['id'])->get();
        return response()->json([
            'message' => 'success',
            'favorites' => $favorites
        ]);
    }
}