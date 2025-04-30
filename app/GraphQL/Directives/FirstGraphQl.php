<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @first
 * 
 * This directive retrieves the first record from a query result.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   latestPost: Post @first
 *   oldestUser: User @first
 * }
 * ```
 */
class FirstGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if (method_exists($items, 'first')) {
            return $items->first();
        }

        return $items[0] ?? null;
    }
} 