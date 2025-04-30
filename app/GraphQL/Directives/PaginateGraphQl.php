<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @paginate
 * 
 * This directive enables pagination for query results,
 * allowing you to fetch data in chunks with cursor-based pagination.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   users: [User!]! @paginate(type: "paginator")
 *   posts: [Post!]! @paginate(type: "connection")
 * }
 * ```
 */
class PaginateGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $type = $this->directiveArgValue('type', 'paginator');
        $perPage = $this->directiveArgValue('perPage', 15);
        $page = $args['page'] ?? 1;

        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if (method_exists($items, 'paginate')) {
            return $items->paginate($perPage, ['*'], 'page', $page);
        }

        if (is_array($items)) {
            $total = count($items);
            $items = array_slice($items, ($page - 1) * $perPage, $perPage);

            return [
                'data' => $items,
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
            ];
        }

        return $items;
    }
} 