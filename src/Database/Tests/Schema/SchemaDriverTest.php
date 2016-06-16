<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Database\Tests\Schema;

use FastD\Database\Schema\SchemaDriver;
use FastD\Database\Drivers\MySQLDriver;
use FastD\Database\Tests\Fixture_Database_TestCast;

class SchemaDriverTest extends Fixture_Database_TestCast
{
    const CONNECTION = [
        'database_host'      => '127.0.0.1',
        'database_port'      => '3306',
        'database_name'      => 'dbunit',
        'database_user'      => 'root',
        'database_pwd'       => '123456',
        'database_prefix'    => 'fd_'
    ];

    public function testTableSchemaReflexRename()
    {
        $driver = new MySQLDriver(self::CONNECTION);

        $schemaDriver = new SchemaDriver($driver);

        $schemaDriver->getSchemaReflex()->reflex(
            __DIR__ . '/Reflex/Rename/' . $schemaDriver->getDbName(),
            'Test\\Rename\\' . $schemaDriver->getDbName()
        );
    }

    public function testTableSchemaReflex()
    {
        $driver = new MySQLDriver(parent::CONNECTION);

        $schemaDriver = new SchemaDriver($driver);

        $schemaDriver->getSchemaReflex()->reflex(
            __DIR__ . '/Reflex/' . $schemaDriver->getDbName(),
            'Test\\' . $schemaDriver->getDbName()
        );
    }
}
