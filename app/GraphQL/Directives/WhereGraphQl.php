<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @where
 * 
 * This directive adds filtering capabilities to a field, allowing clients to filter
 * results based on specific conditions.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   activeUsers: [User!]! @where(column: "status", operator: "=", value: "active")
 *   recentPosts: [Post!]! @where(column: "created_at", operator: ">", value: "2023-01-01")
 * }
 * ```
 */
class WhereGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $column = $this->directiveArgValue('column');
        $operator = $this->directiveArgValue('operator', '=');
        $value = $this->directiveArgValue('value');

        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if (method_exists($items, 'where')) {
            return $items->where($column, $operator, $value);
        }

        return $items;
    }
} 