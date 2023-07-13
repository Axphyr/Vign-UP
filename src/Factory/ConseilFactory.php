<?php

namespace App\Factory;

use App\Entity\Conseil;
use App\Repository\ConseilRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Conseil>
 *
 * @method        Conseil|Proxy                     create(array|callable $attributes = [])
 * @method static Conseil|Proxy                     createOne(array $attributes = [])
 * @method static Conseil|Proxy                     find(object|array|mixed $criteria)
 * @method static Conseil|Proxy                     findOrCreate(array $attributes)
 * @method static Conseil|Proxy                     first(string $sortedField = 'id')
 * @method static Conseil|Proxy                     last(string $sortedField = 'id')
 * @method static Conseil|Proxy                     random(array $attributes = [])
 * @method static Conseil|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ConseilRepository|RepositoryProxy repository()
 * @method static Conseil[]|Proxy[]                 all()
 * @method static Conseil[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Conseil[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Conseil[]|Proxy[]                 findBy(array $attributes)
 * @method static Conseil[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Conseil[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ConseilFactory extends ModelFactory
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
            'noteMinimale' => self::faker()->randomDigit(),
            'questionnaire' => QuestionnaireFactory::new(),
            'txtConseil' => self::faker()->text(),
            'categorieQuestion' => CategorieQuestionFactory::new(),
            'partieConnecte' => self::faker()->boolean(50),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Conseil $conseil): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Conseil::class;
    }
}
