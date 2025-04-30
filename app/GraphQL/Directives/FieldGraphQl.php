<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @field
 * 
 * This directive allows specifying a custom resolver method for a field,
 * either as a class method or a closure.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   customField: String! @field(resolver: "App\\GraphQL\\Resolvers\\CustomResolver@resolve")
 *   computedField: Int! @field(resolver: "App\\GraphQL\\Resolvers\\ComputedResolver@calculate")
 * }
 * ```
 */
class FieldGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $resolver = $this->directiveArgValue('resolver');

        if (!$resolver) {
            return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
        }

        if (str_contains($resolver, '@')) {
            [$class, $method] = explode('@', $resolver);
            $instance = app($class);
            return $instance->{$method}($root, $args, $context, $resolveInfo);
        }

        return app($resolver)($root, $args, $context, $resolveInfo);
    }
} 