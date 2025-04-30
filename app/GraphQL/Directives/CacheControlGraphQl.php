<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Http\Response;

/**
 * @directive @cacheControl
 * 
 * This directive adds HTTP cache control headers to the response,
 * allowing clients and proxies to cache the results.
 * 
 * Usage:
 * ```graphql
 * type Query {
 *   publicData: PublicData! @cacheControl(maxAge: 3600, scope: PUBLIC)
 *   userData: UserData! @cacheControl(maxAge: 300, scope: PRIVATE)
 * }
 * ```
 */
class CacheControlGraphQl extends BaseDirective implements FieldResolver
{
    public function resolveField($root, array $args, GraphQLContext $context, $resolveInfo)
    {
        $maxAge = $this->directiveArgValue('maxAge', 0);
        $scope = $this->directiveArgValue('scope', 'PRIVATE');

        $result = $resolveInfo->defaultResolver($root, $args, $context, $resolveInfo);

        if ($context->response() instanceof Response) {
            $context->response()->header('Cache-Control', "{$scope}, max-age={$maxAge}");
        }

        return $result;
    }
} 