<?php

namespace App\Models\Concrens;

class SyncRelation
{
  public static function sync(
    $relation,
    array $items,
    callable $updateCallback,
    callable $createCallback
  ): void {
    $existing = $relation->get()->keyBy('id');
    $updatedIds = [];

    foreach ($items as $index => $item) {
      if (!empty($item->id) && $existing->has($item->id)) {
        $model = $existing->get($item->id);

        $updateCallback($model, $item, $index);
        $updatedIds[] = $item->id;
      } else {
        $model = $createCallback($relation, $item, $index);

        if ($model && $model->id) {
          $updatedIds[] = $model->id;
        }
      }
    }

    $relation->whereNotIn('id', $updatedIds)->delete();
  }
}
