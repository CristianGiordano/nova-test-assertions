<?php

namespace NovaTestHelpers;

use Illuminate\Database\Eloquent\Model;

trait NovaEndpointAware
{
    /**
     * The pluralised version of the Nova resource.
     *
     * @return string
     */
    abstract public function resource(): string;

    public function endpoint(Model $model = null)
    {
        return $model ? "/nova-api/{$this->resource()}/{$model->getKey()}" : "/nova-api/{$this->resource()}";
    }

    public function performActionEndpoint(array $params = [])
    {
        return "/nova-api/{$this->resource()}/action?" . http_build_query($params);
    }

    public function actionsEndpoint(Model $model = null)
    {
        $queryString = $model ? '?resourceId=' . $model->id : '';

        return "/nova-api/{$this->resource()}/actions" . $queryString;
    }

    public function creationFieldsEndpoint()
    {
        return "/nova-api/{$this->resource()}/creation-fields";
    }
}