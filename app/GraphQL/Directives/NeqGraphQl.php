<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @neq
 * 
 * This directive adds a not-equal comparison to a query,
 * filtering results where a field does not equal a specific value.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   inactiveUsers: [User!]! @neq(column: "status", value: "active")
 *   unpublishedPosts: [Post!]! @neq(column: "status", value: "published")
 * }
 * ```
 */
class NeqGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $column = $this->directiveArgValue('column');
        $value = $this->directiveArgValue('value');

        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if (method_exists($items, 'where')) {
            return $items->where($column, '!=', $value);
        }

        return $items;
    }
} 