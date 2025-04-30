<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Event;

/**
 * @directive @event
 * 
 * This directive dispatches an event after a field is resolved,
 * allowing for event-driven architecture in your GraphQL API.
 * 
 * Usage:
 * ```graphql
 * type Mutation {
 *   createPost(input: CreatePostInput!): Post! @event(dispatch: "PostCreated")
 *   updateUser(input: UpdateUserInput!): User! @event(dispatch: "UserUpdated")
 * }
 * ```
 */
class EventGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $event = $this->directiveArgValue('dispatch');
        $result = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if ($event) {
            Event::dispatch(new $event($result));
        }

        return $result;
    }
} 