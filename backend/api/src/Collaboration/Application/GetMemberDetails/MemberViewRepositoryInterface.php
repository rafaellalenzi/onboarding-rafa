<?php

declare(strict_types=1);

namespace Glance\Onboarding\Collaboration\Application\GetMemberDetails;

interface MemberViewRepositoryInterface
{
    public function findAllMembers(): array;
    public function findMemberDetailsById(int $id): ?Member;

    public function findMemberDetailsByExpId(int $experimentId): ?Member;
}