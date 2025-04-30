<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @all
 * 
 * This directive retrieves all records of a model without any filtering.
 * It's useful for getting complete lists of data.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   users: [User!]! @all
 *   posts: [Post!]! @all
 * }
 * ```
 */
class AllGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if (method_exists($items, 'all')) {
            return $items->all();
        }

        return $items;
    }
} 