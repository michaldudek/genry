<?php
namespace Genry\Templating;

use Splot\Framework\Templating\TemplatingEngineInterface as BaseTemplatingEngineInterface;

interface TemplatingEngineInterface extends BaseTemplatingEngineInterface
{

    /**
     * Clear any cached templates.
     *
     * This is called before regenerating the whole project to make sure
     * that no templates are cached.
     */
    function clearCache();
}
