<?php

namespace App\Entities\Reports;

use App\Models\User;
use Hotpop\Formation\Entities\GeneratesFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    use HasFactory;

	protected $fillable = [
		'id',
        'reported_id',
        'email',
        'submitted_by'
	];

	protected $searchable = [
		'id',
        'reported_id',
        'email',
        'submitted_by'
	];

	protected $editable = [
    	'id',
        'reported_id',
        'email',
        'submitted_by'
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
        return $this->belongsTo(User::class,'reported_by');
    }
    public function reportedUser()
    {
        return $this->belongsTo(User::class,'reported_id');
    }

    public function scopeSearch($query, $searchQuery): void
	{
		$query->where(function ($query) use ($searchQuery) {
            $query->whereHas('user',function($q) use($searchQuery){
                $q->where('id', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('email', 'LIKE', '%' . $searchQuery . '%');
            });
		});
	}
}
