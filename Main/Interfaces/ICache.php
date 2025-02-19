<?php

namespace EO\Interfaces;

interface ICache
{
    public function setData(string $key, array $data);

    public function getData(string $key);

    public function reCached(string $key, mixed $data);

    public function removeCache(string $key);

    public function clearCache();

}