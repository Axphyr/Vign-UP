<?php

namespace App\Factory;

use App\Entity\Ressource;
use App\Repository\RessourceRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Ressource>
 *
 * @method        Ressource|Proxy create(array|callable $attributes = [])
 * @method static Ressource|Proxy createOne(array $attributes = [])
 * @method static Ressource|Proxy find(object|array|mixed $criteria)
 * @method static Ressource|Proxy findOrCreate(array $attributes)
 * @method static Ressource|Proxy first(string $sortedField = 'id')
 * @method static Ressource|Proxy last(string $sortedField = 'id')
 * @method static Ressource|Proxy random(array $attributes = [])
 * @method static Ressource|Proxy randomOrCreate(array $attributes = [])
 * @method static RessourceRepository|RepositoryProxy repository()
 * @method static Ressource[]|Proxy[] all()
 * @method static Ressource[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Ressource[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Ressource[]|Proxy[] findBy(array $attributes)
 * @method static Ressource[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Ressource[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class RessourceFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'descriptif' => self::faker()->text(150),
            'nomRessource' => self::faker()->text(30),
            'typeRessource' => self::faker()->text(20),
            'url' => self::faker()->url(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Ressource $ressource): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Ressource::class;
    }
}
