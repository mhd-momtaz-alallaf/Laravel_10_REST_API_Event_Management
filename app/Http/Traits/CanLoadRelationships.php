<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

trait CanLoadRelationships
{
    public function loadRelationships(
        Model|QueryBuilder|EloquentBuilder $for, // $for is the query we will applay relations loading on it,
        ?array $relations = null ): Model|QueryBuilder|EloquentBuilder { // $relations is the relation/s we want to load.
        $relations = $relations ?? $this->relations ?? []; // (?? means or) this operator checks if the left variable is empety or null then use the right variable.
            // if we want to costomize a specefice array relations defferent from the passed $relations array, in this case we will not pass the array as
        foreach ($relations as $relation) {
        $for->when( // when the first argument ($this->shouldIncludeRelation($relation)) is true, it will run the second function ( fn($q) => ) to alter the query
            $this->shouldIncludeRelation($relation), 
            fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation) // instanceof is a php build in keyword lets us to check what is the class type of a specefic object.
                                                                                          // $for->load($relation) for an existing inctance of the model.
                                                                                          // $q->with($relation) to add the relations by a query builder.
        );
        }

        return $for;
  }

    protected function shouldIncludeRelation(string $relation): bool // this function is for get the relations from the include parameter in the route and load it from the model with the query.
                                                                     // we will load the relations optionally (not allways load the user relation in the index, will load it just when we need it).                                                                 // so we will use the include parameter in the route and add the relation/s we want to load.
    {
        $include = request()->query('include');

        if (!$include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include)); // the explode function lets us to convert a string to array using specefic sprator(,-.:).
                                                                // the array_map function will run the php build in function (trim) for every array element returned by explode (trim is a php build in function that remove all the spaces at the start and the end of the element)
        return in_array($relation, $relations); // a php build in function the checks if model relation $relation is exist in the relations array.
    }
}