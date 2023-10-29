<?php

namespace App\Twig\Components;

use App\Entity\Offre;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class ViewOffre
{
    public string $type = 'success';
    public Offre $offre;
    use DefaultActionTrait;
}
