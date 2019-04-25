<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

abstract class ScopeAwareModel extends Model
{
    protected static $publicFields = [];
    protected static $privateFields = [];

    public function toPublicList(): array
    {
        $returnList = [];
        foreach (static::$publicFields as $fieldName) {
            $returnList[$fieldName] = $this->$fieldName instanceof self
                ? $this->$fieldName->toPublicList()
                : $this->$fieldName;
        }

        return $returnList;
    }

    public function toPrivateList(): array
    {
        $returnList = [];
        foreach (array_merge(static::$publicFields, static::$privateFields) as $fieldName) {
            $returnList[$fieldName] = $this->$fieldName instanceof ScopeAwareModel
                ? $this->$fieldName->toPrivateList()
                : $this->$fieldName;
        }

        return $returnList;
    }
}
