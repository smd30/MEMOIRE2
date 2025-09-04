<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    protected $table = 'devis';

    protected $fillable = [
        'montant',
        'statut',
        'client_id',
        'compagnie_id',
        'vehicule_id',
        'periode_police',
        'date_debut',
        'garanties_selectionnees',
        'calcul_details',
        'date_creation',
        'date_expiration'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'garanties_selectionnees' => 'array',
        'calcul_details' => 'array',
        'date_debut' => 'date',
        'date_creation' => 'datetime',
        'date_expiration' => 'datetime'
    ];

    // Relations selon le diagramme UML
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // Statuts possibles
    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_ACCEPTE = 'accepte';
    const STATUT_REJETE = 'rejete';
    const STATUT_EXPIRE = 'expire';

    // Périodes de police
    const PERIODES = [
        1 => '1 mois',
        3 => '3 mois',
        6 => '6 mois',
        12 => '12 mois'
    ];

    // Scopes utiles
    public function scopeEnAttente($query)
    {
        return $query->where('statut', self::STATUT_EN_ATTENTE);
    }

    public function scopeAcceptes($query)
    {
        return $query->where('statut', self::STATUT_ACCEPTE);
    }

    public function scopeExpires($query)
    {
        return $query->where('statut', self::STATUT_EXPIRE);
    }

    // Méthodes utilitaires
    public function estValide()
    {
        return $this->statut === self::STATUT_EN_ATTENTE && 
               $this->date_expiration > now();
    }

    public function accepter()
    {
        $this->update(['statut' => self::STATUT_ACCEPTE]);
    }

    public function rejeter()
    {
        $this->update(['statut' => self::STATUT_REJETE]);
    }

    public function expirer()
    {
        $this->update(['statut' => self::STATUT_EXPIRE]);
    }

    // Calcul du montant total
    public function calculerMontantTotal()
    {
        $total = 0;
        
        if (isset($this->calcul_details['garanties'])) {
            foreach ($this->calcul_details['garanties'] as $garantie) {
                $total += $garantie['montant'];
            }
        }

        return $total;
    }

    // Formatage pour l'affichage
    public function getMontantFormateAttribute()
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }

    public function getPeriodeFormateeAttribute()
    {
        return self::PERIODES[$this->periode_police] ?? 'Inconnue';
    }

    public function getStatutFormateAttribute()
    {
        $statuts = [
            self::STATUT_EN_ATTENTE => 'En attente',
            self::STATUT_ACCEPTE => 'Accepté',
            self::STATUT_REJETE => 'Rejeté',
            self::STATUT_EXPIRE => 'Expiré'
        ];

        return $statuts[$this->statut] ?? 'Inconnu';
    }
}
