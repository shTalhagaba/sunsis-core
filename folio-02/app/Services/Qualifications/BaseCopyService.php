<?php

namespace App\Services\Qualifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class BaseCopyService
{
    public function copy(Model $source, Model $destination, array $relationshipMap)
    {
        // Copy attributes from the source to the destination model
        $attributes = $source->getAttributes();
        unset($attributes['id']); // Ensure the primary key is not copied
        $destination->fill($attributes);
        $destination->save();

        // Copy each relationship
        foreach ($relationshipMap as $relationName => $nestedRelations) 
        {
            if ($source->$relationName instanceof Collection) 
            {
                foreach ($source->$relationName as $relatedModel) 
                {
                    $relationClass = is_array($nestedRelations) ? $nestedRelations['class'] : $nestedRelations;

                    $newModel = $this->copyNestedModel($relatedModel, $destination, $relationClass);

                    // Recursively copy nested relationships if defined
                    if (is_array($nestedRelations) && isset($nestedRelations['relations'])) 
                    {
                        $this->copyNestedRelationships($relatedModel, $newModel, $nestedRelations['relations']);
                    }
                }
            }
        }

        return $destination;
    }

    protected function copyNestedModel(Model $sourceModel, Model $destinationParent, $relationClass)
    {
        $destinationModel = new $relationClass();
        $attributes = $sourceModel->getAttributes();
        unset($attributes['id']); // Ensure the primary key is not copied
        $destinationModel->fill($attributes);

        $foreignKey = $this->getForeignKey($destinationParent);
        $destinationModel->$foreignKey = $destinationParent->id;
        
        // Ensure the foreign key is set before saving
        $destinationModel->save();

        return $destinationModel;
    }

    protected function copyNestedRelationships(Model $sourceModel, Model $destinationModel, array $relationshipMap)
    {
        foreach ($relationshipMap as $relationName => $nestedRelations) 
        {
            if ($sourceModel->$relationName instanceof Collection) 
            {
                foreach ($sourceModel->$relationName as $relatedModel) 
                {
                    $relationClass = is_array($nestedRelations) ? $nestedRelations['class'] : $nestedRelations;

                    $newNestedModel = $this->copyNestedModel($relatedModel, $destinationModel, $relationClass);

                    // Recursively copy nested relationships if defined
                    if (is_array($nestedRelations) && isset($nestedRelations['relations'])) 
                    {
                        $this->copyNestedRelationships($relatedModel, $newNestedModel, $nestedRelations['relations']);
                    }
                }
            }
        }
    }

    protected function getForeignKey(Model $model)
    {
        return $model->getForeignKey();
    }
}
