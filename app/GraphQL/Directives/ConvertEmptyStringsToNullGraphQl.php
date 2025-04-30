<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @convertEmptyStringsToNull
 * 
 * This directive converts empty strings to null values in the input,
 * helping to maintain consistent data handling.
 * 
 * Usage:
 * ```graphql
 * type Mutation {
 *   updateProfile(input: UpdateProfileInput!): Profile!
 *     @convertEmptyStringsToNull
 *   createUser(input: CreateUserInput!): User!
 *     @convertEmptyStringsToNull
 * }
 * ```
 */
class ConvertEmptyStringsToNullGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $input = $args['input'] ?? $args;
        
        array_walk_recursive($input, function (&$value) {
            if ($value === '') {
                $value = null;
            }
        });

        if (isset($args['input'])) {
            $args['input'] = $input;
        } else {
            $args = $input;
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 