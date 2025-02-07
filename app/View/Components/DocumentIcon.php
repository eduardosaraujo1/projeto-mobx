<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DocumentIcon extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $extension = ''
    ) {}

    public function getColor()
    {
        return match (strtoupper($this->extension)) {
            'DOCX', 'DOC', 'ODT' => 'text-blue-700',
            'XLSX', 'XLS' => 'text-green-700',
            'PDF' => 'text-red-700',
            default => 'text-black',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.document-icon', [
            'color' => $this->getColor(),
        ]);
    }
}
