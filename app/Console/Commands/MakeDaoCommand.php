<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDaoCommand extends GeneratorCommand
{
    protected $signature = 'make:dao {name}';

    protected $description = 'Create a new DAO (Data Access Object) class';

    // 定义生成的类类型
    protected $type = 'DAO';

    // 处理命令，生成DAO文件
    public function handle()
    {
        $name = $this->argument('name');

        // 获取DAO类名
        $daoClassName = $this->getDaoClassName($name);

        // 获取模型类名
        $modelClassName = $this->getModelClassName($daoClassName);

        // 创建DAO类文件
        $this->createDaoFile($daoClassName, $modelClassName);

        $this->info("DAO class '{$daoClassName}' created successfully.");
    }

    // 获取DAO类名（首字母大写）
    protected function getDaoClassName($name)
    {
        // 直接返回传入的类名
        return Str::studly($name);
    }

    // 获取模型类名
    protected function getModelClassName($daoClassName)
    {
        // 假设模型类名与 DAO 类名相似，通常模型类名是 DAO 类名去掉 "Dao" 后缀
        return Str::endsWith($daoClassName, 'Dao')
            ? Str::studly(substr($daoClassName, 0, -3)) . 'Model'  // 去掉 "Dao" 后缀，并加上 "Model"
            : $daoClassName . 'Model';
    }

    // 创建DAO文件
    protected function createDaoFile($daoClassName, $modelClassName)
    {
        $daoContent = $this->buildDaoContent($daoClassName, $modelClassName);

        // 生成DAO类文件路径
        $daoPath = app_path("Daos/{$daoClassName}.php");

        // 写入文件
        File::put($daoPath, $daoContent);
    }

    // 构建DAO文件内容
    protected function buildDaoContent($daoClassName, $modelClassName)
    {
        // DAO类的内容
        $daoContent = "<?php\n\nnamespace App\Daos;\n\n";
        $daoContent .= "use App\Models\\{$modelClassName};\n\n";
        $daoContent .= "class {$daoClassName} extends BaseDao\n";
        $daoContent .= "{\n";
        $daoContent .= "    /**\n";
        $daoContent .= "     * @inheritDoc\n";
        $daoContent .= "     */\n";
        $daoContent .= "    protected function setModel(): string\n";
        $daoContent .= "    {\n";
        $daoContent .= "        return {$modelClassName}::class;\n";
        $daoContent .= "    }\n";
        $daoContent .= "}\n";

        return $daoContent;
    }

    protected function getStub()
    {
    }
}
