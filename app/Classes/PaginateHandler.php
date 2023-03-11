<?php


namespace App\Classes;


use Illuminate\Contracts\Database\Query\Builder as BuilderContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class PaginateHandler
{
    public static function getPaginated(BuilderContract $query, array $options = null, Request $request = null): LengthAwarePaginator
    {
        $request ??= request();
        $perPage = $options['perPage'] ?? $request['perPage'] ?? 15;
        $onEachSide = $options['onEachSide'] ?? $request['onEachSide'] ?? 2;
        $withQueryString ??= $options['withQueryString'] ?? $request['withQueryString'] ?? true;

        $paginated = $query->paginate($perPage);

        // Check current page exceeding
        if ($paginated->currentPage() > $paginated->lastPage()) {
            $request['page'] = $paginated->lastPage();
            $paginated = $query->paginate($perPage);
        }

        $paginated->onEachSide($onEachSide);

        if ($withQueryString) {
            $paginated->withQueryString();
        }

        return $paginated;
    }
}
