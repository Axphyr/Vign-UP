<?php

namespace App\Factory;

use App\Entity\Reponse;
use App\Repository\ReponseRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Reponse>
 *
 * @method static Reponse|Proxy                     createOne(array $attributes = [])
 * @method static Reponse[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Reponse[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Reponse|Proxy                     find(object|array|mixed $criteria)
 * @method static Reponse|Proxy                     findOrCreate(array $attributes)
 * @method static Reponse|Proxy                     first(string $sortedField = 'id')
 * @method static Reponse|Proxy                     last(string $sortedField = 'id')
 * @method static Reponse|Proxy                     random(array $attributes = [])
 * @method static Reponse|Proxy                     randomOrCreate(array $attributes = [])
 * @method static Reponse[]|Proxy[]                 all()
 * @method static Reponse[]|Proxy[]                 findBy(array $attributes)
 * @method static Reponse[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static Reponse[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ReponseRepository|RepositoryProxy repository()
 * @method        Reponse|Proxy                     create(array|callable $attributes = [])
 */
final class ReponseFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'txtReponse' => self::faker()->text(10),
            'nombrePoints' => self::faker()->numberBetween(0, 5),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Reponse $réponse): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Reponse::class;
    }
}
