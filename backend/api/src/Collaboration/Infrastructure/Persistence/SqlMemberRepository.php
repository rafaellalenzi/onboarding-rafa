<?php

declare(strict_types=1);

namespace Glance\Onboarding\Collaboration\Infrastructure\Persistence;

use Doctrine\DBAL\Driver\Connection;
use Glance\Onboarding\Collaboration\Application\GetMemberDetails\MemberViewRepositoryInterface;
use Glance\Onboarding\Collaboration\Application\GetMemberDetails\Member as MemberDetails;
use Glance\Onboarding\Collaboration\Domain\Member;
use Glance\Onboarding\Collaboration\Domain\MemberReadRepositoryInterface;
use Glance\Onboarding\Collaboration\Domain\MemberWriteRepositoryInterface;
use Glance\Onboarding\Collaboration\Domain\IntegerId;
class SqlMemberRepository implements 
MemberViewRepositoryInterface, 
MemberReadRepositoryInterface,
MemberWriteRepositoryInterface
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findNextMemberId(): int
    {
        $query = "SELECT MAX(ID) + 1 AS NEXT_ID FROM MEMBER";
        $statement = $this->connection->prepare($query);
        $statement->execute();

        return (int) $statement->fetchOne();
    }

    public function findAllMembers(): array
    {
        $query = "SELECT
                M.ID AS ID,
                M.FIRST_NAME AS FIRST_NAME,
                M.LAST_NAME AS LAST_NAME,
                M.EMAIL AS EMAIL,
                M.AGE AS AGE, 
                M.EXPERIMENT_ID AS EXPERIMENT_ID

            FROM MEMBER M
        ";
        $statement = $this->connection->prepare($query);
        $statement->execute();

        return array_map(function (array $row) {
            return MemberDetails::fromPersistence($row);
        }, $statement->fetchAllAssociative());
    }

    public function findMemberDetailsById(int $id): ?MemberDetails
    {
        $query = "SELECT
                M.ID AS ID,
                M.FIRST_NAME AS FIRST_NAME,
                M.LAST_NAME AS LAST_NAME,
                M.EMAIL AS EMAIL,
                M.AGE AS AGE, 
                M.EXPERIMENT_ID AS EXPERIMENT_ID

            FROM MEMBER M
            WHERE M.ID = :id
        ";
        $statement = $this->connection->prepare($query);
        $payload = [
            'id' => $id 
        ];
        $statement->execute($payload);
        $rows = $statement->fetchAllAssociative();

        return $rows ? MemberDetails::fromPersistence($rows[0]) : null;
    }

    public function findMemberDetailsByExpId(int $experimentId): ?MemberDetails
    {
        $query = "SELECT
                M.ID AS ID,
                M.FIRST_NAME AS FIRST_NAME,
                M.LAST_NAME AS LAST_NAME,
                M.EMAIL AS EMAIL,
                M.AGE AS AGE, 
                M.EXPERIMENT_ID AS EXPERIMENT_ID

            FROM MEMBER M
            WHERE M.EXPERIMENT_ID = :experimentId
        ";
        $statement = $this->connection->prepare($query);
        $payload = [
            'experimentId' => $experimentId
        ];
        $statement->execute($payload);
        $rows = $statement->fetchAllAssociative();

        return $rows ? MemberDetails::fromPersistence($rows[0]) : null;
    }

    public function findById(IntegerId $id): Member
    {
        $query = "SELECT
                M.ID AS ID,
                M.FIRST_NAME AS FIRST_NAME,
                M.LAST_NAME AS LAST_NAME,
                M.EMAIL AS EMAIL,
                M.AGE AS AGE, 
                M.EXPERIMENT_ID AS EXPERIMENT_ID

            FROM MEMBER M
        ";
        $statement = $this->connection->prepare($query);
        $payload = [
            'id' => $id ->toInteger()
        ];
        $statement->execute($payload);
        $rows = $statement->fetchAllAssociative();

        return Member::fromPersistence($rows[0]);
    }

    public function registerMember(Member $member): void
    {
        $query = "INSERT
            INTO MEMBER (
                ID,
                FIRST_NAME,
                LAST_NAME,
                EMAIL,
                AGE,
                EXPERIMENT_ID
            ) VALUES (
                :id,
                :firstName,
                :lastName,
                :email,
                :age,
                :experimentId
            )
        ";
        $statement = $this->connection->prepare($query);
        $payload = [
            'id' => $member->id()->toInteger(),
            'firstName' => $member->firstName(),
            'lastName' => $member->lastName(),
            'email' => $member->email(),
            'age' => $member->age(),
            'experimentId' => $member->experimentId()->toInteger()
        ];
        $statement->execute($payload);
    }
    public function deleteMember(int $id): void
    {
        $query = "DELETE FROM MEMBER WHERE ID = :id";
        $statement = $this->connection->prepare($query);
        $statement->execute(["id" => $id]);
    }
    public function updateMember(Member $member): void
    {
        $query = "UPDATE MEMBER
            SET
                FIRST_NAME = :firstName,
                LAST_NAME = :lastName,
                EMAIL = :email,
                AGE = :age,
                EXPERIMENT_ID = :experimentId
            WHERE ID = :id
        ";
        $statement = $this->connection->prepare($query);
        $payload = [
            "id" => $member->id()->toInteger(),
            "firstName" => $member->firstName(),
            "lastName" => $member->lastName(),
            "email" => $member->email(),
            "age" => $member->age(),
            "experimentId" => $member->experimentId()->toInteger()
        ];
        $statement->execute($payload);
    }

}
