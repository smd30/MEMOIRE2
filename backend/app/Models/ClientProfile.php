<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'city',
        'postal_code',
        'country',
        'birth_date',
        'driving_license_number',
        'driving_license_date',
        'driving_experience_years',
        'has_garage',
        'additional_info',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'driving_license_date' => 'date',
        'driving_experience_years' => 'integer',
        'has_garage' => 'boolean',
        'additional_info' => 'array',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir l'âge du client
     */
    public function getAge(): int
    {
        return $this->birth_date->age;
    }

    /**
     * Vérifier si le client est majeur
     */
    public function isAdult(): bool
    {
        return $this->getAge() >= 18;
    }

    /**
     * Vérifier si le client est jeune conducteur (moins de 3 ans d'expérience)
     */
    public function isYoungDriver(): bool
    {
        return $this->driving_experience_years < 3;
    }

    /**
     * Vérifier si le client est conducteur expérimenté (plus de 10 ans d'expérience)
     */
    public function isExperiencedDriver(): bool
    {
        return $this->driving_experience_years >= 10;
    }

    /**
     * Obtenir l'adresse complète
     */
    public function getFullAddress(): string
    {
        return "{$this->address}, {$this->postal_code} {$this->city}, {$this->country}";
    }

    /**
     * Obtenir l'expérience de conduite en années
     */
    public function getDrivingExperience(): int
    {
        return $this->driving_experience_years;
    }

    /**
     * Vérifier si le client a un garage
     */
    public function hasGarage(): bool
    {
        return $this->has_garage;
    }

    /**
     * Obtenir les informations additionnelles
     */
    public function getAdditionalInfo(): array
    {
        return $this->additional_info ?? [];
    }

    /**
     * Vérifier si le client a une information spécifique
     */
    public function hasInfo(string $key): bool
    {
        return isset($this->additional_info[$key]);
    }

    /**
     * Obtenir une information spécifique
     */
    public function getInfo(string $key, $default = null)
    {
        return $this->additional_info[$key] ?? $default;
    }

    /**
     * Ajouter une information
     */
    public function addInfo(string $key, $value): void
    {
        $info = $this->additional_info ?? [];
        $info[$key] = $value;
        $this->update(['additional_info' => $info]);
    }

    /**
     * Supprimer une information
     */
    public function removeInfo(string $key): void
    {
        $info = $this->additional_info ?? [];
        unset($info[$key]);
        $this->update(['additional_info' => $info]);
    }

    /**
     * Obtenir le coefficient d'expérience pour le calcul de prime
     */
    public function getExperienceCoefficient(): float
    {
        if ($this->isYoungDriver()) {
            return 1.30; // 30% de majoration pour jeunes conducteurs
        } elseif ($this->isExperiencedDriver()) {
            return 0.90; // 10% de réduction pour conducteurs expérimentés
        } else {
            return 1.00; // Coefficient normal
        }
    }

    /**
     * Obtenir le coefficient garage pour le calcul de prime
     */
    public function getGarageCoefficient(): float
    {
        return $this->hasGarage() ? 0.95 : 1.00; // 5% de réduction si garage
    }

    /**
     * Validation des règles métier
     */
    public static function getValidationRules(): array
    {
        return [
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:100',
            'birth_date' => 'required|date|before:-18 years',
            'driving_license_number' => 'required|string|max:50',
            'driving_license_date' => 'required|date|before:today',
            'driving_experience_years' => 'required|integer|min:0|max:80',
            'has_garage' => 'boolean',
            'additional_info' => 'nullable|array',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    public static function getValidationMessages(): array
    {
        return [
            'birth_date.before' => 'Le client doit être majeur (18 ans minimum)',
            'driving_license_date.before' => 'La date du permis de conduire doit être dans le passé',
            'driving_experience_years.min' => 'L\'expérience de conduite ne peut pas être négative',
            'driving_experience_years.max' => 'L\'expérience de conduite semble excessive',
        ];
    }
}
