<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @model
 * 
 * This directive binds a GraphQL type to a Laravel model,
 * allowing automatic resolution of model instances.
 * 
 * Usage:
 * ```graphql
 * type User @model(class: "App\\Models\\User") {
 *   id: ID!
 *   name: String!
 *   email: String!
 * }
 * 
 * type Post @model(class: "App\\Models\\Post") {
 *   id: ID!
 *   title: String!
 *   content: String!
 * }
 * ```
 */
class ModelGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $class = $this->directiveArgValue('class');
        $id = $args['id'] ?? null;

        if ($class && $id) {
            return app($class)->findOrFail($id);
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 