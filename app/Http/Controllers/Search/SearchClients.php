<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchClients extends Controller
{
    private static function extractNumbers(string $value): ?string
    {
        return preg_replace('/[^0-9]/', '', $value) ?: null;
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // get scopes from query params (read https://wireui.dev/components/select#async-search)
        $search = $request->search;
        $selected = $request->exists('selected') ? $request->input('selected', []) : null;
        $numbersSearch = static::extractNumbers($search ?? '');

        // heavily inspired by https://github.com/wireui/docs/blob/main/src/Examples/UserController.php#L23
        return Client::query()
            ->select('id', 'cpf', 'name')
            ->when(
                value: $search,
                callback: function (Builder $query) use ($search, $numbersSearch) {
                    return $query
                        ->where('name', 'like', "$search%")
                        ->orWhere('cpf', 'like', "$numbersSearch%");
                }
            )
            ->when(
                value: $selected,
                callback: fn(Builder $query) => $query->whereIn('id', $selected),
                default: fn(Builder $query) => $query->limit(10)
            )
            ->get();
    }
}
