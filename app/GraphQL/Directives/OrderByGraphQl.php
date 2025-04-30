<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @orderBy
 * 
 * This directive orders query results by a specified column,
 * allowing you to sort data in ascending or descending order.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   users: [User!]! @orderBy(column: "created_at", direction: "desc")
 *   posts: [Post!]! @orderBy(column: "title", direction: "asc")
 * }
 * ```
 */
class OrderByGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $column = $this->directiveArgValue('column');
        $direction = $this->directiveArgValue('direction', 'asc');

        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if (method_exists($items, 'orderBy')) {
            return $items->orderBy($column, $direction);
        }

        if (is_array($items)) {
            usort($items, function ($a, $b) use ($column, $direction) {
                $valueA = is_array($a) ? $a[$column] : $a->{$column};
                $valueB = is_array($b) ? $b[$column] : $b->{$column};

                if ($direction === 'asc') {
                    return $valueA <=> $valueB;
                }

                return $valueB <=> $valueA;
            });

            return $items;
        }

        return $items;
    }
} 