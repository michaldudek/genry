<?php
namespace Genry\Events;

use Splot\EventManager\AbstractEvent;

use Genry\Page;

class PageRendered extends AbstractEvent
{

    protected $page;

    protected $output;

    public function __construct(Page $page, $output) {
        $this->page = $page;
        $this->output = $output;
    }

    public function getPage() {
        return $this->page;
    }

    public function getOutput() {
        return $this->output;
    }

    public function setOutput($output) {
        $this->output = $output;
    }

}