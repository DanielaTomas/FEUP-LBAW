<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SearchController extends Controller
{
    public function searchUsers(Request $request)
    {
        $search = $request->input('search');
        $users = User::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$search])
            ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$search])
            ->get();

        return view('pages.admin.users',[
            'users' => $users,
        ]);
    }

    public function searchPublicEvents(Request $request)
    {
        $search = $request->input('search');
        $events = Article::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$search])
            ->where('public','=',true)
            ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$search])
            ->get();

        return $events;
    }
}