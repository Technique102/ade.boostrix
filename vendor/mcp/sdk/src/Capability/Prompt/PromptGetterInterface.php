<?php

/*
 * This file is part of the official PHP MCP SDK.
 *
 * A collaboration between Symfony and the PHP Foundation.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mcp\Capability\Prompt;

use Mcp\Exception\PromptGetException;
use Mcp\Exception\PromptNotFoundException;
use Mcp\Schema\Request\GetPromptRequest;
use Mcp\Schema\Result\GetPromptResult;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface PromptGetterInterface
{
    /**
     * @throws PromptGetException      if the prompt execution fails
     * @throws PromptNotFoundException if the prompt is not found
     */
    public function get(GetPromptRequest $request): GetPromptResult;
}
