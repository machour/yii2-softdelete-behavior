<?php

namespace machour\yii2\behaviors;

use yii\base\InvalidConfigException;
use yii\db\Expression;

/**
 * @author Mehdi Achour <machour@gmail.com>
 */
trait SoftDeleteTrait {

    /**
     * Soft deletes a record
     * 
     * @return boolean Returns TRUE if the record was soft deleted, FALSE otherwise
     */
    public function softDelete()
    {
        $this->{self::getDeletedAtAttribute()} = new Expression('NOW()');
        $ret = $this->save(false, [self::getDeletedAtAttribute()]);
        $this->afterSoftDelete();
        return $ret;
    }

    /**
     * This method is invoked after deleting a record.
     *
     * You may override this method to do postprocessing after the record is soft deleted.
     */
    public function afterSoftDelete()
    {
        // Default implementation
    }

    /**
     * Override default delete() behavior in order to soft delete
     *
     * @return boolean Returns TRUE if the record was soft deleted, FALSE otherwise
     */
    public function delete()
    {
    	return $this->softDelete();
    }

    /**
     * Performs a hard delete (deletes from the database)
     *
     * @return boolean Returns TRUE on success, FALSE on failure
     */
    public function hardDelete()
    {
        return parent::delete() !== false;
    }

    /**
     * Finds where deleted_at IS NULL
     *
     * @return \yii\db\ActiveQueryInterface The newly created yii\db\ActiveQueryInterface instance.
     * @throws InvalidConfigException
     */
	static public function findActive() {
		return parent::find()->andWhere(self::tableName() . '.' . self::getDeletedAtAttribute() . ' IS NULL');
	}

    /**
     * Finds where deleted_at IS NOT NULL
     *
     * @return \yii\db\ActiveQueryInterface The newly created yii\db\ActiveQueryInterface instance.
     * @throws InvalidConfigException
     */
	static public function findInactive() {
		return parent::find()->andWhere(self::tableName() . '.' . self::getDeletedAtAttribute() . ' IS NOT NULL');
	}

    /**
     * Finds records regardless of deleted_at
     *
     * @return \yii\db\ActiveQueryInterface The newly created yii\db\ActiveQueryInterface instance.
     * @throws InvalidConfigException
     */
	static public function findBoth() {
		return parent::find();
	}

    /**
     * Gets the deleted_at attribute name
     *
     * @throws \Exception
     */
	static public function getDeletedAtAttribute() {
		throw new InvalidConfigException('You should define the getDeletedAtAttribute() method in your inheritance');
	}

	/**
	 * Restores a soft-deleted attribute
     *
     * @return boolean Returns TRUE if the record could be restored, FALSE otherwise
	 */
	public function restore()
	{
        $this->{self::getDeletedAtAttribute()} = null;
        return $this->save(false, [self::getDeletedAtAttribute()]);
	}


}
