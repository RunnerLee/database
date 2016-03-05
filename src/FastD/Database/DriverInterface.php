<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/12/13
 * Time: 上午11:09
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Database;

use FastD\Database\ORM\Repository;

/**
 * Interface DriverInterface
 *
 * Driver prototype.
 *
 * @package FastD\Database\Drivers
 */
interface DriverInterface
{
    /**
     * @return array
     */
    public function getConfig();

    /**
     * @return string
     */
    public function getDatabaseName();

    /**
     * @return \PDO
     */
    public function getPdo();

    /**
     * Create SQL query statement.
     *
     * @param $sql
     * @return DriverInterface
     */
    public function query($sql);

    /**
     * Bind pdo parameters.
     *
     * @param array $parameters
     * @return $this
     */
    public function setParameter(array $parameters);

    /**
     * Execute create PDO query statement.
     *
     * @return $this
     */
    public function execute();

    /**
     * @param string|null $field Get field value.
     * @return array|bool
     */
    public function getOne($field = null);

    /**
     * @return array|bool
     */
    public function getAll();

    /**
     * @return int|bool
     */
    public function getId();

    /**
     * @return int|bool
     */
    public function getAffected();

    /**
     * Get table repository object.
     *
     * @param string $repository
     * @return Repository
     */
    public function getRepository($repository);

    /**
     * @return DriverError
     */
    public function getError();

    /**
     * DriverInterface constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo);
}