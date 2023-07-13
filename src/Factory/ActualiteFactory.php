<?php

namespace App\Factory;

use App\Entity\Actualite;
use App\Repository\ActualiteRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Actualite>
 *
 * @method        Actualite|Proxy create(array|callable $attributes = [])
 * @method static Actualite|Proxy createOne(array $attributes = [])
 * @method static Actualite|Proxy find(object|array|mixed $criteria)
 * @method static Actualite|Proxy findOrCreate(array $attributes)
 * @method static Actualite|Proxy first(string $sortedField = 'id')
 * @method static Actualite|Proxy last(string $sortedField = 'id')
 * @method static Actualite|Proxy random(array $attributes = [])
 * @method static Actualite|Proxy randomOrCreate(array $attributes = [])
 * @method static ActualiteRepository|RepositoryProxy repository()
 * @method static Actualite[]|Proxy[] all()
 * @method static Actualite[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Actualite[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Actualite[]|Proxy[] findBy(array $attributes)
 * @method static Actualite[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Actualite[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ActualiteFactory extends ModelFactory
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
            'type' => self::faker()->text(30),
            'date' => self::faker()->dateTime(),
            'intitule' => self::faker()->text(30),
            'description' => self::faker()->text(500),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Actualite $actualite): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Actualite::class;
    }
}
