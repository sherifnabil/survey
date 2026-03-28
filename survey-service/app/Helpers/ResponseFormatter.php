<?php

namespace App\Helpers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

class ResponseFormatter
{
  /**
   * Format a paginated response with metadata for pagination
   * @param JsonResource $data The data to be returned in the response
   * @param LengthAwarePaginator $paginationObject The paginator object containing pagination metadata
   * @return array The formatted response with data and pagination metadata
   */
  public static function paginationResponse(JsonResource $data, LengthAwarePaginator $paginator): array
  {
    return [
      'data' => $data,
      'current_page' => $paginator->currentPage(),
      'last_page' => $paginator->lastPage(),
      'per_page' => $paginator->perPage(),
      'total' => $paginator->total(),
      'status' => 'success',
      'code' => Response::HTTP_OK,
    ];
  }

  /**
   * Format a cursor paginated response with metadata for pagination
   * @param JsonResource $data The data to be returned in the response
   * @param CursorPaginator $paginator The paginator object containing pagination metadata
   * @return array The formatted response with data and pagination metadata
   */
  public static function cursorPaginationResponse(JsonResource|array $data, CursorPaginator $paginator, int $total = 0): array
  {
    return [
      'data' => $data,
      'pagination' => [
        'per_page' => $paginator->perPage(),
        'next_url' => self::generateCursorUrl($paginator->nextCursor()?->encode()),
        'prev_url' => self::generateCursorUrl($paginator->previousCursor()?->encode()),
        'total' => $total,
        'last_page' => ceil($total / $paginator->perPage()),
        'total' => $total,
        'last_page' => $total > 0 ? ceil($total / $paginator->perPage()) : null,
      ],
      'status' => 'success',
      'code' => Response::HTTP_OK,
    ];
  }

  private static function generateCursorUrl(?string $cursor): ?string
  {
    if (!$cursor) {
      return null;
    }

    $queryParams = request()->query();
    $queryParams['cursor'] = $cursor;

    return url()->current() . '?' . http_build_query($queryParams);
  }
}
