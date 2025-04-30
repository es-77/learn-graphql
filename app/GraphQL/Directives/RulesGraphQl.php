<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Validator;

/**
 * @directive @rules
 * 
 * This directive adds validation rules to a field, ensuring that input data
 * meets specific requirements before being processed.
 * 
 * Usage:
 * ```graphql
 * type Mutation {
 *   createUser(input: CreateUserInput!): User!
 *     @rules(apply: ["required", "email", "min:8"])
 *   updateProfile(input: UpdateProfileInput!): Profile!
 *     @rules(apply: ["required", "min:3"])
 * }
 * ```
 */
class RulesGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $rules = $this->directiveArgValue('apply', []);
        $input = $args['input'] ?? $args;

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 