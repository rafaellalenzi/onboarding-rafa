<?php

namespace Glance\Onboarding\Collaboration\Domain;

interface MemberWriteRepositoryInterface
{
        public function registerMember(Member $member): void;
        public function deleteMember(int $id): void;
        public function updateMember(Member $member): void;
}
