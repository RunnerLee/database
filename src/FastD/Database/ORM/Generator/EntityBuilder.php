<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/10/11
 * Time: 下午10:00
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Database\ORM\Generator;

/**
 * Class EntityBuilder
 *
 * @package FastD\Database\ORM\Generator
 */
class EntityBuilder extends BuilderAbstract
{
    public function build($namespace, $dir, $flag = BuilderAbstract::BUILD_PSR4)
    {
        $name = $namespace;
        if (false !== ($index = strrpos($namespace, '\\'))) {
            $name = ucfirst(substr($namespace, $index + 1));
            $namespace = ucfirst(substr($namespace, 0, $index));
        }

        $properties= [];
        $methods = [];
        $primary = '';

        foreach ($this->table->getFields() as $alias => $field) {
            $properties[] = $this->generateProperty($alias, $field->getType());
            $methods[] = $this->generateGetSetter($alias, $field->getType());
        }

        $repository = " = '{$name}Repository'";
        if (!empty($namespace)) {
            $repository = " = '{$namespace}\\Repository\\{$name}Repository'";
        }

        $table = $this->table->getName();

        $namespace = ltrim($namespace . '\\Entity;', '\\');
        $namespace = 'namespace ' . $namespace;

        $fields = $this->generateFields();
        $properties = implode(PHP_EOL, $properties);
        $methods = implode(PHP_EOL, $methods);

        $entity = <<<E
<?php

{$namespace}

use FastD\Database\ORM\Entity;

class {$name} extends Entity
{
    /**
     * @var string
     */
    protected \$table = '{$table}';

    /**
     * @var string|null
     */
    protected \$repository{$repository};
    {$primary}
    {$properties}
    {$methods}
}
E;

        if (!is_dir($entityDir = $dir . '/Entity')) {
            mkdir($entityDir, 0755, true);
        }

        file_put_contents($dir . '/Entity/' . $name . '.php', $entity);
    }
}