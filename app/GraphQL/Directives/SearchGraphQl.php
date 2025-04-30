<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @search
 * 
 * This directive adds search functionality to a field, allowing clients to
 * search through data using various search algorithms.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   searchPosts(query: String!): [Post!]! @search
 *   searchUsers(query: String!): [User!]! @search
 * }
 * ```
 */
class SearchGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $query = $args['query'] ?? '';
        $algorithm = $this->directiveArgValue('algorithm', 'like');
        $columns = $this->directiveArgValue('columns', ['*']);

        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if (method_exists($items, 'where')) {
            if ($algorithm === 'like') {
                return $items->where(function ($queryBuilder) use ($query, $columns) {
                    foreach ($columns as $column) {
                        $queryBuilder->orWhere($column, 'LIKE', "%{$query}%");
                    }
                });
            }
        }

        return $items;
    }
} 