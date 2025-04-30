<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @feature
 * 
 * This directive enables feature flagging for GraphQL fields,
 * allowing you to control access to features based on configuration.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   betaFeature: BetaData! @feature(name: "beta-feature")
 *   experimentalFeature: ExperimentalData! @feature(name: "experimental")
 * }
 * ```
 */
class FeatureGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $feature = $this->directiveArgValue('name');
        $user = $context->user();

        if (!$feature) {
            throw new \Exception('Feature name is required');
        }

        if (!config("features.{$feature}", false)) {
            throw new \Exception("Feature {$feature} is not enabled");
        }

        return $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 