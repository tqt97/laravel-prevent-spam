<?php

namespace Tuantq\LaravelPreventSpam\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Honeypot extends Component
{
    protected bool $enabled;

    protected string $fieldName;

    protected string $fieldTimeName;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->enabled = (bool) config('honeypot.enabled');
        $this->fieldName = config('honeypot.field_name');
        $this->fieldTimeName = config('honeypot.field_time_name');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view(
            'prevent-spam::components.honeypot',
            [
                'enabled' => $this->enabled,
                'fieldName' => $this->fieldName,
                'fieldTimeName' => $this->fieldTimeName,
            ]
        );
    }
}
