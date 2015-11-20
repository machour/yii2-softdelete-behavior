<?php

namespace machour\yii2\behaviors;

use yii\db\Expression;

/**
 * @author Mehdi Achour <machour@gmail.com>
 */
trait SoftDeleteTrait {


    /**
     * Soft deletes a record
     * 
     * @return  boolean FALSE
     */
    public function softDelete()
    {
        $this->{self::getDeletedAtAttribute()} = new Expression('NOW()');
        $this->save(false, [self::getDeletedAtAttribute()]);
        $this->afterSoftDelete();
        return false;
    }

    public function afterSoftDelete()
    {
        return false; // default implementation
    }

    /**
     * Override default delete() behavior in order to soft delete
     */
    public function delete()
    {
    	return $this->softDelete();
    }

    /**
     * Performs a hard delete (deletes from the database)
     */
    public function hardDelete()
    {
    	return parent::delete();
    }


	static public function findActive() {
		return parent::find()->andWhere(self::tableName() . '.' . self::getDeletedAtAttribute() . ' IS NULL');
	}

	static public function findInactive() {
		return parent::find()->andWhere(self::tableName() . '.' . self::getDeletedAtAttribute() . ' IS NOT NULL');
	}

	static public function findBoth() {
		return parent::find();
	}

	static public function getDeletedAtAttribute() {
		throw new \Exception('You should define the getDeletedAtAttribute() method in your inheritance');
	}

	/**
	 * Restores a soft-deleted attribute
	 */
	public function restore()
	{
        $this->{self::getDeletedAtAttribute()} = null;
        $this->save(false, [self::getDeletedAtAttribute()]);
        return true;
	}


}
