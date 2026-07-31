<?php

namespace jbrowneuk\di;

final class ContainerFactory
{
    public static function initialiseContainer(\PDO $pdo): \DI\Container
    {
        $definitions = [
            \PDO::class => fn () => $pdo,
            \jbrowneuk\interfaces\IAlbumDBO::class => fn ($c) => new \jbrowneuk\database\AlbumDBO($c->get(\PDO::class)),
            \jbrowneuk\interfaces\IAuthenticationDBO::class => fn ($c) => new \jbrowneuk\database\AuthenticationDBO($c->get(\PDO::class)),
            \jbrowneuk\interfaces\IPostsDBO::class => fn ($c) => new \jbrowneuk\database\PostsDBO($c->get(\PDO::class)),
            \jbrowneuk\interfaces\IAuthentication::class => fn ($c) => new \jbrowneuk\core\Authentication($c->get(\jbrowneuk\interfaces\IAuthenticationDBO::class)),
        ];

        $containerBuilder = new \DI\ContainerBuilder();
        $containerBuilder->addDefinitions($definitions);
        return $containerBuilder->build();
    }
}
