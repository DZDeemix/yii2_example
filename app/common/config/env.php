<?php

$repository = Dotenv\Repository\RepositoryBuilder::create()
    ->withReaders([
        new Dotenv\Repository\Adapter\EnvConstAdapter(),
    ])
    ->withWriters([
        new Dotenv\Repository\Adapter\EnvConstAdapter(),
        new Dotenv\Repository\Adapter\PutenvAdapter(),
    ])
    ->immutable()
    ->make();

Dotenv\Dotenv::create($repository, YZ_BASE_DIR, null)->load();