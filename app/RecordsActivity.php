<?php
/**
 * Created by PhpStorm.
 * User: corean
 * Date: 2018-12-09
 * Time: 19:18
 */

namespace App;


trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) return;
        
        //Activity 모델도 같이 저장
        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }
    
    private static function getActivitiesToRecord()
    {
        return ['created'];
    }
    
    protected function recordActivity($event)
    {
        $this->activities()
             ->create([
                          'type'    => $this->getActivityType($event),
                          'user_id' => auth()->id(),
                      ]);
    }
    
    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
    
    protected function getActivityType($event)
    {
        $type = mb_strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }
}
