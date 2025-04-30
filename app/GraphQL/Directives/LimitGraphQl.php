<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @limit
 * 
 * This directive limits the number of results returned by a query,
 * useful for pagination and performance optimization.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   recentPosts: [Post!]! @limit(value: 10)
 *   latestUsers: [User!]! @limit(value: 5)
 * }
 * ```
 */
class LimitGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $value = $this->directiveArgValue('value', 10);

        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if (method_exists($items, 'take')) {
            return $items->take($value);
        }

        if (is_array($items)) {
            return array_slice($items, 0, $value);
        }

        return $items;
    }
} 