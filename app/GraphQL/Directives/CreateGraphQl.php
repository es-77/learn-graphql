<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @create
 * 
 * This directive creates a new record in the database based on the input data.
 * 
 * Usage:
 * ```graphql
 * type Mutation {
 *   createPost(input: CreatePostInput!): Post! @create
 *   createUser(input: CreateUserInput!): User! @create
 * }
 * ```
 */
class CreateGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $model = $this->directiveArgValue('model');
        $input = $args['input'] ?? $args;

        if ($model) {
            return app($model)->create($input);
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 