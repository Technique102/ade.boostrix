<?php

/*
 * This file is part of the official PHP MCP SDK.
 *
 * A collaboration between Symfony and the PHP Foundation.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mcp\Exception;

use Mcp\Schema\Request\GetPromptRequest;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class PromptNotFoundException extends \RuntimeException implements NotFoundExceptionInterface
{
    public function __construct(
        public readonly GetPromptRequest $request,
    ) {
        parent::__construct(\sprintf('Prompt not found for name: "%s".', $request->name));
    }
}
