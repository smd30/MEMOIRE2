<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractRenewal extends Model
{
    use HasFactory;

    protected $table = 'contract_renewals';

    protected $fillable = [
        'contrat_id',
        'user_id',
        'vehicule_id',
        'compagnie_id',
        'numero_police_precedent',
        'numero_police_nouveau',
        'numero_attestation_nouveau',
        'cle_securite_nouveau',
        'date_debut_nouveau',
        'date_fin_nouveau',
        'periode_police',
        'garanties_selectionnees',
        'prime_rc',
        'garanties_optionnelles',
        'accessoires_police',
        'prime_nette',
        'taxes_tuca',
        'prime_ttc',
        'statut',
        'date_demande',
        'date_renouvellement',
        'motif_renouvellement',
        'observations',
        'prime_precedente',
        'evolution_prime',
        'pourcentage_evolution'
    ];

    protected $casts = [
        'date_debut_nouveau' => 'datetime',
        'date_fin_nouveau' => 'datetime',
        'date_demande' => 'datetime',
        'date_renouvellement' => 'datetime',
        'garanties_selectionnees' => 'array',
        'prime_rc' => 'decimal:2',
        'garanties_optionnelles' => 'decimal:2',
        'accessoires_police' => 'decimal:2',
        'prime_nette' => 'decimal:2',
        'taxes_tuca' => 'decimal:2',
        'prime_ttc' => 'decimal:2',
        'prime_precedente' => 'decimal:2',
        'evolution_prime' => 'decimal:2',
        'pourcentage_evolution' => 'decimal:2'
    ];

    // Statuts possibles
    const STATUTS = [
        'en_attente' => 'En attente',
        'approuve' => 'Approuvé',
        'rejete' => 'Rejeté',
        'renouvele' => 'Renouvelé',
        'annule' => 'Annulé'
    ];

    // Motifs de renouvellement
    const MOTIFS_RENOUVELLEMENT = [
        'expiration_normale' => 'Expiration normale',
        'changement_garanties' => 'Changement de garanties',
        'changement_vehicule' => 'Changement de véhicule',
        'changement_compagnie' => 'Changement de compagnie',
        'negociation_prime' => 'Négociation de prime',
        'autre' => 'Autre'
    ];

    /**
     * Relation avec le contrat original
     */
    public function contrat(): BelongsTo
    {
        return $this->belongsTo(Contrat::class, 'contrat_id');
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le véhicule
     */
    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * Relation avec la compagnie
     */
    public function compagnie(): BelongsTo
    {
        return $this->belongsTo(Compagnie::class);
    }

    /**
     * Scopes utiles
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeApprouve($query)
    {
        return $query->where('statut', 'approuve');
    }

    public function scopeRenouvele($query)
    {
        return $query->where('statut', 'renouvele');
    }

    public function scopeRejete($query)
    {
        return $query->where('statut', 'rejete');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCompagnie($query, $compagnieId)
    {
        return $query->where('compagnie_id', $compagnieId);
    }

    /**
     * Vérifier si le renouvellement est en attente
     */
    public function estEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifier si le renouvellement est approuvé
     */
    public function estApprouve(): bool
    {
        return $this->statut === 'approuve';
    }

    /**
     * Vérifier si le renouvellement est renouvelé
     */
    public function estRenouvele(): bool
    {
        return $this->statut === 'renouvele';
    }

    /**
     * Vérifier si le renouvellement est rejeté
     */
    public function estRejete(): bool
    {
        return $this->statut === 'rejete';
    }

    /**
     * Obtenir le statut en français
     */
    public function getStatutFrancaisAttribute(): string
    {
        return self::STATUTS[$this->statut] ?? 'Inconnu';
    }

    /**
     * Obtenir le motif en français
     */
    public function getMotifFrancaisAttribute(): string
    {
        return self::MOTIFS_RENOUVELLEMENT[$this->statut] ?? 'Non spécifié';
    }

    /**
     * Calculer l'évolution de la prime
     */
    public function calculerEvolutionPrime(): array
    {
        if (!$this->prime_precedente || !$this->prime_ttc) {
            return [
                'evolution_prime' => 0,
                'pourcentage_evolution' => 0,
                'type_evolution' => 'stable'
            ];
        }

        $evolution = $this->prime_ttc - $this->prime_precedente;
        $pourcentage = ($evolution / $this->prime_precedente) * 100;

        $type = 'stable';
        if ($pourcentage > 5) {
            $type = 'augmentation';
        } elseif ($pourcentage < -5) {
            $type = 'diminution';
        }

        return [
            'evolution_prime' => $evolution,
            'pourcentage_evolution' => $pourcentage,
            'type_evolution' => $type
        ];
    }

    /**
     * Obtenir la durée du nouveau contrat en jours
     */
    public function getDureeContratAttribute(): int
    {
        return $this->date_debut_nouveau->diffInDays($this->date_fin_nouveau);
    }

    /**
     * Générer un nouveau numéro de police
     */
    public function genererNumeroPolice(): string
    {
        $prefixe = 'POL';
        $annee = now()->year;
        $mois = now()->format('m');
        $numero = str_pad($this->id ?? 1, 6, '0', STR_PAD_LEFT);
        
        return "{$prefixe}{$annee}{$mois}{$numero}";
    }

    /**
     * Générer un nouveau numéro d'attestation
     */
    public function genererNumeroAttestation(): string
    {
        $prefixe = 'ATT';
        $annee = now()->year;
        $numero = str_pad($this->id ?? 1, 8, '0', STR_PAD_LEFT);
        
        return "{$prefixe}{$annee}{$numero}";
    }

    /**
     * Générer une nouvelle clé de sécurité
     */
    public function genererCleSecurite(): string
    {
        return strtoupper(substr(md5(uniqid()), 0, 8));
    }

    /**
     * Valider les données de renouvellement
     */
    public static function getReglesValidation(): array
    {
        return [
            'contrat_id' => 'required|exists:insurance_contracts,id',
            'user_id' => 'required|exists:users,id',
            'vehicule_id' => 'required|exists:vehicules,id',
            'compagnie_id' => 'required|exists:compagnies,id',
            'periode_police' => 'required|integer|in:1,3,6,12',
            'garanties_selectionnees' => 'required|array',
            'prime_rc' => 'required|numeric|min:0',
            'garanties_optionnelles' => 'required|numeric|min:0',
            'accessoires_police' => 'required|numeric|min:0',
            'prime_nette' => 'required|numeric|min:0',
            'taxes_tuca' => 'required|numeric|min:0',
            'prime_ttc' => 'required|numeric|min:0',
            'motif_renouvellement' => 'required|string|in:' . implode(',', array_keys(self::MOTIFS_RENOUVELLEMENT)),
            'observations' => 'nullable|string|max:1000'
        ];
    }

    /**
     * Messages d'erreur personnalisés
     */
    public static function getMessagesValidation(): array
    {
        return [
            'contrat_id.required' => 'Le contrat original est requis.',
            'contrat_id.exists' => 'Le contrat original n\'existe pas.',
            'periode_police.in' => 'La période de police doit être 1, 3, 6 ou 12 mois.',
            'motif_renouvellement.in' => 'Le motif de renouvellement n\'est pas valide.',
            'prime_ttc.min' => 'La prime TTC doit être positive.'
        ];
    }

    /**
     * Boot method pour calculer automatiquement les valeurs
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($renewal) {
            // Générer les numéros automatiquement
            $renewal->numero_police_nouveau = $renewal->genererNumeroPolice();
            $renewal->numero_attestation_nouveau = $renewal->genererNumeroAttestation();
            $renewal->cle_securite_nouveau = $renewal->genererCleSecurite();
            
            // Calculer l'évolution de la prime
            if ($renewal->contrat) {
                $renewal->prime_precedente = $renewal->contrat->prime_ttc;
                $evolution = $renewal->calculerEvolutionPrime();
                $renewal->evolution_prime = $evolution['evolution_prime'];
                $renewal->pourcentage_evolution = $evolution['pourcentage_evolution'];
            }
        });
    }
}
