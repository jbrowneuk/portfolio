<?php

namespace jbrowneuk;

/**
 * This file is a stop-gap solution while I evaluate Inversion of Control
 * frameworks.
 */

/**
 * Creates an instance of the PostsDBO object
 */
function posts_dbo_factory(\PDO $pdo)
{
    return new PostsDBO($pdo);
}