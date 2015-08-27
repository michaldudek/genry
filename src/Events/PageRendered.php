<?php
namespace Genry\Events;

use Splot\EventManager\AbstractEvent;

use Genry\Page;

/**
 * Event dispatched when a page just rendered.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
class PageRendered extends AbstractEvent
{
    /**
     * Page object.
     *
     * @var Page
     */
    protected $page;

    /**
     * Output string.
     *
     * @var string
     */
    protected $output;

    /**
     * Constructor.
     *
     * @param Page   $page   Page object.
     * @param string $output The output.
     */
    public function __construct(Page $page, $output)
    {
        $this->page = $page;
        $this->output = $output;
    }

    /**
     * Returns the page object.
     *
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Returns the output.
     *
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Sets the output.
     *
     * @param string $output The output.
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }
}
