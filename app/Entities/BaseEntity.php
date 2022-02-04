<?php


namespace App\Entities;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseEntity
{
    public function __construct(Model $model)
    {
        $this->modelToEntity($model, $this);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \App\Entities\BaseEntity $entity
     */
    private function modelToEntity(Model $model, BaseEntity $entity): void
    {
        $modelFields = array_merge(array_keys($model->getRelations()), $model->fillable);

        foreach ($modelFields as $fieldName) {

            if (isset($model->$fieldName)) {

                if ($model->$fieldName instanceof Model) {

                    if (method_exists($model->$fieldName, 'toEntity')) {
                        $entity->$fieldName = $model->$fieldName->toEntity();
                    }

                } elseif ($model->$fieldName instanceof Collection) {

                    foreach ($model->$fieldName as $key => $relationShipModel) {
                        if (method_exists($relationShipModel->$fieldName, 'toEntity')) {
                            $model->$fieldName[$key] = $relationShipModel->$fieldName->toEntity();

                        }
                    }

                    $entity->$fieldName = $model->$fieldName;

                } else {
                    $entity->$fieldName = $model->$fieldName;
                }
            }

        }
    }
}