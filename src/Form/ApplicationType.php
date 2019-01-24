<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType {

    /**
     *
     * Permet d'avoir la configuration de base d'un champ
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    protected function getConfiguration($label, $placeholder, $options = []) {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
    // array_merge va fusionner le tableau avec le label et le placeholder avec le tableau d'options (qui est par défaut un tableau vide car on n'a pas toujours besoin de l'appeler


}