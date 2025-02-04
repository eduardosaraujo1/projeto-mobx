<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Imobiliaria;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SearchImobiliarias extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Collection
    {
        // get scopes from query params (read https://wireui.dev/components/select#async-search)
        $search = $request->search;
        $selected = $request->exists('selected') ? $request->input('selected', []) : null;

        // heavily inspired by https://github.com/wireui/docs/blob/main/src/Examples/UserController.php#L23
        return Imobiliaria::query()
            ->select('id', 'name')
            ->when(
                value: $search,
                callback: fn(Builder $query) => $query->where('name', 'like', "%$search%"),
            )
            ->when(
                value: $selected,
                callback: fn(Builder $query) => $query->whereIn('id', $selected),
                default: fn(Builder $query) => $query->limit(10)
            )
            ->get();
    }
}
