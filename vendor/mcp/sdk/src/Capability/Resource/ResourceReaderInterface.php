<?php

/*
 * This file is part of the official PHP MCP SDK.
 *
 * A collaboration between Symfony and the PHP Foundation.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mcp\Capability\Resource;

use Mcp\Exception\ResourceNotFoundException;
use Mcp\Exception\ResourceReadException;
use Mcp\Schema\Request\ReadResourceRequest;
use Mcp\Schema\Result\ReadResourceResult;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface ResourceReaderInterface
{
    /**
     * @throws ResourceReadException     if the resource execution fails
     * @throws ResourceNotFoundException if the resource is not found
     */
    public function read(ReadResourceRequest $request): ReadResourceResult;
}
