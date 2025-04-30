<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @builder
 * 
 * This directive allows using a custom query builder method
 * to modify the query before execution.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   featuredPosts: [Post!]! @builder(method: "featured")
 *   popularProducts: [Product!]! @builder(method: "popular")
 * }
 * ```
 */
class BuilderGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $method = $this->directiveArgValue('method');
        $items = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if ($method && method_exists($items, $method)) {
            return $items->{$method}();
        }

        return $items;
    }
} 