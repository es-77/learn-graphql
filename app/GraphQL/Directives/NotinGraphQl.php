<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @notIn
 * 
 * This directive adds a NOT IN clause to a query,
 * filtering results where a field does not match any value in a list.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   nonAdminUsers: [User!]! @notIn(column: "role", values: ["admin"])
 *   nonPublishedPosts: [Post!]! @notIn(column: "status", values: ["published"])
 * }
 * ```
 */
class NotinGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $column = $this->directiveArgValue('column');
        $values = $this->directiveArgValue('values', []);

        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if (method_exists($items, 'whereNotIn')) {
            return $items->whereNotIn($column, $values);
        }

        return $items;
    }
} 