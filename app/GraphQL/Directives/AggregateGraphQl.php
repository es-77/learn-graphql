<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @aggregate
 * 
 * This directive allows performing aggregation operations on a field,
 * such as counting, summing, averaging, etc.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   totalUsers: Int! @aggregate(function: "count")
 *   averagePostLikes: Float! @aggregate(function: "avg", column: "likes")
 *   totalRevenue: Float! @aggregate(function: "sum", column: "amount")
 * }
 * ```
 */
class AggregateGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $function = $this->directiveArgValue('function');
        $column = $this->directiveArgValue('column', '*');

        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if (method_exists($items, $function)) {
            return $items->{$function}($column);
        }

        return $items;
    }
} 