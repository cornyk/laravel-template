<?php

namespace App\Daos;

use App\Models\BaseModel;

abstract class BaseDao
{
    /**
     * 设置当前模型
     * @return  string
     */
    abstract protected function setModel(): string;

    /**
     * 获取当前模型
     * @param array $params
     * @return BaseModel
     */
    protected function getModel(array $params = []): BaseModel
    {
        return app($this->setModel(), $params);
    }
}
