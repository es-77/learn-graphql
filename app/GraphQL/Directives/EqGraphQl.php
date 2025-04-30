<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @eq
 * 
 * This directive adds an equality comparison to a query,
 * filtering results where a field equals a specific value.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   activeUsers: [User!]! @eq(column: "status", value: "active")
 *   publishedPosts: [Post!]! @eq(column: "status", value: "published")
 * }
 * ```
 */
class EqGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $column = $this->directiveArgValue('column');
        $value = $this->directiveArgValue('value');

        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if (method_exists($items, 'where')) {
            return $items->where($column, '=', $value);
        }

        return $items;
    }
} 