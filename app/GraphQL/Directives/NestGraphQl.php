<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @nest
 * 
 * This directive enables nested field resolution in the GraphQL schema,
 * allowing you to access nested properties of objects.
 * 
 * Usage:
 * ```graphql
 * type User {
 *   profile: Profile @nest
 *   settings: Settings @nest
 * }
 * 
 * type Profile {
 *   address: Address @nest
 * }
 * ```
 */
class NestGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $path = $this->directiveArgValue('path', $resolveInfo->fieldName);
        $default = $this->directiveArgValue('default');

        if (is_array($root) && isset($root[$path])) {
            return $root[$path];
        }

        if (is_object($root) && isset($root->{$path})) {
            return $root->{$path};
        }

        return $default ?? $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
    }
} 