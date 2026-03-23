<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\BaseModel;

trait HasRelationships
{
    /**
     * Один-ко-многим (как belongsTo в Laravel).
     *
     * Использование в Photo:
     *   public function user(): BelongsTo
     *   {
     *       return $this->belongsTo(User::class, 'author_id');
     *   }
     */
    public function belongsTo(string $relatedClass, string $foreignKey): BelongsTo
    {
        return new BelongsTo($this->db, $relatedClass, $foreignKey);
    }

    /**
     * Один-ко-многим (как hasMany в Laravel).
     *
     * Использование в User:
     *   public function photos(): HasMany
     *   {
     *       return $this->hasMany(Photo::class, 'author_id');
     *   }
     */
    public function hasMany(string $relatedClass, string $foreignKey): HasMany
    {
        return new HasMany($this->db, $relatedClass, $foreignKey);
    }
}