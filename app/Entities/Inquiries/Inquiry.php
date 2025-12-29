<?php

namespace App\Entities\Inquiries;

use App\Models\User;
use EMedia\Formation\Entities\GeneratesFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'submitted_id',
        'user_id',
        'message'
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
        return $this->belongsTo(User::class, 'id');
    }

    public function inquiredUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeSearch($query, $searchQuery): void
    {
        $query->where(function ($query) use ($searchQuery)
        {
            $query->whereHas('user', function ($q) use ($searchQuery)
            {
                $q->where('id', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('email', 'LIKE', '%' . $searchQuery . '%');
            });
        });
    }
}
