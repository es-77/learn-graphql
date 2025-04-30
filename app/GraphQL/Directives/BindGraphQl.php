<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @bind
 * 
 * This directive allows binding specific values to field arguments,
 * making them available in the resolver context.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   userPosts: [Post!]! @bind(key: "user_id", value: "me.id")
 *   categoryProducts: [Product!]! @bind(key: "category_id", value: "1")
 * }
 * ```
 */
class BindGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $key = $this->directiveArgValue('key');
        $value = $this->directiveArgValue('value');

        if ($key && $value) {
            $args[$key] = $value;
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 