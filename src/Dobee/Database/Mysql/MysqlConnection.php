<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/8
 * Time: 上午12:03
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Database\Mysql;

use Dobee\Database\Connection\ConnectionInterface;
use Dobee\Database\Repository\Repository;

if (!class_exists('\\medoo')) {
    include __DIR__ . '/Medoo/medoo.min.php';
}

/**
 * Class MysqlConnection
 *
 * @package Dobee\Kernel\Configuration\Drivers\Db\Mysql
 */
class MysqlConnection implements ConnectionInterface
{
    /**
     * @var \PDOStatement
     */
    private $statement;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var array|Repository[]
     */
    private $repositories = array();

    /**
     * @var string
     */
    private $connectionName;

    private $medoo;

    /**
     * @param array $options
     * @throws \Exception
     */
    public function __construct($options = array())
    {
        $this->medoo = new \medoo(array(
            'database_type' => $options['database_type'],
            'database_name' => $options['database_name'],
            'server' => $options['database_host'],
            'username' => $options['database_user'],
            'password' => $options['database_pwd'],
            'port' => isset($options['database_port']) ? $options['database_port'] : 3306,
            'charset' => isset($options['database_charset']) ? $options['database_charset'] : 'utf8',
            'option' => array(
                \PDO::ATTR_CASE => \PDO::CASE_NATURAL
            )
        ));

        $this->prefix = isset($options['database_prefix']) ? $options['database_prefix'] : '';
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param $dql
     * @return $this
     */
    public function createQuery($dql)
    {
        $this->statement = $this->medoo->pdo->prepare($dql);

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setParameters($name, $value = '')
    {
        $this->statement->bindParam($name, $value);

        return $this;
    }

    /**
     * @return $this
     */
    public function getQuery()
    {
        $this->statement->execute();

        return $this;
    }

    /**
     * @param int $fetch_mode
     * @return array
     */
    public function getResult($fetch_mode = \PDO::FETCH_ASSOC)
    {
        return $this->statement->fetchAll($fetch_mode);
    }

    /**
     * @param              $table
     * @param array        $where
     * @param string|array $field
     * @return bool
     */
    public function find($table, $where, $field = '*')
    {
        return $this->medoo->get($table, $field, $where);
    }

    /**
     * @param              $table
     * @param array        $where
     * @param string|array $field
     * @return array|bool
     */
    public function findAll($table, $where = array(), $field = '*')
    {
        return $this->medoo->select($table, $field, $where);
    }

    /**
     * @param $repository
     * @return Repository
     */
    public function getRepository($repository)
    {
        if (isset($this->repositories[$repository])) {
            return $this->repositories[$repository];
        }

        if (false !== strpos($repository, ':')) {
            $repository = str_replace(':', '\\', $repository);
        }

        $name = $repository;

        $repository .= 'Repository';

        $repository = new $repository();

        if ($repository instanceof Repository) {

            $repository->setConnection($this);

            $repository->setPrefix($this->getPrefix());

            $repository->setTable($this->parseTableName($name));
        }

        return $repository;
    }

    /**
     * @param $name
     * @return string
     */
    private function parseTableName($name)
    {
        if (false !== ($pos = strrpos($name, '\\'))) {
            $name = preg_replace_callback(
                '/([A-Z])/',
                function ($match) {
                    return '_' . strtolower($match[1]);
                },
                substr($name, $pos + 1)
            );

            $name = ltrim($name, '_');
        }

        return $this->getPrefix() . $name;
    }

    /**
     * @return array|bool
     */
    public function logs()
    {
        return $this->medoo->log();
    }

    /**
     * @param       $table
     * @param array $data
     * @return int|bool
     */
    public function insert($table, $data = array())
    {
        return $this->medoo->insert($table, $data);
    }

    /**
     * @param       $table
     * @param array $data
     * @param array $where
     * @return int|bool
     */
    public function update($table, $data = array(), $where = array())
    {
        return $this->medoo->update($table, $data, $where);
    }

    /**
     * @param       $table
     * @param array $where
     * @return int|bool
     */
    public function delete($table, $where = array())
    {
        if (empty($where)) {
            return false;
        }

        return $this->medoo->delete($table, $where);
    }

    /**
     * @param       $table
     * @param array $where
     * @return int|bool
     */
    public function count($table, $where = array())
    {
        return $this->medoo->count($table, $where);
    }

    /**
     * @param       $table
     * @param array $where
     * @return int|bool
     */
    public function has($table, $where = array())
    {
        return $this->medoo->has($table, $where);
    }

    /**
     * @return array
     */
    public function error()
    {
        return $this->medoo->error();
    }

    /**
     * @return string
     */
    public function getLastQuery()
    {
        return $this->medoo->last_query();
    }

    /**
     * @param string $connection
     * @return $this
     */
    public function setConnectionName($connection)
    {
        $this->connectionName = $connection;

        return $this;
    }

    /**
     * @return string
     */
    public function getConnectionName()
    {
        return $this->connectionName;
    }
}