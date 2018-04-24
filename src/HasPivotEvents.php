<?php

namespace Signifly\PivotEvents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

trait HasPivotEvents
{
    protected $pivotChanges = [];

    public function setPivotChanges(string $type, string $relation, array $ids = [])
    {
        foreach ($ids as $id => $attributes) {
            $this->pivotChanges[$type][$relation][$id] = $attributes;
        }
    }

    public function getPivotChanges($type = null)
    {
        if ($type) {
            return $this->pivotChanges[$type];
        }

        return $this->pivotChanges;
    }

    public static function pivotAttaching($callback)
    {
        static::registerModelEvent('pivotAttaching', $callback);
    }

    public static function pivotAttached($callback)
    {
        static::registerModelEvent('pivotAttached', $callback);
    }

    public static function pivotDetaching($callback)
    {
        static::registerModelEvent('pivotDetaching', $callback);
    }

    public static function pivotDetached($callback)
    {
        static::registerModelEvent('pivotDetached', $callback);
    }

    public static function pivotUpdating($callback)
    {
        static::registerModelEvent('pivotUpdating', $callback);
    }

    public static function pivotUpdated($callback)
    {
        static::registerModelEvent('pivotUpdated', $callback);
    }

    public function firePivotAttachingEvent($halt = true)
    {
        return $this->fireModelEvent('pivotAttaching', $halt);
    }

    public function firePivotAttachedEvent($halt = false)
    {
        return $this->fireModelEvent('pivotAttached', $halt);
    }

    public function firePivotDetachingEvent($halt = true)
    {
        return $this->fireModelEvent('pivotDetaching', $halt);
    }

    public function firePivotDetachedEvent($halt = false)
    {
        return $this->fireModelEvent('pivotDetached', $halt);
    }

    public function firePivotUpdatingEvent($halt = true)
    {
        return $this->fireModelEvent('pivotUpdating', $halt);
    }

    public function firePivotUpdatedEvent($halt = false)
    {
        return $this->fireModelEvent('pivotUpdated', $halt);
    }

    /**
     * Get the observable event names.
     *
     * @return array
     */
    public function getObservableEvents()
    {
        return array_merge(
            [
                'retrieved', 'creating', 'created', 'updating', 'updated',
                'saving', 'saved', 'restoring', 'restored',
                'deleting', 'deleted', 'forceDeleted',
                'pivotAttaching', 'pivotAttached',
                'pivotDetaching', 'pivotDetached',
                'pivotUpdating', 'pivotUpdated',
            ],
            $this->observables
        );
    }

    /**
     * Instantiate a new BelongsToMany relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @param  string  $table
     * @param  string  $foreignPivotKey
     * @param  string  $relatedPivotKey
     * @param  string  $parentKey
     * @param  string  $relatedKey
     * @param  string  $relationName
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    protected function newBelongsToMany(Builder $query, Model $parent, $table, $foreignPivotKey, $relatedPivotKey,
                                        $parentKey, $relatedKey, $relationName = null)
    {
        return new BelongsToMany($query, $parent, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relationName);
    }
}
