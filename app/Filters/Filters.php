<?php
/**
 * Created by PhpStorm.
 * User: corean
 * Date: 2018-12-01
 * Time: 23:50
 */

namespace App\Filters;


use Illuminate\Http\Request;

abstract class Filters
{
    protected $request, $builder;
    protected $filters = [];
    
    /**
     * ThreadFilters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * @param $builder
     * @return mixed
     */
    public function apply($builder)
    {
        $this->builder = $builder;
        
        $this->getFilters()
            ->filter(function ($value, $filter) {
                return method_exists($this, $filter);
            })
            ->each(function ($value, $filter) {
                return $this->$filter($value);
            });

        return $this->builder;
        
    }
    
    public function getFilters()
    {
        return collect($this->request->only($this->filters));
    }
    
}
