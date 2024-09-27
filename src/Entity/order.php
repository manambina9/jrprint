<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Order
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $client;

    #[ORM\Column(type: 'text')]
    private $details;

    #[ORM\Column(type: 'string', length: 50)]
    private $status;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;
}
