<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/12/13
 * Time: 下午10:36
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

include __DIR__ . '/../vendor/autoload.php';

use FastD\Database\Drivers\DriverFactory;
use FastD\Database\Drivers\MySQL;

$driver = DriverFactory::createDriver([
    'database_type' => 'mysql',
    'database_user' => 'root',
    'database_pwd'  => '123456',
    'database_host' => '127.0.0.1',
    'database_port' => 3306,
    'database_name' => 'test',
]);

$driver = new MySQL([
    'database_user' => 'root',
    'database_pwd'  => '123456',
    'database_host' => '127.0.0.1',
    'database_port' => 3306,
    'database_name' => 'test',
]);

// Get \PDO instance
$driver->getPDO();

// Get QueryBuilderInterface
$driver->getQueryBuilder();

// General operation.
//$driver->table('test')->find();

$result = $driver
    ->createQuery(
        'select * from test where `name`=:name'
    )
    ->setParameter('name', 'janhuang')
    ->getQuery()
    ->getOne()
;
echo '<pre>';
print_r($result);
echo '</pre>';

$result = $driver
    ->table('test')
    ->where(['id' => ':id'])
    ->find(['id' => 1])
;
echo '<pre>';
print_r($result);
echo '</pre>';

$id = $driver
    ->table('test')
    ->save([
        'name' => ':name'
    ], [
        'name' => 'bbb'
    ], [
        'id[!=]' => 1
    ]);
;

echo $id;
