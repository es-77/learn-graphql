<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @createMany
 * 
 * This directive creates multiple records in the database based on the input data.
 * 
 * Usage:
 * ```graphql
 * type Mutation {
 *   createPosts(input: [CreatePostInput!]!): [Post!]! @createMany
 *   createUsers(input: [CreateUserInput!]!): [User!]! @createMany
 * }
 * ```
 */
class CreateManyGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $model = $this->directiveArgValue('model');
        $input = $args['input'] ?? $args;

        if ($model) {
            return collect($input)->map(function ($item) use ($model) {
                return app($model)->create($item);
            });
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 