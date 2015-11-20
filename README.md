SoftDelete behavior for Yii2
============================

This extension allows you to store any attribute change happening to an ActiveRecord


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```sh
php composer.phar require machour/yii2-softdelete-behavior "*"
```

or add

```json
"machour/yii2-softdelete-behavior": "*"
```

to the require section of your `composer.json` file.


Configuration
-------------

You need to configure your model as follows:

```php
class Blog extends ActiveRecord
{
    use \machour\yii2\behaviors\SoftDeleteTrait;

    static public function getDeletedAtAttribute() 
    {
        return self::tableName() . '.deleted_at';
    }

}
```
