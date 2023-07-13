<?php

namespace App\Factory;

use App\Entity\CategorieQuestion;
use App\Repository\CategorieQuestionRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<CategorieQuestion>
 *
 * @method        CategorieQuestion|Proxy create(array|callable $attributes = [])
 * @method static CategorieQuestion|Proxy createOne(array $attributes = [])
 * @method static CategorieQuestion|Proxy find(object|array|mixed $criteria)
 * @method static CategorieQuestion|Proxy findOrCreate(array $attributes)
 * @method static CategorieQuestion|Proxy first(string $sortedField = 'id')
 * @method static CategorieQuestion|Proxy last(string $sortedField = 'id')
 * @method static CategorieQuestion|Proxy random(array $attributes = [])
 * @method static CategorieQuestion|Proxy randomOrCreate(array $attributes = [])
 * @method static CategorieQuestionRepository|RepositoryProxy repository()
 * @method static CategorieQuestion[]|Proxy[] all()
 * @method static CategorieQuestion[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static CategorieQuestion[]|Proxy[] createSequence(array|callable $sequence)
 * @method static CategorieQuestion[]|Proxy[] findBy(array $attributes)
 * @method static CategorieQuestion[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CategorieQuestion[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class CategorieQuestionFactory extends ModelFactory
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
            'Nom' => self::faker()->unique()->text(55),
            'description' => null,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(CategorieQuestion $categorieQuestion): void {})
        ;
    }

    protected static function getClass(): string
    {
        return CategorieQuestion::class;
    }
}
