<?php

namespace App\Security\Voter;

use App\Entity\Option;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class OptionVoter extends Voter
{
    const EDIT_ACTION = 'edit';
    const DELETE_ACTION = 'delete';

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::EDIT_ACTION, self::DELETE_ACTION])
            && $subject instanceof Option;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($subject->getOwner() === $user) {
            return true;
        }

        return false;
    }
}
