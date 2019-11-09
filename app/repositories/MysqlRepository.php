<?php
namespace app\repositories;

abstract class MysqlRepository
{
    /** @var string */
    public static $modelClass;
    /** @var string */
    public static $table;

    /** @var \PDO */
    private $connection;

    /**
     * MysqlRepository constructor.
     * @param \PDO $connection
     */
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    public function findOne($id)
    {
        $stmt = $this->getConnection()->prepare('SELECT * FROM ' . static::$table . ' WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetchObject(static::$modelClass);
    }

    public function findOneForUpdate($id)
    {
        $stmt = $this->getConnection()->prepare('SELECT * FROM ' . static::$table . ' WHERE id = :id FOR UPDATE');
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetchObject(static::$modelClass);
    }

    public function findAll()
    {
        $stmt = $this->getConnection()->prepare('SELECT * FROM ' . static::$table);
        $stmt->execute();

        $models = [];
        while ($model = $stmt->fetchObject(static::$modelClass)) {
            $models[$model->id] = $model;
        }

        return $models;
    }
}
