<?php


Flight::map('jsonResponse', function($data, $code = 200, $options = 0) {
    Flight::_json($data, $code, true, 'utf-8', $options);
});

Flight::map('getArrayFromModels', function (array $models): array
{
    return array_map(fn($model) => $model->toArray(), $models);
});



