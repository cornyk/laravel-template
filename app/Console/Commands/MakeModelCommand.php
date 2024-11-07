<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MakeModelCommand extends GeneratorCommand
{
    protected $signature = 'make:model {name}';

    protected $description = 'Create a new Eloquent model class';

    // 定义生成的模型类类型
    protected $type = 'Model';

    // 处理命令，生成模型文件
    public function handle()
    {
        $name = $this->argument('name');

        // 获取表名（去掉Model后缀并转为复数形式）
        $tableName = $this->getTableName($name);

        // 检查表是否存在
        if (!Schema::hasTable($tableName)) {
            $this->error("Table '{$tableName}' does not exist in the database.");
            return;
        }

        // 获取表的字段信息
        $columns = Schema::getColumnListing($tableName);

        // 获取字段注释
        $columnComments = $this->getColumnComments($tableName);

        // 获取字段类型
        $columnTypes = $this->getColumnTypes($tableName);

        // 获取表的注释
        $tableComment = $this->getTableComment($tableName);

        // 创建模型文件
        $this->createModel($name, $columns, $columnComments, $columnTypes, $tableName, $tableComment);

        $this->info("Model class '$name' created successfully.");
    }

    // 去掉 "Model" 后缀并转为复数形式生成表名
    protected function getTableName($modelName)
    {
        // 去掉 "Model" 后缀
        $modelName = Str::endsWith($modelName, 'Model') ? substr($modelName, 0, -5) : $modelName;

        // 转为小写复数形式
        return Str::snake(Str::plural($modelName));
    }

    // 获取字段注释
    protected function getColumnComments($tableName)
    {
        // 查询数据库的 INFORMATION_SCHEMA 获取字段注释（以 MySQL 为例）
        $comments = DB::select(
            "SELECT COLUMN_NAME, COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ?",
            [$tableName, env('DB_DATABASE')]  // 根据当前数据库连接使用表名和数据库名
        );

        $columnComments = [];
        foreach ($comments as $comment) {
            $columnComments[$comment->COLUMN_NAME] = $comment->COLUMN_COMMENT;
        }

        return $columnComments;
    }

    // 获取字段类型
    protected function getColumnTypes($tableName)
    {
        // 查询数据库的 INFORMATION_SCHEMA 获取字段类型（以 MySQL 为例）
        $types = DB::select(
            "SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ?",
            [$tableName, env('DB_DATABASE')]
        );

        $columnTypes = [];
        foreach ($types as $type) {
            $columnTypes[$type->COLUMN_NAME] = $type->DATA_TYPE;
        }

        return $columnTypes;
    }

    // 获取表的注释
    protected function getTableComment($tableName)
    {
        // 查询数据库的 INFORMATION_SCHEMA 获取表注释
        $comment = DB::selectOne(
            "SELECT TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ?",
            [$tableName, env('DB_DATABASE')]
        );

        // 如果表没有注释，则返回默认值
        return $comment ? $comment->TABLE_COMMENT : '数据表';
    }

    // 创建模型文件
    protected function createModel($name, $columns, $columnComments, $columnTypes, $tableName, $tableComment)
    {
        $modelClassName = $this->getModelClassName($name);

        // 构建模型内容
        $modelContent = $this->buildModelContent($modelClassName, $columns, $columnComments, $columnTypes, $tableName, $tableComment);

        // 生成模型文件路径
        $modelPath = app_path("Models/{$modelClassName}.php");

        // 写入文件
        File::put($modelPath, $modelContent);
    }

    // 获取模型的类名（首字母大写）
    protected function getModelClassName($name)
    {
        return Str::studly($name);  // 转换为 StudlyCase 格式
    }

    // 构建模型文件内容
    protected function buildModelContent($modelClassName, $columns, $columnComments, $columnTypes, $tableName, $tableComment)
    {
        // 模型头部的文档注释部分
        $docBlock = "/**\n";
        $docBlock .= " * {$tableName} {$tableComment}\n";  // 使用实际的表名和表注释
        $docBlock .= " *\n";

        // 生成字段注释
        foreach ($columns as $column) {
            // 获取字段类型并映射到 PHP 类型
            $phpType = $this->mapDatabaseTypeToPhpType($columnTypes[$column]);

            // 获取字段注释，如果没有注释则使用字段名称
            $comment = isset($columnComments[$column]) ? $columnComments[$column] : '字段描述';
            $docBlock .= " * @property {$phpType} \${$column} {$comment}\n";
        }

        // 模型内容
        $docBlock .= " */\n\n";

        // 模型类的代码部分
        $modelContent = "<?php\n\nnamespace App\Models;\n\n";
        $modelContent .= $docBlock;
        $modelContent .= "class {$modelClassName} extends BaseModel\n";
        $modelContent .= "{\n";
        $modelContent .= "    /**\n";
        $modelContent .= "     * The table associated with the model.\n";
        $modelContent .= "     *\n";
        $modelContent .= "     * @var string\n";
        $modelContent .= "     */\n";
        $modelContent .= "    protected \$table = '{$tableName}';\n\n";

        // 这里将 $fillable 设为空数组
        $modelContent .= "    /**\n";
        $modelContent .= "     * The attributes that are mass assignable.\n";
        $modelContent .= "     *\n";
        $modelContent .= "     * @var array\n";
        $modelContent .= "     */\n";
        $modelContent .= "    protected \$fillable = [];\n"; // 空的 $fillable

        $modelContent .= "}\n";

        return $modelContent;
    }

    // 数据库字段类型映射到 PHP 类型
    protected function mapDatabaseTypeToPhpType($dbType)
    {
        // 映射数据库类型到 PHP 类型
        $typeMap = [
            'int' => 'int',
            'tinyint' => 'int',
            'smallint' => 'int',
            'mediumint' => 'int',
            'bigint' => 'int',
            'decimal' => 'float',
            'float' => 'float',
            'double' => 'float',
            'varchar' => 'string',
            'text' => 'string',
            'char' => 'string',
            'datetime' => 'Carbon\Carbon',
            'timestamp' => 'Carbon\Carbon',
            'date' => 'Carbon\Carbon',
            'time' => 'Carbon\Carbon',
            'year' => 'int',
            'boolean' => 'bool',
            'json' => 'array',
            'jsonb' => 'array',
            'enum' => 'string',
            'set' => 'string',
            'binary' => 'string',
        ];

        // 默认类型为 mixed
        return $typeMap[$dbType] ?? 'mixed';
    }

    protected function getStub()
    {
    }
}
