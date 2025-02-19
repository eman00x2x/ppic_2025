<?php

namespace EO\Interfaces;

interface IService
{

	function list(array $request, string $target_url);

	function get(int $id);

	function create(array$request);

	function update(int $id, array $request);

	function destroy(int $id);

}