<?php

namespace App\Trait;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait UpdatedTimestamps
{

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new DateTimeImmutable());
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTimeImmutable());
        }
    }
}
