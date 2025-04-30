<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @namespaced
 * 
 * This directive defines a namespaced type in the GraphQL schema,
 * allowing you to organize your types into logical groups.
 * 
 * Usage:
 * ```graphql
 * type User @namespaced(namespace: "App\\Models") {
 *   id: ID!
 *   name: String!
 * }
 * 
 * type Post @namespaced(namespace: "App\\Models") {
 *   id: ID!
 *   title: String!
 * }
 * ```
 */
class NamespacedGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $namespace = $this->directiveArgValue('namespace');
        $type = $this->directiveArgValue('type');

        if ($namespace && $type) {
            $class = $namespace . '\\' . $type;
            return app($class);
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 