<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @count
 * 
 * This directive counts the number of records returned by a query,
 * useful for pagination and statistics.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   totalUsers: Int! @count
 *   activePosts: Int! @count
 * }
 * ```
 */
class CountGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if (method_exists($items, 'count')) {
            return $items->count();
        }

        return count($items);
    }
} 