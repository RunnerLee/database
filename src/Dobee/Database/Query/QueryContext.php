<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/17
 * Time: 下午11:21
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace Dobee\Database\Query;

/**
 * Prototype query context.
 *
 * Class QueryContext
 *
 * @package Dobee\Database\Query
 */
class QueryContext
{
    protected $table;
    protected $where;
    protected $fields = '*';
    protected $limit;
    protected $offset;
    protected $join;
    protected $group;
    protected $order;
    protected $having;
    protected $value;
    protected $keys;
    protected $sql;

    const CONTEXT_INSERT = 1;
    const CONTEXT_UPDATE = 2;
    const CONTEXT_DELETE = 3;

    protected function parseWhere(array $where)
    {
        if (empty($where)) {
            return '';
        }

        $conditions = reset($where);

        $joint = '';

        if (is_array($conditions)) {
            $jointArr = array_keys($where);
            $joint = $jointArr[0];
            unset($jointArr);
        } else {
            $conditions = $where;
        }

        $where = [];

        foreach ($conditions as $key => $value) {
            if (false === strpos($value, ' ')) {
                $where[] = '`' . $key . '`=\'' . $value . '\'';
            } else {
                $where[] = '`' . $key . '`' . $value;
            }
        }

        if ('' !== $joint) {
            $joint = ' ' . $joint . ' ';
        }

        return implode($joint, $where);
    }

    public function data(array $data, $operation = QueryContext::CONTEXT_UPDATE)
    {
        switch ($operation) {
            case QueryContext::CONTEXT_INSERT:
                $keys = array_keys($data);
                $values = array_values($data);
                $this->keys = '(`' . implode('`,`', $keys) . '`)';
                $this->value = '(\'' . implode('\',\'', $values) . '\')';
                break;
            case QueryContext::CONTEXT_UPDATE:
            default:
                $values = [];
                foreach ($data as $name => $value) {
                    $values[] = '`' . $name . '`=\'' . $value . '\'';
                }
                $this->value = implode(',', $values);
        }

        return $this;
    }

    public function table($table)
    {
        $this->fields   = '*';
        $this->where    = null;
        $this->group    = null;
        $this->limit    = null;
        $this->having   = null;
        $this->order    = null;
        $this->keys     = null;
        $this->value    = null;
        $this->sql      = null;
        $this->join     = null;

        $this->table = str_replace('``', '`', '`' . $table . '`');

        return $this;
    }

    public function where(array $where = [])
    {
        $where = $this->parseWhere($where);

        if ('' != $where) {
            $this->where = ' WHERE ' . $where;
        }

        return $this;
    }

    public function fields(array $fields = [])
    {
        if (array() !== $fields) {
            $this->fields = '';
            foreach ($fields as $value) {
                if (false === strpos($value, ' ')) {
                    $this->fields .= '`' . $value . '`,';
                } else {
                    $this->fields .= $value . ',';
                }
            }
            $this->fields = trim($this->fields, ',');
        } else {
            $this->fields = '*';
        }

        return $this;
    }

    public function group($group)
    {
        $this->group = ' GROUP BY ' . $group;

        return $this;
    }

    public function having(array $having)
    {
        $this->having = ' HAVING ' . $this->parseWhere($having);

        return $this;
    }

    public function order($order)
    {
        $this->order = ' ORDER BY ' . $order;

        return $this;
    }

    public function limit($limit = null, $offset = null)
    {
        $this->limit = null;

        if (null !== $limit) {
            $this->limit = ' LIMIT ' . (null === $offset ? '' : ($offset . ',')) . $limit;
        }

        return $this;
    }

    public function select()
    {
        $this->sql = 'SELECT ' . $this->fields . ' FROM ' . $this->table . $this->where . $this->group . $this->having . $this->order . $this->limit . ';';

        return $this;
    }

    public function update()
    {
        $this->sql = 'UPDATE ' . $this->table . ' SET ' . $this->value . $this->where . $this->limit . ';';

        return $this;
    }

    public function delete()
    {
        $this->sql = 'DELETE FROM ' . $this->table . $this->where . $this->limit . ';';

        return $this;
    }

    public function insert()
    {
        $this->sql = 'INSERT INTO ' . $this->table . $this->keys . ' VALUES ' . $this->value . ';';

        return $this;
    }

    public function getSql()
    {
        return $this->sql;
    }
}