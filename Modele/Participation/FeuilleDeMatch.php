<?php

require_once(__DIR__ . '/../Joueur/Joueur.php');
require_once(__DIR__ . '/../Joueur/JoueurStatut.php');
require_once(__DIR__ . '/../Rencontre/Rencontre.php');
require_once(__DIR__ . '/Poste.php');
require_once(__DIR__ . '/TitulaireOuRemplacant.php');
require_once(__DIR__ . '/Participation.php');

class FeuilleDeMatch {
    private readonly array $participants;

    public function __construct(array $participants) {
        $this->participants = $participants;
    }

    public function getParticipants(): array {
        return $this->participants;
    }

    public function getParticipantAuPoste(Poste $poste, TitulaireOuRemplacant $titulaireOuRemplacant): ?Participation {
        foreach ($this->participants as $participant) {
            if ($participant->getPoste() === $poste
                && $participant->getTitulaireOuRemplacant() === $titulaireOuRemplacant
            ) {
                return $participant;
            }
        }

        return null;
    }

    public function getRemplacantAuPoste(Poste $poste): ?Participation {
        foreach ($this->participants as $participant) {
            if ($participant->getPoste() === $poste
                && $participant->getTitulaireOuRemplacant() === TitulaireOuRemplacant::REMPLACANT
            ) {
                return $participant;
            }
        }

        return null;
    }

    public function estComplete(): bool {
        return $this->tousLesPostesOntUnTitulaire() && $this->tousLesParticipantsSontActifs();
    }

    private function tousLesPostesOntUnTitulaire(): bool {
        foreach (Poste::cases() as $poste) {
            if($this->getParticipantAuPoste($poste, TitulaireOuRemplacant::TITULAIRE) === null) {
                return false;
            }
        }
        return true;
    }

    private function tousLesParticipantsSontActifs(): bool {
        foreach ($this->getParticipants() as $participant) {
            if($participant->getParticipant()->getStatut() !== JoueurStatut::ACTIF) {
                return false;
            }
        }
        return true;
    }

    public function estEvalue() {
        foreach ($this->getParticipants() as $participant) {
            if($participant->getPerformance() === null) {
                return false;
            }
        }
        return true;
    }
}

