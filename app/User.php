<?php

namespace app;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Platform\App\Activity\ActivityRecorder;
use Platform\Observers\UserObserver;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use ActivityRecorder;
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    public $appName = 'user';

    protected $verbs = [
        'is_banned' => 'ban|unban|boolean'
    ];

    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->display_name
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'display_name', 'email', 'password', 'confirmation_code',
        'reset_pin', 'se' , 'is_password_change_required', 'is_banned',
        'is_active', 'last_login_location',
    ];

    public $searchable = [
            'displayName' => [
                'column' => 'display_name',
                'operation' => 'ILIKE'
            ],
            'email' => [
                'column' => 'email',
                'operation' => 'ILIKE'
            ],
            'createdAt' => [
                'column' => 'created_at',
                'operation' => 'date'
            ],
            'updatedAt' => [
                'column' => 'updated_at',
                'operation' => 'date'
            ],
        ];

    public $sortable = [
        'displayName' => 'display_name',
        'email' => 'email',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public $globalSearchColumns = ['email', 'display_name'];

    public $pivots = [
        'tag' => [
            'pivotTable' => 'App\UserUserTag',
            'pivotSearchField' => 'tag_id',
            'pivotResultField' => 'user_id',
            'relation' => 'App\UserTag',
            'operation' => 'ILIKE',
            'relationField' => 'name',
            'modelField' => 'id',
        ]
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'is_god','remember_token', 'confirmation_code',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'last_login_location' => 'object',
    ];

    public $incrementing = false;

    public function techpacks()
    {
        return $this->belongsToMany(
            'App\Techpack',
            'techpack_user',
            'user_id',
            'techpack_id'
        )->withPivot('permission');
    }

    public function roles()
    {
        return $this->belongsToMany(
            'App\Role',
            'role_user',
            'user_id',
            'role_id'
        );
    }

    public function groups()
    {
        return $this->belongsToMany(
            'App\Group',
            'group_user',
            'user_id',
            'group_id'
        );
    }

    /**
     * Get providers for the user.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function providers()
    {
        return $this->belongsToMany(
            'App\Provider',
            'provider_user',
            'user_id',
            'provider_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(
            'App\UserTag',
            'user_user_tag',
            'user_id',
            'tag_id'
        )->withPivot('tagged_by')->withTimestamps();
    }

    /**
     * A user has many favourited picks
     *
     */
    public function pickFavourites()
    {
        return $this->belongsToMany(
            'Platform\Picks\Models\Pick',
            'pick_favourites',
            'user_id',
            'pick_id'
        );
    }

    // public static function table()
    // {
    //     $model = new static;
    //     return $model->getTable();
    // }

    // public static function boot()
    // {
    //     $events = new \Illuminate\Events\Dispatcher;
    //     static::observe(new UserObserver());
    //     parent::boot($events);
    // }

    public function userDetails()
    {
        return $this->hasOne('App\UserDetail', 'user_id');
    }

    public function notes()
    {
        return $this->hasMany('App\UserNote', 'user_id')
                    ->whereNull('deleted_at');
    }

    /**
     * Sample Submissions relation for user
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sampleSumissions()
    {
        return $this->hasMany('App\SampleSubmission', 'user_id');
    }

    /**
     * Sample Submissions relation for user
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function collabInvites()
    {
        return $this->hasMany('Platform\CollabBoard\Models\CollabInvite', 'user_id');
    }

    public function transformSchema()
    {

        $sortable = ['Name' => 'display_name' ,
                'Email' => 'email' ,
                'Created At' => 'users.created_at' ,
                'Updated At' => 'users.updated_at'
        ];

        $filterable = ['Name' => 'display_name' ,
                'Email' => 'email' ,
                'Active' => 'is_active',
                'Ban'    => 'is_banned',
                'SeUser' => 'se',
                'LoginDetails' => 'last_login_location'
        ];

        $filterOperation = ['Name' => 'ILIKE' ,
                'Email' => 'ILIKE' ,
                'Active' => '=',
                'Ban'    => '=',
                'SeUser' => '=',
                'LoginDetails' => 'ILIKE'
        ];


        return ['sortable' => $sortable , 'filterable' => $filterable , 'operation' => $filterOperation ];
    }

    public function reportSchema()
    {

        $feColumns = [
                        ['label' => 'Name' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Email' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Banned' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Tags' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Created At' , 'isSort' => true , 'isFilter' => false],
                        ['label' => 'Updated At' , 'isSort' => true , 'isFilter' => false]
                     ];

        //return ['headers' => $feColumns , 'sortable' => ['orderby' => $sortable] , 'filterable' => ['type' => $filterable] , 'paginate' => $paginate ];
        return ['headers' => $feColumns ];
    }
}
