<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/2/17
 * Time: 下午10:08
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Database\Tests;

use PHPUnit_Extensions_Database_DB_IDatabaseConnection;

/**
 * Class Fixture_Database_TestCast
 *
 * @package FastD\Database\Tests
 */
abstract class Fixture_Database_TestCast extends \PHPUnit_Extensions_Database_TestCase
{
    /**
     * Custom connection information.
     *
     * @const array
     */
    const CONNECTION = [];

    /**
     * @return class
     */
    protected function buildDSN()
    {
        return new class(static::CONNECTION)
        {
            private $user;
            private $pwd;
            private $charset;
            private $dsn;
            private $name;

            public function __construct($connection)
            {
                $this->dsn = sprintf('mysql:host=%s;dbname=%s', $connection['host'], $connection['dbname']);
                $this->user = $connection['user'];
                $this->pwd = $connection['pwd'];
                $this->charset = isset($connection['charset']) ? $connection['charset'] : 'utf8';
                $this->name = $connection['dbname'];
            }

            public function getDSN()
            {
                return $this->dsn;
            }

            public function getUser()
            {
                return $this->user;
            }

            public function getPwd()
            {
                return $this->pwd;
            }

            public function getCharset()
            {
                return $this->charset;
            }

            public function getName()
            {
                return $this->name;
            }
        };
    }

    /**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        $dsn = $this->buildDSN();

        return $this->createDefaultDBConnection(new \PDO($dsn->getDSN(), $dsn->getUser(), $dsn->getPwd()), $dsn->getName());
    }

    /**
     * Returns the test dataset.
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(__DIR__ . '/DataSet/base.yml');
    }
}