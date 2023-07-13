<?php

namespace App\Factory;

use App\Entity\Sujet;
use App\Repository\SujetRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Sujet>
 *
 * @method        Sujet|Proxy                     create(array|callable $attributes = [])
 * @method static Sujet|Proxy                     createOne(array $attributes = [])
 * @method static Sujet|Proxy                     find(object|array|mixed $criteria)
 * @method static Sujet|Proxy                     findOrCreate(array $attributes)
 * @method static Sujet|Proxy                     first(string $sortedField = 'id')
 * @method static Sujet|Proxy                     last(string $sortedField = 'id')
 * @method static Sujet|Proxy                     random(array $attributes = [])
 * @method static Sujet|Proxy                     randomOrCreate(array $attributes = [])
 * @method static SujetRepository|RepositoryProxy repository()
 * @method static Sujet[]|Proxy[]                 all()
 * @method static Sujet[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Sujet[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Sujet[]|Proxy[]                 findBy(array $attributes)
 * @method static Sujet[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Sujet[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class SujetFactory extends ModelFactory
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
            'dateCreation' => self::faker()->dateTime(), // TODO add DATETIME ORM type manually
            'titre' => self::faker()->words(rand(1, 4), true),
            'user' => UserFactory::new(),
            'lastMessageDate' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Sujet $sujet): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Sujet::class;
    }
}
