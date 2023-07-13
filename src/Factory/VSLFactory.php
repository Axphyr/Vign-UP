<?php

namespace App\Factory;

use App\Entity\VSL;
use App\Repository\VSLRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<VSL>
 *
 * @method        VSL|Proxy                     create(array|callable $attributes = [])
 * @method static VSL|Proxy                     createOne(array $attributes = [])
 * @method static VSL|Proxy                     find(object|array|mixed $criteria)
 * @method static VSL|Proxy                     findOrCreate(array $attributes)
 * @method static VSL|Proxy                     first(string $sortedField = 'id')
 * @method static VSL|Proxy                     last(string $sortedField = 'id')
 * @method static VSL|Proxy                     random(array $attributes = [])
 * @method static VSL|Proxy                     randomOrCreate(array $attributes = [])
 * @method static VSLRepository|RepositoryProxy repository()
 * @method static VSL[]|Proxy[]                 all()
 * @method static VSL[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static VSL[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static VSL[]|Proxy[]                 findBy(array $attributes)
 * @method static VSL[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static VSL[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class VSLFactory extends ModelFactory
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
            'latitude' => self::faker()->latitude(48.56, 49.34),
            'longitude' => self::faker()->longitude(3.6, 5.03),
            'superficie' => self::faker()->randomFloat(),
            'description' => self::faker()->text('30'),
            'approved' => 1,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(VSL $vSL): void {})
        ;
    }

    protected static function getClass(): string
    {
        return VSL::class;
    }
}
