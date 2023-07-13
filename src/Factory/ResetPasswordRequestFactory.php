<?php

namespace App\Factory;

use App\Entity\ResetPasswordRequest;
use App\Repository\ResetPasswordRequestRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ResetPasswordRequest>
 *
 * @method        ResetPasswordRequest|Proxy                     create(array|callable $attributes = [])
 * @method static ResetPasswordRequest|Proxy                     createOne(array $attributes = [])
 * @method static ResetPasswordRequest|Proxy                     find(object|array|mixed $criteria)
 * @method static ResetPasswordRequest|Proxy                     findOrCreate(array $attributes)
 * @method static ResetPasswordRequest|Proxy                     first(string $sortedField = 'id')
 * @method static ResetPasswordRequest|Proxy                     last(string $sortedField = 'id')
 * @method static ResetPasswordRequest|Proxy                     random(array $attributes = [])
 * @method static ResetPasswordRequest|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ResetPasswordRequestRepository|RepositoryProxy repository()
 * @method static ResetPasswordRequest[]|Proxy[]                 all()
 * @method static ResetPasswordRequest[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ResetPasswordRequest[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static ResetPasswordRequest[]|Proxy[]                 findBy(array $attributes)
 * @method static ResetPasswordRequest[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ResetPasswordRequest[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ResetPasswordRequestFactory extends ModelFactory
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
            'expiresAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'hashedToken' => self::faker()->text(100),
            'requestedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'selector' => self::faker()->text(20),
            'user' => UserFactory::random(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ResetPasswordRequest $resetPasswordRequest): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ResetPasswordRequest::class;
    }
}
