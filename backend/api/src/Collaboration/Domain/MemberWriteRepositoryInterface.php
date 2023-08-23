<?php

namespace Glance\Onboarding\Collaboration\Domain;

interface MemberWriteRepositoryInterface
{
        public function registerMember(Member $member): void;
}
