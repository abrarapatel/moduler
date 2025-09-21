<?php

namespace AbrarPatel\Moduler\Services;

use Illuminate\Support\Facades\File;

class StubGenerater
{
    public function getStub($type, $name)
    {
        $stubPath = __DIR__ . "/../stubs/{$type}.stub";
        $content = File::get($stubPath);
        return str_replace('{{name}}', $name, $content);
    }

    public function getMigrationStub($name, $schema)
    {
        $stubPath = __DIR__ . "/../stubs/migration.stub";
        $content = File::get($stubPath);
        $tableName = strtolower($name) . 's';

        $schemaContent = '';

        foreach ($schema['fields'] as $field) {
            $schemaContent .= "\n\t\t\t" . $this->getFieldLine($field);
        }

        if (isset($schema['timestamps']) && $schema['timestamps'] === true) {
            $schemaContent .= "\n\t\t\t\$table->timestamps();";
        }

        if (isset($schema['softDeletes']) && $schema['softDeletes'] === true) {
            $schemaContent .= "\n\t\t\t\$table->softDeletes();";
        }

        if (isset($schema['index']) && is_array($schema['index'])) {
            $schemaContent .= "\n\t\t\t\$table->index(['" . implode("', '", $schema['index']) . "']);";
        }

        $fileContent = str_replace('{{table}}', $tableName, $content);
        $fileContent = str_replace('{{schema}}', $schemaContent, $fileContent);

        return $fileContent;
    }

    private function getFieldLine($field)
    {
        $type = $field['type'];
        $postfix = '';
        $params = '';

        $constraints = $field['constraints'] ?? [];

        // Parameters for specific types
        if ($type == 'char' || $type == 'string') {
            // if type is char or string
            if (isset($field['props']['length']) && is_numeric($field['props']['length'])) {
                // if length is set, it will be added as parameter
                $params .= ", {$field['props']['length']}";
            }
        } else if ($type == 'decimal' || $type == 'double' || $type == 'float') {
            // if type is decimal, double or float
            if (isset($field['props']['precision']) && is_numeric($field['props']['precision'])) {
                // if precision is set, it will be added as parameter
                $params .= ", {$field['props']['precision']}";
            }

            if (isset($field['props']['scale']) && is_numeric($field['props']['scale'])) {
                // if scale is set, it will be added as parameter
                $params .= ", {$field['props']['scale']}";
            }
        }

        if ($type == 'integer' || $type == 'bigInteger' || $type == 'char' || $type == 'string' || $type == 'decimal' || $type == 'double' || $type == 'float' || $type == 'longText' || $type == 'text') {
            // if type is numeric or string or text (no boolean, date, timestamp, etc)
            if ($type == 'integer' || $type == 'bigInteger') {
                // if type is integer or bigInteger, check for increment and primary key constraints
                if (in_array('increment', $constraints)) {
                    // if increment is set, it will be primary key by default
                    $type = $type == 'bigInteger' ? 'bigIncrements' : 'increments';
                } else if (in_array('primary', $constraints) && !in_array('increment', $constraints)) {
                    // if primary is set but increment is not set, it will be normal integer with primary key
                    $postfix .= "->primary()";
                }
            } else {
                // for other than integer and bigInteger, check for primary key constraint
                if (in_array('primary', $constraints)) {
                    // if primary is set, it will be primary key in postfix
                    $postfix .= "->primary()";
                }
            }
        }

        if ($type == 'integer' || $type == 'bigInteger' || $type == 'decimal' || $type == 'double' || $type == 'float') {
            // if type is numeric
            if (in_array('unsigned', $constraints)) {
                // if unsigned is set, it will be unsigned in postfix
                $postfix .= "->unsigned()";
            }
        }

        if ($type == 'enum') {
            if (isset($field['props']['allowed_values']) && is_array($field['props']['allowed_values'])) {
                $enumValues = array_map(function ($val) {
                    // Keep integers as-is, wrap everything else in quotes
                    if (is_int($val)) {
                        return $val;
                    }
                    return "'" . addslashes($val) . "'";
                }, $field['props']['allowed_values']);

                $params .= ", [" . implode(", ", $enumValues) . "]";
            } else {
                // fallback: treat as string with length 255
                $type = 'string';
            }
        }

        if (!in_array('primary', $constraints) && !in_array('increment', $constraints)) {
            // if nullable is set, it will be nullable in postfix
            if (in_array('nullable', $constraints)) {
                $postfix .= "->nullable()";
            }

            if (in_array('unique', $constraints)) {
                // if unique is set, it will be unique in postfix
                $postfix .= "->unique()";
            } else {
                if (in_array('index', $constraints)) {
                    // if index is set, it will be index in postfix
                    $postfix .= "->index()";
                }

                if (isset($field['props']['default'])) {
                    $default = $field['props']['default'];
                    if (is_string($default)) {
                        $default = "'" . addslashes($default) . "'";
                    } else if (is_bool($default)) {
                        $default = $default ? 'true' : 'false';
                    }

                    $postfix .= "->default({$default})";
                }
            }
        }

        $line = "\$table->{$type}('{$field['name']}'{$params}){$postfix};";

        return $line;
    }
}
