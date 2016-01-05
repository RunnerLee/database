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

namespace FastD\Database\ORM;

use FastD\Database\Drivers\DriverInterface;

/**
 * Class Repository
 *
 * @package FastD\Database\Repository
 */
abstract class Repository extends HttpRequestHandle
{
    const FIELDS    = [];
    const ALIAS     = [];
    const PRIMARY   = '';

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @param DriverInterface $driverInterface
     */
    public function __construct(DriverInterface $driverInterface = null)
    {
        $this->setDriver($driverInterface);
    }

    /**
     * @return DriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param DriverInterface|null $driverInterface
     * @return $this
     */
    public function setDriver(DriverInterface $driverInterface = null)
    {
        $this->driver = $driverInterface;

        return $this;
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
     * @return array
     */
    public function getFields()
    {
        return static::FIELDS;
    }

    /**
     * @return array
     */
    public function getAlias()
    {
        return static::ALIAS;
    }

    /**
     * @return string
     */
    public function getPrimary()
    {
        return static::PRIMARY;
    }

    /**
     * Fetch one row.
     *
     * @param array $where
     * @param array $field
     * @return array The found object.
     */
    public function find(array $where = [], array $field = [])
    {
        return $this->driver
            ->table(
                $this->getTable()
            )
            ->where($where)
            ->field(array () === $field ? $this->getFields() : $field)
            ->find()
            ;
    }

    /**
     * Fetch all rows.
     *
     * @param array $where
     * @param array|string $field
     * @return array The found object.
     */
    public function findAll(array $where = [],  array $field = [])
    {
        return $this->driver
            ->table(
                $this->getTable()
            )
            ->where($where)
            ->field(array () === $field ? $this->getAlias() : $field)
            ->findAll()
        ;
    }

    /**
     * Save row into table.
     *
     * @param array $data
     * @param array $where
     * @param array $params
     * @return bool|int
     */
    public function save(array $data = [], array $where = [], array $params = [])
    {
        return $this->driver
            ->table(
                $this->getTable()
            )
            ->save(empty($data) ? $this->data : $data, $where, empty($params) ? $this->params : $params);
    }

    /**
     * @param array $where
     * @param array $params
     * @return int|bool
     */
    public function count(array $where = [], array $params = [])
    {
        return $this->driver->table($this->getTable())->count($where, $params);
    }

    /**
     * @param string $sql
     * @return DriverInterface
     */
    public function createQuery($sql)
    {
        return $this->driver->createQuery($sql);
    }

    /**
     * @param int  $page
     * @param int  $showList
     * @param int  $showPage
     * @param null $lastId
     * @return
     */
    /*public function pagination($page = 1, $showList = 25, $showPage = 5, $lastId = null)
    {
        return $this->driver->pagination($this->getTable(), $page, $showList, $showPage, $lastId);
    }*/

    /**
     * Return query errors.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->driver->getErrors();
    }

    /**
     * @return \FastD\Database\Drivers\Query\QueryBuilderInterface
     */
    public function getQueryBuilder()
    {
        return $this->driver->getQueryBuilder();
    }
}