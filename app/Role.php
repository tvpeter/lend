<?php

namespace App;

use Spatie\Permission\Models\Role as SpatieRole;

/**
 * App\Models\Role.
 *
 * @property int                                                               $id
 * @property string                                                            $name
 * @property string                                                            $guard_name
 * @property null|string                                                       $display_name
 * @property null|\Illuminate\Support\Carbon                                   $created_at
 * @property null|\Illuminate\Support\Carbon                                   $updated_at
 * @property \App\Models\User[]|\Illuminate\Database\Eloquent\Collection       $users
 * @property null|int                                                          $users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Permission\Models\Role permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends SpatieRole
{
    public function getRouteKeyName()
    {
        return 'name';
    }
}