<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @method
 * 
 * This directive allows specifying a custom method to resolve a field,
 * either as a class method or a closure.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   userStats: UserStats! @method(name: "getUserStats")
 *   systemInfo: SystemInfo! @method(name: "getSystemInfo")
 * }
 * ```
 */
class MethodGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $method = $this->directiveArgValue('name');

        if (!$method) {
            return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
        }

        if (method_exists($root, $method)) {
            return $root->{$method}($args, $context);
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 