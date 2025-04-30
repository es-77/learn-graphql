<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @deprecated
 * 
 * This directive marks a field as deprecated in the GraphQL schema,
 * providing a reason for deprecation and suggesting alternatives.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   oldField: String! @deprecated(reason: "Use newField instead")
 *   legacyData: Data! @deprecated(reason: "This will be removed in v2.0")
 * }
 * ```
 */
class DeprecatedGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $reason = $this->directiveArgValue('reason', 'No longer supported');
        
        // Log deprecation warning
        \Log::warning("Deprecated field used: {$resolveInfo->fieldName}. Reason: {$reason}");

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 