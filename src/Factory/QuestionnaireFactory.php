<?php

namespace App\Factory;

use App\Entity\Questionnaire;
use App\Repository\QuestionnaireRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Questionnaire>
 *
 * @method static Questionnaire|Proxy                     createOne(array $attributes = [])
 * @method static Questionnaire[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Questionnaire[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Questionnaire|Proxy                     find(object|array|mixed $criteria)
 * @method static Questionnaire|Proxy                     findOrCreate(array $attributes)
 * @method static Questionnaire|Proxy                     first(string $sortedField = 'id')
 * @method static Questionnaire|Proxy                     last(string $sortedField = 'id')
 * @method static Questionnaire|Proxy                     random(array $attributes = [])
 * @method static Questionnaire|Proxy                     randomOrCreate(array $attributes = [])
 * @method static Questionnaire[]|Proxy[]                 all()
 * @method static Questionnaire[]|Proxy[]                 findBy(array $attributes)
 * @method static Questionnaire[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 * @method static Questionnaire[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static QuestionnaireRepository|RepositoryProxy repository()
 * @method        Questionnaire|Proxy                     create(array|callable $attributes = [])
 */
final class QuestionnaireFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        $tabRoleConnecte = ['ROLE_VIGNERON', 'ROLE_PRESTATAIRE', 'ROLE_FOURNISSEUR'];
        $tabRole = ['ROLE_USER'];
        if (self::faker()->boolean(70)) {
            $tabRole[0] = $tabRoleConnecte[self::faker()->numberBetween(0, 2)];
        }

        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'Nom' => self::faker()->text(15),
            'partieConnecte' => self::faker()->numberBetween(0, 5),
            'description' => self::faker()->text(200),
            'imagePresentation' => file_get_contents('public/img/vignup.png'),
            'roleConnecte' => $tabRole,
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Questionnaire $questionnaire): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Questionnaire::class;
    }
}
