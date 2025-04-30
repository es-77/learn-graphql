<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Hash;

/**
 * @directive @hash
 * 
 * This directive hashes field values using Laravel's Hash facade,
 * useful for password fields and sensitive data.
 * 
 * Usage:
 * ```graphql
 * type Mutation {
 *   createUser(input: CreateUserInput!): User!
 *     @hash(field: "password")
 *   updatePassword(input: UpdatePasswordInput!): Boolean!
 *     @hash(field: "new_password")
 * }
 * ```
 */
class HashGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $field = $this->directiveArgValue('field');
        $input = $args['input'] ?? $args;

        if ($field && isset($input[$field])) {
            $input[$field] = Hash::make($input[$field]);
        }

        if (isset($args['input'])) {
            $args['input'] = $input;
        } else {
            $args = $input;
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 