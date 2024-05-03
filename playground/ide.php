<?php

require __DIR__.'/../vendor/autoload.php';

use Gksh\Bitmask\TinyBitmask;

enum Panel: int
{
    case Project = 1 << 0;
    case Terminal = 1 << 1;
    case SourceControl = 1 << 2;
    case Extensions = 1 << 3;
}

class Panels extends TinyBitmask
{
    public function isVisible(Panel $panel): bool
    {
        return $this->has($panel->value);
    }

    public function togglePanel(Panel $panel): Panels
    {
        return $this->toggle($panel->value);
    }
}

class Ide
{
    public Panels $panels;

    public function __construct()
    {
        $this->panels = Panels::make();
    }

    public function togglePanel(Panel $panel): self
    {
        $this->panels->togglePanel($panel);

        return $this;
    }
}

$ide = (new Ide())
    ->togglePanel(Panel::Project)
    ->togglePanel(Panel::Terminal);

dump([
    'project' => $ide->panels->isVisible(Panel::Project), // true
    'terminal' => $ide->panels->isVisible(Panel::Terminal), // true
    'source_control' => $ide->panels->isVisible(Panel::SourceControl), // false
    'extensions' => $ide->panels->isVisible(Panel::Extensions), // false
]);
