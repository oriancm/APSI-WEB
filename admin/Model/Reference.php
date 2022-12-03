<?php

use Admin\Model\Model;

/**
 * Classe Reference pour recuperer les données rentrées dans le formulaire
 */
class Reference extends Model
{
    private int $id;

    private string $titre;

    private int $secteur;

    private DateTime $dateD;

    private DateTime $dateF;

    private string $moe;

    private string $archi;

    private int $montant;

    private int $nbE;

    public function __construct()
    {
        //
        $this->table = 'reference';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     * @return Reference
     */
    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return int
     */
    public function getSecteur(): int
    {
        return $this->secteur;
    }

    /**
     * @param int $secteur
     */
    public function setSecteur(int $secteur): self
    {
        $this->secteur = $secteur;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateD(): DateTime
    {
        return $this->dateD;
    }

    /**
     * @param DateTime $dateD
     */
    public function setDateD(DateTime $dateD): self
    {
        $this->dateD = $dateD;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateF(): DateTime
    {
        return $this->dateF;
    }

    /**
     * @param DateTime $dateF
     */
    public function setDateF(DateTime $dateF): self
    {
        $this->dateF = $dateF;

        return $this;
    }

    /**
     * @return string
     */
    public function getMoe(): string
    {
        return $this->moe;
    }

    /**
     * @param string $moe
     */
    public function setMoe(string $moe): self
    {
        $this->moe = $moe;

        return $this;
    }

    /**
     * @return string
     */
    public function getArchi(): string
    {
        return $this->archi;
    }

    /**
     * @param string $archi
     */
    public function setArchi(string $archi): self
    {
        $this->archi = $archi;

        return $this;
    }

    /**
     * @return int
     */
    public function getMontant(): int
    {
        return $this->montant;
    }

    /**
     * @param int $montant
     */
    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * @return int
     */
    public function getNbE(): int
    {
        return $this->nbE;
    }

    /**
     * @param int $nbE
     */
    public function setNbE(int $nbE): self
    {
        $this->nbE = $nbE;

        return $this;
    }
}