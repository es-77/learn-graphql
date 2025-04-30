<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @like
 * 
 * This directive adds a LIKE clause to a query,
 * filtering results where a field matches a pattern.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   searchUsers(query: String!): [User!]! @like(column: "name")
 *   searchPosts(query: String!): [Post!]! @like(column: "title")
 * }
 * ```
 */
class LikeGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $column = $this->directiveArgValue('column');
        $value = $args['query'] ?? '';

        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if (method_exists($items, 'where')) {
            return $items->where($column, 'LIKE', "%{$value}%");
        }

        return $items;
    }
} 