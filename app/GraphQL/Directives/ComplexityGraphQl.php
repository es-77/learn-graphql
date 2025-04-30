<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @complexity
 * 
 * This directive allows specifying the computational complexity of a field,
 * helping to prevent overly complex queries.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   userFriends: [User!]! @complexity(complexity: 10)
 *   nestedComments: [Comment!]! @complexity(complexity: 20)
 * }
 * ```
 */
class ComplexityGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $complexity = $this->directiveArgValue('complexity', 1);
        $maxComplexity = $this->directiveArgValue('max', 1000);

        if ($complexity > $maxComplexity) {
            throw new \Exception("Query complexity exceeds maximum allowed value of {$maxComplexity}");
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 