<?php

namespace App\Entities\Personalities;

use App\Models\User;
use EMedia\Formation\Entities\GeneratesFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personality extends Model
{

    use HasFactory;

    protected $table = 'personality';

    const MUSIC = 1;
    const PARTYING = 2;
    const TRAVEL = 3;
    const EXERCISE = 4;

    protected $fillable = [
        'user_id',
        'occupation',
        'hometown',
        'nationality',
        'height',
        'relationship_type',
        'dating_intentions',
        'min_age',
        'max_age',
        'like_to_have_more_childran',
        'interests'
    ];

    protected $searchable = [
        'name'
    ];

    protected $editable = [
        'name',
    ];

    /**
     *
     * Add any update only validation rules for this model
     *
     * @return array
     */
    public function getCreateRules()
    {
        return [
            'name' => 'required',
        ];
    }

    /**
     *
     * Add any update only validation rules for this model
     *
     * @return array
     */
    public function getUpdateRules()
    {
        return [
            'name' => 'required',
        ];
    }

    /**
     *
     * Add any update only validation messations
     *
     * @return array
     */
    public function getCreateValidationMessages()
    {
        return [];
    }

    /**
     *
     * Add any update only validation messations
     *
     * @return array
     */
    public function getUpdateValidationMessages()
    {
        return [];
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}
