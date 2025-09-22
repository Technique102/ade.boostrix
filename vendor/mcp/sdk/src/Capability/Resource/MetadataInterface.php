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

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface MetadataInterface extends IdentifierInterface
{
    public function getName(): string;

    public function getDescription(): ?string;

    public function getMimeType(): ?string;

    /**
     * Size in bytes.
     */
    public function getSize(): ?int;
}
