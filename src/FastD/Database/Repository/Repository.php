<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/12
 * Time: 上午11:15
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace FastD\Database\Repository;

use FastD\Database\Driver\Driver;

/**
 * Class Repository
 *
 * @package FastD\Database\Repository
 */
class Repository implements RepositoryInterface
{
    /**
     * @var
     */
    protected $table;

    /**
     * @var Driver
     */
    protected $connection;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @param Driver $driver
     */
    public function __construct(Driver $driver = null)
    {
        $this->connection = $driver;
    }

    /**
     * Return mapping database table full name.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Reset database table mapping related.
     *
     * @param string $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return Driver
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param array $where
     * @param array $field
     * @return array|bool
     */
    public function find(array $where = [], array $field = [])
    {
        if (!is_array($where)) {
            $where = array('id' => $where);
        }

        $row = $this->connection->find($this->getTable(), $where, $field);

        return null === $this->getFields() ? $row : $this->parseTableFieldsData($row, $this->getFields());
    }

    /**
     * @param array $where
     * @param array|string $field
     * @return array|bool
     */
    public function findAll(array $where = [],  array $field = [])
    {
        if (!is_array($where)) {
            $where = array('id' => $where);
        }

        $list = $this->connection->findAll($this->getTable(), $where, $field);

        if (null === $this->getFields()) {
            return $list;
        }

        foreach ($list as $key => $value) {
            $list[$key] = $this->parseTableFieldsData($value, $this->getFields());
        }

        return $list;
    }

    /**
     * @param array $data
     * @return int|bool
     */
    public function insert(array $data = array())
    {
        return $this->connection->insert($this->getTable(), $data);
    }

    /**
     * @param array $data
     * @param array $where
     * @return int|bool
     */
    public function update(array $data = [], array $where = [])
    {
        return $this->connection->update($this->getTable(), $data, $where);
    }

    /**
     * @param array $where
     * @return int|bool
     */
    public function count(array $where = [])
    {
        return $this->connection->count($this->getTable(), $where);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->connection->getErrors();
    }

    /**
     * @return string
     */
    public function getLastQuery()
    {
        return $this->connection->getQueryString();
    }

    /**
     * @return array
     */
    public function getQueryLogs()
    {
        return $this->connection->getLogs();
    }

    /**
     * @param $dql
     * @return Driver
     */
    public function createQuery($dql)
    {
        return $this->connection->createQuery($dql);
    }

    /**
     * @param int  $page
     * @param int  $showList
     * @param int  $showPage
     * @param null $lastId
     * @return \FastD\Database\Pagination\QueryPagination
     */
    public function pagination($page = 1, $showList = 25, $showPage = 5, $lastId = null)
    {
        return $this->connection->pagination($this->getTable(), $page, $showList, $showPage, $lastId);
    }

    public function persist(EntityAbstract $entityAbstract)
    {

        $connection = $this->connection->table($entityAbstract->getTable());
    }
}