<?php

use Laracasts\Presenter\PresentableTrait;

class EquipmentLog extends Eloquent {

    use PresentableTrait;


    protected $presenter = 'BB\Presenters\EquipmentLogPresenter';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'equipment_log';


    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'key_fob_id', 'device', 'active', 'last_update', 'finished', 'notes', 'reason'
    ];

    public function getDates()
    {
        return array('created_at', 'updated_at', 'started', 'last_update', 'finished');
    }


    public function user()
    {
        return $this->belongsTo('User');
    }

    public function keyFob()
    {
        return $this->belongsTo('KeyFob');
    }

} 