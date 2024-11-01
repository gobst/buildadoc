<?php

/**
 * This file is part of BuildADoc.
 *
 * (c) Guido Obst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace Exception;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Throwable;

final class TemplateNotFoundException extends FileNotFoundException
{
    public function __construct(string $path = null, Throwable $previous = null)
    {
        if ($path === null) {
            $message = 'Template could not be found.';
        } else {
            $message = sprintf('Template "%s" could not be found.', $path);
        }
        parent::__construct($message, 404, $previous, $path);
    }
}
