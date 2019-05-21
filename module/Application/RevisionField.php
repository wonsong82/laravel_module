<?php

namespace Module\Application;

use Illuminate\Database\Eloquent\Model;

class RevisionField extends Model
{
    protected $table = 'revision_fields';
    protected $fillable = [
        'revision_id',
        'field_name',
        'old_value',
        'new_value',
        'is_dirty',
        'is_related',
        'model',
        'entity',
        'entity_id'
    ];



    // RELATIONS

    public function revision()
    {
        $this->belongsTo(Revision::class);
    }


}
