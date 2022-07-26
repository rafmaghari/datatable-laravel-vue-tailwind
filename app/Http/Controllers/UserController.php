<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListRequest;
use App\Models\User;
use App\Services\BaseList;
use App\Services\UserList;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $columns = UserList::COLUMNS;
        $hobbies = User::select('hobby')->groupBy('hobby')->get();
        return view('app', compact('columns', 'hobbies'));
    }

    public function list(ListRequest $request)
    {
        try {
            $queries = $request->queries ?? '';
            $selectType = $request->selectType;
            $columns = UserList::COLUMNS;

            $request->sort_field = $request->sort_field ?? 'users.id';
            $userListData = (new UserList())->process($queries, $columns);

            /** for selecting all */
            if ($selectType === 'all') {
                $allFilteredIds = BaseList::selectAll($userListData);
                return response()->json(['data' => $allFilteredIds]);
            }

            /** paginate and sorting */
            $paginatedRecords = BaseList::paginateData($userListData, $request);

            return response($paginatedRecords);
        } catch (\Exception $e) {
            \Log::info($e);

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function store()
    {
        $user = User::create(request()->all());
        return response($user);
    }
}
