<?php

namespace App;

use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * App\Models\Permission.
 *
 * @property int                                                               $id
 * @property string                                                            $name
 * @property string                                                            $guard_name
 * @property null|string                                                       $display_name
 * @property null|string                                                       $description
 * @property null|\Illuminate\Support\Carbon                                   $created_at
 * @property null|\Illuminate\Support\Carbon                                   $updated_at
 * @property \App\Models\Permission[]|\Illuminate\Database\Eloquent\Collection $permissions
 * @property null|int                                                          $permissions_count
 * @property \App\Models\Role[]|\Illuminate\Database\Eloquent\Collection       $roles
 * @property null|int                                                          $roles_count
 * @property \App\Models\User[]|\Illuminate\Database\Eloquent\Collection       $users
 * @property null|int                                                          $users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Permission\Models\Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Permission\Models\Permission role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Permission extends SpatiePermission
{
    public function getRouteKeyName()
    {
        return 'name';
    }
}
