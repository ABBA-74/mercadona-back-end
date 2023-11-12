<?php

namespace App\Data;

class JobsData
{
    public const JOBS =  [
        'Cadre' => [
            'Directeur des opérations retail',
            'Responsable de la stratégie commerciale',
            'Chef de division alimentaire'
        ],
        'Ingénieur' => [
            'Ingénieur en agroalimentaire',
            'Ingénieur logistique e-commerce',
            'Ingénieur qualité produit alimentaire'
        ],
        'Administratif' => [
            'Assistant de gestion',
            'Coordinateur administratif des ventes en ligne',
            'Responsable des services généraux'
        ],
        'Commercial' => [
            'Commercial terrain produits alimentaires',
            'Responsable compte clé e-commerce',
            'Chef de secteur distribution alimentaire'
        ],
        'Opérationnel' => [
            'Manager de rayon alimentaire',
            'Superviseur de la chaîne d\'emballage',
            'Responsable de la logistique de distribution'
        ]
    ];

    public const EMPLOYMENT_TYPE = [
        'Temps plein',
        'Temps partiel',
        'Contractuel',
        'Stagiaire',
        'Temporaire'
    ];

}
