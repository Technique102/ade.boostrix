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

use Mcp\Schema\Request\CallToolRequest;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ToolNotFoundException extends \RuntimeException implements NotFoundExceptionInterface
{
    public function __construct(
        public readonly CallToolRequest $request,
    ) {
        parent::__construct(\sprintf('Tool not found for call: "%s".', $request->name));
    }
}
