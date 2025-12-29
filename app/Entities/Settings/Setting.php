<?php

namespace App\Entities\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'setting_key',
        'setting_value',
    ];

    protected $searchable = [
        'setting_key',
        'setting_value'
    ];

    protected $editable = [
        'setting_key',
        'setting_key',
    ];

    public function getCreateRules()
    {
        return [
            'setting_key' => 'required|unique:settings,setting_key',
        ];
    }

    public function getUpdateRules($id = null)
    {

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

}