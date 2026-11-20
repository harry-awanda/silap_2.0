<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountAudit extends Model {
  public $timestamps = false;

  protected $fillable = ['actor_id', 'user_id', 'action', 'metadata', 'created_at'];

  protected function casts(): array {
    return ['metadata' => 'array', 'created_at' => 'datetime'];
  }
}
