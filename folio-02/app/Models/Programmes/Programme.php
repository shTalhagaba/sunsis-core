<?php

namespace App\Models\Programmes;

use App\Models\Lookups\ProgrammeTypeLookup;
use App\Models\MediaSection;
use App\Models\Tags\Tag;
use App\Models\Training\TrainingRecord;
use App\Traits\Filterable;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Programme extends Model implements Auditable, HasMedia
{
    use \OwenIt\Auditing\Auditable, Filterable, HasMediaTrait;

    protected $table = 'programmes';

    protected $guarded = [];

    protected $auditExclude = [
        'comments',
    ];

    public function training_records()
    {
    	return $this->hasMany(TrainingRecord::class, 'programme_id');
    }

    public function qualifications()
    {
    	return $this->hasMany(ProgrammeQualification::class, 'programme_id')
            ->orderBy('main', 'DESC')
            ->orderBy('sequence')
            ->orderBy('id');
    }

    public function training_plans()
    {
    	return $this->hasMany(ProgrammeTrainingPlan::class, 'programme_id');
    }
    
    public function sessions()
    {
        return $this->hasMany(ProgrammeDeliveryPlanSession::class, 'programme_id')->where('is_template', 0)->orderBy('session_sequence');
    }

    public function templateSessions()
    {
        return $this->hasMany(ProgrammeDeliveryPlanSession::class, 'programme_id')->where('is_template', 1)->orderBy('session_sequence');
    }

    public function programmeType()
    {
        return $this->hasOne(ProgrammeTypeLookup::class, 'id', 'programme_type');
    }

    public function tags() 
    {
        return $this->morphToMany(Tag::class, 'taggable')
            ->where('type', 'Programme')
            ->orderBy('order_column');
    }
    
    public function mediaSections() 
    {
        return $this->morphToMany(
                MediaSection::class, 
                'model', 
                'media_section_has_models',
                'model_id',
                'section_id'
            )
            ->where('media_sections.type', 'Programme')
            ->orderBy('media_sections.name');
    }

    public function isSafeToDelete()
    {
        return $this->training_records->count() == 0;
    }

    public function scopeActive()
    {
        return $this->status;
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($programme) {
            $programme->qualifications()->each(function ($qualification) {
                $qualification->delete();
            });
            $programme->training_plans()->each(function ($plan) {
                $plan->delete();
            });
        });
    }

    /**
     * {@inheritdoc}
     */
    public function transformAudit(array $data): array
    {
        if($data['event'] == 'created')
        {
            if (\Illuminate\Support\Arr::has($data, 'new_values.status'))
            {
                $data['new_values']['status'] = $data['new_values']['status'] == 1 ? 'Active' : 'In Active';
            }
        }
        else
        {
            if (\Illuminate\Support\Arr::has($data, 'new_values.status'))
            {
                $data['old_values']['status'] = $data['old_values']['status'] == 1 ? 'Active' : 'In Active';
                $data['new_values']['status'] = $data['new_values']['status'] == 1 ? 'Active' : 'In Active';
            }
        }

        return $data;
    }
}
