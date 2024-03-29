<?php

declare(strict_types=1);

namespace App\Application\User\Query;

use App\Application\DateUtilsInterface;
use App\Application\User\View\ProfileView;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;

final class GetUserProfileQueryHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private DateUtilsInterface $dateUtils,
    ) {
    }

    public function __invoke(GetUserProfileQuery $query): ProfileView
    {
        $user = $this->userRepository->findProfileByUuid($query->uuid);
        if (!$user) {
            throw new UserNotFoundException();
        }

        $user = current($user);

        return new ProfileView(
            username: sprintf('%s %s.', $user['firstName'], $user['lastName'][0]),
            email: $user['email'],
        );
    }
}
