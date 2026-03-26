<?php

namespace jbrowneuk;

use \DI\ContainerBuilder;

final class ContainerFactory
{
    public static function initialiseContainer(\PDO $pdo): \DI\Container
    {
        $definitions = [
            \PDO::class => fn () => $pdo,
            IAlbumDBO::class => fn ($c) => new AlbumDBO($c->get(\PDO::class)),
            IAuthenticationDBO::class => fn ($c) => new AuthenticationDBO($c->get(\PDO::class)),
            IPostsDBO::class => fn ($c) => new PostsDBO($c->get(\PDO::class)),
            IAuthentication::class => fn ($c) => new Authentication($c->get(IAuthenticationDBO::class)),
        ];

        $containerBuilder = new ContainerBuilder;
        $containerBuilder->addDefinitions($definitions);
        $container = $containerBuilder->build();

        return $container;
    }
}

function initialiseContainer(\PDO $pdo): \DI\Container
{
    return ContainerFactory::initialiseContainer($pdo);
}
