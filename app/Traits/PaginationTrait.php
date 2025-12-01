<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait PaginationTrait
{
    public function paginateResponse(
        LengthAwarePaginator $paginator,
        string $message = 'Success'
    ): array {
        return [
            'status'  => true,
            'message' => $message,

            'data' => [
                'docs' => $paginator->items(),

                'total'        => $paginator->total(),
                'count'        => $paginator->count(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),

                'has_more' => $paginator->hasMorePages(),

                'links' => [
                    'first' => $paginator->url(1),
                    'last'  => $paginator->url($paginator->lastPage()),
                    'next'  => $paginator->nextPageUrl(),
                    'prev'  => $paginator->previousPageUrl(),
                ],
            ],
        ];
    }
}
