<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @namespace
 * 
 * This directive defines a namespace for a GraphQL type,
 * allowing you to organize your types into logical groups.
 * 
 * Usage:
 * ```graphql
 * type User @namespace(field: "App\\Models") {
 *   id: ID!
 *   name: String!
 * }
 * 
 * type Post @namespace(field: "App\\Models") {
 *   id: ID!
 *   title: String!
 * }
 * ```
 */
class NamespaceGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $namespace = $this->directiveArgValue('field');
        $type = $this->directiveArgValue('type');

        if ($namespace && $type) {
            $class = $namespace . '\\' . $type;
            return app($class);
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 