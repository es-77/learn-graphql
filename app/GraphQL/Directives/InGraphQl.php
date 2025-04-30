<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @in
 * 
 * This directive adds an IN clause to a query,
 * filtering results where a field matches any value in a list.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   usersByRoles(roles: [String!]!): [User!]! @in(column: "role")
 *   postsByStatus(status: [String!]!): [Post!]! @in(column: "status")
 * }
 * ```
 */
class InGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $column = $this->directiveArgValue('column');
        $values = $args[$column] ?? [];

        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if (method_exists($items, 'whereIn')) {
            return $items->whereIn($column, $values);
        }

        return $items;
    }
} 