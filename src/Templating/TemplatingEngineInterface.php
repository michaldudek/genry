<?php
namespace Genry\Templating;

use Splot\Framework\Templating\TemplatingEngineInterface as BaseTemplatingEngineInterface;

/**
 * General templating engine interface for Genry.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
interface TemplatingEngineInterface extends BaseTemplatingEngineInterface
{

    /**
     * Clear any cached templates.
     *
     * This is called before regenerating the whole project to make sure
     * that no templates are cached.
     */
    public function clearCache();
}
