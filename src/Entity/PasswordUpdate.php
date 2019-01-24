<?php

namespace App\Entity;
// on l'a créé comme une entité puis on a retiré tout ce qui avait trait à l'ORM donc ce n'est plus une entité

use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate
{
    private $oldPassword;


    /**
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit faire au minimum 8 caractères.")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword", message="Vous n'avez pas entré deux fois le même mot de passe.")
     */
    private $confirmPassword;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
