<?php

require __DIR__.'/../vendor/autoload.php';

use Gksh\Bitmask\Bitmask;

enum Panel: int
{
    case Project = 1 << 0;
    case Terminal = 1 << 1;
    case SourceControl = 1 << 2;
    case Extensions = 1 << 3;
}

class Ide
{
    public Bitmask $panels;

    public function __construct()
    {
        $this->panels = Bitmask::tiny();
    }

    public function togglePanel(Panel $panel): self
    {
        $this->panels = $this->panels->toggle($panel);

        return $this;
    }
}

$ide = (new Ide)
    ->togglePanel(Panel::Project)
    ->togglePanel(Panel::Terminal);

dump([
    'project' => $ide->panels->has(Panel::Project), // true
    'terminal' => $ide->panels->has(Panel::Terminal), // true
    'source_control' => $ide->panels->has(Panel::SourceControl), // false
    'extensions' => $ide->panels->has(Panel::Extensions), // false
]);
