<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * @directive @enum
 * 
 * This directive defines an enumeration type in the GraphQL schema,
 * providing a set of named values.
 * 
 * Usage:
 * ```graphql
 * enum UserRole @enum {
 *   ADMIN
 *   USER
 *   GUEST
 * }
 * 
 * enum PostStatus @enum {
 *   DRAFT
 *   PUBLISHED
 *   ARCHIVED
 * }
 * ```
 */
class EnumGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $value = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);
        $values = $this->directiveArgValue('values', []);

        if (!in_array($value, $values)) {
            throw new \Exception("Invalid enum value: {$value}");
        }

        return $value;
    }
} 