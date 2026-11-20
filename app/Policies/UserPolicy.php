<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy {
  public function viewAny(User $actor): bool {
    return $actor->disabled_at === null && $actor->hasAnyRole(['superadmin', 'admin']);
  }

  public function create(User $actor): bool {
    return $this->viewAny($actor);
  }

  public function update(User $actor, User $user): bool {
    return $this->viewAny($actor) && (! $user->hasRole('superadmin') || $actor->hasRole('superadmin'));
  }

  public function resetPassword(User $actor, User $user): bool {
    return ! $actor->is($user) && $this->update($actor, $user);
  }

  public function setActive(User $actor, User $user): bool {
    return ! $actor->is($user) && $this->update($actor, $user);
  }
}
