<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Database\Tests\Fixtures;

use FastD\Database\Fixtures\FixtureLoader;
use FastD\Database\Tests\Fixture_Database_TestCast;
use FastD\Database\Tests\Fixtures\DemoFixture;

class FixtureTest extends Fixture_Database_TestCast
{
    const CONNECTION = [
        'database_host' => '127.0.0.1',
        'database_port' => '3306',
        'database_name' => 'dbunit',
        'database_user' => 'root',
        'database_pwd' => '123456'
    ];

    public function testFixtures()
    {
        $fixture = new FixtureLoader($this->getLocalDriver());

        $fixture->registerFixture(new DemoFixture());

        $fixture->run();
    }
}
